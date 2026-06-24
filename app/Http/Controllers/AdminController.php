<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Recette;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Conversation;
use App\Models\Message;
use App\Mail\AdminMessageMail;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Tableau de bord administrateur.
     */
    public function dashboard()
    {
        $stats = [
            'users'    => User::count(),
            'recettes' => Recette::count(),
            'posts'    => Post::count(),
            'comments' => Comment::count(),
            'likes'    => Like::count(),
        ];

        $users    = User::orderBy('created_at', 'desc')->get();
        $recettes = Recette::with('user')->withCount('likes')->latest()->get();
        $posts    = Post::with('user')->withCount(['comments', 'likes'])->latest()->limit(50)->get();
        $comments = Comment::with(['user', 'post'])->latest()->limit(50)->get();
        $search   = \App\Http\Controllers\SearchController::query(request('q'));

        return view('page.admin', compact('stats', 'users', 'recettes', 'posts', 'comments', 'search'));
    }

    /* =======================================================================
     |  GESTION DES UTILISATEURS
     ======================================================================= */

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return back()->with('admin_error', "Impossible de supprimer un compte administrateur.");
        }

        // Nettoyage des donnees liees
        Like::where('user_id', $user->id)->delete();
        Comment::where('user_id', $user->id)->delete();
        foreach (Recette::where('user_id', $user->id)->get() as $recette) {
            $recette->ingredients()->delete();
            $recette->likes()->delete();
            $recette->delete();
        }
        Post::where('user_id', $user->id)->delete();
        Message::where('user_id', $user->id)->delete();
        Conversation::where('user_one', $user->id)->orWhere('user_two', $user->id)->delete();

        if ($user->avatar && file_exists(public_path($user->avatar))) {
            @unlink(public_path($user->avatar));
        }

        $user->delete();

        return back()->with('admin_success', "Utilisateur supprime avec succes.");
    }

    public function toggleRole($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return back()->with('admin_error', "Vous ne pouvez pas modifier votre propre role.");
        }
        $user->role = $user->isAdmin() ? 'user' : 'admin';
        $user->save();

        return back()->with('admin_success', "Role de {$user->name} mis a jour : {$user->role}.");
    }

    /* =======================================================================
     |  GESTION DU CONTENU
     ======================================================================= */

    public function destroyRecette($id)
    {
        $recette = Recette::findOrFail($id);
        if ($recette->image_path && Storage::disk('public')->exists($recette->image_path)) {
            Storage::disk('public')->delete($recette->image_path);
        }
        $recette->ingredients()->delete();
        $recette->likes()->delete();
        Comment::where('recette_id', $recette->id)->delete();
        $recette->delete();

        return back()->with('admin_success', "Recette supprimee.");
    }

    public function destroyPost($id)
    {
        $post = Post::findOrFail($id);
        $post->comments()->delete();
        $post->likes()->delete();
        if ($post->image_path && Storage::disk('public')->exists($post->image_path)) {
            Storage::disk('public')->delete($post->image_path);
        }
        $post->delete();

        return back()->with('admin_success', "Publication supprimee.");
    }

    public function destroyComment($id)
    {
        Comment::findOrFail($id)->delete();
        return back()->with('admin_success', "Commentaire supprime.");
    }

    /* =======================================================================
     |  EXPORT PDF DES UTILISATEURS
     ======================================================================= */

    public function exportUsers()
    {
        $users = User::orderBy('id')->get();
        $filename = 'utilisateurs_ouratable_' . now()->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('admin.users-pdf', compact('users'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    /* =======================================================================
     |  COMMUNICATION : EMAIL DIRECT
     ======================================================================= */

    public function sendEmail(Request $request)
    {
        $request->validate([
            'target'  => 'required|string', // 'all' ou un id utilisateur
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        if ($request->target === 'all') {
            $recipients = User::whereNotNull('email')->get();
        } else {
            $recipients = User::where('id', $request->target)->get();
        }

        $sent = 0;
        $failed = 0;
        foreach ($recipients as $user) {
            try {
                Mail::to($user->email)->send(
                    new AdminMessageMail($request->subject, $request->message, $user->name)
                );
                $sent++;
            } catch (\Exception $e) {
                $failed++;
                Log::error("Echec email admin vers {$user->email}: " . $e->getMessage());
            }
        }

        $msg = "Email envoye a {$sent} utilisateur(s).";
        if ($failed > 0) {
            $msg .= " {$failed} echec(s) (voir logs).";
        }

        return back()->with('admin_success', $msg);
    }

    /* =======================================================================
     |  COMMUNICATION : MESSAGE PRIVE (messagerie communaute)
     ======================================================================= */

    public function sendDirectMessage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        $adminId = Auth::id();
        $otherId = (int) $request->user_id;

        if ($adminId === $otherId) {
            return back()->with('admin_error', "Vous ne pouvez pas vous envoyer un message a vous-meme.");
        }

        $conversation = Conversation::where('is_group', false)
            ->where(function ($q) use ($adminId, $otherId) {
                $q->where('user_one', $adminId)->where('user_two', $otherId);
            })->orWhere(function ($q) use ($adminId, $otherId) {
                $q->where('user_one', $otherId)->where('user_two', $adminId);
            })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one' => $adminId,
                'user_two' => $otherId,
                'is_group' => false,
                'last_message_at' => now(),
            ]);
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $adminId,
            'content' => $request->content,
            'is_read' => false,
        ]);
        $conversation->update(['last_message_at' => now()]);

        return back()->with('admin_success', "Message prive envoye via la messagerie communaute.");
    }

    /* =======================================================================
     |  COMMUNICATION : ANNONCE COMMUNAUTE (publication)
     ======================================================================= */

    public function broadcastPost(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            'user_id' => Auth::id(),
            'type' => 'annonce',
            'content' => $request->content,
            'image_path' => $imagePath,
        ]);

        return back()->with('admin_success', "Annonce publiee dans la communaute.");
    }
}

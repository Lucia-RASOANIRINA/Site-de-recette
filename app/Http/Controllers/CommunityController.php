<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Challenge;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{

    // Page publique community
    public function index()
    {
        $posts = Post::with(['user', 'comments.user', 'likes'])->latest()->get();

        // Calculer les statistiques réelles
        $totalMembres = User::count();
        $totalPosts = Post::count();
        $totalComments = Comment::count();

        return view('page.community', compact(
            'posts', 
            'totalMembres', 
            'totalPosts', 
            'totalComments'
        ));
    }

    // Page utilisateur
    public function userCommunity()
    {
        $posts = Post::with(['user', 'comments.user', 'likes'])->latest()->paginate(10);
        
        $challenge = Challenge::where('status', 'active')->first();
        
        // Calculer les statistiques réelles
        $totalMembres = User::count();
        $totalPosts = Post::count();
        $totalComments = Comment::count();

        return view('page.UserCommunity', compact(
            'posts', 
            'challenge', 
            'totalMembres', 
            'totalPosts', 
            'totalComments'
        ));
    }

    // Ajouter un post
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'type' => 'required'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'content' => $request->content,
            'image_path' => $imagePath
        ]);

        return back();
    }

    // Commenter
    public function comment(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'post_id' => 'required'
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
            'content' => $request->content
        ]);

        return back();
    }

    // Like/Unlike
    public function like($id)
    {
        $like = Like::where('post_id', $id)
                    ->where('user_id', auth()->id())
                    ->first();

        if ($like) {
            // Annuler le like
            $like->delete();
        } else {
            // Ajouter le like
            Like::create([
                'user_id' => auth()->id(),
                'post_id' => $id
            ]);
        }

        return back();
    }
}
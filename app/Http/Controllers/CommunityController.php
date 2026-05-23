<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Challenge;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\ChatGroup;
use App\Models\GroupMessage;
use App\Models\AiChallenge;
use App\Services\DeepSeekService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
{
    protected $deepSeek;

    public function __construct(DeepSeekService $deepSeek)
    {
        $this->deepSeek = $deepSeek;
    }

    public function index()
    {
        $posts = Post::with(['user', 'comments.user', 'likes'])->latest()->get();
        $totalMembres = User::count();
        $totalPosts = Post::count();
        $totalComments = Comment::count();

        return view('page.community', compact('posts', 'totalMembres', 'totalPosts', 'totalComments'));
    }

    public function userCommunity()
    {
        $posts = Post::with(['user', 'comments.user', 'likes'])->latest()->paginate(10);
        $challenge = Challenge::where('status', 'active')->first();
        
        $aiChallenge = AiChallenge::where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();
            
        if (!$aiChallenge) {
            $this->generateNewAiChallenge();
            $aiChallenge = AiChallenge::where('is_active', true)->latest()->first();
        }
        
        $totalMembres = User::count();
        $totalPosts = Post::count();
        $totalComments = Comment::count();
        
        $conversations = Conversation::where(function($q) {
                $q->where('user_one', Auth::id())->orWhere('user_two', Auth::id());
            })
            ->where('is_group', false)
            ->with(['userOne', 'userTwo', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
            
        $groups = ChatGroup::withCount('members')->get();
        $myGroups = ChatGroup::whereHas('members', function($q) {
            $q->where('user_id', Auth::id());
        })->get();
        
        $unreadCount = 0;
        foreach ($conversations as $conv) {
            $unreadCount += $conv->unreadMessagesFor(Auth::id());
        }

        return view('page.UserCommunity', compact(
            'posts', 'challenge', 'totalMembres', 'totalPosts', 'totalComments',
            'conversations', 'unreadCount', 'groups', 'myGroups', 'aiChallenge'
        ));
    }

    public function generateNewAiChallenge()
    {
        $challengeData = $this->deepSeek->generateChallenge();
        
        AiChallenge::where('is_active', true)->update(['is_active' => false]);
        
        return AiChallenge::create([
            'title' => $challengeData['title'],
            'description' => $challengeData['description'],
            'ingredients' => $challengeData['ingredients'],
            'difficulty' => $challengeData['difficulty'],
            'duration' => $challengeData['duration'],
            'expires_at' => now()->addDays($challengeData['duration']),
            'is_active' => true,
        ]);
    }

    public function joinGroup($groupId)
    {
        $group = ChatGroup::findOrFail($groupId);
        
        if (!$group->isMember(Auth::id())) {
            $group->members()->attach(Auth::id(), ['joined_at' => now()]);
            $group->increment('member_count');
            
            Conversation::firstOrCreate([
                'group_id' => $group->id,
                'is_group' => true,
            ], [
                'user_one' => Auth::id(),
                'user_two' => Auth::id(),
                'last_message_at' => now(),
            ]);
        }
        
        return response()->json(['success' => true, 'message' => 'Groupe rejoint', 'is_member' => true]);
    }

    public function leaveGroup($groupId)
    {
        $group = ChatGroup::findOrFail($groupId);
        
        if ($group->isMember(Auth::id())) {
            $group->members()->detach(Auth::id());
            $group->decrement('member_count');
        }
        
        return response()->json(['success' => true, 'message' => 'Groupe quitté', 'is_member' => false]);
    }

    public function getGroupMessages($groupId)
    {
        $group = ChatGroup::findOrFail($groupId);
        
        if (!$group->isMember(Auth::id())) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
        
        $messages = GroupMessage::where('group_id', $groupId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'user_id' => $msg->user_id,
                    'user_name' => $msg->user->name,
                    'content' => $msg->content,
                    'is_own' => $msg->user_id == Auth::id(),
                    'time' => $msg->created_at->format('H:i'),
                ];
            });
            
        return response()->json($messages);
    }

    public function sendGroupMessage(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:chat_groups,id',
            'content' => 'required|string|max:1000'
        ]);
        
        $group = ChatGroup::findOrFail($request->group_id);
        
        if (!$group->isMember(Auth::id())) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
        
        $message = GroupMessage::create([
            'group_id' => $group->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);
        
        $conversation = Conversation::where('group_id', $group->id)->first();
        if ($conversation) {
            $conversation->update(['last_message_at' => now()]);
        }
        
        return response()->json([
            'id' => $message->id,
            'user_id' => $message->user_id,
            'user_name' => Auth::user()->name,
            'content' => $message->content,
            'is_own' => true,
            'time' => $message->created_at->format('H:i'),
        ]);
    }

    public function getGroups()
    {
        $groups = ChatGroup::withCount('members')->get()->map(function($group) {
            return [
                'id' => $group->id,
                'name' => $group->name,
                'description' => $group->description,
                'icon' => $group->icon,
                'category' => $group->category,
                'member_count' => $group->members_count,
                'is_member' => $group->isMember(Auth::id()),
            ];
        });
        
        return response()->json($groups);
    }

    public function startConversation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $userId = Auth::id();
        $otherUserId = $request->user_id;

        if ($userId == $otherUserId) {
            return response()->json(['error' => 'Vous ne pouvez pas discuter avec vous-même'], 400);
        }

        $conversation = Conversation::where('is_group', false)
            ->where(function($query) use ($userId, $otherUserId) {
                $query->where('user_one', $userId)->where('user_two', $otherUserId);
            })->orWhere(function($query) use ($userId, $otherUserId) {
                $query->where('user_one', $otherUserId)->where('user_two', $userId);
            })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one' => $userId,
                'user_two' => $otherUserId,
                'last_message_at' => now(),
                'is_group' => false,
            ]);
        }

        return response()->json([
            'conversation_id' => $conversation->id,
            'user' => User::find($otherUserId),
            'is_group' => false
        ]);
    }

    public function startGroupConversation($groupId)
    {
        $group = ChatGroup::findOrFail($groupId);
        
        if (!$group->isMember(Auth::id())) {
            return response()->json(['error' => 'Vous devez rejoindre le groupe d\'abord'], 403);
        }
        
        $conversation = Conversation::firstOrCreate(
            ['group_id' => $groupId, 'is_group' => true],
            [
                'user_one' => Auth::id(),
                'user_two' => Auth::id(),
                'last_message_at' => now(),
                'is_group' => true,
            ]
        );
        
        return response()->json([
            'conversation_id' => $conversation->id,
            'group_id' => $groupId,
            'group_name' => $group->name,
            'is_group' => true
        ]);
    }

    public function getMessages($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        
        if ($conversation->is_group) {
            $group = ChatGroup::find($conversation->group_id);
            if (!$group || !$group->isMember(Auth::id())) {
                return response()->json(['error' => 'Non autorisé'], 403);
            }
            
            $messages = GroupMessage::where('group_id', $group->id)
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($msg) {
                    return [
                        'id' => $msg->id,
                        'user_id' => $msg->user_id,
                        'user_name' => $msg->user->name,
                        'content' => $msg->content,
                        'is_own' => $msg->user_id == Auth::id(),
                        'is_read' => true,
                        'time' => $msg->created_at->format('H:i'),
                        'is_group' => true,
                    ];
                });
                
            return response()->json($messages);
        }
        
        if ($conversation->user_one != Auth::id() && $conversation->user_two != Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        Message::where('conversation_id', $conversationId)
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $messages = Message::where('conversation_id', $conversationId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($message) {
                return [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'user_name' => $message->user->name,
                    'content' => $message->content,
                    'is_own' => $message->user_id == Auth::id(),
                    'is_read' => $message->is_read,
                    'time' => $message->created_at->format('H:i'),
                    'is_group' => false,
                ];
            });

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|string|max:1000'
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);
        
        if ($conversation->is_group) {
            return $this->sendGroupMessage($request);
        }
        
        if ($conversation->user_one != Auth::id() && $conversation->user_two != Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'is_read' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'id' => $message->id,
            'user_id' => $message->user_id,
            'user_name' => Auth::user()->name,
            'content' => $message->content,
            'is_own' => true,
            'is_read' => false,
            'time' => $message->created_at->format('H:i'),
            'is_group' => false,
        ]);
    }

    public function getConversations()
    {
        $privateConvs = Conversation::where('is_group', false)
            ->where(function($q) {
                $q->where('user_one', Auth::id())->orWhere('user_two', Auth::id());
            })
            ->with(['userOne', 'userTwo', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function($conv) {
                $otherUser = $conv->user_one == Auth::id() ? $conv->userTwo : $conv->userOne;
                return [
                    'id' => $conv->id,
                    'type' => 'private',
                    'user_id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'avatar' => strtoupper(substr($otherUser->name, 0, 1)),
                    'last_message' => $conv->lastMessage ? $conv->lastMessage->content : null,
                    'last_message_time' => $conv->last_message_at ? $conv->last_message_at->diffForHumans() : null,
                    'unread_count' => $conv->unreadMessagesFor(Auth::id()),
                ];
            });
            
        $groupConvs = Conversation::where('is_group', true)
            ->whereHas('group', function($q) {
                $q->whereHas('members', function($sq) {
                    $sq->where('user_id', Auth::id());
                });
            })
            ->with('group')
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function($conv) {
                $lastMsg = GroupMessage::where('group_id', $conv->group_id)->latest()->first();
                return [
                    'id' => $conv->id,
                    'type' => 'group',
                    'group_id' => $conv->group_id,
                    'name' => $conv->group->name,
                    'avatar' => $conv->group->icon,
                    'last_message' => $lastMsg ? $lastMsg->content : null,
                    'last_message_time' => $conv->last_message_at ? $conv->last_message_at->diffForHumans() : null,
                    'unread_count' => 0,
                    'member_count' => $conv->group->member_count,
                    'description' => $conv->group->description,
                    'category' => $conv->group->category,
                    'is_member' => true,
                ];
            });
            
        $allConversations = $privateConvs->concat($groupConvs)->sortByDesc('last_message_time')->values();
        
        return response()->json($allConversations);
    }

    public function getGroupMembers($groupId)
    {
        $group = ChatGroup::findOrFail($groupId);
        
        if (!$group->isMember(Auth::id())) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
        
        $members = $group->members()->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'initials' => strtoupper(substr($user->name, 0, 2)),
            ];
        });
        
        return response()->json($members);
    }

    public function getUsers()
    {
        $users = User::where('id', '!=', Auth::id())
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'initials' => strtoupper(substr($user->name, 0, 2)),
                ];
            });
            
        return response()->json($users);
    }

    public function markAsRead($conversationId)
    {
        Message::where('conversation_id', $conversationId)
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }

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

        $post = Post::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'content' => $request->content,
            'image_path' => $imagePath
        ]);

        return response()->json(['success' => true, 'post' => $post]);
    }

    public function comment(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'post_id' => 'required'
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
            'content' => $request->content
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'user_name' => $comment->user->name,
                'user_initial' => strtoupper(substr($comment->user->name, 0, 1)),
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
                'is_owner' => true
            ]
        ]);
    }

    // Modification d'un commentaire
    public function updateComment(Request $request, $id)
    {
        $comment = Comment::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $comment->update(['content' => $request->content]);
        
        return response()->json(['success' => true, 'comment' => $comment]);
    }

    // Suppression d'un commentaire
    public function deleteComment($id)
    {
        $comment = Comment::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $comment->delete();
        
        return response()->json(['success' => true]);
    }

    public function like($id)
    {
        $post = Post::findOrFail($id);
        $like = Like::where('post_id', $id)
                    ->where('user_id', auth()->id())
                    ->first();

        if ($like) {
            $like->delete();
            $likesCount = $post->likes()->count();
            return response()->json(['success' => true, 'liked' => false, 'likes' => $likesCount]);
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'post_id' => $id
            ]);
            $likesCount = $post->likes()->count();
            return response()->json(['success' => true, 'liked' => true, 'likes' => $likesCount]);
        }
    }

    public function updatePost(Request $request, $id)
    {
        $post = Post::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $data = ['content' => $request->content];
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
            $data['image_path'] = $imagePath;
        }
        
        if ($request->has('remove_image') && $request->remove_image == '1') {
            $data['image_path'] = null;
        }
        
        $post->update($data);
        
        return response()->json(['success' => true]);
    }

    public function deletePost($id)
    {
        $post = Post::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $post->delete();
        return response()->json(['success' => true]);
    }
}
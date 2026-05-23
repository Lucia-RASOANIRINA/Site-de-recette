<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecetteController;
use App\Http\Controllers\CommunityController;

Route::get('/', [RecetteController::class, 'index']);

Route::get('/login', function () {
    return view('page.login'); 
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/UserHome', [RecetteController::class, 'userIndex'])->middleware('auth')->name('user.home');
Route::post('/recettes/{id}/like', [RecetteController::class, 'like'])->middleware('auth');

Route::get('/community', [CommunityController::class, 'index'])->name('community.public');
Route::get('/UserCommunity', [CommunityController::class, 'userCommunity'])->middleware('auth')->name('community.user');

Route::middleware(['auth'])->group(function () {
    Route::post('/community/post', [CommunityController::class, 'store']);
    Route::post('/community/comment', [CommunityController::class, 'comment']);
    Route::post('/community/like/{id}', [CommunityController::class, 'like']);
    
    // Routes pour les commentaires (modification et suppression)
    Route::put('/community/comment/{id}', [CommunityController::class, 'updateComment']);
    Route::delete('/community/comment/{id}', [CommunityController::class, 'deleteComment']);
    
    Route::post('/community/start-conversation', [CommunityController::class, 'startConversation']);
    Route::get('/community/messages/{conversationId}', [CommunityController::class, 'getMessages']);
    Route::post('/community/send-message', [CommunityController::class, 'sendMessage']);
    Route::get('/community/conversations', [CommunityController::class, 'getConversations']);
    Route::post('/community/mark-as-read/{conversationId}', [CommunityController::class, 'markAsRead']);
    Route::get('/community/users', [CommunityController::class, 'getUsers']);
    
    Route::post('/community/join-group/{groupId}', [CommunityController::class, 'joinGroup']);
    Route::post('/community/leave-group/{groupId}', [CommunityController::class, 'leaveGroup']);
    Route::get('/community/group-messages/{groupId}', [CommunityController::class, 'getGroupMessages']);
    Route::post('/community/send-group-message', [CommunityController::class, 'sendGroupMessage']);
    Route::get('/community/groups', [CommunityController::class, 'getGroups']);
    Route::get('/community/group-members/{groupId}', [CommunityController::class, 'getGroupMembers']);
    Route::post('/community/start-group-conversation/{groupId}', [CommunityController::class, 'startGroupConversation']);
    Route::post('/community/generate-ai-challenge', [CommunityController::class, 'generateNewAiChallenge']);

    // Routes pour les posts (modification et suppression)
    Route::put('/community/post/{id}', [CommunityController::class, 'updatePost']);
    Route::delete('/community/post/{id}', [CommunityController::class, 'deletePost']);
});
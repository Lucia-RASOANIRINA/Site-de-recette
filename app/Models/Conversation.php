<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['user_one', 'user_two', 'group_id', 'is_group', 'last_message_at'];

    protected $casts = [
        'last_message_at' => 'datetime',
        'is_group' => 'boolean',
    ];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two');
    }

    public function group()
    {
        return $this->belongsTo(ChatGroup::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    public function getOtherUser($userId)
    {
        if ($this->is_group) {
            return null;
        }
        return $this->user_one == $userId ? $this->userTwo : $this->userOne;
    }

    public function unreadMessagesFor($userId)
    {
        if ($this->is_group) {
            return 0;
        }
        
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }
}
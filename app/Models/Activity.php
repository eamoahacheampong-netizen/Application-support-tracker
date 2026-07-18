<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'status', 'severity'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // NEW: A single activity can have many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Allow these fields to be saved
    protected $fillable = ['activity_id', 'user_id', 'body', 'file_path',];

    // Relationship: This comment belongs to a specific ticket
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    // Relationship: This comment belongs to the engineer who wrote it
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
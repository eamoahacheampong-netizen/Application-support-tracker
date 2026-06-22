<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Activity $activity)
    {
        // 1. Security validation: Ensure they actually typed something
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        // 2. Save the comment and link it to the current engineer and the specific ticket
        $activity->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body
        ]);

        return back();
    }
}
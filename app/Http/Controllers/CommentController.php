<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Activity $activity)
    {
        // 1. Validate the incoming data (ensure the file is safe)
        $request->validate([
            'body' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5MB
        ]);

        // 2. Handle the file upload if one exists
        $filePath = null;
        if ($request->hasFile('attachment')) {
            // This saves the file to storage/app/public/attachments
            $filePath = $request->file('attachment')->store('attachments', 'public');
        }

        // 3. Create the comment and save the file path to the database
        $activity->comments()->create([
            'body' => $request->body,
            'user_id' => auth()->id(),
            'file_path' => $filePath,
        ]);

        return back();
    }
}
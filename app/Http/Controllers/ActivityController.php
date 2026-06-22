<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the incoming data to ensure no bad data gets in
        $request->validate([
            'title' => 'required|string|max:255',
            'severity' => 'required|string|in:Low,Medium,High',
        ]);

        // 2. Save the ticket with the new severity field
        Activity::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'severity' => $request->severity, // Capturing the new severity level!
            'status' => 'Pending',
        ]);

        return back();
    }

    public function update(Request $request, Activity $activity)
    {
        $activity->update([
            'status' => $request->status
        ]);
        
        return back();
    }
}
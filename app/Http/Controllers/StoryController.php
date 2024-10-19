<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StoryController extends Controller
{
    public function index()
    {
        try {
            $stories = Story::all();
            return view('stories.index', compact('stories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to fetch stories at the moment: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new story.
     */
    public function create()
    {
        try {
            $users = User::all();
            return view('stories.create', compact('users'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to load the story creation form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created story in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'branch_count' => 'required|integer|min:1',
                'section_count' => 'required|integer|min:1',
            ]);

            // Create a new story
            Story::create([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'description' => $request->description,
                'branch_count' => $request->branch_count,
                'section_count' => $request->section_count,
            ]);

            return redirect()->route('stories.index')->with('success', 'Story created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the story: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified story.
     */
    public function show(Story $story)
    {
        try {
            return view('stories.show', compact('story'));
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to display the story: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified story.
     */
    public function edit(Story $story)
    {
        try {
            return view('stories.edit', compact('story'));
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to load the edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified story in storage.
     */
    public function update(Request $request, Story $story)
    {
        try {
            // Validate the request data
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'branch_count' => 'required|integer|min:1',
                'section_count' => 'required|integer|min:1',
            ]);

            // Update the story
            $story->update([
                'title' => $request->title,
                'description' => $request->description,
                'branch_count' => $request->branch_count,
                'section_count' => $request->section_count,
            ]);

            return redirect()->route('stories.index')->with('success', 'Story updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the story: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified story from storage.
     */
    public function destroy(Story $story)
    {
        try {
            $story->delete();

            return redirect()->route('stories.index')->with('success', 'Story deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the story: ' . $e->getMessage());
        }
    }

    public function userStories(User $user)
    {
        try {
            // Fetch stories related to the user
            $stories = $user->stories; // Assumes you have a 'stories' relationship in User model
            return view('stories.user_stories', compact('user', 'stories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to fetch stories for this user: ' . $e->getMessage());
        }
    }
}

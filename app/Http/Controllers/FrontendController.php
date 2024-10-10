<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    public function index()
    {
        try {
            return view('frontend.index');
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to fetch this page: ' . $e->getMessage());
        }
    }

    public function stories(Request $request)
    {
        try {
            $search = $request->get('search');
            $filter = $request->get('filter');

            // Get stories based on the search query and filter
            $stories = Story::when($search, function ($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->when($filter === 'my', function ($query) {
                return $query->where('user_id', Auth::id());
            })
            ->latest()
            ->paginate(10);
            return view('frontend.stories', compact('stories', 'search', 'filter'));
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to fetch this page: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('frontend.create');
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to fetch this page: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Create a new story
        Story::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(), // Associate the story with the authenticated user
        ]);

        return redirect()->route('frontend.create')->with('success', 'Story created successfully!');
    }

    public function show(Story $story)
    {
        $story->load('sections.branches');
        return view('frontend.show', compact('story'));
    }
}

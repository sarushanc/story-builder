<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Section;
use App\Models\Story;
use Barryvdh\DomPDF\Facade\Pdf;
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
        try {
            // Validate the request data
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'branch_count' => 'required|integer|min:1',
                'section_count' => 'required|integer|min:1',
                'multimedia' => 'nullable|string',
            ]);

            // Create a new story
            Story::create([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'description' => $request->description,
                'branch_count' => $request->branch_count,
                'section_count' => $request->section_count,
                'multimedia' => $request->multimedia,
            ]);

            return redirect()->route('frontend.stories')->with('success', 'Story created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the story: ' . $e->getMessage());
        }
    }



    public function show(Story $story)
    {
        $story->load('sections.branches');
        return view('frontend.show', compact('story'));
    }

    public function sectionStore(Request $request)
    {
        try {
            $request->validate([
                'story_id' => 'required|exists:stories,id',
                'content' => 'required|string',
                'parent_id' => 'nullable|exists:sections,id',
                'multimedia' => 'nullable|string',
            ]);

            // Retrieve the story from the request
            $story = Story::findOrFail($request->story_id);

            $isRoot = $request->parent_id === null;

            if ($isRoot) {
                if ($story->section_count <= 0) {
                    return back()->with('error', 'Cannot add branches. The section limit has been reached.');
                }

                // If the section is a root-level branch
                $directBranches = $story->sections()->whereNull('parent_id')->count();
                // Check if the total branch limit is reached
                if ($directBranches >= $story->branch_count) {
                    return back()->with('error', 'Cannot add more branches. Branch limit for the story reached.');
                }

                $branchLevel = $directBranches + 1;
                $sectionNumber = 1;
            } else {
                // If adding a child section (branch)
                $parentSection = Section::findOrFail($request->parent_id);

                if (!$parentSection) {
                    return back()->with('error', 'Parent section not found.');
                }

                if ($parentSection->section_number >= $story->section_count) {
                    return back()->with('error', 'Cannot add branches. The section limit has been reached.');
                }

                // Count child sections under the parent section
                $parentChildrenCount = $parentSection->branches()->count();

                // Enforce subsection limit for the parent section
                if ($parentChildrenCount >= $story->branch_count) {
                    return back()->with('error', 'Cannot add more branches. Section limit for this parent section reached.');
                }

                $branchLevel = $parentSection->branches()->count() + 1;
                $sectionNumber = $parentSection->section_number + 1;
            }

            // Create the new section (either branch or subsection)
            $story->sections()->create([
                'user_id' => Auth::id(),
                'parent_id' => $request->parent_id,
                'content' => $request->content,
                'multimedia' => $request->multimedia,
                'section_number' => $sectionNumber,
                'branch_level' => $branchLevel,
            ]);

            return back()->with('success', 'Section created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to create the section for this story: ' . $e->getMessage());
        }
    }

    public function like($sectionId)
    {
        $section = Section::findOrFail($sectionId);
        $userId = Auth::id();

        // Check if the user already liked this section
        $existingLike = Like::where('section_id', $sectionId)->where('user_id', $userId)->first();

        if ($existingLike) {
            return back()->with('error', 'You have already liked this section.');
        }

        // Create a new like
        Like::create([
            'section_id' => $sectionId,
            'user_id' => $userId,
        ]);

        return back()->with('success', 'You liked this section.');
    }

    public function downloadEbook($id)
    {
        $story = Story::find($id);

        // Get the most liked section
        $mostLikedSection = $story->sections()
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->first();

        // Get all ancestors of the most liked section
        $ancestorSections = $mostLikedSection->getAncestors();

        // Load the view into PDF
        $pdf = PDF::loadView('frontend.ebook', compact('story', 'mostLikedSection', 'ancestorSections'));

        // Download the generated PDF
        return $pdf->download($story->title . '_ebook.pdf');
    }
}

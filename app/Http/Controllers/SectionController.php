<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SectionController extends Controller
{
    public function index(Story $story)
    {
        try {
            // Fetch direct branches of the story (where 'parent_id' is null)
            $branches = $story->sections()->whereNull('parent_id')->get();

            // Return the view with story and branches
            return view('sections.index', compact('story', 'branches'));
        } catch (\Exception $e) {
            // Log the error and redirect back with an error message
            Log::error('Error fetching branches for story: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to fetch branches for this story.');
        }
    }

    public function create(Story $story, $parent_id = null)
    {
        // Fetch the sections of the story for the dropdown if needed
        $sections = $story->sections;

        // Pass the parent_id to the view
        return view('sections.create', compact('story', 'sections', 'parent_id'));
    }

    public function store(Request $request, Story $story)
    {
        try {
            $request->validate([
                'content' => 'required|string',
                'parent_id' => 'nullable|exists:sections,id',
                'multimedia' => 'nullable|string',
            ]);

            // Determine if we are adding to the root or to a subsection
            $isRoot = $request->parent_id === null;

            // If adding a root branch (direct branch of the story)
            if ($isRoot) {
                // Count direct branches of the story (sections with no parent)
                $directBranches = $story->sections()->whereNull('parent_id')->count();

                // Enforce story-wide branch limit
                if ($directBranches >= $story->branch_count) {
                    return redirect()->route('stories.branches', $story->id)
                        ->with('error', 'Cannot add more branches. Branch limit for the story reached.');
                }

                // Determine section number for the new direct branch
                $sectionNumber = $directBranches + 1;
                $branchLevel = 1; // Root branch has level 1
            } else {
                // Adding a subsection or branch under a specific section (not root)
                $parentSection = Section::find($request->parent_id);
                if (!$parentSection) {
                    return redirect()->route('stories.branches', $story->id)
                        ->with('error', 'Parent section not found.');
                }

                // Count existing children for this parent section (both branches and subsections)
                $parentChildrenCount = $parentSection->children()->count();

                // Enforce section limit per section (parent section)
                if ($parentChildrenCount >= $story->section_count) {
                    return redirect()->route('sections.show', ['story' => $story->id, 'section' => $parentSection->id])
                        ->with('error', 'Cannot add more sections. Section limit for this parent section reached.');
                }

                // Count direct branches (immediate children) under this parent section
                $parentDirectBranches = $parentSection->children()->whereNull('parent_id')->count();

                // Enforce branch limit for this parent section
                if ($parentDirectBranches >= $story->branch_count) {
                    return redirect()->route('sections.show', ['story' => $story->id, 'section' => $parentSection->id])
                        ->with('error', 'Cannot add more branches. Branch limit for this parent section reached.');
                }

                // The branch level remains the same as the parent's branch level
                $branchLevel = $parentSection->branch_level;
                $sectionNumber = $parentSection->children()->count() + 1; // Get the next section number under this parent
            }

            // Create the new section (branch or subsection)
            $newSection = $story->sections()->create([
                'user_id' => Auth::id(),
                'parent_id' => $request->parent_id,
                'content' => $request->content,
                'multimedia' => $request->multimedia,
                'section_number' => $sectionNumber,
                'branch_level' => $branchLevel, // Keep the branch level the same
            ]);

            return back()->with('success', 'Section created successfully.');
        } catch (\Exception $e) {
            // Handle the exception (for debugging or logging)
            dd($e);
        }
    }

    public function edit(Story $story, Section $section)
    {
        // Ensure the section belongs to the given story
        if ($section->story_id != $story->id) {
            return redirect()->route('stories.branches', $story->id)->with('error', 'Section not found in this story.');
        }

        // Fetch existing sections of the story for potential parent sections
        $sections = $story->sections;

        return view('sections.edit', compact('story', 'section', 'sections'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:sections,id',
            'multimedia' => 'nullable|string',
        ]);

        $section->update([
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'multimedia' => $request->multimedia,
        ]);

        return redirect()->route('stories.branches', $section->story_id)->with('success', 'Section updated successfully.');
    }

    public function destroy(Story $story, Section $section)
    {
        if ($section->story_id != $story->id) {
            return redirect()->route('stories.branches', $story->id)->with('error', 'Section not found in this story.');
        }

        $section->delete();

        return redirect()->route('stories.branches', $story->id)->with('success', 'Section deleted successfully.');
    }

    public function show(Story $story, Section $section)
    {
        // Ensure the section belongs to the specified story
        if ($section->story_id != $story->id) {
            return redirect()->route('stories.branches', $story->id)->with('error', 'Section not found in this story.');
        }

        // Return the view to show the section details
        return view('sections.show', compact('story', 'section'));
    }

    public function showBranches(Story $story, Section $section)
    {
        // Get all child sections of the current section
        $childSections = $section->children; // Assuming you have a relationship 'children' in the Section model

        return view('sections.branches', compact('story', 'section', 'childSections'));
    }
}

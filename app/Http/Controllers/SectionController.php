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
            $branches = $story->branches;

            // Return the view with story and branches
            return view('sections.index', compact('story', 'branches'));
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to fetch branches for this story: ' . $e->getMessage());
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
            // Validate the request
            $request->validate([
                'content' => 'required|string',
                'parent_id' => 'nullable|exists:sections,id',
                'multimedia' => 'nullable|string',
            ]);

            $totalSections = $story->sections()->count();

            // Check if the total number of sections exceeds the story's section limit
            if ($totalSections >= $story->section_count) {
                return back()->with('error', 'Cannot add more sections. Total section limit for the story reached.');
            }

            $isRoot = $request->parent_id === null;

            if ($isRoot) {
                $directBranches = $story->branches()->whereNull('parent_id')->count();

                if ($directBranches >= $story->branch_count) {
                    return back()->with('error', 'Cannot add more branches. Branch limit for the story reached.');
                }

                $branchLevel = $directBranches + 1;
                $sectionNumber = 1;
            } else {
                // If adding a subsection (not a root-level branch)
                $parentSection = Section::find($request->parent_id);

                if (!$parentSection) {
                    return back()->with('error', 'Parent section not found.');
                }

                // Count subsections (child sections) under the parent section
                $parentChildrenCount = $parentSection->branches()->count();

                // Enforce subsection limit for the parent section
                if ($parentChildrenCount >= $story->branch_count) {
                    return back()->with('error', 'Cannot add more sections. Section limit for this parent section reached.');
                }

                $branchLevel = $parentSection->branches()->count() + 1;
                // Determine the section number
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

            // Section::recalculateSections($story);
            return back()->with('success', 'Section created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to create the section for this story: ' . $e->getMessage());
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

        return back()->with('success', 'Section updated successfully.');
    }

    public function destroy(Story $story, Section $section)
    {
        if ($section->story_id != $story->id) {
            return redirect()->route('stories.branches', $story->id)->with('error', 'Section not found in this story.');
        }

        $section->delete();

        return back()->with('success', 'Section deleted successfully.');
    }

    public function show(Story $story, Section $section)
    {
        if ($section->story_id != $story->id) {
            return redirect()->route('stories.branches', $story->id)->with('error', 'Section not found in this story.');
        }
        $childSections = $section->children ?? collect();
        return view('sections.show', compact('story', 'section', 'childSections'));
    }

    public function showBranches(Story $story, Section $section)
    {
        $childSections = $section->children ?? collect();

        return view('sections.branches', compact('story', 'section', 'childSections'));
    }
}

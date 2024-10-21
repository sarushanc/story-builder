<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Like;
use App\Models\Multimedia;
use App\Models\Section;
use App\Models\Story;
use App\Models\User;
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
            $userFilter = $request->get('user_filter'); // New filter for user stories

            // Get stories based on the search query and filter
            $stories = Story::when($search, function ($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->when($filter === 'my', function ($query) {
                return $query->where('user_id', Auth::id());
            })
            ->when($userFilter, function ($query) use ($userFilter) {
                return $query->where('user_id', $userFilter); // Filter by selected user
            })
            ->latest()
            ->paginate(10);

            // Fetch all users for the user filter dropdown
            $users = User::all();

            return view('frontend.stories', compact('stories', 'search', 'filter', 'users', 'userFilter'));
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
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'branch_count' => 'required|integer|min:1',
                'section_count' => 'required|integer|min:1',
                'multimedia.*' => 'nullable|file|mimes:jpg,jpeg,png,bmp,gif,svg,mp4,mov,avi,mp3,wav|max:10240',
            ]);

            // Create a new story
            $story = Story::create([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'description' => $request->description,
                'branch_count' => $request->branch_count,
                'section_count' => $request->section_count,
            ]);

            if ($request->hasFile('multimedia')) {
                foreach ($request->file('multimedia') as $file) {
                    // Upload file to a storage (like S3 or local)
                    $path = $file->store('multimedia', [
                        'disk' => 's3',
                        'visibility' => 'public',
                    ]);

                    // Save multimedia information in a separate table
                    Multimedia::create([
                        'mediable_id' => $story->id,
                        'mediable_type' => Story::class,
                        'file_path' => $path,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            return redirect()->route('story.stories')->with('success', 'Story created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the story: ' . $e->getMessage());
        }
    }

    public function show(Story $story)
    {
        $story->load('sections.branches', 'multimedias');
        $hasLikedSections = $story->sections()->whereHas('likes')->exists();
        return view('frontend.show', compact('story', 'hasLikedSections'));
    }

    public function sectionStore(Request $request)
    {
        try {
            $request->validate([
                'story_id' => 'required|exists:stories,id',
                'content' => 'required|string',
                'parent_id' => 'nullable|exists:sections,id',
                'multimedia.*' => 'nullable|file|mimes:jpg,jpeg,png,bmp,gif,svg,mp4,mov,avi,mp3,wav|max:10240',
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
            $section = $story->sections()->create([
                'user_id' => Auth::id(),
                'parent_id' => $request->parent_id,
                'content' => $request->content,
                'section_number' => $sectionNumber,
                'branch_level' => $branchLevel,
            ]);

            if ($request->hasFile('multimedia')) {
                foreach ($request->file('multimedia') as $file) {
                    $filePath = $file->store('multimedia', [
                        'disk' => 's3',
                        'visibility' => 'public',
                    ]);
                    $fileType = $file->getMimeType(); // Get the file's MIME type
                    $fileSize = $file->getSize(); // Get the file's size in bytes

                    // Create multimedia record associated with the section
                    $section->multimedias()->create([
                        'file_path' => $filePath,
                        'file_type' => $fileType,
                        'file_size' => $fileSize,
                    ]);
                }
            }

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

    public function destroyStory(Story $story)
    {
        try {
            if (!Auth::user()->isAdmin) {
                return back()->with('error', 'You do not have permission to delete this story.');
            }
            $story->delete();

            return redirect()->route('story.index')->with('success', 'Story deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the story: ' . $e->getMessage());
        }
    }

    public function storyMultimedia(Request $request)
    {
        try {
            $request->validate([
                'story_id' => 'required|exists:stories,id',
                'multimedia.*' => 'required|file|mimes:jpg,jpeg,png,bmp,gif,svg,mp4,mov,avi,mp3,wav|max:10240',
            ]);

            // Check if the story already has 3 multimedia items
            $existingCount = Multimedia::where('mediable_id', $request->story_id)
                ->where('mediable_type', Story::class)
                ->count();

            if ($existingCount >= 3) {
                return back()->with('error', 'You can only add up to 3 multimedia items.');
            }

            foreach ($request->file('multimedia') as $file) {
                if ($existingCount < 3) {
                    // Store the file
                    $path = $file->store('multimedia', [
                        'disk' => 's3',
                        'visibility' => 'public',
                    ]);

                    // Save multimedia information in the database
                    Multimedia::create([
                        'mediable_id' => $request->story_id,
                        'mediable_type' => Story::class,
                        'file_path' => $path,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);

                    $existingCount++; // Increment the count after each successful upload
                } else {
                    break; // Stop if the max count is reached
                }
            }

            return redirect()->back()->with('success', 'Multimedia added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while adding multimedia: ' . $e->getMessage());
        }
    }

    public function sectionMultimedia(Request $request, $sectionId)
    {
        // Find the section by ID
        $section = Section::findOrFail($sectionId);

        // Check if the authenticated user is either the section creator or an admin
        if (Auth::id() !== $section->user_id && !Auth::user()->isAdmin) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Ensure the section doesn't exceed the 3 multimedia limit
        if ($section->multimedias->count() >= 3) {
            return redirect()->back()->with('error', 'You can only add up to 3 multimedia items.');
        }

        // Validate the multimedia files
        $request->validate([
            'multimedia.*' => 'required|file|mimes:jpg,jpeg,png,bmp,gif,svg,mp4,mov,avi,mp3,wav|max:20480', // Max file size 20MB
        ]);

        // Handle the multimedia file uploads
        if ($request->hasFile('multimedia')) {
            // Calculate remaining number of multimedia items allowed
            $remainingSlots = 3 - $section->multimedias->count();
            $multimediaFiles = array_slice($request->file('multimedia'), 0, $remainingSlots); // Limit the number of files to the available slots

            foreach ($multimediaFiles as $file) {
                $filePath = $file->store('multimedia', [
                    'disk' => 's3',
                    'visibility' => 'public',
                ]);
                $fileType = $file->getMimeType(); // Get the file's MIME type
                $fileSize = $file->getSize();     // Get the file's size in bytes

                // Create multimedia record associated with the section
                $section->multimedias()->create([
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                    'file_size' => $fileSize,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Multimedia added successfully!');
    }

    public function rankings()
    {
        // Fetch users along with their points, ordered by points in descending order (highest first)
        $rankedUsers = Achievement::with('user')
            ->orderBy('points', 'desc')
            ->get();

        // Pass the ranked users to the view
        return view('frontend.rankings', compact('rankedUsers'));
    }
}

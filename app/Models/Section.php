<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'user_id',
        'parent_id',
        'section_number',
        'branch_level',
        'content',
        'multimedia',
    ];

    // Relationship to Story
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    // A section may have a parent section (if it's a subsection or nested under another section)
    public function parent()
    {
        return $this->belongsTo(Section::class, 'parent_id');
    }

    // A section can have many child branches (direct or nested)
    public function branches()
    {
        return $this->hasMany(Section::class, 'parent_id');
    }

    // Relationship to User (who created this section)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Likes for this section
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Check if the section is a root branch (i.e., no parent)
    public function isRootBranch()
    {
        return is_null($this->parent_id);
    }

    // Helper method to count the number of branches under this section
    public function totalBranchesCount()
    {
        return $this->branches()->count();
    }

    // Check if the section has child branches (i.e., branches or subsections)
    public function hasBranches()
    {
        return $this->branches()->exists();
    }

    // public static function recalculateSections($story)
    // {
    //     // Get all root-level sections (parent_id = null) for the story
    //     $rootSections = $story->sections()->whereNull('parent_id')->get();

    //     // Start the numbering and branch level for root sections
    //     $branchNumber = 1;
    //     foreach ($rootSections as $rootSection) {
    //         // Update root section number and branch level
    //         $rootSection->update([
    //             'section_number' => 1,
    //             'branch_level' => $branchNumber++,
    //         ]);

    //         // Recursively update subsections (children) of the root section
    //         self::recalculateChildSections($rootSection, 1);
    //     }
    // }

    // /**
    //  * Recursive function to update section_number and branch_level for child sections.
    //  */
    // protected static function recalculateChildSections($parentSection, $currentSectionNumber)
    // {
    //     // Fetch the child sections (direct branches) of the parent section
    //     $childSections = $parentSection->branches()->get();

    //     // Initialize section number for the children
    //     $nextSectionNumber = $currentSectionNumber + 1;
    //     $branchNumber = 1;

    //     foreach ($childSections as $childSection) {
    //         // Update the section number and branch level for each child
    //         $childSection->update([
    //             'section_number' => $nextSectionNumber,  // Incremented section number for each child
    //             'branch_level' => $branchNumber,
    //         ]);

    //         // Recursively update the subsections of this child
    //         self::recalculateChildSections($childSection, $nextSectionNumber);

    //         $branchNumber++;
    //     }
    // }
}

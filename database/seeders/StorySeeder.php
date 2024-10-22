<?php

namespace Database\Seeders;

use App\Models\Story;
use App\Models\Section;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    public function run()
    {
        // Create two stories
        $stories = [
            [
                'title' => 'Adventure in the Jungle',
                'description' => 'A thrilling adventure through the dense jungle filled with unexpected surprises.',
                'branch_count' => 2,  // Number of branches per section
                'section_count' => 3,  // Levels of sections
                'sections' => [
                    'The journey begins as the explorers step into the lush green jungle. Excitement fills the air.',
                    'They encounter a mysterious tribe living in harmony with nature.',
                    'After days of exploring, the team finds a hidden waterfall.',
                ],
            ],
            [
                'title' => 'Journey to the Moon',
                'description' => 'An imaginative story about traveling to the moon and exploring its mysteries.',
                'branch_count' => 2,  // Number of branches per section
                'section_count' => 3,  // Levels of sections
                'sections' => [
                    'In a small town, a group of children build a rocket to reach the moon.',
                    'Blast off day arrives, and they launch into the sky with excitement.',
                    'Upon landing on the moon, they discover a magical landscape.',
                ],
            ],
        ];

        foreach ($stories as $storyData) {
            $story = Story::create([
                'user_id' => 1, // Assuming the user with ID 1 exists
                'title' => $storyData['title'],
                'description' => $storyData['description'],
                'branch_count' => $storyData['branch_count'],
                'section_count' => $storyData['section_count'],
            ]);

            // Create sections and branches
            $this->createSections($story, $storyData['sections'], $storyData['branch_count'], $storyData['section_count']);
        }
    }

    private function createSections(Story $story, array $sectionsData, int $branchCount, int $sectionCount)
    {
        foreach ($sectionsData as $index => $sectionContent) {
            // Create the root section
            $section = $story->sections()->create([
                'user_id' => 1, // Assuming the user with ID 1 exists
                'content' => $sectionContent,
                'section_number' => $index + 1,
                'branch_level' => 1,
                'parent_id' => null, // Root section has no parent
                'story_id' => $story->id, // Set the story_id
            ]);

            // Create branches for the root section
            $this->createBranches($section, $branchCount, $sectionCount, 1, $story->id);
        }
    }

    private function createBranches(Section $parentSection, int $branchCount, int $sectionCount, int $currentLevel, int $storyId)
    {
        // Check if we can create more levels
        if ($currentLevel < $sectionCount) {
            for ($i = 1; $i <= $branchCount; $i++) {
                // Create the branch
                $branch = $parentSection->branches()->create([
                    'user_id' => 1, // Assuming the user with ID 1 exists
                    'content' => "Branch $i of Section {$parentSection->section_number}, Level $currentLevel.",
                    'section_number' => $i,
                    'branch_level' => $currentLevel + 1,
                    'parent_id' => $parentSection->id, // Set parent to current section
                    'story_id' => $storyId, // Set the story_id
                ]);

                // Create sub-branches
                $this->createBranches($branch, $branchCount, $sectionCount, $currentLevel + 1, $storyId);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Story;
use App\Models\Section;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'isAdmin' => true,
        ]);

        // Create a regular user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'isAdmin' => false,
        ]);

        // Create first story
        $story1 = Story::create([
            'user_id' => $testUser->id,
            'title' => 'The Adventure Begins',
            'description' => 'This is a tale of bravery, mystery, and great adventure. Follow the characters as they embark on a quest filled with peril and excitement.',
            'branch_count' => 3,
            'section_count' => 3,
        ]);

        // Add root section and branches for story1
        $rootSection1 = Section::create([
            'story_id' => $story1->id,
            'user_id' => $testUser->id,
            'content' => 'Once upon a time in a land far away, there was a small village hidden deep within a magical forest. Here began the great adventure of a young boy destined for greatness.',
            'section_number' => 1,
            'branch_level' => 1,
            'parent_id' => null, // Root section
        ]);

        // First branch for root section
        $branch1 = Section::create([
            'story_id' => $story1->id,
            'user_id' => $testUser->id,
            'content' => 'The boy left his village with nothing but a map and an old sword handed down by his father. He knew the journey ahead would be treacherous, but his courage never wavered.',
            'section_number' => 2,
            'branch_level' => 2,
            'parent_id' => $rootSection1->id,
        ]);

        // Second branch for root section
        $branch2 = Section::create([
            'story_id' => $story1->id,
            'user_id' => $testUser->id,
            'content' => 'Along the way, he met a group of travelers who joined him. Together, they ventured into the unknown, facing creatures of legend and uncovering ancient secrets.',
            'section_number' => 3,
            'branch_level' => 3,
            'parent_id' => $rootSection1->id,
        ]);

        // Create second story
        $story2 = Story::create([
            'user_id' => $testUser->id,
            'title' => 'The Lost City',
            'description' => 'A daring expedition into the heart of a forgotten jungle in search of a legendary city lost to time.',
            'branch_count' => 3,
            'section_count' => 3,
        ]);

        // Add root section and branches for story2
        $rootSection2 = Section::create([
            'story_id' => $story2->id,
            'user_id' => $testUser->id,
            'content' => 'The team of explorers stood at the edge of the dense jungle, gazing at the horizon where the ancient city was said to be hidden. Their mission was clear, but the dangers unknown.',
            'section_number' => 1,
            'branch_level' => 1,
            'parent_id' => null, // Root section
        ]);

        // First branch for root section of second story
        $branch1Story2 = Section::create([
            'story_id' => $story2->id,
            'user_id' => $testUser->id,
            'content' => 'They pushed deeper into the jungle, battling the thick vegetation and extreme weather. Each step brought them closer to the unknown mysteries that lay ahead.',
            'section_number' => 2,
            'branch_level' => 2,
            'parent_id' => $rootSection2->id,
        ]);

        // Second branch for root section of second story
        $branch2Story2 = Section::create([
            'story_id' => $story2->id,
            'user_id' => $testUser->id,
            'content' => 'Amid the ruins, they found clues that pointed to an even greater discovery beneath the surface. The city wasn’t just lost; it was buried in time, waiting to be uncovered.',
            'section_number' => 3,
            'branch_level' => 3,
            'parent_id' => $rootSection2->id,
        ]);
    }
}

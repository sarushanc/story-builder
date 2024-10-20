<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'user_id',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($like) {
            $section = $like->section;
            $sectionUser = $section->user;
            $storyUser = $section->story->user;

            $sectionAchievement = Achievement::firstOrCreate(
                ['user_id' => $sectionUser->id],
                ['points' => 0]
            );
            $sectionAchievement->increment('points');

            // Award 1 point to story creator
            $storyAchievement = Achievement::firstOrCreate(
                ['user_id' => $storyUser->id],
                ['points' => 0]
            );
            $storyAchievement->increment('points');
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'branch_count',
        'section_count',
        'multimedia',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    // Get only the direct branches of the story (sections with no parent)
    public function branches()
    {
        return $this->hasMany(Section::class)->whereNull('parent_id');
    }
}

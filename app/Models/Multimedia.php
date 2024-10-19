<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multimedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'mediable_id',  // Allow mediable_id to be mass assignable
        'mediable_type',  // Allow mediable_type to be mass assignable
        'file_path',
        'file_type',
        'file_size',
    ];

    public function mediable()
    {
        return $this->morphTo();
    }
}

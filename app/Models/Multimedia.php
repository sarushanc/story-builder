<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multimedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'mediable_id', 
        'mediable_type',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function mediable()
    {
        return $this->morphTo();
    }
}

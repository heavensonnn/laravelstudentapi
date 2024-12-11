<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;
    protected $fillable = [
        'discussionid',
        'lesson_id',
        'Discussion_Topic',
        'created_at'
    ];
}
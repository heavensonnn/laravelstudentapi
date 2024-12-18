<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'Lesson_ID',
        'Module_ID',
        'Topic_Title',
        'Lesson',
        'Handout',
        'File'
    ];
}

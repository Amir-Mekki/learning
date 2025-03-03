<?php

namespace App\Modules\Assignments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Courses\Models\Course;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'course_id', 'due_date', 'max_score'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
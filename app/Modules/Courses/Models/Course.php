<?php

namespace App\Modules\Courses\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'department_id', 'teacher_id', 'type', 'level'];

    public const TYPES = [
        'online' => 'En ligne',
        'hybrid' => 'Hybride',
        'in_person' => 'En personne',
    ];

    public const LEVELS = [
        'certificate' => 'Certificat',
        'undergraduate' => 'Premier cycle',
        'postgraduate' => 'Deuxième cycle',
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}

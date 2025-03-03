<?php

namespace App\Modules\Courses\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'position',
    ];

    /**
     * Relation avec le cours
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relation avec les ressources associées
     */
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}

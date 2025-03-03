<?php

namespace App\Modules\Courses\Observers;

use App\Modules\Courses\Models\Enrollment;
use App\Modules\StudentPortal\Models\Achievement;

class EnrollmentObserver
{
    public function updated(Enrollment $enrollment)
    {
        $user_id = $enrollment->user_id;
        $course_id = $enrollment->course_id;

        // Badge : Première étape terminée
        if ($enrollment->progress >= 10 && !Achievement::where('user_id', $user_id)->where('title', 'Première étape')->exists()) {
            Achievement::create([
                'user_id' => $user_id,
                'course_id' => $course_id,
                'type' => 'badge',
                'title' => 'Première étape'
            ]);
        }

        // Badge : 50% du cours
        if ($enrollment->progress >= 50 && !Achievement::where('user_id', $user_id)->where('title', 'À mi-chemin')->exists()) {
            Achievement::create([
                'user_id' => $user_id,
                'course_id' => $course_id,
                'type' => 'badge',
                'title' => 'À mi-chemin'
            ]);
        }

        // Certificat : Cours terminé
        if ($enrollment->progress == 100 && !Achievement::where('user_id', $user_id)->where('title', 'Certificat de réussite')->exists()) {
            Achievement::create([
                'user_id' => $user_id,
                'course_id' => $course_id,
                'type' => 'certificat',
                'title' => 'Certificat de réussite'
            ]);
        }
    }
}

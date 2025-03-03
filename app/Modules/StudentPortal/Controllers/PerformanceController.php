<?php

namespace App\Modules\StudentPortal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Courses\Models\Enrollment;
use App\Modules\Courses\Models\Course;
use App\Modules\Assignments\Models\AssignmentSubmission;

class PerformanceController extends Controller
{
    /**
     * Récupérer les performances de l'étudiant connecté.
     */
    public function index()
    {
        $user = Auth::user();

        // Nombre total de cours inscrits
        $totalCourses = Enrollment::where('user_id', $user->id)->count();

        // Nombre de cours terminés
        $completedCourses = Enrollment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Progression moyenne sur tous les cours
        $averageProgress = Enrollment::where('user_id', $user->id)
            ->avg('progress');

        // Nombre total de devoirs soumis
        $totalAssignments = AssignmentSubmission::where('user_id', $user->id)->count();

        // Score moyen sur les devoirs
        $averageScore = AssignmentSubmission::where('user_id', $user->id)
            ->avg('score');

        return response()->json([
            'total_courses' => $totalCourses,
            'completed_courses' => $completedCourses,
            'average_progress' => round($averageProgress, 2),
            'total_assignments' => $totalAssignments,
            'average_score' => round($averageScore, 2),
        ]);
    }
}

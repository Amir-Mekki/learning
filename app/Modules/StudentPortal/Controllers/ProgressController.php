<?php

namespace App\Modules\StudentPortal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Courses\Models\Enrollment;

class ProgressController extends Controller
{
    /**
     * Afficher le tableau de bord des progrès de l'utilisateur authentifié.
     */
    public function index()
    {
        $user = Auth::id();

        $progress = Enrollment::where('user_id', $user)
            ->with(['course' => function ($query) {
                $query->withCount(['lessons as total_lessons'])
                      ->withCount(['lessons as completed_lessons' => function ($query) {
                          $query->where('status', 'completed');
                      }]);
            }])
            ->get()
            ->map(function ($enrollment) {
                return [
                    'course_name' => $enrollment->course->name,
                    'status' => $enrollment->status,
                    'progress' => $enrollment->progress,
                    'completed_lessons' => $enrollment->course->completed_lessons,
                    'total_lessons' => $enrollment->course->total_lessons,
                    'last_updated' => $enrollment->updated_at,
                ];
            });

        return response()->json($progress);
    }
}

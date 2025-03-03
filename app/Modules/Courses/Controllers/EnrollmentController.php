<?php

namespace App\Modules\Courses\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Courses\Models\Enrollment;
use App\Modules\Courses\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Afficher les inscriptions en fonction du rôle de l'utilisateur.
     */
    public function index()
    {
        $user = Auth::user();

        if (Auth::hasRole(config('keycloak.client_id'), 'admin')) {
            // Admin : voir toutes les inscriptions
            $enrollments = Enrollment::with('course')->paginate(10);
        } elseif (Auth::hasRole(config('keycloak.client_id'), 'department_head')) {
            // Chef de département : voir les inscriptions des étudiants de son département
            $enrollments = Enrollment::whereHas('course.department', function ($query) use ($user) {
                $query->where('department_head_id', $user->id);
            })->with(['course', 'course.department'])->paginate(10);
        } else {
            // Étudiant : voir ses propres inscriptions
            $enrollments = Enrollment::where('user_id', $user->id)->with('course')->paginate(10);
        }

        return response()->json($enrollments);
    }

    /**
     * Inscrire un étudiant à un cours.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (Auth::hasRole(config('keycloak.client_id'), 'admin') || Auth::hasRole(config('keycloak.client_id'), 'department_head')) {
            // Admin ou chef de département : inscrire un étudiant spécifique
            $request->validate([
                'student_id' => 'required|exists:users,id',
                'course_id' => 'required|exists:courses,id'
            ]);

            $student_id = $request->student_id;
        } else {
            // Étudiant : inscription personnelle
            $request->validate([
                'course_id' => 'required|exists:courses,id'
            ]);

            $student_id = $user->id;
        }

        // Vérifier si déjà inscrit
        if (Enrollment::where('user_id', $student_id)->where('course_id', $request->course_id)->exists()) {
            return response()->json(['message' => 'Already enrolled'], 409);
        }

        $enrollment = Enrollment::create([
            'user_id' => $student_id,
            'course_id' => $request->course_id,
            'status' => 'in_progress',
            'progress' => 0,
        ]);

        return response()->json($enrollment, 201);
    }

    /**
     * Mettre à jour le statut ou la progression d'une inscription (Admin et Chef de département uniquement).
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $user = Auth::user();

        if (!Auth::hasRole(config('keycloak.client_id'), 'admin') && !Auth::hasRole(config('keycloak.client_id'), 'department_head')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'student_id' => 'required|exists:users,id',
            'status' => 'in:in_progress,completed,cancelled',
            'progress' => 'integer|min:0|max:100'
        ]);

        $enrollment->update($request->only(['status', 'progress', 'student_id']));

        return response()->json($enrollment);
    }

    /**
     * Désinscrire un utilisateur d'un cours.
     */
    public function destroy(Enrollment $enrollment)
    {
        $this->authorize('delete', $enrollment);
        $enrollment->delete();

        return response()->json(['message' => 'Unenrolled successfully']);
    }
}

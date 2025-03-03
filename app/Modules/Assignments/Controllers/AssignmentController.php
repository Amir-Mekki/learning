<?php

namespace App\Modules\Assignments\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Assignments\Models\Assignment;
use App\Modules\Assignments\Models\AssignmentSubmission;
use App\Modules\Courses\Models\Enrollment;

class AssignmentController extends Controller
{
    /**
     * Lister les devoirs d'un cours.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Auth::hasRole(config('keycloak.client_id'), 'teacher')) {
            // Enseignant : voir ses propres devoirs
            $assignments = Assignment::where('teacher_id', $user->id)->with('course')->paginate(10);
        } elseif (Auth::hasRole(config('keycloak.client_id'), 'student')) {
            // Étudiant : voir les devoirs des cours où il est inscrit
            $assignments = Assignment::whereHas('course.enrollments', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with('course')->paginate(10);
        } else {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        return response()->json($assignments);
    }

    /**
     * Créer un nouveau devoir (enseignant seulement).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!Auth::hasRole(config('keycloak.client_id'), 'teacher')) {
            return response()->json(['message' => 'Seuls les enseignants peuvent créer des devoirs'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'due_date' => 'required|date',
        ]);

        $validated['teacher_id'] = $user->id;
        $assignment = Assignment::create($validated);

        return response()->json($assignment, 201);
    }

    /**
     * Voir un devoir spécifique.
     */
    public function show($id)
    {
        $assignment = Assignment::with('course')->findOrFail($id);
        return response()->json($assignment);
    }

    /**
     * Mettre à jour un devoir (enseignant seulement).
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $assignment = Assignment::findOrFail($id);

        if ($user->id !== $assignment->teacher_id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|date',
        ]);

        $assignment->update($validated);
        return response()->json($assignment);
    }

    /**
     * Supprimer un devoir (enseignant seulement).
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $assignment = Assignment::findOrFail($id);

        if ($user->id !== $assignment->teacher_id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $assignment->delete();
        return response()->json(['message' => 'Devoir supprimé avec succès']);
    }

    /**
     * Soumettre un devoir (étudiant seulement).
     */
    public function submit(Request $request, $assignmentId)
    {
        $user = Auth::user();

        if (!Auth::hasRole(config('keycloak.client_id'), 'student')) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $assignment = Assignment::findOrFail($assignmentId);

        // Vérifier si l'étudiant est inscrit au cours
        $isEnrolled = Enrollment::where('user_id', $user->id)->where('course_id', $assignment->course_id)->exists();

        if (!$isEnrolled) {
            return response()->json(['message' => 'Vous devez être inscrit au cours pour soumettre ce devoir'], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string',
            'file_path' => 'nullable|string',
        ]);

        $submission = AssignmentSubmission::create([
            'user_id' => $user->id,
            'assignment_id' => $assignmentId,
            'content' => $validated['content'],
            'file_path' => $validated['file_path'] ?? null,
            'status' => 'submitted',
        ]);

        return response()->json($submission, 201);
    }

    /**
     * Noter une soumission (enseignant seulement).
     */
    public function grade(Request $request, $submissionId)
    {
        $user = Auth::user();
        $submission = AssignmentSubmission::findOrFail($submissionId);
        $assignment = Assignment::findOrFail($submission->assignment_id);

        if ($user->id !== $assignment->teacher_id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'grade' => $validated['grade'],
            'feedback' => $validated['feedback'] ?? null,
            'status' => 'graded',
        ]);

        return response()->json($submission);
    }
}

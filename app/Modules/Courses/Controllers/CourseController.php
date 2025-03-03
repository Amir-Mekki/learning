<?php

namespace App\Modules\Courses\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Courses\Models\Course;

class CourseController extends Controller
{
    /**
     * Afficher la liste des cours avec pagination, tri et recherche.
     */
    public function index(Request $request)
    {
        $query = Course::with(['lessons', 'teacher', 'department']);

        // Recherche sur le nom et la description des cours
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrer par département
        if ($departmentId = $request->input('department_id')) {
            $query->where('department_id', $departmentId);
        }

        // Filtrer par enseignant
        if ($teacherId = $request->input('teacher_id')) {
            $query->where('teacher_id', $teacherId);
        }

        // Appliquer le tri
        $orderBy = $request->input('orderBy', 'created_at');
        $order = $request->input('order', 'desc');

        if (in_array($orderBy, ['name', 'description', 'created_at'])) {
            $query->orderBy($orderBy, $order);
        }

        // Pagination
        $pageLength = $request->input('pageLength', 10);
        $courses = $query->paginate($pageLength);

        return response()->json($courses);
    }

    /**
     * Afficher un cours spécifique.
     */
    public function show($id)
    {
        $course = Course::with(['lessons', 'teacher', 'department'])->findOrFail($id);
        return response()->json($course);
    }

    /**
     * Créer un nouveau cours.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'teacher_id' => 'required|exists:users,id',
            'type' => 'required|in:' . implode(',', array_keys(Course::TYPES)),
            'level' => 'required|in:' . implode(',', array_keys(Course::LEVELS)),
        ]);

        $course = Course::create($validated);
        return response()->json($course, 201);
    }

    /**
     * Mettre à jour un cours existant.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'sometimes|exists:departments,id',
            'teacher_id' => 'sometimes|exists:users,id',
            'type' => 'sometimes|in:' . implode(',', array_keys(Course::TYPES)),
            'level' => 'sometimes|in:' . implode(',', array_keys(Course::LEVELS)),
        ]);

        $course = Course::findOrFail($id);
        $course->update($validated);

        return response()->json($course);
    }

    /**
     * Supprimer un cours.
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(['message' => 'Cours supprimé avec succès']);
    }
}

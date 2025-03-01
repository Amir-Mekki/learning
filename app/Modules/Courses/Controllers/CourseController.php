<?php

namespace App\Modules\Courses\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Courses\Models\Course;

class CourseController extends Controller
{
    /**
     * Afficher la liste des cours.
     */
    public function index()
    {
        return response()->json(Course::all());
    }

    /**
     * Afficher un cours spécifique.
     */
    public function show($id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course);
    }

    /**
     * Créer un nouveau cours.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|integer',
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
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'sometimes|integer',
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

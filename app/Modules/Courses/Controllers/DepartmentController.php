<?php

namespace App\Modules\Courses\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Courses\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Afficher la liste des départements avec pagination, tri et recherche.
     */
    public function index(Request $request)
    {
        $query = Department::with('departmentHead');

        // Recherche par nom
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Tri
        $orderBy = $request->input('orderBy', 'created_at');
        $order = $request->input('order', 'desc');

        if (in_array($orderBy, ['name', 'created_at'])) {
            $query->orderBy($orderBy, $order);
        }

        // Pagination
        $pageLength = $request->input('pageLength', 10);
        $departments = $query->paginate($pageLength);

        return response()->json($departments);
    }

    /**
     * Afficher un département spécifique.
     */
    public function show($id)
    {
        $department = Department::with('departmentHead')->findOrFail($id);
        return response()->json($department);
    }

    /**
     * Créer un nouveau département.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'department_head_id' => 'nullable|exists:users,id',
        ]);

        $department = Department::create($validated);
        return response()->json($department, 201);
    }

    /**
     * Mettre à jour un département existant.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:departments,name,' . $id,
            'department_head_id' => 'sometimes|exists:users,id',
        ]);

        $department = Department::findOrFail($id);
        $department->update($validated);

        return response()->json($department);
    }

    /**
     * Supprimer un département.
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json(['message' => 'Département supprimé avec succès']);
    }
}

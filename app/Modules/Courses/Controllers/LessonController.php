<?php

namespace App\Modules\Courses\Controllers;

use App\Modules\Courses\Models\Lesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $query = Lesson::with(['resources' => function ($query) use ($request) {
            // Appliquer la recherche sur les ressources
            if ($search = $request->input('search')) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%");
            }
    
            // Appliquer le tri sur les ressources
            $orderBy = $request->input('orderBy', 'created_at');
            $order = $request->input('order', 'desc');
    
            if (in_array($orderBy, ['name', 'type', 'created_at'])) {
                $query->orderBy($orderBy, $order);
            }
        }]);
    
        // Appliquer la recherche sur les leçons
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
    
        // Appliquer le tri sur les leçons
        $orderBy = $request->input('orderBy', 'created_at');
        $order = $request->input('order', 'desc');
    
        if (in_array($orderBy, ['title', 'description', 'created_at'])) {
            $query->orderBy($orderBy, $order);
        }
    
        // Appliquer la pagination
        $pageLength = $request->input('pageLength', 10);
        
        $lessons = $query->paginate($pageLength);
    
        return response()->json($query->paginate($pageLength));
    }
   

    public function show($id)
    {
        $lesson = Lesson::with('resources')->findOrFail($id);
        return response()->json($lesson);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'position' => 'nullable|integer|min:1'
        ]);
        
        $lesson = Lesson::create($data);
        return response()->json($lesson, 201);
    }

    public function update(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'position' => 'nullable|integer|min:1'
        ]);
        
        $lesson->update($data);
        return response()->json($lesson);
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return response()->json(null, 204);
    }
}

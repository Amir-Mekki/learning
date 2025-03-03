<?php

namespace App\Modules\Courses\Controllers;

use App\Modules\Courses\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ResourceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'file' => 'required|file|max:10240'
        ]);
        
        $path = $request->file('file')->store('resources');
        
        $resource = Resource::create([
            'lesson_id' => $data['lesson_id'],
            'name' => $request->file('file')->getClientOriginalName(),
            'type' => $request->file('file')->getMimeType(),
            'path' => $path
        ]);
        
        return response()->json($resource, 201);
    }

    public function destroy(Resource $resource)
    {
        Storage::delete($resource->path);
        $resource->delete();
        return response()->json(null, 204);
    }
}

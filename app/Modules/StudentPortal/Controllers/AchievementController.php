<?php

namespace App\Modules\StudentPortal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\StudentPortal\Models\Achievement;

class AchievementController extends Controller
{
    /**
     * Récupérer les réussites de l'utilisateur authentifié.
     */
    public function index()
    {
        $user = Auth::id();
        $achievements = Achievement::where('user_id', $user)->get();

        return response()->json($achievements);
    }
}

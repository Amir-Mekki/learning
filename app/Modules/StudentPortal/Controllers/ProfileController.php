<?php

namespace App\Modules\StudentPortal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Afficher le profil de l'utilisateur authentifié.
     */
    public function show()
    {
        return response()->json(Auth::user());
    }

    /**
     * Mettre à jour le profil (nom et mot de passe).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['message' => 'Profil mis à jour avec succès', 'user' => $user]);
    }
}

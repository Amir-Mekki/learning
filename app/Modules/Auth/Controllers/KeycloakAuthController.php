<?php

namespace App\Modules\Auth\Controllers;

use Vizir\KeycloakWebGuard\Controllers\AuthController as BaseAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vizir\KeycloakWebGuard\Exceptions\KeycloakCallbackException;
use Vizir\KeycloakWebGuard\Facades\KeycloakWeb;

class KeycloakAuthController extends BaseAuthController
{
    public function callback(Request $request)
    {
        if (!empty($request->input('error'))) {
            $error = $request->input('error_description') ?: $request->input('error');
            throw new KeycloakCallbackException($error);
        }

        $state = $request->input('state');
        if (empty($state) || !KeycloakWeb::validateState($state)) {
            KeycloakWeb::forgetState();
            throw new KeycloakCallbackException('Invalid state');
        }

        $code = $request->input('code');
        if (!empty($code)) {
            $token = KeycloakWeb::getAccessToken($code);

            if (Auth::validate($token)) {
                $user = Auth::user();

                // Redirection selon le rôle de l'utilisateur
                if ($user->hasRole('admin')) {
                    return redirect('/admin');
                } elseif ($user->hasRole('teacher')) {
                    return redirect('/teacher');
                } elseif ($user->hasRole('department_head')) {
                    return redirect('/department-head');
                } elseif ($user->hasRole('student')) {
                    return redirect('/student');
                } elseif ($user->hasRole('guest')) {
                    return redirect('/guest');
                }

                return redirect('/dashboard');
            }
        }

        return redirect(route('keycloak.login'));
    }
}

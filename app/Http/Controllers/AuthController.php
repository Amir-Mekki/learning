<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class AuthController extends Controller
{
    // public function login()
    // {
    //     return Auth::guard('keycloak')->redirect();
    // }

    public function login()
    {
        $query = http_build_query([
            'client_id'     => config('keycloak.client_id'),
            'redirect_uri'  => config('keycloak.redirect_uri'),
            'response_type' => 'code',
            'scope'         => 'openid profile email',
        ]);

        return redirect(config('keycloak.base_url') . '/realms/' . config('keycloak.realm') . '/protocol/openid-connect/auth?' . $query);
    }

    public function callback(Request $request)
    {
        $code = $request->get('code');

        if (!$code) {
            return redirect('/login')->with('error', 'Code de Keycloak manquant');
        }

        $response = Http::asForm()->post(config('keycloak.base_url') . '/realms/' . config('keycloak.realm') . '/protocol/openid-connect/token', [
            'client_id'     => config('keycloak.client_id'),
            'client_secret' => config('keycloak.client_secret'),
            'code'          => $code,
            'redirect_uri'  => config('keycloak.redirect_uri'),
            'grant_type'    => 'authorization_code',
        ]);

        if ($response->failed()) {
            return redirect('/login')->with('error', 'Impossible d\'obtenir le token');
        }

        $tokenData = $response->json();
        $accessToken = $tokenData['access_token'];

        $userInfo = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(config('keycloak.base_url') . '/realms/' . config('keycloak.realm') . '/protocol/openid-connect/userinfo');

        if ($userInfo->failed()) {
            return redirect('/login')->with('error', 'Impossible de récupérer les informations utilisateur');
        }

        $userData = $userInfo->json();

        $user = User::firstOrCreate(
            ['email' => $userData['email']],
            ['name' => $userData['name'], 'keycloak_id' => $userData['sub']]
        );

        Auth::guard('web')->login($user);
                // dd(Auth::check());
        return redirect('/dashboard');
    }


    public function logout()
    {
        Auth::guard('keycloak')->logout();
        return redirect('/');
    }
}

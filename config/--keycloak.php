<?php
return [
    'base_url' => env('KEYCLOAK_SERVER_URL'),
    'realm' => env('KEYCLOAK_REALM'),
    'client_id' => env('KEYCLOAK_CLIENT_ID'),
    'client_secret' => env('KEYCLOAK_CLIENT_SECRET'),
    'redirect_uri' => env('KEYCLOAK_REDIRECT_URI'),
    'logout_redirect_uri' => env('KEYCLOAK_LOGOUT_REDIRECT'),
    'public_key' => env('KEYCLOAK_PUBLIC_KEY'),
    'realm_public_key' => env('KEYCLOAK_PUBLIC_KEY'),
];
?>
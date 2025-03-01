<?php

namespace App\Models;

use Vizir\KeycloakWebGuard\Models\KeycloakUser;

class User extends KeycloakUser
{
    protected $fillable = [
        'sub', 'name', 'email', 'preferred_username', 'given_name', 'family_name', 'email_verified'
    ];

    protected $primaryKey = 'sub';
    public $incrementing = false;
}

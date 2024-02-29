<?php

namespace App\Controllers\User;

class UserSessionController
{
    public function getSession(): array
    {
        return [
            'isLoggedIn' => isset($_SESSION['user']),
            'roles' => $_SESSION['user']['roles'] ?? null,
        ];
    }

}
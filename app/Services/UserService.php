<?php

namespace App\Services;

class UserService
{
    public function getSession(): array
    {

        $userExists = isset($_SESSION['user']);

        $roles = null;

        if ($userExists) {
            $roles = $_SESSION['user']['roles'] ?? null;
        }

        return [
            'isLoggedIn' => $userExists,
            'roles' => $roles,
        ];
    }

    public function getUser(): array
    {
        return $_SESSION['user'];
    }
}
<?php

namespace App\Services;

class UserService
{
    public function getSession(): array
    {
        return [
            'isLoggedIn' => isset($_SESSION['user']),
            'roles' => $_SESSION['user']['roles'] ?? null,
        ];
    }

        public function getUser(): array
    {
        return $_SESSION['user'] ?? [];
    }
}
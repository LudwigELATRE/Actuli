<?php

namespace App\Controllers;

use App\lib\View;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Homepage
{
    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $view = new View();
        $html = $view->render('/homepage/homepage.html.twig',['user' => $userData]);
        $response->getBody()->write($html);
        return $response;

    }

    public function show(int $id): void
    {
        echo 'Je suis le post ' . $id;
    }

    public function showPost()
    {
        echo 'salut';
    }
}
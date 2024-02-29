<?php

namespace App\Controllers;

use App\lib\View;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Homepage
{

    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = new View();
        var_dump($_SESSION);
        $html = $view->render('/homepage/homepage.html.twig',['text' => $_SESSION]);
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
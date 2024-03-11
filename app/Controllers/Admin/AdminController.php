<?php

namespace App\Controllers\Admin;

use App\lib\View;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AdminController
{
    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $paramUser = $data->getUser();

        $view = new View();
        $html = $view->render('/admin/admin.html.twig',[
            'user' => $userData,
            'userparam' => $paramUser
        ]);
        $response->getBody()->write($html);
        return $response;

    }

}
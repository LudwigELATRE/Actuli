<?php

namespace App\Controllers\Admin;

use App\lib\View;
use App\Repository\UserRepository;
use App\Services\UserService;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserCrudController
{

    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $paramUser = $data->getUser();
        $usersRepository = new UserRepository();
        $allUsers = $usersRepository->getAllUsers();

        $view = new View();
        $html = $view->render('/admin/users.html.twig',[
            'user' => $userData,
            'userparam' => $paramUser,
            'allusers' => $allUsers
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * @throws Exception
     */
    public function deleteUser(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $categoryRepository = new UserRepository();
        $categoryRepository->deleteUserWithPostsAndComments($args);
        return $response->withHeader('Location', '/admin/users')->withStatus(302);
    }
}
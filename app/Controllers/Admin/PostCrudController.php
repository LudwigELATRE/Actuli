<?php

namespace App\Controllers\Admin;

use App\lib\View;
use App\Repository\PostRepository;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PostCrudController
{
    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $paramUser = $data->getUser();
        $postRepository = new PostRepository();
        $posts = $postRepository->getAllPost();
        dump($posts);

        $view = new View();
        $html = $view->render('/admin/posts.html.twig',[
            'user' => $userData,
            'userparam' => $paramUser,
            'posts' => $posts,
        ]);
        $response->getBody()->write($html);
        return $response;

    }

}
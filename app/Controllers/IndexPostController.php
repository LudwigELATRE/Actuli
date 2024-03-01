<?php

namespace App\Controllers;

use App\lib\View;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Services\UserService;
use PDO;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class IndexPostController
{
    public function indexPost(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $postsRepository = new PostRepository();
        $posts = $postsRepository->getAllPost();
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAllCategory();

        $view = new View();
        $html = $view->render('/blogpage/blog.html.twig', [
            "posts" => $posts,
            'user' => $userData,
            'categories' => $categories
        ]);
        $response->getBody()->write($html);
        return $response;
    }
}
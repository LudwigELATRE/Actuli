<?php

namespace App\Controllers;

use App\lib\View;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class IndexPostController
{
    public function indexPost(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
/*        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM posts");
        $stmt->execute();
        $posts = $stmt->fetch(PDO::FETCH_ASSOC);*/

        $view = new View();
        $html = $view->render('/blogpage/blog.html.twig');
        $response->getBody()->write($html);
        return $response;
    }
}
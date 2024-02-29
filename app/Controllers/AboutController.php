<?php

namespace App\Controllers;

use App\lib\View;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AboutController
{
    public function about(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = new View();
        $html = $view->render('/aboutpage/about.html.twig',[]);
        $response->getBody()->write($html);
        return $response;

    }
}
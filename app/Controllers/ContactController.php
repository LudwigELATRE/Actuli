<?php

namespace App\Controllers;

use App\lib\View;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ContactController
{
    public function contact(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = new View();
        $html = $view->render('/contactpage/contact.html.twig',[]);
        $response->getBody()->write($html);
        return $response;

    }

}
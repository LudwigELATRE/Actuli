<?php

namespace App\Controllers;

use App\lib\View;
use App\Repository\UserRepository;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Homepage
{
    /**
     * Affiche la page d'accueil du site.
     * Récupère les données de session de l'utilisateur via UserService et les transmet à la vue de la page d'accueil.
     * Génère la réponse HTML en utilisant les données de l'utilisateur et la rend dans la vue spécifiée.
     *
     * @param RequestInterface $request La requête HTTP entrante.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @return ResponseInterface La réponse contenant le HTML de la page d'accueil.
     */
    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $view = new View();
        $html = $view->render('/homepage/homepage.html.twig',[
            'user' => $userData,
            ]);
        $response->getBody()->write($html);
        return $response;

    }
}
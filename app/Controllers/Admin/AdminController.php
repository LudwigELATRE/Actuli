<?php

namespace App\Controllers\Admin;

use App\lib\View;
use App\Repository\UserRepository;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AdminController
{
    /**
     * Affiche la page principale de l'administration.
     * Récupère les informations de l'utilisateur actuellement connecté et la liste de tous les utilisateurs
     * enregistrés dans la base de données à l'aide du UserRepository. Prépare et envoie la vue de l'administration
     * avec ces données.
     *
     * @param RequestInterface $request La requête HTTP entrante.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @return ResponseInterface La réponse contenant le HTML de la page d'administration.
     */
    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $paramUser = $data->getUser();
        $usersRepository = new UserRepository();
        $allUsers = $usersRepository->getAllUsers();

        $view = new View();
        $html = $view->render('/admin/admin.html.twig',[
            'user' => $userData,
            'userparam' => $paramUser,
            'allusers' => $allUsers
        ]);
        $response->getBody()->write($html);
        return $response;

    }

}
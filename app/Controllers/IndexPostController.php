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
    /**
     * Affiche la page principale du blog avec la liste de tous les posts et les catégories disponibles.
     * Récupère les données des utilisateurs via UserService pour vérifier l'authentification,
     * extrait tous les posts et toutes les catégories de la base de données à l'aide des repositories respectifs,
     * et les transmet à la vue pour l'affichage.
     *
     * @param RequestInterface $request La requête HTTP entrante.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @return ResponseInterface La réponse contenant le HTML généré pour la page du blog.
     */
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
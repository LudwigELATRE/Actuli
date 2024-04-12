<?php

namespace App\Controllers\Admin;

use App\Entity\Category;
use App\lib\View;
use App\Repository\CategoryRepository;
use App\Services\UserService;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CategoryAdminController
{
    /**
     * Affiche toutes les catégories existantes dans l'administration.
     * Récupère la liste des catégories à partir du CategoryRepository et les affiche
     * via la vue appropriée avec les informations de l'utilisateur actuel.
     *
     * @param RequestInterface $request La requête HTTP entrante.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @return ResponseInterface La réponse contenant le HTML de la page de gestion des catégories.
     */
    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $paramUser = $data->getUser();
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAllCategory();

        $view = new View();
        $html = $view->render('/admin/category.html.twig',[
            'user' => $userData,
            'userparam' => $paramUser,
            'categories' => $categories,
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * Affiche la page pour ajouter une nouvelle catégorie.
     * Prépare une vue pour la saisie des informations de la nouvelle catégorie.
     *
     * @param RequestInterface $request La requête HTTP entrante.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @return ResponseInterface La réponse contenant le HTML du formulaire d'ajout de catégorie.
     */
    public function category(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $paramUser = $data->getUser();

        $view = new View();
        $html = $view->render('/admin/ajouterCategory.html.twig',[
            'user' => $userData,
            'userparam' => $paramUser,
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * Crée une nouvelle catégorie à partir des données fournies via le formulaire.
     * Valide les données, crée une nouvelle instance de catégorie et tente de l'enregistrer dans la base de données.
     * Redirige vers la liste des catégories si la création est réussie ou retourne un message d'erreur si elle échoue.
     *
     * @param RequestInterface $request La requête HTTP contenant les données du formulaire.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @return ResponseInterface La réponse, soit une redirection, soit une vue avec un message d'erreur.
     */
    public function createCategory(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $categorieRepository = new CategoryRepository();
        $categories = $categorieRepository->getAllCategory();
        $queryParams = $request->getParsedBody();
        if (!empty($queryParams) && isset($queryParams["name"], $queryParams["description"]))
        {
            $slug = strtolower(trim(str_replace(' ', '-', $queryParams["name"])));
            $datetime = new \DateTimeImmutable();
            try {
                $category = new Category($queryParams["name"],$queryParams["description"],$slug,$datetime);
                $categoryRepositoy = new CategoryRepository();
                $categoryRepositoy->save($category->getName(),$category->getDescription(),$category->getSlug(),$category->getCreatedAt());
                return $response->withHeader('Location', '/admin/categories')->withStatus(302);
            }catch (Exception $e)
            {
                // Gestion des erreurs (par exemple, email déjà existant)
                $error = "Une erreur est survenue : " . $e->getMessage();
                // Passer l'erreur à la vue
                $view = new View();
                $html = $view->render('/admin/ajouterCategory.html.twig', [
                    'error' => $error,
                    'categories' => $categories,
                ]);
                $response->getBody()->write($html);
                return $response;
            }
        }else {
            $error = "Veuillez fournir tous les champs requis.";
            $view = new View();
            $html = $view->render('/admin/ajouterCategory.html.twig', [
                'error' => $error,
                'user' => $userData,
                'categories' => $categories,
            ]);
            $response->getBody()->write($html);
            return $response;
        }
    }

    /**
     * Supprime une catégorie spécifiée par l'ID passé en argument.
     * Effectue la suppression dans la base de données et redirige vers la liste des catégories.
     *
     * @param RequestInterface $request La requête HTTP entrante.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @param array $args Arguments routage dynamique (par exemple, ID de la catégorie).
     * @return ResponseInterface Redirection vers la liste des catégories après suppression.
     * @throws Exception Si la suppression échoue pour une raison quelconque.
     */
    public function deleteCategory(RequestInterface $request, ResponseInterface $response,array $args): ResponseInterface
    {
        $categoryRepository = new CategoryRepository();
        $categoryRepository->delete($args);
        return $response->withHeader('Location', '/admin/categories')->withStatus(302);
    }
}
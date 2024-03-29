<?php

namespace App\Controllers\Admin;

use App\Entity\Category;
use App\lib\View;
use App\Repository\CategoryRepository;
use App\Services\UserService;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CategoryCrudController
{
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
     * @throws Exception
     */
    public function deleteCategory(RequestInterface $request, ResponseInterface $response,array $args): ResponseInterface
    {
        $categoryRepository = new CategoryRepository();
        $categoryRepository->delete($args);
        return $response->withHeader('Location', '/admin/categories')->withStatus(302);
    }
}
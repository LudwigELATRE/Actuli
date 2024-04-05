<?php

namespace App\Controllers;
use App\lib\View;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\PostsComment;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PostController
{
    public function showPost(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $postRepository = new PostRepository();
        $post = $postRepository->findById($args);
        $postsComment = new PostsComment();
        $comments = $postsComment->getAllComment($args);
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAllCategory();

        $view = new View();
        $html = $view->render('/blogpage/show.html.twig', [
            "post" => $post,
            'user' => $userData,
            'comments' => $comments,
            'categories' => $categories
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    public function addComment(RequestInterface $request, ResponseInterface $response, array $args)
    {
        $data = new UserService();
        $userData = $data->getSession();
        $queryParams = $request->getParsedBody();
        if (!empty($queryParams) && isset($queryParams["content"]))
        {
            try {
                $datetime = new \DateTimeImmutable();
                $data = [
                    'postId' => $args['id'],
                    'author' => $_SESSION['user']['firstname'] . $_SESSION['user']['lastname'],
                    'content' => $queryParams["content"],
                    'email' => $_SESSION['user']['email'],
                    'createdAt' => $datetime->format('Y-m-d'),
                ];

                $postrepository = new PostsComment();
                $postrepository->save($data);

                return $response->withHeader('Location', '/post/'.$args['id'])->withStatus(302);
            }catch (\Exception $e)
            {
                // Gestion des erreurs (par exemple, email déjà existant)
                $error = "Une erreur est survenue : " . $e->getMessage();
                // Passer l'erreur à la vue
                $view = new View();
                $html = $view->render('/blogpage/show.html.twig', [
                    'error' => $error,
                ]);
                $response->getBody()->write($html);
                return $response;
            }
        }
    }

    public function postsUser(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $postRepository = new PostRepository();
        $posts = $postRepository->getPostFormUser($data->getUser()['id']);

        $view = new View();
        $html = $view->render('/user/post.html.twig',[
            'user' => $userData,
            'posts' => $posts
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    public function ajouterPostUser(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $categorieRepository = new CategoryRepository();
        $categories = $categorieRepository->getAllCategory();

        $view = new View();
        $html = $view->render('/user/ajouterPost.html.twig',
            [
                'user' => $userData,
                'categories' => $categories,
            ]);
        $response->getBody()->write($html);
        return $response;
    }

    public function createPost(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $categorieRepository = new CategoryRepository();
        $categories = $categorieRepository->getAllCategory();
        $queryParams = $request->getParsedBody();
        if (!empty($queryParams) && isset($queryParams["categorie"], $queryParams["title"], $queryParams["content"]))
        {
            $slug = strtolower(trim(str_replace(' ', '-', $queryParams["title"])));
            $datetime = new \DateTimeImmutable();
            $image = basename(str_replace(' ', '-', strtolower($_FILES['post-image']['name'])));
            try {
                $data = [
                    'userId' => $data->getUser()['id'],
                    'categorie' => $queryParams["categorie"],
                    'title' => $queryParams["title"],
                    'slug' => $slug,
                    'content' => $queryParams["content"],
                    'image' => $image,
                    'published' => $queryParams["published"],
                    'createdAt' => $datetime->format('Y-m-d'),
                ];
                /*dd($data);*/
                $postRepositoy = new PostRepository();
                $postRepositoy->save($data);
                return $response->withHeader('Location', '/mon-compte/mes-posts')->withStatus(302);
            }catch (\Exception $e)
            {
                // Gestion des erreurs (par exemple, email déjà existant)
                $error = "Une erreur est survenue : " . $e->getMessage();
                // Passer l'erreur à la vue
                $view = new View();
                $html = $view->render('/user/ajouterPost.html.twig', [
                    'error' => $error,
                    'categories' => $categories,
                    ]);
                $response->getBody()->write($html);
                return $response;
            }
        }else {
            $error = "Veuillez fournir tous les champs requis.";
            $view = new View();
            $html = $view->render('/user/ajouterPost.html.twig', [
                'error' => $error,
                'user' => $userData,
                'categories' => $categories,
            ]);
            $response->getBody()->write($html);
            return $response;
        }

    }

    public  function updatePost(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $categorieRepository = new CategoryRepository();
        $categories = $categorieRepository->getAllCategory();
        $postRepository = new PostRepository();
        $post = $postRepository->findById($args);
        $view = new View();
        $html = $view->render('/user/updatePost.html.twig', [
            "post" => $post,
            "user" => $userData,
            'categories' => $categories,
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    public function updatePostFromUser(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $categorieRepository = new CategoryRepository();
        $categories = $categorieRepository->getAllCategory();
        $queryParams = $request->getParsedBody();
        if (!empty($queryParams) && isset($queryParams["categorie"], $queryParams["title"], $queryParams["content"]))
        {
            $slug = strtolower(trim(str_replace(' ', '-', $queryParams["title"])));
            $datetime = new \DateTimeImmutable();
            try {
                $data = [
                    'id' => $queryParams["id"],
                    'categorie' => $queryParams["categorie"],
                    'title' => $queryParams["title"],
                    'slug' => $slug,
                    'content' => $queryParams["content"],
                    'published' => $queryParams["published"],
                    'updatedAt' => $datetime->format('Y-m-d'),
                ];
                $postRepositoy = new PostRepository();
                $postRepositoy->updatePost($data);
                return $response->withHeader('Location', '/mon-compte/mes-posts')->withStatus(302);
            }catch (\Exception $e)
            {
                // Gestion des erreurs (par exemple, email déjà existant)
                $error = "Une erreur est survenue : " . $e->getMessage();
                // Passer l'erreur à la vue
                $view = new View();
                $html = $view->render('/user/updatePost.html.twig', [
                    'error' => $error,
                    'user' => $userData,
                    'categories' => $categories,
                ]);
                $response->getBody()->write($html);
                return $response;
            }
        }else {
            $error = "Veuillez fournir tous les champs requis.";
            $view = new View();
            $html = $view->render('/user/updatePost.html.twig', [
                'error' => $error,
                'user' => $userData,
                'categories' => $categories,
            ]);
            $response->getBody()->write($html);
            return $response;
        }
    }

    public function deletePostsUser(RequestInterface $request, ResponseInterface $response,array $args): ResponseInterface
    {
        $postRepository = new PostRepository();
        $postRepository->deletePostFromUser($args);
        return $response->withHeader('Location', '/mon-compte/mes-posts')->withStatus(302);
    }

}
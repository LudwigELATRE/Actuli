<?php

namespace App\Controllers;
use App\lib\View;
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

        $view = new View();
        $html = $view->render('/blogpage/show.html.twig', [
            "post" => $post,
            'user' => $userData,
            'comments' => $comments,
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    public function addComment(RequestInterface $request, ResponseInterface $response, array $args)
    {
        global $pdo; // Assurez-vous que $pdo est bien une instance PDO connectée à votre base de données
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

                // Préparation de la requête
                $stmt = $pdo->prepare("INSERT INTO posts_comment (post_id,  author, content, email, createdAt) VALUES (:post_id, :author, :content, :email, :createdAt)");

                // Exécution de la requête avec les valeurs
                $stmt->execute([
                    ':post_id' => $data["postId"],
                    ':author' => $data["author"],
                    ':content' => $data["content"],
                    ':email' => $data["email"],
                    ':createdAt' => $data["createdAt"],
                ]);

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
}
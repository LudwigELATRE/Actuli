<?php

namespace App\Controllers;
use App\Controllers\User\UserSessionController;
use App\lib\View;
use PDO;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PostController
{
    public function showPost(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        global $pdo;
        $data = new UserSessionController();
        $userData = $data->getSession();
        dump($userData);
        $id = $args['id'];
        $stmt = $pdo->prepare("
            SELECT posts.*, users.firstname, users.lastname 
            FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE posts.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        $view = new View();
        $html = $view->render('/blogpage/show.html.twig', [
            "post" => $post,
            'user' => $userData
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    public function add(RequestInterface $request, ResponseInterface $response)
    {
        global $pdo; // Assurez-vous que $pdo est bien une instance PDO connectée à votre base de données
        $queryParams = $request->getParsedBody();
        if (!empty($queryParams) && isset($queryParams["content"]))
        {
            try {
                $author = $_SESSION['user']['firstname'] . $_SESSION['user']['lastname'];
                $content = $queryParams["content"];
                $email = $_SESSION['user']['email'];
                $createdAt = new \DateTime();

                // Préparation de la requête
                $stmt = $pdo->prepare("INSERT INTO users (author, content, email, createdAt) VALUES (:author, :content, :email, :createdAt)");

                // Exécution de la requête avec les valeurs
                $stmt->execute([
                    ':author' => $author,
                    ':content' => $content,
                    ':email' => $email,
                    ':createdAt' => $createdAt,
                ]);
                dump($stmt);
                die();
                return $response->withHeader('Location', '/post/{id}')->withStatus(302);
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
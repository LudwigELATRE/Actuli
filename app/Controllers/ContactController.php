<?php

namespace App\Controllers;

use App\lib\View;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ContactController
{
    public function contact(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        global $pdo; // Assurez-vous que $pdo est bien une instance PDO connectée à votre base de données
        $data = new UserService();
        $userData = $data->getSession();
        $queryParams = $request->getParsedBody();

        if (!empty($queryParams) && isset($queryParams["name"], $queryParams["subject"], $queryParams["message"]))
        {
            try {
                $name = $queryParams["name"];
                $subject = $queryParams["subject"];
                $message = $queryParams["message"]; // Toujours hasher les mots de passe

                // Préparation de la requête
                $stmt = $pdo->prepare("INSERT INTO contact (name, subject, message) VALUES (:name, :subject, :message)");

                // Exécution de la requête avec les valeurs
                $stmt->execute([
                    ':name' => $name,
                    ':subject' => $subject,
                    ':message' => $message,
                ]);
                // Redirection vers le profil de l'utilisateur
                return $response->withHeader('Location', '/')->withStatus(302);
            }catch (\Exception $e)
            {
                // Gestion des erreurs (par exemple, email déjà existant)
                $error = "Une erreur est survenue : " . $e->getMessage();
                // Passer l'erreur à la vue
                $view = new View();
                $html = $view->render('/contactpage/contact.html.twig', ['error' => $error]);
                $response->getBody()->write($html);
                return $response;
            }
        }else {
            $error = "Veuillez fournir tous les champs requis.";
            $view = new View();
            $html = $view->render('/contactpage/contact.html.twig', [
                'error' => $error,
                'user' => $userData
                ]);
            $response->getBody()->write($html);
            return $response;
        }

    }

}
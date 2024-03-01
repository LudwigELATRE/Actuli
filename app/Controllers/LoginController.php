<?php

namespace App\Controllers;

use App\Controllers\User\UserSessionController;
use App\lib\View;
use PDO;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController
{
    public function login(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        global $pdo;
        $data = new UserSessionController();
        $userData = $data->getSession();
        // Supposons que les données soient envoyées via POST pour une meilleure sécurité
        $data = $request->getParsedBody(); // Utilisez getParsedBody() au lieu de getQueryParams() pour les données POST
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        // Préparation de la requête SQL pour sélectionner l'utilisateur par e-mail
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData['isLoggedIn']){
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                return $response->withHeader('Location', '/')->withStatus(302);
            } else {
                // Authentification échouée, préparez le message d'erreur
                $error = "Invalid credentials. Please try again.";

                // Affichez la page de connexion avec le message d'erreur
                $view = new View();
                $html = $view->render('/loginpage/login.html.twig', ['error' => $error]);
                $response->getBody()->write($html);
                return $response;
            }
        }else{
            return $response->withHeader('Location', '/')->withStatus(302);
        }
    }

    public function register(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        global $pdo; // Assurez-vous que $pdo est bien une instance PDO connectée à votre base de données
        $queryParams = $request->getQueryParams();
        $data = new UserSessionController();
        $userData = $data->getSession();
        if (!$userData['isLoggedIn'])
        {
            if (!empty($queryParams) && isset($queryParams["firstname"], $queryParams["lastname"], $queryParams["email"], $queryParams["password"])) {
                try {
                    $firstname = $queryParams["firstname"];
                    $lastname = $queryParams["lastname"];
                    $email = $queryParams["email"];
                    $password = password_hash($queryParams["password"], PASSWORD_DEFAULT); // Toujours hasher les mots de passe
                    $roles = 'ROLE_USER'; // ou 'ROLE_ADMIN' selon le cas

                    // Préparation de la requête
                    $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, roles) VALUES (:firstname, :lastname, :email, :password, :roles)");

                    // Exécution de la requête avec les valeurs
                    $stmt->execute([
                        ':firstname' => $firstname,
                        ':lastname' => $lastname,
                        ':email' => $email,
                        ':password' => $password,
                        ':roles' => $roles
                    ]);

                    // Redirection vers le profil de l'utilisateur
                    return $response->withHeader('Location', '/login')->withStatus(302);
                } catch (\Exception $e)
                {
                    // Gestion des erreurs (par exemple, email déjà existant)
                    $error = "Une erreur est survenue : " . $e->getMessage();
                    // Passer l'erreur à la vue
                    $view = new View();
                    $html = $view->render('/loginpage/register.html.twig', ['error' => $error]);
                    $response->getBody()->write($html);
                    return $response;
                }
            } else {
                // Aucune donnée ou données incomplètes, rester sur la page d'inscription
                $error = "Veuillez fournir tous les champs requis.";
                $view = new View();
                $html = $view->render('/loginpage/register.html.twig', ['error' => $error]);
                $response->getBody()->write($html);
                return $response;
            }
        }else{
            return $response->withHeader('Location', '/')->withStatus(302);
        }
    }

    public function logout()
    {
        $_SESSION = [];

        session_destroy();

        header("Location: /login");
        exit;
    }
}
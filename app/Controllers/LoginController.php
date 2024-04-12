<?php

namespace App\Controllers;

use App\Entity\User;
use App\lib\View;
use App\Repository\UserRepository;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController
{
    /**
     * Traite la tentative de connexion de l'utilisateur.
     * Vérifie les identifiants fournis (email et mot de passe) et authentifie l'utilisateur.
     * Redirige vers la page d'accueil si la connexion réussit ou affiche un message d'erreur si elle échoue.
     *
     * @param RequestInterface $request La requête HTTP contenant les données du formulaire de connexion.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @return ResponseInterface
     */
    public function login(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $data = $request->getParsedBody(); // Utilisez getParsedBody() au lieu de getQueryParams() pour les données POST

        $dataUser = [
            'email' => $data['email'] ?? '',
            'password' => $data['password'] ?? ''
        ];
        $userRepository = new UserRepository();
        $user = $userRepository->findBySomeField($dataUser);

        if (!$userData['isLoggedIn']){
            if ($user && password_verify($dataUser['password'], $user['password'])) {
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

    /**
     * Gère l'enregistrement d'un nouvel utilisateur.
     * Vérifie les données du formulaire et enregistre le nouvel utilisateur dans la base de données.
     * Redirige vers la page de connexion après l'enregistrement réussi ou affiche un message d'erreur en cas d'échec.
     *
     * @param RequestInterface $request La requête HTTP contenant les données du formulaire d'inscription.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @return ResponseInterface
     */
    public function register(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $data = new UserService();
        $userData = $data->getSession();
        if (!$userData['isLoggedIn'])
        {
            if (!empty($queryParams) && isset($queryParams["firstname"], $queryParams["lastname"], $queryParams["email"], $queryParams["password"], $queryParams["sexe"] )) {
                $slug = strtolower($queryParams["firstname"]."-".$queryParams["lastname"]);
                $datetime = new \DateTimeImmutable();
                try {
                    $user = new User($queryParams["firstname"],$queryParams["lastname"],$queryParams["email"],password_hash($queryParams["password"], PASSWORD_DEFAULT),'ROLE_USER',$datetime,$queryParams["sexe"],$slug);
                    $userRepository = new UserRepository();
                    $userRepository->save($user);

                    return $response->withHeader('Location', '/login')->withStatus(302);
                } catch (\Exception $e)
                {
                    // Gestion des erreurs (par exemple, email déjà existant)
                    $error = "Une erreur est survenue";
                    // Passer l'erreur à la vue
                    $view = new View();
                    $html = $view->render('/loginpage/register.html.twig', ['error' => $error]);
                    $response->getBody()->write($html);
                    return $response;
                }
            } else {
                $view = new View();
                $html = $view->render('/loginpage/register.html.twig');
                $response->getBody()->write($html);
                return $response;
            }
        }else{
            return $response->withHeader('Location', '/')->withStatus(302);
        }
    }

    /**
     * Déconnecte l'utilisateur.
     * Efface les données de la session et redirige vers la page de connexion.
     */
    public function logout()
    {
        $_SESSION = [];

        session_destroy();

        header("Location: /login");
        exit;
    }
}
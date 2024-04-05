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

    public function register(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $data = new UserService();
        $userData = $data->getSession();
        if (!$userData['isLoggedIn'])
        {
            if (!empty($queryParams) && isset($queryParams["firstname"], $queryParams["lastname"], $queryParams["email"], $queryParams["password"], $queryParams["password"] )) {
                $datetime = new \DateTimeImmutable();
                try {
/*                    $data = [
                        'firstname' => $queryParams["firstname"],
                        'lastname' => $queryParams["lastname"],
                        'email' => $queryParams["email"],
                        'password' => password_hash($queryParams["password"], PASSWORD_DEFAULT),
                        'roles' => 'ROLE_USER'
                    ];*/
                    $user = new User($queryParams["firstname"],$queryParams["lastname"],$queryParams["email"],password_hash($queryParams["password"], PASSWORD_DEFAULT),'ROLE_USER',$datetime,$queryParams["sexe"]);
                    dd($user);
                    //$userRepository = new UserRepository();
                    $userRepository = new UserRepository();
                    $userRepository->save($data);

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

    public function logout()
    {
        $_SESSION = [];

        session_destroy();

        header("Location: /login");
        exit;
    }
}
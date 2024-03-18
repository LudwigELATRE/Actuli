<?php

namespace App\Controllers;

use App\lib\View;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController
{
    public function compte(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $UserRepositoy = new UserRepository();
        $paramUser = $UserRepositoy->getUser($data->getUser()['id']);

        $view = new View();
        $html = $view->render('/user/user.html.twig',[
            'user' => $userData,
            'userparam' => $paramUser
        ]);
        $response->getBody()->write($html);
        return $response;

    }

    public function profile(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $userRepositoy = new UserRepository();
        $paramUser = $userRepositoy->getUser($data->getUser()['id']);

            $view = new View();
            $html = $view->render('/user/update.html.twig',[
                'user' => $userData,
                'userparam' => $paramUser
            ]);
            $response->getBody()->write($html);
            return $response;
    }
    public function updateProfile(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $userRepositoy = new UserRepository();
        $paramUser = $userRepositoy->getUser($data->getUser()['id']);
        $queryParams = $request->getParsedBody();

        if (!empty($queryParams) && isset($queryParams["profile"], $queryParams["mobile"], $queryParams["address"]))
        {
            try {
                $data = [
                    'id' => $paramUser[0]['id'],
                    'firstname' => $queryParams["firstname"],
                    'lastname' => $queryParams["lastname"],
                    'profile' => $queryParams["profile"],
                    'mobile' => $queryParams["mobile"],
                    'address' => $queryParams["address"]
                ];
                $userRepositoy = new UserRepository();
                $userRepositoy->update($data);
                $user = $userRepositoy->getUser($paramUser['id']);
                /*$_SESSION['user'] = $user;*/
                return $response->withHeader('Location', '/mon-compte')->withStatus(302);
            }catch (\Exception $e)
            {
                // Gestion des erreurs (par exemple, email déjà existant)
                $error = "Une erreur est survenue : " . $e->getMessage();
                // Passer l'erreur à la vue
                $view = new View();
                $html = $view->render('/user/update.html.twig', ['error' => $error]);
                $response->getBody()->write($html);
                return $response;
            }
        }else {
            $view = new View();
            $html = $view->render('/user/update.html.twig',[
                'user' => $userData,
                'userparam' => $paramUser
            ]);
            $response->getBody()->write($html);
            return $response;
        }
    }

}
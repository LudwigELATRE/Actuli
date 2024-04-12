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
    /**
     * Gère la requête pour afficher la page du compte utilisateur.
     * Récupère les données de session de l'utilisateur et les détails de l'utilisateur à partir de la base de données,
     * puis rend la vue avec ces données.
     *
     * @param RequestInterface $request Requête entrante.
     * @param ResponseInterface $response Réponse à envoyer.
     * @return ResponseInterface Réponse avec le contenu HTML généré.
     */
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

    /**
     * Gère la requête pour afficher la page de profil de l'utilisateur pour mise à jour.
     * Récupère les données de session et les informations de l'utilisateur à partir de la base de données,
     * puis rend la vue correspondante avec ces informations.
     *
     * @param RequestInterface $request Requête entrante.
     * @param ResponseInterface $response Réponse à envoyer.
     * @return ResponseInterface Réponse avec le contenu HTML généré.
     */
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
    /**
     * Gère la mise à jour des informations de profil de l'utilisateur.
     * Récupère les données soumises via le formulaire, met à jour les informations de l'utilisateur dans la base de données,
     * et redirige vers la page du compte ou affiche des erreurs en cas de problème.
     *
     * @param RequestInterface $request Requête entrante contenant les données du formulaire.
     * @param ResponseInterface $response Réponse à envoyer.
     * @return ResponseInterface Réponse redirigée ou avec le contenu HTML en cas d'erreur.
     */
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
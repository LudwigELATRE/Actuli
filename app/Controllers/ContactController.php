<?php

namespace App\Controllers;

use App\lib\View;
use App\Repository\ContactRepository;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ContactController
{
    public function contact(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $queryParams = $request->getParsedBody();

        if (!empty($queryParams) && isset($queryParams["name"], $queryParams["subject"], $queryParams["message"]))
        {
            try {
                $data = [
                    'name' => $queryParams["name"],
                    'subject' => $queryParams["subject"],
                    'message' => $queryParams["message"]
                ];
                $contactRepositoy = new ContactRepository();
                $contactRepositoy->save($data);
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
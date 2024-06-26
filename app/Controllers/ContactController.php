<?php

namespace App\Controllers;

use App\Entity\Contact;
use App\lib\View;
use App\Repository\ContactRepository;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ContactController
{
    /**
     * Traite la soumission du formulaire de contact.
     * Vérifie si les données requises sont présentes, crée une nouvelle instance de Contact, et tente de sauvegarder
     * les informations dans la base de données à l'aide de ContactRepository.
     * Redirige vers la page d'accueil si la sauvegarde réussit, sinon, affiche un message d'erreur.
     *
     * @param RequestInterface $request La requête HTTP contenant les données du formulaire de contact.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @return ResponseInterface Redirection ou réponse HTML avec un message d'erreur.
     */
    public function contact(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $queryParams = $request->getParsedBody();
        if (!empty($queryParams) && isset($queryParams["name"], $queryParams["email"], $queryParams["subject"], $queryParams["message"]))
        {
            try {
                $contact = new Contact($queryParams["name"],$queryParams["email"],$queryParams["subject"],$queryParams["message"]);
                $contactRepositoy = new ContactRepository();
                $contactRepositoy->save($contact->getName(),$contact->getEmail(),$contact->getSubject(),$contact->getMessage());
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
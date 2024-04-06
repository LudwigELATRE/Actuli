<?php

namespace App\Controllers\Admin;

use App\lib\View;
use App\Repository\ContactRepository;
use App\Services\UserService;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ContactAdminController
{
    /**
     * Displays the admin page listing all users.
     * Retrieves user data from the session and all other users from the UserRepository.
     * Renders the list using the 'users.html.twig' template, which includes functionality
     * for admin actions like editing or deleting users.
     *
     * @param RequestInterface $request The incoming HTTP request.
     * @param ResponseInterface $response The HTTP response to be returned.
     * @return ResponseInterface The response containing the HTML of the admin user management page.
     */
    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $paramUser = $data->getUser();
        $contactRepository = new ContactRepository();
        $allContacts = $contactRepository->getAllContacts();

        $view = new View();
        $html = $view->render('/admin/contact.html.twig',[
            'user' => $userData,
            'userparam' => $paramUser,
            'allcontacts' => $allContacts
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * Supprime une catégorie spécifiée par l'ID passé en argument.
     * Effectue la suppression dans la base de données et redirige vers la liste des catégories.
     *
     * @param RequestInterface $request La requête HTTP entrante.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @param array $args Arguments routage dynamique (par exemple, ID de la catégorie).
     * @return ResponseInterface Redirection vers la liste des catégories après suppression.
     * @throws Exception Si la suppression échoue pour une raison quelconque.
     */
    public function deleteContact(RequestInterface $request, ResponseInterface $response,array $args): ResponseInterface
    {
        $categoryRepository = new ContactRepository();
        $categoryRepository->delete($args);
        return $response->withHeader('Location', '/admin/contacts')->withStatus(302);
    }
}
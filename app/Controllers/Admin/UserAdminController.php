<?php

namespace App\Controllers\Admin;

use App\lib\View;
use App\Repository\UserRepository;
use App\Services\UserService;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserAdminController
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
        $usersRepository = new UserRepository();
        $allUsers = $usersRepository->getAllUsers();

        $view = new View();
        $html = $view->render('/admin/users.html.twig',[
            'user' => $userData,
            'userparam' => $paramUser,
            'allusers' => $allUsers
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * Handles the deletion of a user, along with their associated posts and comments.
     * Retrieves the user ID from the route parameters, deletes the user, and redirects to the user list.
     * This method can throw an exception if the deletion process fails.
     *
     * @param RequestInterface $request The incoming HTTP request.
     * @param ResponseInterface $response The HTTP response to be returned.
     * @param array $args Route parameters containing the user ID to be deleted.
     * @return ResponseInterface The response, redirecting to the user list with a status code of 302.
     * @throws Exception If the deletion process encounters any errors.
     */
    public function deleteUser(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $categoryRepository = new UserRepository();
        $categoryRepository->deleteUserWithPostsAndComments($args);
        return $response->withHeader('Location', '/admin/users')->withStatus(302);
    }
}
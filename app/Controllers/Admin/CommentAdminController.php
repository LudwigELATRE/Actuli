<?php

namespace App\Controllers\Admin;

use App\lib\View;
use App\Repository\PostsCommentRepository;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CommentAdminController
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
        $commentRepository = new PostsCommentRepository();
        $allComments = $commentRepository->getAllComments();

        $view = new View();
        $html = $view->render('/admin/comment.html.twig',[
            'user' => $userData,
            'userparam' => $paramUser,
            'allcomments' => $allComments
        ]);
        $response->getBody()->write($html);
        return $response;
    }
}
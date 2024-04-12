<?php

namespace App\Controllers\Admin;

use App\lib\View;
use App\Repository\PostRepository;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PostAdminController
{
    /**
     * Displays the admin page listing all blog posts.
     * Retrieves user data and post data from their respective services and repositories.
     * Renders the list of posts using the 'posts.html.twig' template, showing the posts along with the user data
     * to provide context for admin functionalities such as editing or deleting posts.
     *
     * @param RequestInterface $request The incoming HTTP request.
     * @param ResponseInterface $response The HTTP response to be returned.
     * @return ResponseInterface The response containing the HTML of the admin post management page.
     */
    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $paramUser = $data->getUser();
        $postRepository = new PostRepository();
        $posts = $postRepository->getAllPost();

        $view = new View();
        $html = $view->render('/admin/posts.html.twig',[
            'user' => $userData,
            'userparam' => $paramUser,
            'posts' => $posts,
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * Supprime un post spécifique appartenant à un utilisateur. L'ID du post est pris des arguments de routage.
     *
     * @param RequestInterface $request La requête HTTP.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @param array $args Arguments routage dynamique (par exemple, ID du post).
     * @return ResponseInterface
     */
    public function deletePostsUser(RequestInterface $request, ResponseInterface $response,array $args): ResponseInterface
    {
        $postRepository = new PostRepository();
        $postRepository->deletePostFromUser($args);
        return $response->withHeader('Location', '/admin/posts')->withStatus(302);
    }

}
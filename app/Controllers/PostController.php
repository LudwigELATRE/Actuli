<?php

namespace App\Controllers;
use App\Entity\Post;
use App\Entity\PostComment;
use App\lib\View;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\PostsCommentRepository;
use App\Repository\UserRepository;
use App\Services\ImageService;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PostController
{
    /**
     * Affiche les détails d'un post spécifique, y compris les commentaires associés et les catégories.
     * Les données sont extraites en utilisant le UserService pour l'utilisateur, le PostRepository pour les posts,
     * et le PostsCommentRepository pour les commentaires.
     *
     * @param RequestInterface $request La requête HTTP.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @param array $args Arguments routage dynamique (par exemple, ID du post).
     * @return ResponseInterface
     */
    public function showPost(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $postRepository = new PostRepository();
        $post = $postRepository->findById($args);
        $postsCommentRepository = new PostsCommentRepository();
        $comments = $postsCommentRepository->getAllCommentPostId($args);
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAllCategory();
        $UserRepositoy = new UserRepository();
        $paramUser = $UserRepositoy->getUser($data->getUser()['id']);

        $view = new View();
        $html = $view->render('/blogpage/show.html.twig', [
            "post" => $post,
            'user' => $userData,
            'comments' => $comments,
            'categories' => $categories,
            'paramUser' => $paramUser
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * Ajoute un commentaire à un post. Les données du commentaire sont récupérées de la requête POST,
     * et enregistrées via PostsCommentRepository.
     *
     * @param RequestInterface $request La requête HTTP contenant les données du formulaire.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @param array $args Arguments routage dynamique (par exemple, ID du post).
     */
    public function addComment(RequestInterface $request, ResponseInterface $response, array $args)
    {
        $data = new UserService();
        $queryParams = $request->getParsedBody();
        $error = [];
        if (!empty($queryParams) && isset($queryParams["content"])) {
            try {
                $UserRepositoy = new UserRepository();
                $user = $UserRepositoy->getUser($data->getUser()['id']);
                $datetime = new \DateTimeImmutable();
                $author = $user[0]['firstname'] . $user[0]['lastname'];
                $postComment = new PostComment($user[0]['id'],$args['id'],$author,$user[0]['email'],$queryParams["content"],$datetime);
                $postCommentrepository = new PostsCommentRepository();
                $postCommentrepository->save($postComment);

                return $response->withHeader('Location', '/post/'.$args['id'])->withStatus(302);
            }catch (\Exception $e)
            {
                $error[] = "Une erreur est survenue : " . $e->getMessage();
                $view = new View();
                $html = $view->render('/blogpage/show.html.twig', [
                    'error' => $error,
                ]);
                $response->getBody()->write($html);
                return $response;
            }
        }
    }

    /**
     * Affiche tous les posts d'un utilisateur spécifique, utilisant les données de session pour identifier l'utilisateur.
     * Les posts sont récupérés via le PostRepository.
     *
     * @param RequestInterface $request La requête HTTP.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @return ResponseInterface
     */
    public function postsUser(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $postRepository = new PostRepository();
        $posts = $postRepository->getPostFormUser($data->getUser()['id']);

        $view = new View();
        $html = $view->render('/user/post.html.twig',[
            'user' => $userData,
            'posts' => $posts
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * Affiche la page pour ajouter un nouveau post par l'utilisateur. Rend la vue avec les catégories disponibles
     * pour sélection à l'aide du CategoryRepository.
     *
     * @param RequestInterface $request La requête HTTP.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @return ResponseInterface
     */
    public function ajouterPostUser(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $categorieRepository = new CategoryRepository();
        $categories = $categorieRepository->getAllCategory();

        $view = new View();
        $html = $view->render('/user/ajouterPost.html.twig',
            [
                'user' => $userData,
                'categories' => $categories,
            ]);
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * Traite la création d'un nouveau post par un utilisateur. Valide et enregistre les données du post,
     * y compris une image si fournie.
     *
     * @param RequestInterface $request La requête HTTP contenant les données du formulaire.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @return ResponseInterface
     */
    public function createPost(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $categorieRepository = new CategoryRepository();
        $categories = $categorieRepository->getAllCategory();
        $queryParams = $request->getParsedBody();
        $erreurs = [];
        if (!empty($queryParams) && !empty($queryParams['categorie']) && !empty($queryParams['title']) && !empty($queryParams['content']) && isset($queryParams["categorie"], $queryParams["title"], $queryParams["content"])) {
            $slug = strtolower(trim(str_replace(' ', '-', $queryParams["title"])));
            $datetime = new \DateTimeImmutable();
            try {
                $imageService = new ImageService();
                $file = $_FILES['post-image'];
                $image = $imageService->enregistrementImage($file);
                if ($image['success'] === true) {
                    $post = new Post($data->getUser()['id'],$queryParams["categorie"],$queryParams["title"],$queryParams["content"],$slug,$image['name'],$queryParams["published"],$datetime);
                    $postRepositoy = new PostRepository();
                    $postRepositoy->save($post->getUserId(),$post->getCategory(),$post->getTitle(),$post->getContent(),$post->getSlug(),$post->getImage(),$post->getPublished(),$post->getCreatedAt());
                    return $response->withHeader('Location', '/mon-compte/mes-posts')->withStatus(302);
                }
                $erreurs[] = $image['message'];
                $view = new View();
                $html = $view->render('/user/ajouterPost.html.twig', [
                    'error' => $erreurs,
                    'categories' => $categories,
                ]);
                $response->getBody()->write($html);
                return $response;
            }catch (\Exception $e)
            {
                $erreurs[] = "Une erreur est survenue : " . $e->getMessage();
                $view = new View();
                $html = $view->render('/user/ajouterPost.html.twig', [
                    'error' => $erreurs,
                    'categories' => $categories,
                    ]);
                $response->getBody()->write($html);
                return $response;
            }
        }else {
            $erreurs[] = "Veuillez fournir tous les champs requis.";
            $view = new View();
            $html = $view->render('/user/ajouterPost.html.twig', [
                'error' => $erreurs,
                'user' => $userData,
                'categories' => $categories,
            ]);
            $response->getBody()->write($html);
            return $response;
        }
    }

    /**
     * Prépare et affiche la page de modification d'un post existant pour l'utilisateur.
     * Charge les données du post à partir de la base de données pour édition.
     *
     * @param RequestInterface $request La requête HTTP.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @param array $args Arguments routage dynamique (par exemple, ID du post).
     * @return ResponseInterface
     */
    public  function updatePost(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $categorieRepository = new CategoryRepository();
        $categories = $categorieRepository->getAllCategory();
        $postRepository = new PostRepository();
        $post = $postRepository->findById($args);
        $view = new View();
        $html = $view->render('/user/updatePost.html.twig', [
            "post" => $post,
            "user" => $userData,
            'categories' => $categories,
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * Met à jour les informations d'un post existant à partir des données fournies par l'utilisateur.
     * Valide les entrées et met à jour la base de données.
     *
     * @param RequestInterface $request La requête HTTP contenant les données du formulaire.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @return ResponseInterface
     */
    public function updatePostFromUser(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = new UserService();
        $userData = $data->getSession();
        $categorieRepository = new CategoryRepository();
        $categories = $categorieRepository->getAllCategory();
        $queryParams = $request->getParsedBody();
        if (!empty($queryParams) && isset($queryParams["categorie"], $queryParams["title"], $queryParams["content"])) {
            $slug = strtolower(trim(str_replace(' ', '-', $queryParams["title"])));
            $datetime = new \DateTimeImmutable();
            $fileName = $_FILES['post-image']['name'];
            $image = basename(str_replace(' ', '-', strtolower($fileName)));
            try {
                $post = new Post($data->getUser()['id'],$queryParams["categorie"],$queryParams["title"],$queryParams["content"],$slug,$image,$queryParams["published"],$datetime);
                $post->setId($queryParams["id"]);
                $post->setUpdatedAt($datetime);
                $postRepositoy = new PostRepository();
                $postRepositoy->updatePost($post->getId(),$post->getCategory(),$post->getTitle(),$post->getContent(),$post->getSlug(),$post->getImage(),$post->getPublished(),$post->getUpdatedAt());
                return $response->withHeader('Location', '/mon-compte/mes-posts')->withStatus(302);
            }catch (\Exception $e)
            {
                $error = "Une erreur est survenue : " . $e->getMessage();
                $view = new View();
                $html = $view->render('/user/updatePost.html.twig', [
                    'error' => $error,
                    'user' => $userData,
                    'categories' => $categories,
                ]);
                $response->getBody()->write($html);
                return $response;
            }
        }else {
            $error = "Veuillez fournir tous les champs requis.";
            $view = new View();
            $html = $view->render('/user/updatePost.html.twig', [
                'error' => $error,
                'user' => $userData,
                'categories' => $categories,
            ]);
            $response->getBody()->write($html);
            return $response;
        }
    }

    /**
     * Supprime un post spécifique appartenant à un utilisateur. L'ID du post est pris des arguments de routage.
     *
     * @param RequestInterface $request La requête HTTP.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @param array $args Arguments routage dynamique (par exemple, ID du post).
     * @return ResponseInterface
     * @throws \Exception
     */
    public function deletePostsUser(RequestInterface $request, ResponseInterface $response,array $args): ResponseInterface
    {
        $postRepository = new PostRepository();
        $postRepository->deletePostFromUser($args);
        return $response->withHeader('Location', '/mon-compte/mes-posts')->withStatus(302);
    }

}
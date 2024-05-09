<?php

use App\Controllers\Admin\AdminController;
use App\Controllers\Admin\CategoryAdminController;
use App\Controllers\Admin\CommentAdminController;
use App\Controllers\Admin\ContactAdminController;
use App\Controllers\Admin\PostAdminController;
use App\Controllers\Admin\UserAdminController;
use App\Controllers\ContactController;
use App\Controllers\Homepage;
use App\Controllers\IndexPostController;
use App\Controllers\LoginController;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Services\ConnexionService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$connexionService = new ConnexionService();
$connexionDatabase = $connexionService->connexionDatabase();

session_start();

$userData = [
    'isLoggedIn' => isset($_SESSION['user']),
    'roles' => $_SESSION['user']['roles'] ?? null,
];


$app = AppFactory::create();
$twig = Twig::create(__DIR__ . '/../templates', ['debug' => true]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
$twig->getEnvironment()->enableDebug();
$twig->getEnvironment()->addGlobal('user', $userData);
$app->add(TwigMiddleware::create($app, $twig));
$app->get('/', [Homepage::class, 'index']);
$app->get('/blog', [IndexPostController::class, 'indexPost']);
$app->get('/post/{id}', [PostController::class, 'showPost']);
$app->map(['GET', 'POST'],'/post/{id}/ajout-commentaire', [PostController::class, 'addComment']);
$app->map(['GET', 'POST'],'/contact', [ContactController::class, 'contact']);
$app->map(['GET', 'POST'],'/mon-compte', [UserController::class, 'compte']);
$app->map(['GET'],'/mon-compte/profil', [UserController::class, 'profile']);
$app->map(['POST'],'/mon-compte/profil/update', [UserController::class, 'updateProfile']);
$app->map(['GET'],'/mon-compte/mes-posts', [PostController::class, 'postsUser']);
$app->map(['GET'],'/mon-compte/mes-posts/ajouter', [PostController::class, 'ajouterPostUser']);
$app->map(['POST'],'/mon-compte/mes-posts/ajouter/post', [PostController::class, 'createPost']);
$app->map(['GET'],'/mon-compte/mes-posts/modifier/{id}', [PostController::class, 'updatePost']);
$app->map(['POST'],'/mon-compte/mes-posts/modifier/post', [PostController::class, 'updatePostFromUser']);
$app->map(['GET'],'/mon-compte/mes-posts/supprimer/{id}', [PostController::class, 'deletePostsUser']);
$app->get('/register', [LoginController::class, 'register']);
$app->map(['GET', 'POST'], '/login', LoginController::class . ':login');
$app->get('/logout', [LoginController::class, 'logout']);
$app->get('/admin', [AdminController::class, 'index']);
$app->get('/admin/users', [UserAdminController::class, 'index']);
$app->map(['GET'],'/admin/users/supprimer/{id}', [UserAdminController::class, 'deleteUser']);
$app->get('/admin/posts', [PostAdminController::class, 'index']);
$app->map(['GET'],'/admin/posts/supprimer/{id}', [PostAdminController::class, 'deletePostsUser']);
$app->get('/admin/categories', [CategoryAdminController::class, 'index']);
$app->get('/admin/categories/categorie', [CategoryAdminController::class, 'category']);
$app->map(['POST'],'/admin/categories/categorie/ajout', [CategoryAdminController::class, 'createCategory']);
$app->map(['GET'],'/admin/categories/supprimer/{id}', [CategoryAdminController::class, 'deleteCategory']);
$app->get('/admin/comments', [CommentAdminController::class, 'index']);
$app->map(['GET'],'/admin/comments/supprimer/{id}', [CommentAdminController::class, 'deleteComment']);
$app->get('/admin/contacts', [ContactAdminController::class, 'index']);
$app->map(['GET'],'/admin/contacts/supprimer/{id}', [ContactAdminController::class, 'deleteContact']);


$app->get('/{routes:.+}', function (RequestInterface $request, ResponseInterface $response) use ($twig) {
    return $twig->render($response->withStatus(404), 'error.twig');
});
$app->run();

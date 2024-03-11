<?php

use App\Controllers\Admin\AdminController;
use App\Controllers\ContactController;
use App\Controllers\Homepage;
use App\Controllers\IndexPostController;
use App\Controllers\LoginController;
use App\Controllers\PostController;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Factory\AppFactory;


require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../db/connDB.php';

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
$app->get('/register', [LoginController::class, 'register']);
$app->map(['GET', 'POST'], '/login', LoginController::class . ':login');
$app->get('/logout', [LoginController::class, 'logout']);
$app->get('/admin', [AdminController::class, 'index']);
/*$app->get('/blog/article/{id}', [ShowPostController::class, 'show']);
$app->get('/blog/ajout-article', [AddPostController::class, 'renderCreationForm']);
$app->post('/blog/ajout-article', [AddPostController::class, 'add']);
$app->get('/blog/suppression-article/{id}', [DeletePostController::class, 'renderDeleteForm']);
$app->post('/blog/suppression-article/{id}', [DeletePostController::class, 'remove']);
$app->get('/blog/modification-article/{id}', [UpdatePostController::class, 'renderUpdateForm']);
$app->post('/blog/modification-article/{id}', [UpdatePostController::class, 'update']);
$app->post('/blog/article/{id}/ajout-commentaire', [AddCommentController::class, 'add']);*/

$app->get('/{routes:.+}', function (RequestInterface $request, ResponseInterface $response) use ($twig) {
    return $twig->render($response->withStatus(404), 'error.twig');
});
$app->run();



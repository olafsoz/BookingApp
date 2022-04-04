<?php

require 'vendor/autoload.php';
session_start();

use App\Controllers\ReservationsController;
use App\Controllers\UsersController;
use App\Controllers\ApartmentsController;
use App\View;
use App\Redirect;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', [UsersController::class, 'start']);
    $r->addRoute('GET', '/users/register', [UsersController::class, 'showRegister']);
    $r->addRoute('GET', '/users/login', [UsersController::class, 'showLogin']);
    $r->addRoute('POST', '/users/register', [UsersController::class, 'register']);
    $r->addRoute('POST', '/users/login', [UsersController::class, 'login']);

    $r->addRoute('GET', '/main', [ApartmentsController::class, 'index']);
    $r->addRoute('GET', '/reservations', [ReservationsController::class, 'reservationsAll']);
    $r->addRoute('POST', '/reservations/{id:\d+}/delete', [ReservationsController::class, 'deleteBooking']);
    $r->addRoute('GET', '/apartment/{id:\d+}', [ReservationsController::class, 'viewOne']);
    $r->addRoute('GET', '/apartment/{id:\d+}/edit', [ApartmentsController::class, 'edit']);
    $r->addRoute('POST', '/apartment/{id:\d+}', [ApartmentsController::class, 'update']);
    $r->addRoute('POST', '/apartment/{id:\d+}/reserve', [ReservationsController::class, 'reserve']);
    $r->addRoute('POST', '/apartment/{id:\d+}/delete', [ApartmentsController::class, 'delete']);

    $r->addRoute('GET', '/create/listing', [ApartmentsController::class, 'createListing']);
    $r->addRoute('POST', '/create/listing', [ApartmentsController::class, 'addListingToDb']);
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        var_dump('404 Not Found');
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        var_dump('Method Not Allowed');
        break;
    case FastRoute\Dispatcher::FOUND:
        $contr = $routeInfo[1][0];
        $method = $routeInfo[1][1];

        $response = (new $contr)->$method($routeInfo[2]);

        $loader = new FilesystemLoader('app/Views');
        $twig = new Environment($loader);

        if ($response instanceof View) {
            echo $twig->render($response->getPath() . '.html', $response->getVariables());
        }
        if ($response instanceof Redirect)
        {
            header('Location: ' . $response->getLocation());
            exit;
        }
        break;
}
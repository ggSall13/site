<?php
use Src\Controllers\HomeController;
use Src\Controllers\LoginController;
use Src\Controllers\RegisterController;
use Src\Core\Middleware\Auth;
use Src\Core\Middleware\Guest;

const MIDDLEWARE = [
   'auth' => Auth::class,
   'guest' => Guest::class
];

/**
 * @var Src\Core\Router\Router $router
 */

$router->get('', [HomeController::class, 'index']);

$router->get('register', [RegisterController::class, 'index'])->only('guest');
$router->post('register', [RegisterController::class, 'store']);

$router->get('login', [LoginController::class, 'index'])->only('guest');
$router->post('login', [LoginController::class, 'login']);

$router->get('logout', [LoginController::class, 'logout'])->only('auth');

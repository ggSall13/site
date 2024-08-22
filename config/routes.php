<?php
use Src\Controllers\HomeController;
use Src\Controllers\LoginController;
use Src\Controllers\RegisterController;

/**
 * @var Src\Core\Router\Router $router
 */

$router->get('', [HomeController::class, 'index']);
$router->get('register', [RegisterController::class, 'index']);
$router->post('register', [RegisterController::class, 'store']);
$router->get('login', [LoginController::class, 'index']);
$router->post('login', [LoginController::class, 'login']);

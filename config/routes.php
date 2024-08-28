<?php

use Src\Controllers\AdsController;
use Src\Controllers\HomeController;
use Src\Controllers\LoginController;
use Src\Controllers\ProfileController;
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

$router->get('profile', [ProfileController::class, 'index'])->only('auth');
$router->get('profile/edit', [ProfileController::class, 'edit'])->only('auth');
$router->post('profile/edit', [ProfileController::class, 'update']);

$router->get('ads/new', [AdsController::class, 'index'])->only('auth');
$router->post('ads/new', [AdsController::class, 'store']);

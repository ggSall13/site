<?php

use Src\Core\Middleware\Auth;
use Src\Core\Middleware\Guest;

const MIDDLEWARE = [
   'auth' => Auth::class,
   'guest' => Guest::class
];

/**
 * @var Src\Core\Router\Router $router
 */

$router->get('', ['home', 'index']);

$router->get('register', ['register', 'index'])->only('guest');
$router->post('register', ['register', 'store']);

$router->get('login', ['login', 'index'])->only('guest');
$router->post('login', ['login', 'login']);

$router->get('logout', ['login', 'logout'])->only('auth');

$router->get('profile', ['profile', 'index'])->only('auth');
$router->get('profile/edit', ['profile', 'edit'])->only('auth');
$router->post('profile/edit', ['profile', 'update']);

$router->get('ads/new', ['ads', 'index'])->only('auth');
$router->post('ads/new', ['ads', 'store']);
$router->delete('ads/delete/(?P<id>\d+)', ['ads', 'delete'])->only('auth');

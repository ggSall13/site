<?php
session_start();
// подключение путей и автозагрузки
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/paths.php';
require_once APP_DIR . '/vendor/autoload.php';

use Src\Core\Router\Router;
use Src\Core\Database\Database;

$db = Database::getInstance();
$conn = $db->getConnection();

$router = new Router();
// добавление роутов
require_once APP_DIR . '/config/routes.php';

$router->run();

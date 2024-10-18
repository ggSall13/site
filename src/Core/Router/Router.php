<?php

namespace Src\Core\Router;

use Src\Core\View\View;

class Router
{
   private array $routes = [];

   private string $uri;

   private array $params = [];

   private string $method;
   // получем uri и метод
   public function __construct()
   {
      $this->uri = parse_url(trim($_SERVER['REQUEST_URI'], '/'))['path'];
      $this->method = $_POST['method'] ?? $_SERVER['REQUEST_METHOD'];
   }

   private function add(string $uri, string $method, array $controller)
   {
      $this->routes[] = [
         'uri' => $uri,
         'method' => strtoupper($method),
         'controller' => $controller,
         'middleware' => null
      ];

      return $this;
   }


   public function get(string $uri, array $controller)
   {
      return $this->add($uri, 'GET', $controller);
   }

   public function post(string $uri, array $controller)
   {
      return $this->add($uri, 'POST', $controller);
   }

   public function delete(string $uri, array $controller)
   {
      return $this->add($uri, 'DELETE', $controller);
   }

   public function only($key)
   {
      $this->routes[count($this->routes) - 1]['middleware'] = $key;
   }

   private function match()
   {
      // Флаг
      $routeFound = false;
      
      // Через цикл проверяем роутер на совпадения uri и method
      foreach ($this->routes as $route) {
         if (preg_match("#^{$route['uri']}$#", $this->uri, $matches) && $route['method'] == $this->method) {
            // Получение ключей (?P<key>) из routes
            $keys = $this->extractKeys($matches);

            [$controller, $action] = $route['controller'];

            $this->params = [
               'controller' => $controller,
               'action' => $action,
            ];

            /*
               Слияние $keys и $this->params
               в $keys лежат значения (?P<key>) из routes
               это поможет для дальнейшей работы с пагинацией и т.п
            */
            if ($keys !== null) {
               $this->params = array_merge($this->params, $keys);
            }

            // middleware
            $this->handleMiddleware($route);

            $routeFound = true;
            break;
         }
      }

      // ошибка если флаг false
      if (!$routeFound) {
         View::showError(404);
         return false;
      } else {
         return true;
      }
   }

   private function extractKeys(array $matches)
   {
      // $keys это (?P<key>) из routes
      $keys = null;

      if ($matches[0] != '') {
         /*
            Получение (?P<key>) из routes
         */
         $keys = array_filter($matches, function ($value, $key) {
            if (is_string($key)) {
               return $key;
            }
         }, ARRAY_FILTER_USE_BOTH);
      }

      return $keys;
   }

   private function handleMiddleware(array $route)
   {
      if ($route['middleware']) {
         $middleware = MIDDLEWARE[$route['middleware']];

         (new $middleware)->handle();
      }
   }

   public function run()
   {

      if ($this->match()) {
         // Получаем имя контроллера для подключения класса
         // И передачи для абстрактного класса Controller
         $controller = ucfirst($this->params['controller']) . 'Controller';
         $action = $this->params['action'];

         // Имя класса типа HomeController::class;
         $className = 'Src\\Controllers\\' . $controller;

         //создаем экземпляр класса и вызываем метод если он существует
         if (class_exists($className) && method_exists($className, $action)) {
            $routeFound = true;
            $class = new $className($this->params);
            $class->$action();
         } else {
            View::showError(404);
         }
      } else {
         View::showError(404);
      }
   }
}

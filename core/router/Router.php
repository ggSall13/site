<?php

namespace Src\Core\Router;

use Src\Core\View\View;

class Router
{
   private array $routes = [];

   private string $uri;

   private string $method;
   // получем uri и метод
   public function __construct()
   {
      $this->uri = parse_url(trim($_SERVER['REQUEST_URI'], '/'))['path'];
      $this->method = $_SERVER['REQUEST_METHOD'];
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

   public function only($key)
   {
      $this->routes[count($this->routes) - 1]['middleware'] = $key;
   }

   public function run()
   {
      // Флаг
      $routeFound = false;
      // Через цикл проверяем роутер на совпадения uri и method
      
      foreach ($this->routes as $route) {
         if (preg_match("#^{$route['uri']}$#", $this->uri, $matches) && $route['method'] == $this->method) {
            // получаем $controller $action из массива $route['controller']
            [$controller, $action] = $route['controller'];

            // middleware

            if ($route['middleware']) {
               $middleware = MIDDLEWARE[$route['middleware']];
               
               (new $middleware)->handle();
            }

            //создаем экземпляр класса и вызываем метод если он существует
            if (class_exists($controller) && method_exists($controller, $action)) {
               $routeFound = true;
               $class = new $controller();
               $class->$action();
            } else {
               View::showError(404);
            }
            
            break;
         }
      }
      // ошибка если флаг false
      if (!$routeFound) {
         View::showError(404);
      }
   }
}

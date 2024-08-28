<?php

namespace Src\Core\Controller;

use Src\Core\Validator\Validator;
use Src\Core\View\View;
use Src\Core\Auth\Auth;

abstract class Controller
{
   // Получем view для дальнейшей работы
   protected $view;

   protected $auth;

   protected $validator;
   // protected $route;

   public function __construct()
   {
      $this->view = new View();
      $this->auth = new Auth();
      $this->validator = new Validator();
   }

   public function to($url)
   {
      header('Location: ' . $url);
   }

   protected function load($fillable, $data)
   {
      $post = [];
      foreach ($fillable as $v) {
         if (isset($data[$v])) {
            $post[$v] = trim($data[$v]);
         }
      }

      return $post;
   }
}

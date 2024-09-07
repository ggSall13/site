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

   protected $params;

   protected $model;

   public function __construct($params)
   {
      $this->view = new View();
      $this->auth = new Auth();
      $this->validator = new Validator();
      $this->params = $params;
      $this->model = $this->loadModel();
   }

   public function to($url)
   {
      header('Location: ' . $url);
   }

   public function loadModel()
   {
      /* 
         В $this->params лежит массив в котором 0 => имя контроллера 1 => имя метода
      */
      
      $modelPath = APP_DIR . '/src/models/' . ucfirst($this->params[0]) . '.php';
      $modelName = 'Src\\Models\\' . ucfirst($this->params[0]);

      if (file_exists($modelPath) && class_exists($modelName)) {
         return new $modelName();
      }

   }

   public function validate(array $data)
   {
      $this->validator->validate($data);
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

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

   // $params передается из Router
   // в $params лежит имя контроллера и метода
   // И еще лежит айди или страница, за счет регулярных выражений
   public function __construct($params)
   {
      $this->view = new View();
      $this->auth = new Auth();
      $this->validator = new Validator();
      $this->params = $params;
      $this->model = $this->loadModel();
   }

   protected function to($url)
   {
      header('Location: ' . $url);
   }

   private function loadModel()
   {
      /* 
         В $this->params лежит массив в котором 0 => имя контроллера 1 => имя метода
      */

      $modelPath = APP_DIR . '/src/models/' . ucfirst($this->params['controller']) . '.php';
      $modelName = 'Src\\Models\\' . ucfirst($this->params['controller']);

      if (file_exists($modelPath) && class_exists($modelName)) {
         return new $modelName();
      }
   }

   protected function validate(array $data)
   {
      $this->validator->validate($data);
   }

   // Для перевода кириллицы в латиницу
   protected function translit($string) 
   {
      $translitArray = [
         'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'E', 'Ж' => 'Gh', 'З' => 'Z', 
         'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 
         'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch', 
         'Ъ' => 'Y', 'Ы' => 'Y', 'Ь' => 'Y', 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya', 'а' => 'a', 'б' => 'b', 'в' => 'v', 
         'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'gh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 
         'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 
         'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => 'y', 'ы' => 'y', 'ь' => 'y', 
         'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
     ];
     
     $string = str_replace(' ', '-', $string);
     $string =  strtr(strtolower($string), $translitArray);

     $string = strtolower($string) . '-' . $this->createToken();

     return $string;
   }


   protected function createToken()
   {
      return substr(str_shuffle("0123456789abcdefghijklmnopqrsntyvwxyz"), 0, 5) . date('I');
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

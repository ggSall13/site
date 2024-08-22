<?php

namespace Src\Core\Controller;
use Src\Core\View\View;

abstract class Controller
{
   // Получем view для дальнейшей работы
   protected $view;
   // protected $route;

   public function __construct()
   {
      $this->view = new View();
   }

   public function to($url)
   { 
      header ('Location: ' . $url);
   }
}

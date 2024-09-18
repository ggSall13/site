<?php

namespace Src\Controllers;

use Src\Core\Controller\Controller;

class HomeController extends Controller
{
   public function index()
   {
      $vars = [
         'categories' => $this->model->getCategories(),
      ];

      $this->view->page('home', $vars);
   }
}

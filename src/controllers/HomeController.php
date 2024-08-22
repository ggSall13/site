<?php

namespace Src\Controllers;

use Src\Core\Controller\Controller;

class HomeController extends Controller
{
   public function index()
   {
      $this->view->page('home');
   }
}

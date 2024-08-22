<?php

namespace Src\Controllers;
use Src\Core\Controller\Controller;

class LoginController extends Controller
{
   public function index()
   {
      $this->view->page('login');
   }
}

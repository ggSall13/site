<?php

namespace Src\Controllers;

use Src\Core\Validator\Validator;
use Src\Core\Controller\Controller;

class RegisterController extends Controller
{
   public function index()
   {
      $this->view->page('register');
   }

   public function store()
   {
      $validator = new Validator();
      $validator->validate($_POST);

      if ($validator->hasErrors()) {
         $_SESSION['errors'] = $validator->getErrors();
         $_SESSION['inputs'] = $_POST;
         
         $this->to('/register');
      }
   }
}

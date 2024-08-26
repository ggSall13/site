<?php

namespace Src\Controllers;

use Src\Core\Controller\Controller;

class LoginController extends Controller
{
   public function index()
   {
      $this->view->page('login');
   }

   public function login()
   {
      $data = $this->load(['email', 'password', 'checkMe'], $_POST);

      if (!$this->auth->attempt($data)) {
         $_SESSION['errors'] = 'Такого пользователя нет';
         $_SESSION['inputs'] = $data['email'];

         $this->to('/login');

         return;
      }

      $this->to('/');
   }

   public function logout()
   {
      if (isset($_SESSION['user'])) {
         unset($_SESSION['user']);
      }

      if (isset($_COOKIE['user'])) {
         setcookie('user', '', time() - 3600);
      }

      $this->to('/');
   }
}

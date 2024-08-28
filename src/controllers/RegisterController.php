<?php

namespace Src\Controllers;

use Src\Core\Validator\Validator;
use Src\Core\Controller\Controller;
use Src\Models\Register;

class RegisterController extends Controller
{
   public function index()
   {
      $this->view->page('user/register');
   }

   public function store()
   {
      $this->validator->validate($_POST);

      if ($this->validator->hasErrors()) {
         $_SESSION['errors'] = $this->validator->getErrors();
         $_SESSION['inputs'] = $_POST;
         
         $this->to('/register');
         return;
      }
      
      $model = new Register();
   
      $fillable = ['name', 'email', 'phone', 'password'];

      $data = $this->load($fillable, $_POST);

      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

      // Если ошибка то db error 
      if (!$model->register($data)) {
         $_SESSION['errors']['dberror'] = 'db error';
         
         $this->to('/register');
         
         return;
      }

      $this->to('/login');
   }
}

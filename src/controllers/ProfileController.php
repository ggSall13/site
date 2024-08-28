<?php

namespace Src\Controllers;

use Src\Core\Controller\Controller;
use Src\Models\Profile;

class ProfileController extends Controller
{
   public function index()
   {
      $vars = [
         'auth' => $this->auth
      ];

      $this->view->page('user/profile', $vars);
   }

   public function edit()
   {
      $vars = [
         'auth' => $this->auth
      ];

      $this->view->page('user/edit', $vars);
   }

   public function update()
   {
      $fillable = ['id', 'name', 'email', 'phone'];
      $data = $this->load($fillable, $_POST);

      $this->validator->validate($data);

      if ($this->validator->hasErrors()) {
         $_SESSION['errors'] = $this->validator->getErrors();
         $_SESSION['inputs'] = $_POST;

         $this->to('/profile/edit');
         die();
      }

      $model = new Profile();

      if (!$model->update($data)) {
         $_SESSION['errors']['dberror'] = 'Db error';
         $this->to('/profile/edit');
      }

      $_SESSION['success']['edit'] = 'Данные изменены успешно';
      $this->auth->updSesionCookie($data);
      $this->to('/profile/edit');
   }
}

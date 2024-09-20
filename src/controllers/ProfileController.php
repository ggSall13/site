<?php

namespace Src\Controllers;

use Src\Core\Controller\Controller;

class ProfileController extends Controller
{
   public function index()
   {
      $vars = [
         'cookie' => $this->auth->cookie(),
         'ads' => $this->model->getAds($_SESSION['user']['id'] ?? $this->auth->cookie()->id),
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

      $data['userSlug'] = $this->translit($data['name']);

      if (!$this->model->update($data)) {
         $_SESSION['errors']['dberror'] = 'Db error';
         $this->to('/profile/edit');
      }

      $_SESSION['success']['edit'] = 'Данные изменены успешно';
      $this->auth->updSesionCookie($data);
      $this->to('/profile/edit');
   }
}

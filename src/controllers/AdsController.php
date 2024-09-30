<?php

namespace Src\Controllers;

use Src\Core\Controller\Controller;
use Src\Core\Upload\UploadImage;
use Src\Core\View\View;

// Контроллер объявлений
class AdsController extends Controller
{
   public function create()
   {
      $vars = [
         'categories' => $this->model->getCategories(),
         'cookie' => $this->auth->cookie()
      ];

      $this->view->page('ads/new', $vars);
   }

   public function view()
   {
      $ad = $this->model->getAdInfoBySlug($this->params['name']);

      $this->showError(!$ad);

      $vars = [
         'ad' =>  $ad
      ];

      $this->view->page('ads/view', $vars);
   }

   public function edit()
   {
      $ad =  $this->model->getAdInfoById($this->params['id']);

      $this->showError(!$ad);

      $vars = [
         'ad' => $ad,
         'categories' => $this->model->getCategories()
      ];

      $this->view->page('ads/edit', $vars);
   }

   public function store()
   {
      $this->validate(['title' => $_POST['title'], 'price' => $_POST['price']]);

      if ($this->validator->hasErrors()) {
         $_SESSION['inputs'] = $_POST;
         $_SESSION['errors'] = $this->validator->getErrors();

         $this->to('/ads/new');
      }

      $data = $this->load(['title', 'price', 'categorySlug', 'description', 'userId'], $_POST);
      $data['slug'] = $this->translit($data['title']);


      if (!$this->model->createAd($data)) {
         $_SESSION['errors']['insert'] = 'Не удалось загрузить объявление';
         $this->to('/ads/new');
      }

      // Создание класса для перемещения изображений если вообще есть изображения
      // И если валидация $title и вставка в БД прошла успешно
      $this->uploadImage();

      $this->to('/profile');
   }

   public function update() 
   {
      if (isset($_POST['imageName'])) {
         $this->model->deleteImagesById($_POST['imageName']);
      }

      $this->to('/profile');
   }

   public function delete()
   {
      $id = $this->params['id'];

      if (!$this->model->deleteAd($id)) {
         $_SESSION['erorrs']['delete'] = 'Не удалось удалить пост';

         $this->to('/profile');
      }

      $this->to('/profile');
   }

   private function uploadImage()
   {
      if ($_FILES['images']['name'][0] !== '') {
         $uploadImage = new UploadImage(
            $_FILES['images']['name'],
            $_FILES['images']['tmp_name'],
            $_FILES['images']['type'],
            $_FILES['images']['error'],
            $_FILES['images']['size'],
         );

         $uploadImages = $uploadImage->move('/ads');
         // Если не удалось переместить изображения, или выдались ошибки
         if (!$uploadImages) {
            $this->to('/ads/new');
         } else {
            // В $uploadImages возвращается массив из имен перенесенных фотографий
            $this->model->uploadImage($uploadImages);
         }
      }
   }
}

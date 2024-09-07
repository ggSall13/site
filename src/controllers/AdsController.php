<?php

namespace Src\Controllers;

use Src\Core\Controller\Controller;
use Src\Core\Upload\UploadImage;

// Контроллер объявлений
class AdsController extends Controller
{
   public function index()
   {
      $vars = [
         'categories' => $this->model->getCategories()
      ];
      $this->view->page('ads/new', $vars);
   }

   public function store()
   {
      // валидация поля title
      $this->validator->validateField('title', $_POST['title']);

      if ($this->validator->hasErrors()) {
         $_SESSION['errors'] = $this->validator->getErrors();
         $this->to('/ads/new');
         die();
      }

      $data = $this->load(['title', 'categoryId', 'description'], $_POST);

      // Создание класса для перемещения изображений если вообще есть изображения
      // И если валидация $title и вставка в БД прошла успешно
      if (isset($_FILES['images']) && $this->model->createAd($data)) {
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
            die();
         } else {
            // В $uploadImages возвращается массив из имен перенесенных фотографий
            $this->model->uploadImage($uploadImages);
         }
      }

      $this->to('/profile');
      die();
   }
}

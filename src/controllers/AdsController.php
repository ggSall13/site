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
         'categories' => $this->model->getCategories(),
         'cookie' => $this->auth->cookie()
      ];

      $this->view->page('ads/new', $vars);
   }

   public function view()
   {
      $vars = [
         'ad' =>  $this->model->getAdInfoBySlug($this->params['name'])
      ];

      $this->view->page('ads/view', $vars);
   }

   public function store()
   {
      $this->validate(['title' => $_POST['title'], 'price' => $_POST['price']]);

      if ($this->validator->hasErrors()) {
         $_SESSION['inputs'] = $_POST;
         $_SESSION['errors'] = $this->validator->getErrors();
         
         $this->to('/ads/new');
         die();
      }

      $data = $this->load(['title', 'price', 'categoryId', 'description', 'userId'], $_POST);
      $data['slug'] = $this->translit($data['title']);
      

      $this->model->createAd($data);

      // Создание класса для перемещения изображений если вообще есть изображения
      // И если валидация $title и вставка в БД прошла успешно
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
            die();
         } else {
            // В $uploadImages возвращается массив из имен перенесенных фотографий
            $this->model->uploadImage($uploadImages);
         }
      }

      $this->to('/profile');
      die();
   }

   public function delete()
   {
      $id = $this->params['id'];

      if (!$this->model->delete($id)) {
         $_SESSION['erorrs']['delete'] = 'Не удалось удалить пост';

         $this->to('/profile');
         die();
      }

      $this->to('/profile');
      die();
   }
}

<?php

namespace Src\Models;

use Src\Core\Model\Model;

class Ads extends Model
{
   public function getCategories()
   {
      return $this->db->findTable('subCategories');
   }

   public function createAd($data)
   {
      /*
         В $data['categorySlug'] лежит строка типа videocarti/3
         где videocarti это categorySlug 
         а цифра 3 это айди родительской категории
      */
      [$categorySlug, $parentCategoryId] = explode('/', $data['categorySlug']);

      // Перезапись $data и добавления ключа parentCategoryId
      $data['categorySlug'] = $categorySlug;
      $data['parentCategoryId'] = $parentCategoryId;

      if ($this->db->insert('ads', $data)) {
         return true;
      }

      return false;
   }

   public function uploadImage($data)
   {
      $lastInsertId = $this->db->getLastInsertId();

      foreach ($data as $image) {
         $this->db->insert(
            'images',
            [
               'dirPath' => $image['dir'],
               'urlPath' => $image['url'],
               'adId' => $lastInsertId
            ]
         );
      }
   }

   public function deleteImagesById(array $id)
   {
      $images = [];

      if (is_array($id)) {
         foreach ($id as $val) {
            $images[] = $this->db->findAll('images', ['id' => $val], ['dirPath', 'id']);
         }
      }

      foreach ($images as $image) {
         $this->deleteImages($image);
         /*
            По идее в $images всегда будет массив вида
            n => [
               0 => [
                  'dirPath' => text,
                  'id' => 228
               ]
            ], 
            n => [
               0 => [
                  'dirPath' => text,
                  'id' => 228
               ]
            ], и т.д
         */
         $this->db->delete('images', ['id' => $image[0]['id']]);
      }
   }

   public function deleteAd($id)
   {
      $images = $this->db->findAll('images', ['adId' => $id], 'dirPath');


      if ($this->db->delete('ads', ['id' => $id]) && $this->deleteImages($images)) {
         return true;
      } else {
         return false;
      }
   }

   public function getAdInfoById($id)
   {
      $ad = $this->db->find('ads', ['id' => $id]);

      if (!$ad) {
         return false;
      }

      $images = $this->db->findAll('images', ['adId' => $ad['id']], ['urlPath', 'dirPath', 'id']);
      $user = $this->db->find('users', ['id' => $ad['userId']]);

      // Получение от пользователя только имя и телефон
      $editUser = [
         'name' => $user['name'],
         'phone' => $user['phone'],
      ];

      return [
         'adInfo' => $ad,
         'images' => $images,
         'user' => $editUser
      ];
   }

   public function getAdInfoBySlug($slug)
   {
      $ad = $this->db->find('ads', ['adSlug' => $slug]);

      if (!$ad) {
         return false;
      }

      $user = $this->db->find('users', ['id' => $ad['userId']]);
      $images = $this->db->findAll('images', ['adId' => $ad['id']], 'urlPath');

      // Получение от пользователя только имя и телефон
      $editUser = [
         'name' => $user['name'],
         'phone' => $user['phone'],
         'userSlug' => $user['userSlug'],
      ];

      /*
         Переобразование многомерного массива images если images вообще есть
         и получение всех фотографий, а именно urlPath
         Потому что на страницу грузятся изображения только с помощью ссылки
      */
      if ($images) {
         $images = array_column($images, 'urlPath');
      }

      return [
         'adInfo' => $ad,
         'images' => $images,
         'user' => $editUser
      ];
   }

   private function deleteImages(array $path)
   {
      if (empty($path)) {
         return true;
      }
      
      $deletedFile = true;

      foreach ($path as $file) {
         if (file_exists($file['dirPath'])) {
            $deletedFile = unlink($file['dirPath']) ? true : false;
         }
      }

      if ($deletedFile) {
         return true;
      } else {
         return false;
      }
   }
}

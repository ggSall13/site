<?php

namespace Src\Models;

use Src\Core\Model\Model;

class Ads extends Model
{
   public function getCategories()
   {
      return $this->db->findTable('categories');
   }

   public function createAd($data)
   {
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

   public function delete($id)
   {
      $images = $this->db->findAll('images', ['adId' => $id], 'dirPath');


      if ($this->db->delete('ads', ['id' => $id]) && $this->deleteImages($images)) {
         return true;
      } else {
         return false;
      }
   }

   public function getAdInfoBySlug($slug)
   {
      $ad = $this->db->find('ads', ['slug' => $slug]);
      $images = $this->db->findAll('images', ['adId' => $ad['id']], 'urlPath');
      $user = $this->db->find('users', ['id' => $ad['userId']]);

      // Получение от пользователя только имя и телефон
      $editUser = [
         'name' => $user['name'],
         'phone' => $user['phone']
      ];

      /*
         Переобразование многомерного массива images если images вообще есть
         и получение всех фотографий, а именно urlPath
         Потому что на страницу грузятся изображения только с помощью ссылки
      */
      if ($images) {
         $images = array_column($images, 'urlPath');
      }

      if (!$ad) {
         return false;
      }

      return [
         'adInfo' => $ad,
         'images' => $images,
         'user' => $editUser
      ];
   }

   private function deleteImages(array $path)
   {
      $deletedFile = false;

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

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

   public function uploadImage($data, $params)
   {
      $postId = $params['id'] ?? $this->db->getLastInsertId();

      foreach ($data as $image) {
         $this->db->insert(
            'images',
            [
               'dirPath' => $image['dir'],
               'urlPath' => $image['url'],
               'adId' => $postId
            ]
         );
      }
   }

   public function deleteImagesById(array $id)
   {
      $images = [];
      $deletedFile = false;
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
         $deletedFile = $this->db->delete('images', ['id' => $image[0]['id']]);
      }
      return $deletedFile;
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
      $user = $this->db->find('users', ['id' => $ad['userId']], ['name', 'phone', 'id']);

      return [
         'adInfo' => $ad,
         'images' => $images,
         'user' => $user
      ];
   }

   public function getAdInfoBySlug($slug)
   {
      $ad = $this->db->find('ads', ['adSlug' => $slug]);

      if (!$ad) {
         return false;
      }

      $user = $this->db->find('users', ['id' => $ad['userId']], ['name', 'phone', 'userSlug', 'id']);
      $images = $this->db->findAll('images', ['adId' => $ad['id']], 'urlPath');

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
         'user' => $user
      ];
   }

   public function countImages($id)
   {
      $count = $this->db->sqlRequest("SELECT COUNT(*) AS count FROM images WHERE adId = :id", ['id' => $id]);
      return $count[0];
   }

   public function updateAd($data)
   {
      // получение $categorySlug, $parentCategoryId из $data где processori/3
      // Где processori имя категории, а 3 айди категории родителя
      [$categorySlug, $parentCategoryId] = explode('/', $data['categorySlug']);

      $data['categorySlug'] = $categorySlug;
      $data['parentCategoryId'] = $parentCategoryId;

      if ($this->db->update('ads', $data, ['id' => $data['id']])) {
         return true;
      }

      return false;
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

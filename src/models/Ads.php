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

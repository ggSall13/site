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
         $this->db->insert('images', ['path' => $image, 'adId' => $lastInsertId]);
      }
   }
}

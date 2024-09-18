<?php

namespace Src\Models;

use Src\Core\Model\Model;

class Profile extends Model
{
   public function update($data): int|false
   {
      return $this->db->update('users', $data, [
         'id' => $data['id']
      ]);
   }

   public function getAds($id)
   {
      return $this->db->sqlRequest(
         "SELECT ads.*, 
            (SELECT MIN(images.urlPath) FROM images 
            WHERE images.adId = ads.id) AS urlPath
         FROM ads
         WHERE ads.userId = 4"
      );
   }
}

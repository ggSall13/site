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
}

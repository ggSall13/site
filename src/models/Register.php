<?php

namespace Src\Models;

use Src\Core\Model\Model;

class Register extends Model
{
   public function register($data): int|false
   {
      return $this->db->insert('users', $data);
   }
}

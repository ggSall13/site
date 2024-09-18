<?php

namespace Src\Models;

use Src\Core\Model\Model;

class Home extends Model
{
   public function getCategories()
   {
      return $this->db->findTable('categories');
   }
}

<?php

namespace Src\Core\Model;
use Src\Core\Database\Database;

abstract class Model
{
   protected $db;

   public function __construct() {
      $this->db = Database::getInstance();
   }
}

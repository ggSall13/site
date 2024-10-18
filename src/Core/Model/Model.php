<?php

namespace Src\Core\Model;
use Src\Core\Database\DbOperations;

abstract class Model
{
   protected $db;

   public function __construct() {
      $this->db = new DbOperations();
   }
}

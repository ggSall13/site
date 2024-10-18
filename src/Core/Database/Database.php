<?php

namespace Src\Core\Database;

use PDO;
use PDOException;

class Database
{
   private static $instance = null;

   private $conn = null;

   private function __construct() {}

   private function __clone() {}

   public static function getInstance()
   {
      if (self::$instance == null) {
         self::$instance = new self();
      }

      return self::$instance;
   }

   public function getConnection()
   {
      $config = $this->loadConfig();

      if ($this->conn == null) {
         try {
            $this->conn = new PDO($config['dsn'], $config['user'], $config['pass']);
         } catch (PDOException $e) {
            throw new PDOException('Db Error');
         }
      }
      return $this->conn;
   }

   public static function getPdo()
   {
      return self::getInstance()->getConnection();
   }

   private function loadConfig()
   {
      return require_once CONFIG . '/database.php';
   }
}

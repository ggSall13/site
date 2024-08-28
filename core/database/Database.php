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

   public function find(string $table, array $condition, string $need = '*')
   {
      // $need то что мы хотим взять из таблицы
      // то есть если хотим взять name то пишем name

      // Берем только первый ключ чтобы получилась строка
      $field = array_keys($condition)[0];

      $where = "WHERE $field = :$field";

      $sql = "SELECT {$need} FROM {$table} $where";

      $stmt = $this->conn->prepare($sql);

      try {
         $stmt->execute($condition);
      } catch (PDOException $e) {
         die($e->getMessage());
      }

      return $stmt->fetch(PDO::FETCH_ASSOC);
   }

   public function findAll(string $table, array $data, string $need = '*')
   {
      // $need то что мы хотим взять из таблицы
      // то есть если хотим взять name то пишем name

      $condition = [];

      foreach ($data as $key => $val) {
         $condition[] = "$key = :$key";
      }

      $where = 'WHERE ' . implode(' AND ', $condition);

      $sql = "SELECT {$need} FROM {$table} $where";

      $stmt = $this->conn->prepare($sql);

      try {
         $stmt->execute($data);
      } catch (PDOException $e) {
         die($e->getMessage());
      }

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   public function findTable(string $table)
   {
      $stmt = $this->conn->prepare("SELECT * FROM {$table}");

      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   public function insert(string $table, array $data): int
   {
      $fields = array_keys($data);

      $set = [];

      $columns = implode(', ', $fields);

      foreach ($fields as $val) {
         $set[] = ":$val";
      }

      $set = implode(', ', $set);

      $sql = "INSERT INTO {$table} ($columns) VALUES ($set)";

      $stmt = $this->conn->prepare($sql);

      try {
         $stmt->execute($data);
      } catch (PDOException $e) {
         die($e->getMessage());
      }

      return $this->conn->lastInsertId();
   }

   public function update(string $table, array $data, array $conditions = [])
   {
      $fields = array_keys($data);

      $set = [];

      $where = '';

      foreach ($fields as $val) {
         $set[] = "$val = :$val";
      }

      if (count($conditions) > 0) {
         $whereParts = [];

         $condFields = array_keys($conditions);

         foreach ($condFields as $val) {
            $whereParts[] = "$val = :$val";
         }

         $where .= 'WHERE ' . implode(' AND ', $whereParts);
      }
      
      $set = implode(', ', $set);

      $sql = "UPDATE {$table} SET {$set} {$where}";

      $stmt = $this->conn->prepare($sql);

      try {
         $stmt->execute($data);
      } catch (PDOException $e) {
         die($e->getMessage());
      }

      return true;
   }

   private function loadConfig()
   {
      return require_once CONFIG . '/database.php';
   }
}

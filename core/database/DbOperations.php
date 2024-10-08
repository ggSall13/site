<?php

namespace Src\Core\Database;

use Src\Core\Database\Database;
use PDOException;
use PDO;

class DbOperations
{
   protected $conn = null;
   public function __construct()
   {
      $this->conn = Database::getPdo();
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

   public function findAll(string $table, array $conditions, string|array $need = '*')
   {
      // Достать только нужное в таблице

      // $need то что мы хотим взять из таблицы
      // то есть если хотим взять name то пишем name

      $data = [];

      foreach ($conditions as $key => $val) {
         $data[] = "$key = :$key";
      }

      if (is_array($need)) {
         $need = implode(', ', $need);
      }

      $where = 'WHERE ' . implode(' AND ', $data);

      $sql = "SELECT {$need} FROM {$table} $where";

      $stmt = $this->conn->prepare($sql);

      try {
         $stmt->execute($conditions);
      } catch (PDOException $e) {
         die($e->getMessage());
      }

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   public function findTable(string $table)
   {
      // Достать все что лежит в таблице
      $stmt = $this->conn->prepare("SELECT * FROM {$table}");

      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }


   public function insert(string $table, array $data): int
   {
      /*
         $data должен быть ассоциативным массивом, где ключ = название поля в таблице
         А значение = значение
      */

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

   // public function findAllAndJoin(string $table1, string $on, string $table2, string $on2, array $conditions = null, $need = '*')
   // {
   //    // Достать только нужное в таблице

   //    // $need то что мы хотим взять из таблицы
   //    // то есть если хотим взять name то пишем name

   //    $data = [];

   //    $where = '';

   //    if ($conditions) {
   //       foreach ($conditions as $key => $val) {
   //          $data[] = "{$table1}.$key = :$key";
   //       }

   //       $where = 'WHERE ' . implode(' AND ', $data);
   //    }

   //    if (is_array($need)) {
   //       $need = implode(', ', $need);
   //    }

   //    $sql = "SELECT {$need} FROM {$table1} JOIN {$table2} ON {$table2}.{$on2} = {$table1}.{$on} {$where}";
   //    dump($sql);
   //    $stmt = $this->conn->prepare($sql);

   //    try {
   //       $stmt->execute($conditions);
   //    } catch (PDOException $e) {
   //       die($e->getMessage());
   //    }

   //    return $stmt->fetchAll(PDO::FETCH_ASSOC);
   // }


   public function delete(string $table, array $conditions)
   {
      $fields = array_keys($conditions);

      $where = '';

      $set = [];

      foreach ($conditions as $key => $condition) {
         $set[] = "$key = :$key";
      }

      $where = 'WHERE ' . implode(' AND ', $set);

      $sql = "DELETE FROM {$table} $where";

      $stmt = $this->conn->prepare($sql);

      return $stmt->execute($conditions);
   }

   // Для запроса sql собственного
   public function sqlRequest($sql, $params = [])
   {
      $stmt = $this->conn->prepare($sql);

      $stmt->execute($params);

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   public function getLastInsertId()
   {
      return $this->conn->lastInsertId();
   }
}

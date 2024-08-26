<?php

namespace Src\Core\Auth;

use Src\Core\Database\Database;
use Src\Core\Auth\User;


// Класс для аутентификации

class Auth
{
   private $db;
   

   public function __construct()
   {
      $this->db = Database::getInstance();
   }
   
   public function attempt(array $data)
   {
      $user = $this->db->find('users', ['email' => $data['email']]);

      if (!$user) {
         return false;
      }

      if (!password_verify($data['password'], $user['password'])) {
         return false;
      }

      if (isset($data['checkMe']) && $data['checkMe'] !== '') {
         // setcookie('name', $user['name'], time() + 10 * 365 * 24 * 60);
         // setcookie('email', $user['email'], time() + 10 * 365 * 24 * 60);
         // setcookie('phone', $user['phone'], time() + 10 * 365 * 24 * 60);
         // setcookie('id', $user['id'], time() + 10 * 365 * 24 * 60);
         
         /* 
            Что-бы получить то что лежит  $_COOKIE['user']
            Его нужно json_decode в переменную, чтобы получить объект класса
            Потому что в cookie нельзя положить массив как в сессию
         */

         setcookie('user', json_encode($user), time() + 10 * 365 * 24 * 60);
      }

      $_SESSION['user'] = [
         'id' => $user['id'],
         'phone' => $user['phone'],
         'email' => $user['email'],
         'name' => $user['name'],
      ];

      return true;
   }

   public function user($id)
   {
      $user =  $this->db->find('users', ['id' => $id]);

      if (!$user) {
         return false;
      }

      return new User($user['id'], $user['name'], $user['email']);
   }
}

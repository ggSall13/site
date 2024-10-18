<?php

namespace Src\Core\Auth;

use Src\Core\Database\DbOperations;
use Src\Core\Auth\User;


// Класс для аутентификации

class Auth
{
   private $db;


   public function __construct()
   {
      $this->db = new DbOperations();
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
         /* 
            Что-бы получить то что лежит  $_COOKIE['user']
            Его нужно json_decode в переменную, чтобы получить объект класса
            Потому что в cookie нельзя положить массив как в сессию
         */

         setcookie('user', json_encode($user), time() + 10 * 365 * 24 * 60 * 60, '/');
      }

      $_SESSION['user'] = [
         'id' => $user['id'],
         'phone' => $user['phone'],
         'email' => $user['email'],
         'name' => $user['name'],
      ];

      return true;
   }

   // Обновление сессии, и если есть то куки. 
   public function updSesionCookie($data)
   {
      $user = $this->db->find('users', ['id' => $data['id']]);

      if (!$user) {
         return false;
      }

      if (isset($_COOKIE['user'])) {
         setcookie('user', json_encode($user), time() + 10 * 365 * 24 * 60 * 60, '/');
      }

      $_SESSION['user'] = [
         'id' => $user['id'],
         'phone' => $user['phone'],
         'email' => $user['email'],
         'name' => $user['name'],
      ];

      return true;
   }

   public function cookie()
   {
      if (isset($_COOKIE['user'])) {
         return json_decode($_COOKIE['user']);
      }

      return false;
   }
}

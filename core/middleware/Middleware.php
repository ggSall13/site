<?php

namespace Src\Core\Middleware;

abstract class Middleware 
{
   protected function checkAuth()
   {
      if (isset($_SESSION['user'])) {
         return true;
      }

      if (isset($_COOKIE['user'])) {
         return true;
      }

      return false;
   }
}

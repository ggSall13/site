<?php

namespace Src\Core\Middleware;

use Src\Core\Middleware\Middleware;

class Auth extends Middleware
{
   public function handle()
   {
      if (!$this->checkAuth())
      {
         header('Location: /register');
      }
   }
}


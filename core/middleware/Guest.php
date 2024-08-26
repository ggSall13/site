<?php

namespace Src\Core\Middleware;

use Src\Core\Middleware\Middleware;

class Guest extends Middleware
{
   public function handle()
   {
      if ($this->checkAuth())
      {
         header('Location: /');
      }
   }
}


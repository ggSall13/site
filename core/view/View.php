<?php

namespace Src\Core\View;

class View 
{
   public static function showError(string|int $code = 404)
   {
      $errPath = APP_DIR . '/views/errors/' . $code . '.php';

      if (file_exists($errPath)) {
         require_once $errPath;
      }
   }

   public function page(string $file, array $vars = [])
   {
      extract(array_merge($this->defaultExtract(), $vars));
      $filePath = APP_DIR . '/views/pages/' . $file . '.php';

      if (file_exists($filePath)) {
         require_once $filePath;
      }
   }

   public function inc(string $file, array $vars = [])
   {
      extract(array_merge($this->defaultExtract(), $vars));
      $filePath = APP_DIR . '/views/incs/' . $file . '.php';

      if (file_exists($filePath)) {
         require_once $filePath;
      }
   }

   private function defaultExtract()
   {
      return [
         'view' => $this,
      ];
   }
}

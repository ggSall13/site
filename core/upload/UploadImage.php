<?php

namespace Src\Core\Upload;

class UploadImage
{

   private $uploadFiles = [];

   public function __construct(
      private $name,
      private $tmpname,
      private $type,
      private $error,
      private $size
   ) {}

   public function move($path, $maxImages = 5)
   {
      $this->uploadFiles = [];

      if ($this->imageValidate($maxImages)) {
         $filePath = WWW . '/storage' . $path . '/' .date('Y') . '/' . date('M');
         $fileUrl = APP_URL . '/public/storage' . $path . '/' .date('Y') . '/' . date('M');

         if (!is_dir($filePath)) {
            mkdir($filePath, 0777, true);
         }

         $fileNames = $this->createFileName();
         $tmpFilePaths = $this->tmpname;

         $error = [];

         /*
            Циклом пробегаемсся по массиву из имен
            По индексу берем tmp_name И закидываем в папку
         */
         foreach ($fileNames as $index => $val) {
            if (isset($tmpFilePaths[$index])) {
               $tmpFilePath[$index] = $tmpFilePaths[$index];
               $this->uploadFiles[$index]['dir'] = $filePath . "/$val";
               $this->uploadFiles[$index]['url'] = "$fileUrl/$val";
               
               if (!move_uploaded_file($tmpFilePath[$index], $filePath. "/$val")) {
                  $error['error'] = 'Не удалось загрузить изображение';
               }
           }
         }
         
         if (empty($error)) {
            return $this->uploadFiles;
         } else {
            $_SESSION['errors']['image'] = $error;
            return false;
         }
      }

      return false;
   }

   private function createFileName()
   {
      $exts = $this->getExt();

      $fileNames = [];

      foreach ($exts as $index => $val) {
         $fileNames[] = date('d-m-Y-H-i-s') . "-$index.$val"; 
      }

      return $fileNames;
   }

   private function imageValidate($maxImages = 5)
   {
      // для проверки расширения, и размера изображения, и error
      $errors = [];
      $allowedExts = ['png', 'jpg'];

      // проверка на расширение
      foreach ($this->getExt() as $val) {
         if (!in_array($val, $allowedExts)) {
            $errors['ext'] = 'Файл должен быть png или jpg';
            break;
         }
      }

      // проверка на размер
      foreach ($this->size as $val) {
         if ($val > 2097152) {
            $errors['size'] = 'Файл должен весить меньше 2 мегабайт';
            break;
         }
      }

      // проверка на кол-во
      if (count($this->name) > $maxImages) {
         $errors['count'] = 'На объявлении можно максимум 5 изображении';
      }

      if (empty($errors)) {
         return true;
      } else {
         $_SESSION['errors']['image'] = $errors;
         return false;
      }
   }

   private function getExt()
   {
      $arr = [];

      foreach ($this->name as $val) {
         $arr[] = pathinfo($val, PATHINFO_EXTENSION);
      }

      return $arr;
   }
}

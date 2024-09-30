<?php

namespace Src\Models;

use Src\Core\Model\Model;

class Show extends Model
{
   public function getAllCategories()
   {
      $categories = $this->db->findAllAndJoin(
         'categories',
         'id',
         'subCategories',
         'parentCategoryId'
      );

      $allCategories = [];

      /*
        Проходим циклом по категориям, чтобы категории корректно отображались
       */

      foreach ($categories as $key => $category) {
         $allCategories[$category['categoryName']][] = $category;
      }

      return $allCategories;
   }

   public function getAllAds($category = null)
   {
      // проверка что если $category не null то ['categorySlug' => $category]
      $categorySlug = $category ? ['categorySlug' => $category] : null;

      $ads = $this->db->findAllAndJoin(
         'ads',
         'parentCategoryId',
         'categories',
         'id',
         $categorySlug
      );

      /*
         Если в перменную $category приходит что-то но $ads пустой
         То мы делаем другой запрос в БД
         Где ищем categories.slug = $category
      */

      if ($category && !$ads) {
         $ads = $this->db->findAllAndJoin(
            'categories',
            'id',
            'ads',
            'parentCategoryId',
            ['slug' => $category]
         );
      }

      return $ads ?? false;
   }
}

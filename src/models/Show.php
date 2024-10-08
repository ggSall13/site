<?php

namespace Src\Models;

use Src\Core\Model\Model;

class Show extends Model
{
   public function getAllCategories()
   {
      $categories = $this->db->sqlRequest(
         "SELECT * FROM categories JOIN subCategories
         ON categories.id = subCategories.parentCategoryId"
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

   public function getAllAds($category = null, $sort = null, $orderSort = null)
   {
      $needs = implode(', ', $this->needs());

      // Полуачем здесь для SQL запроса цену
      $priceSort = $this->gerPriceAds($sort);

      $sortByOrder = $this->getOrderSort($orderSort);

      // $set = это для SQL запроса, в основном для сортировки по категориям + цене + дате добавления
      $set = $this->createSet($category, $priceSort, $sortByOrder);

      $ads = $this->getSortedAds($set, $needs, $category);

      $images = $this->getImages();

      return $this->getAd($images, $ads) ?? false;
   }

   // Получение краткой информации об объявлении
   private function needs()
   {
      return ['ads.id', 'ads.title', 'ads.price', 'ads.adSlug', 'ads.description', 'ads.createdAt'];
   }

   // Здесь мы получаем конкретно сортировку цены, от суммы и до суммы
   private function gerPriceAds($sort)
   {
      $sqlSort = [];
      $params = [];

      // Проверка на существование и корректность значений
      if (isset($sort['from']) && is_numeric($sort['from'])) {
         $sqlSort[] = "ads.price >= :from";
         $params['from'] = (int)$sort['from'];
      }

      if (isset($sort['to']) && is_numeric($sort['to'])) {
         $sqlSort[] = "ads.price <= :to";
         $params['to'] = (int)$sort['to'];
      }

      // в 'query' находится "ads.price >= :from" AND "ads.price <= :to"
      // в 'params' находится ['to' => число, 'from' => число]
      $result = ['query' => implode(' AND ', $sqlSort), 'params' => $params];

      return empty($sqlSort) ? null : $result;
   }

   private function getImages()
   {
      return $this->db->sqlRequest(
         "SELECT ads.id, 
            (SELECT MIN(images.urlPath) FROM images
            WHERE images.adId = ads.id) AS urlPath
         FROM ads
         "
      );
   }

   // метод для получения объявления, и фотографии для него
   private function getAd($images, $ads)
   {
      $imagesMap = [];

      foreach ($images as $image) {
         $imagesMap[$image['id']] = $image['urlPath'];
      }

      foreach ($ads as &$ad) {
         if (isset($imagesMap[$ad['id']])) {
            $ad['image'] = $imagesMap[$ad['id']];
         }
      }

      return $ads;
   }

   private function getSortedAds($set, $needs, $category)
   {
      $sql = "SELECT {$needs}, categories.categoryName, categories.slug 
      FROM ads JOIN categories ON ads.parentCategoryId = categories.id";

      $conditions = [];
      $params = [];
      $order = '';

      if ($category) {
         $conditions['category'] = 'categorySlug = :category';
         $params['category'] = $category;
      }

      if (isset($set['price']) && isset($set['price']['query'])) {
         $conditions['price'] = $set['price']['query'];
         $params = array_merge($params, $set['price']['params'],);
      }

      if (isset($set['sortBy']) && isset($set['sortBy']['query'])) {
         $order = $set['sortBy']['query'];
      }

      if (!empty($conditions)) {
         $sql .= ' WHERE ' . implode(' AND ', $conditions);
      }

      if ($order) {
         $sql .= $order['sortBy'];
      }

      $ads = $this->db->sqlRequest($sql, $params);


      // Если вдруг есть категория, типо videocarti, 
      // но при этом нет постов, то ищем уже по categories.slug
      // ТО есть по категории родителя, напирмер computeri

      if (!$ads && $category) {
         $sql = "SELECT {$needs}, categories.categoryName, categories.slug 
         FROM ads JOIN categories ON ads.parentCategoryId = categories.id";

         $conditions['category'] = 'slug = :category';
         $params['category'] = $category;

         if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
         }

         $ads = $this->db->sqlRequest($sql, $params);
      }


      return $ads;
   }

   private function createSet($category, $priceSort, $orderSort)
   {
      $set = [];

      if ($category) {
         $set['categorySlug'] = $category;
      }

      if ($priceSort) {
         $set['price'] = $priceSort;
      }

      if ($orderSort) {
         $set['sortBy'] = $orderSort;
      }
      

      return $set;
   }

   private function getOrderSort($orderSort) 
   {
      $sqlSort = [];

      if (isset($orderSort['sortBy'])) {
         switch ($orderSort['sortBy']) {
            case 'price' :
               $sqlSort['sortBy'] = ' ORDER BY ads.price';
               break;
            case 'priceDesc' :
               $sqlSort['sortBy'] = ' ORDER BY ads.price DESC';
               break;
            case 'date' :
               $sqlSort['sortBy'] = ' ORDER BY ads.createdAt DESC';
               break;
         }
      }

      return ['query' => $sqlSort];
   }
}

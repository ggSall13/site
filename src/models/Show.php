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

      foreach ($categories as $category) {
         $allCategories[$category['categoryName']][] = $category;
      }

      return $allCategories;
   }

   public function getAllAds($category = null, $sort = null, $orderSort = null, $start = null, $search = null)
   {
      $needs = implode(', ', $this->needs());

      // Получаем здесь для SQL запроса цену
      $priceSort = $this->getPriceAds($sort);
      // Получаем здесь для SQL запроса сортировку
      $sortByOrder = $this->getOrderSort($orderSort);
      // Получаем здесь для SQL запроса поиск
      $searchParams = $this->getSearch($search);
      // $set = это для SQL запроса, в основном для сортировки по категориям + цене + дате добавления
      $set = $this->createSet($category, $priceSort, $sortByOrder, $searchParams);

      $ads = $this->getSortedAds($set, $needs, $category, $start, $searchParams);

      $images = $this->getImages();

      return $this->getAd($images, $ads) ?? false;
   }

   public function adsCount($category, $sort, $orderSort, $search)
   {
      $needs = implode(', ', $this->needs());

      // Получаем здесь для SQL запроса цену
      $priceSort = $this->getPriceAds($sort);
      // Получаем здесь для SQL запроса сортировку
      $sortByOrder = $this->getOrderSort($orderSort);
      // Получаем здесь для SQL запроса поиск
      $searchParams = $this->getSearch($search);

      // $set = это для SQL запроса, в основном для сортировки по категориям + цене + дате добавления
      $set = $this->createSet($category, $priceSort, $sortByOrder, $searchParams);

      $ads = $this->getSortedAds($set, $needs, $category, null, $searchParams);
   
      return count($ads);
   }

   // Получение краткой информации об объявлении
   private function needs()
   {
      return ['ads.id', 'ads.title', 'ads.price', 'ads.adSlug', 'ads.description', 'ads.createdAt',];
   }

   // Здесь мы получаем конкретно сортировку цены, от суммы и до суммы
   private function getPriceAds($sort)
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

      if (isset($params['to']) && $params['to'] <= 0) {
         $params['to'] = 1000000;
      }

      // в 'query' находится "ads.price >= :from" AND "ads.price <= :to"
      // в 'params' находится ['to' => число, 'from' => число]
      $result = ['query' => implode(' AND ', $sqlSort), 'params' => $params];

      return empty($sqlSort) ? null : $result;
   }

   // для создания поиска
   private function getSearch($search)
   {
      $sql = '';
      $params = [];

      if (isset($search) && $search !== '') {
         $sql = '(ads.title LIKE :search1 OR ads.description LIKE :search2)';
         $params['search1'] = '%' . h($search) . '%';
         $params['search2'] = '%' . h($search) . '%';
      }

      $result = ['query' => $sql, 'params' => $params];

      return empty($params) ? null : $result;
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

   private function getSortedAds($set, $needs, $category, $start = null, $search = null)
   {
      $sql = $this->createQuery($needs);

      $params = [];
      $conditions = $this->createConditions($category, $set, $search, $params);
      $order = $this->createOrder($set);

      if (!empty($conditions)) {
         $sql .= ' WHERE ' . implode(' AND ', $conditions);
      }

      if ($order) {
         $sql .= $order['sortBy'];
      } else {
         $sql .= ' ORDER BY id DESC';
      }

      if (isset($start) && is_array($start)) {
         $sql .= ' LIMIT ' . implode(', ', $start);
      }  

      $ads = $this->db->sqlRequest($sql, $params);

      // Если вдруг есть категория, типо videocarti, 
      // но при этом нет постов, то ищем уже по categories.slug
      // ТО есть по категории родителя, напирмер computeri

      if (!$ads && $category) {
         $ads = $this->getAdsByParentCategory($needs, $category, $params, $start);
      }

      return $ads;
   }

   private function createOrder($set)
   {
      return isset($set['sortBy']) && isset($set['sortBy']['query']) ? $set['sortBy']['query'] : '';
   }

   private function createConditions($category, $set, $search, &$params)
   {
      $conditions = [];

      if ($category) {
         $conditions['category'] = 'categorySlug = :category';
         $params['category'] = $category;
      }

      if (isset($set['price']) && isset($set['price']['query'])) {
         $conditions['price'] = $set['price']['query'];
         $params = array_merge($params, $set['price']['params']);
      }

      if (isset($search) && isset($search['query'])) {
         $conditions['search'] = $set['search']['query'];
         $params = array_merge($params, $set['search']['params']);
      }

      return $conditions;
   }

   private function getAdsByParentCategory($needs, $category, &$params, $start)
   {
      $sql = $this->createQuery($needs);

      $conditions['category'] = 'slug = :category';
      $params['category'] = $category;

      if (!empty($conditions)) {
         $sql .= ' WHERE ' . implode(' AND ', $conditions);
      }

      if (isset($start) && is_array($start)) {
         $sql .= ' LIMIT ' . implode(', ', $start);
      } 

      return $this->db->sqlRequest($sql, $params);
   }

   private function createQuery($needs)
   {
      return "SELECT {$needs}, categories.categoryName, categories.slug 
      FROM ads JOIN categories ON ads.parentCategoryId = categories.id";
   }

   private function createSet($category, $priceSort, $orderSort, $search)
   {
      $set = [];
      if ($category && $category !== '') {
         $set['categorySlug'] = $category;
      }

      if ($priceSort && $priceSort !== '') {
         $set['price'] = $priceSort;
      }

      if ($orderSort && $orderSort !== '') {
         $set['sortBy'] = $orderSort;
      }

      if (is_array($search)) {
         $set['search'] = $search;
      }

      return $set;
   }

   private function getOrderSort($orderSort)
   {
      $sqlSort = [];

      if (isset($orderSort['sortBy'])) {
         switch ($orderSort['sortBy']) {
            case 'price':
               $sqlSort['sortBy'] = ' ORDER BY ads.price';
               break;
            case 'priceDesc':
               $sqlSort['sortBy'] = ' ORDER BY ads.price DESC';
               break;
            case 'date':
               $sqlSort['sortBy'] = ' ORDER BY ads.createdAt DESC';
               break;
         }
      }

      return !empty($sqlSort) ? ['query' => $sqlSort] : '';
   }

   private function countAds($countAds = 0)
   {
      return $countAds;
   }
}

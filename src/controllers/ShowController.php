<?php

namespace Src\Controllers;

use Src\Core\Controller\Controller;
use Src\Core\Pagination\Pagination;

class ShowController extends Controller
{
   public function index()
   {
      $sort = $this->getParams();
      
      $vars = [
         'categories' => $this->model->getAllCategories(),
         'ads' => $sort['ads'],
         'pagination' => $sort['pagination']
      ];

      $this->view->page('ads/show', $vars);
   }

   private function getParams()
   {
      $category = $this->params['categoryName'] ?? null;

      $sort = $this->createSort();

      $orderSort = $this->createOrderSort();
      
      $search = $this->createSeach();

      $pagination = $this->createPagination($category, $sort, $orderSort, $search);

      $ads = $this->model->getAllAds($category, $sort, $orderSort, $pagination->start(), $search);

      return [
         'pagination' => $pagination,
         'ads' => $ads,
      ];
   }

   private function createSeach()
   {
      $search = null;

      if (isset($_GET['search'])) {
         $search = h($_GET['search']);
      }

      return $search;
   }
   private function createSort()
   {
      $sort = null;

      if (isset($_GET['from']) || isset($_GET['to'])) {
         $sort = [
            'from' => (int)$_GET['from'],
            'to' => (int)$_GET['to']
         ];
      }

      return $sort;
   }

   private function createOrderSort()
   {
      $orderSort = null;

      if (isset($_GET['sortBy'])) {
         $orderSort = [
            'sortBy' => h($_GET['sortBy'])
         ];
      }

      return $orderSort;
   }

   private function createPagination($category = null, $sort = null, $orderSort = null, $search = null)
   {
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      $max = 5;
      $total = $this->model->adsCount($category, $sort, $orderSort, $search);

      return new Pagination($page, $max, $total);
   }
}

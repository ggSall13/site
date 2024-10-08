<?php

namespace Src\Controllers;

use Src\Core\Controller\Controller;

class ShowController extends Controller
{
   public function index()
   {
      $category = $this->params['categoryName'] ?? null;
      
      $sort = null;
      if (isset($_GET['from']) || isset($_GET['to'])) {
         $sort = [
            'from' => $_GET['from'], 
            'to' => $_GET['to']
         ];
      }

      $orderSort = null;
      
      if (isset($_GET['sortBy'])) {
         $orderSort = [
            'sortBy' => $_GET['sortBy']
         ];
      }

      $vars = [
         'categories' => $this->model->getAllCategories(),
         'ads' => $this->model->getAllAds($category, $sort, $orderSort)
      ];

      $this->view->page('ads/show', $vars);
   }
}

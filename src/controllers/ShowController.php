<?php

namespace Src\Controllers;

use Src\Core\Controller\Controller;

class ShowController extends Controller
{
   public function index()
   {
      $category = $this->params['categoryName'] ?? null;

      $vars = [
         'categories' => $this->model->getAllCategories(),
         'ads' => $this->model->getAllAds($category)
      ];

      $this->view->page('ads/show', $vars);
   }
}

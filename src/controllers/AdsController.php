<?php

namespace Src\Controllers;

use Src\Core\Controller\Controller;

// Контроллер объявлений
class AdsController extends Controller
{
   public function index()
   {
      $this->view->page('ads/new');
   }

   public function store()
   {
      dd($_FILES, $_POST);
   }
}

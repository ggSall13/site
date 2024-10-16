<?php

namespace Src\Core\Pagination;

class Pagination
{
   protected $pagesCnt = 1;
   public function __construct(
      protected $page = 1,
      protected $max = 10,
      // Количество постов
      protected $total = 1
   ) {
      $this->pagesCnt = $this->getPagesCnt();
      $this->page = (int)$this->getPage();
   }

   private function getPage()
   {
      if ($this->page < 1) {
         $this->page = 1;
      }

      if ($this->page > $this->pagesCnt) {
         $this->page = $this->pagesCnt;
      }

      return $this->page;
   }

   // Количество страницц
   private function getPagesCnt()
   {
      return (int)ceil($this->total / $this->max);
   }

   // Это для sql limit start offset
   public function start()
   {

      if ($this->page < 1) {
         $this->page = 1;
      }

      $start = ($this->page - 1) * $this->max;

      return ['start' => $start, 'offset' => $this->max];
   }

   public function getHtml()
   {
      $this->getLink();
      $html = '<nav aria-label="Page navigation example"> <ul class="pagination">';
      for ($i = 1; $i <= $this->pagesCnt; $i++) {
         $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$this->getLink()}page=$i\">$i</a></li>";
      }

      $html .= '</ul></nav>';

      return $html;
   }

   private function getLink()
   {
      $url = $_SERVER['REQUEST_URI'];
      $url = explode('?', $url);
      $uri = $url[0];
      $uri .= '?';

      if (isset($url[1]) && $url[1] !== '') {

         $params = explode('&', $url[1]);

         foreach ($params as $param) {
            // Чтобы в uri не добавлялось page
            if (!str_contains($param, 'page=')) {
               $uri .= "{$param}&";
            }
         }
      }

      return $uri;
   }
}

<?php

/**
 * @var Src\Core\View\View $view
 */

?>

<?php $view->inc('start', ['title' => 'Test']); ?>
<?php $view->inc('header'); ?>
<div class="container content">
   <h1 class="py-5">Профиль</h1>
   <div class="col-md-3">
      <a href="/profile/edit">Редактировать профиль</a>
   </div>
   <hr>
   <div class="col-md-3 py-3">
      <h2>Ваши объявления :</h2>
   </div>
   <div class="row">
      <?php if ($view) : ?>
         <h5>Объявлений нет, <a href="/ads/new">добавить</a></h5>
      <?php else : ?>
         <div class="col-md-3 py-3">
            <div class="card" style="width: 18rem;">
               <img src="" class="card-img-top" alt="...">
               <div class="card-body">
                  <h5 class="card-title">Card title</h5>
                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                  <a href="#" class="btn btn-primary">Go somewhere</a>
               </div>
            </div>
         </div>
      <?php endif; ?>
   </div>
</div>
<?php $view->inc('footer') ?>
<?php $view->inc('end'); ?>
<?php

/**
 * @var Src\Core\View\View $view
 */

?>

<?php $view->inc('start', ['title' => 'Home']); ?>
<?php $view->inc('header'); ?>
<div class="container content">
   <div class="row">
      <div class="col-md-3 py-3">
         <a href="/all" class="category-card">
            <div class="card" style="width: 18rem;">
               <img src="<?= APP_URL . '/public/assets/images/place_holder_image.png' ?>" class="img-category" alt="...">
               <div class="card-body">
                  <h5 class="card-title">Все категории</h5>
               </div>
            </div>
         </a>
      </div>
      <?php foreach ($categories as $val) : ?>
         <div class="col-md-3 py-3">
            <a href="<?= "/{$val['slug']}" ?>" class="category-card">
               <div class="card" style="width: 18rem;">
                  <img src="<?= $val['urlPath'] ?? APP_URL . '/public/assets/images/place_holder_image.png' ?>" class="img-category" alt="...">
                  <div class="card-body">
                     <h5 class="card-title"><?= $val['categoryName'] ?></h5>
                  </div>
               </div>
            </a>
         </div>
      <?php endforeach; ?>
   </div>
</div>
<?php $view->inc('footer') ?>
<?php $view->inc('end'); ?>
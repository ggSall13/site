<?php

/**
 * @var Src\Core\View\View $view
 */

 CONST TEST = 'e';

?>

<?php $view->inc('start', ['title' => 'Test']); ?>
<?php $view->inc('header'); ?>
<div class="container content">
   <div class="row">
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
   </div>
</div>
<?php $view->inc('footer') ?>
<?php $view->inc('end'); ?>
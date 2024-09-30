<?php

/**
 * @var Src\Core\View\View $view
 */

?>
<?php $view->inc('start') ?>
<?php $view->inc('header') ?>
<div class="container content filters py-5">
   <h2>Фильтры</h2>
   <hr>
   <div class="row">
      <div class="col-md-2">
         <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
               Категории
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
               <?php foreach ($categories as $categoryName => $category) : ?>
                  <li class="dropdown-submenu">
                     <span class="dropdown-item"><?= h($categoryName) ?></span>
                     <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/<?= 'show/' . h($category[0]['slug']) ?>">Все в <?= h($categoryName) ?></a></li>
                        <?php foreach ($category as $subCategory) : ?>
                           <li><a class="dropdown-item" href="/<?= 'show/' . h("{$subCategory['subCategorySlug']}") ?>"><?= h($subCategory['subCategoryName']) ?></a></li>
                        <?php endforeach; ?>
                     </ul>
                  </li>
               <?php endforeach; ?>
            </ul>
         </div>
      </div>
   </div>
</div>
<?php $view->inc('footer') ?>

<?php $view->inc('end') ?>
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
         <h5>Выбор категории</h5>
         <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
               Категории
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
               <a href="/show" class="dropdown-item">Все категории</a>
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
      <div class="col-md-3">
         <h5>Сортировать по:</h5>
         <form action="" method="get">
            <div class="row">
               <div class="input-group">
                  <input type="number" class="form-control inputInline" value="<?= isset($_GET['from']) ? h($_GET['from']) : ''?>" name="from" placeholder="От">
                  <input type="number" class="form-control inputInline" value="<?= isset($_GET['to']) ? h($_GET['to']) : ''?>" name="to" placeholder="До">
                  <button type="submit" class="btn btn-success">Поиск</button>
               </div>
               <div class="py-3">
                  <div class="form-check">
                     <input class="form-check-input" type="radio" value="date" name="sortBy" id="dateAdd">
                     <label class="form-check-label" for="dateAdd">
                        Дате добавления
                     </label>
                  </div>
                  <div class="form-check">
                     <input class="form-check-input" type="radio" value="priceDesc" name="sortBy" id="priceDesc">
                     <label class="form-check-label" for="priceDesc">
                        Убыванию цены
                     </label>
                  </div>
                  <div class="form-check">
                     <input class="form-check-input" type="radio" value="price" name="sortBy" id="price">
                     <label class="form-check-label" for="price">
                        Возрастанию цены
                     </label>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
   <hr>
   <div class="ads">
      <div class="row">
         <?php if (empty($ads)) : ?>
            <h3 class="py-3">Нет объявлений</h3>
         <?php endif; ?>
         <?php foreach ($ads as $ad) : ?>
            <div class="col-md-3 py-3">
               <a href="/ads/<?= $ad['adSlug'] ?>" class="post-card">
                  <img src="<?= $ad['image'] ?? WWW_URL . '/assets/images/place_holder_image.png' ?>" class="img-card-top img-post" alt="...">
                  <div class="card" style="width: 18rem;">
                     <hr>
                     <div class="card-body">
                        <h5 class="card-title"><?= $ad['title'] ?></h5>
                        <p class="card-text pt-3">
                           <?php if (strlen($ad['description']) > 100) : ?>
                              <?= substr($ad['description'], 0, 100) ?>
                           <?php else : ?>
                              <?= $ad['description']; ?>
                           <?php endif; ?>
                        </p>
                        <h6 class="card-test pt-3">
                           <?= date('d.m.Y H:s', strtotime($ad['createdAt'])) ?>
                        </h6>
                        <h6 class="card-text pt-3">
                           <?= $ad['price'] ?> ₽
                        </h6>
                     </div>
                  </div>
               </a>
            </div>
         <?php endforeach; ?>
      </div>
   </div>
</div>
<?php $view->inc('footer') ?>

<?php $view->inc('end') ?>
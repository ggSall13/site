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
      <div class="col-md-6">
         <h5>Сортировать по:</h5>
         <form action="" method="get">
            <div class="row">
               <div class="input-group">
                  <input type="number" class="form-control inputInline" value="<?= isset($_GET['from']) ? h($_GET['from']) : '' ?>" name="from" placeholder="От">
                  <input type="number" class="form-control inputInline" value="<?= isset($_GET['to']) ? h($_GET['to']) : '' ?>" name="to" placeholder="До">
                  <input class="form-control me-2" value="<?= isset($_GET['search']) ? h($_GET['search']) : '' ?>" name="search" type="search" placeholder="Поиск" aria-label="Search">
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
         <a href="/show"><button class="btn btn-success">Очистить Сортировку</button></a>
      </div>
   </div>
   <hr>
   <div class="ads">
      <div class="row">
         <?php if (empty($ads)) : ?>
            <h3 class="py-3">Нет объявлений</h3>
         <?php endif; ?>
         <div class="col-md-9 py-3">
            <?php foreach ($ads as $ad) : ?>
               <a href="/ads/<?= $ad['adSlug'] ?>" class="post-card">
                  <div class="card mb-3">
                     <div class="card-body">
                        <div class="row g-0">
                           <div class="col-md-4">
                              <img src="<?= $ad['image'] ?? WWW_URL . '/assets/images/place_holder_image.png' ?>" class="img-card-top img-post" alt="...">
                           </div>
                           <div class="col-md-8">
                              <h5 class="card-title"><?= h($ad['title']) ?></h5>
                              <p class="card-text pt-5">
                                 <?php if (mb_strlen($ad['description']) > 100) : ?>
                                    <?= h(mb_substr($ad['description'], 0, 100) . '...')?>
                                 <?php else : ?>
                                    <?= h($ad['description']); ?>
                                 <?php endif; ?>
                              </p>
                              <h6 class="card-test pt-5">
                                 <?= date('d.m.Y H:s', strtotime($ad['createdAt'])) ?>
                              </h6>
                              <h6 class="card-text pt-5">
                                 <?= h($ad['price']) ?> ₽
                              </h6>
                           </div>
                        </div>
                     </div>
                  </div>
               </a>
            <?php endforeach; ?>
         </div>
      </div>
   </div>
   <?= $pagination->getHtml() ?>
</div>
<?php $view->inc('footer') ?>

<?php $view->inc('end') ?>
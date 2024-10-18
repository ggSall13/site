<?php

/**
 * @var Src\Core\View\View $view
 */
?>
<?php $view->inc('start'); ?>
<?php $view->inc('header'); ?>

<div class="container py-5">
   <h3>Имя пользователя: <?= $userInfo['name'] ?></h3>
   <h4>На сайте с <?= date('d.m.y', strtotime($userInfo['created_At'])) ?></h4>
   <hr>
   <h4>Объвления пользователя:</h4>
   <div class="row">
      <?php foreach ($ads as $ad) : ?>
         <div class="col-md-3 py-3">
            <a href="<?= "/ads/{$ad['adSlug']}" ?>" class="post-card">
               <div class="card" style="width: 18rem;">
                  <img src="<?= $ad['urlPath'] ?? WWW_URL . '/assets/images/place_holder_image.png' ?>" class="img-card-top img-post" alt="...">
                  <hr>
                  <div class="card-body">
                     <h5 class="card-title"><?= h($ad['title']) ?></h5>
                     <p class="card-text pt-3">
                        <?php if (mb_strlen($ad['description']) > 100) : ?>
                           <?= h(mb_substr($ad['description'], 0, 100)) ?>
                        <?php else : ?>
                           <?= h($ad['description']); ?>
                        <?php endif; ?>
                     </p>
                  </div>
               </div>
            </a>
         </div>
      <?php endforeach; ?>
   </div>
</div>

<?php $view->inc('footer'); ?>
<?php $view->inc('end'); ?>
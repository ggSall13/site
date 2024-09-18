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
      <?php if (!$ads) : ?>
         <h5>Объявлений нет, <a href="/ads/new">добавить</a></h5>
      <?php else : ?>
         <h5><a href="/ads/new">добавить</a></h5>
         </h5>
         <div class="mb-3 error">
            <?php if (isset($_SESSION['erorrs']['delete'])) : ?>
               <p><?= $_SESSION['erorrs']['delete'] ?></p>
            <?php endif; ?>
         </div>
         <?php foreach ($ads as $ad) : ?>
            <div class="col-md-3 py-3">
               <a href="" class="post-card">
                  <div class="card" style="width: 18rem;">
                     <img src="<?= $ad['urlPath'] ?? APP_URL . '/public/assets/images/place_holder_image.png' ?>" class="img-card-top img-post" alt="...">
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
                        <div class="buttons pt-5">
                           <a href="<?= "/ads/edit/{$ad['id']}" ?>" class="btn btn-success">Edit</a>
                           <form action="<?= "/ads/delete/{$ad['id']}" ?>" method="post">
                              <input type="hidden" name="method" value="DELETE">
                              <button class="btn btn-danger ms-5">Delete</button>
                           </form>
                        </div>
                     </div>
                  </div>
               </a>
            </div>
         <?php endforeach; ?>
      <?php endif; ?>
   </div>
</div>

<?php unset($_SESSION['erorrs'])?>
<?php $view->inc('footer') ?>
<?php $view->inc('end'); ?>
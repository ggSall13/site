<?php

/**
 * @var Src\Core\View\View $view
 */
?>


<?php $view->inc('start', ['title' => 'Пост']) ?>
<?php $view->inc('header') ?>

<div class="container content">
   <div class="row">
      <div id="carouselExample" class="carousel slide my-5 carousel-post">
         <div class="carousel-inner">
            <?php if (!empty($ad['images'])) : ?>
               <?php foreach ($ad['images'] as $key => $image) : ?>
                  <?php if ($key == 0) : ?>
                     <div class="carousel-item active carousel-img">
                        <img src="<?= $image ?>" class="d-block fixed-size" alt="...">
                     </div>
                  <?php else : ?>
                     <div class="carousel-item carousel-img">
                        <img src="<?= $image ?>" class="d-block fixed-size" alt="...">
                     </div>
                  <?php endif; ?>
               <?php endforeach; ?>
            <?php else : ?>
               <div class="carousel-item active carousel-img">
                  <img src="<?= WWW_URL . '/assets/images/place_holder_image.png' ?>" class="d-block fixed-size" alt="...">
               </div>
            <?php endif; ?>
         </div>
         <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon test-button" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
         </button>
         <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon test-button" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
         </button>
      </div>
      <div class="card card-info" style="width: 32rem;height: 450px;text-align:center;">
         <div class="card-body">
            <h5 class="card-title"><?= h($ad['adInfo']['title']) ?></h5>
            <h6 class="card-subtitle mb-2 text-body-secondary">
               Дата публикации:
               <?= date('m.d.Y H:s', strtotime($ad['adInfo']['createdAt'])) ?>
            </h6>
            <hr>
            <p class="card-text"><a href="/users/<?= $ad['user']['userSlug'] ?>"><?= $ad['user']['name'] ?></a></p>
            <p class="card-text">
               <button class="btn btn-success" id="showPhone" onclick="replaceButtonWithText('<?= h(base64_encode($ad['user']['phone'])) ?>')">
                  Показать телефон
               </button>
            </p>
            <p class="card-text price">
               Цена: <?= $ad['adInfo']['price'] . ' ₽' ?>
            </p>
         </div>
      </div>
   </div>
   <hr>
   <div class="description col-md-7">
      <h4>Описание:</h4>
      <p><?= $ad['adInfo']['description'] ?></p>
   </div>
</div>

<script>
   function replaceButtonWithText(text) {
      var button = document.getElementById('showPhone');
      text = atob(text);
      button.outerHTML = "<button class='btn btn-success'>" + text + "</button>";
   }
</script>


<?php $view->inc('footer') ?>
<?php $view->inc('end') ?>
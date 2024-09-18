<?php

/**
 * @var Src\Core\View\View $view
 */

?>

<?php $view->inc('start') ?>
<?php $view->inc('header') ?>

<main class="main">
   <div class="container py-5 content">
      <form action="/ads/new" method="post" enctype="multipart/form-data">
         <div class="mb-3 error">
         </div>
         <div class="mb-3">
            <input type="hidden" name="userId" value="<?= $_SESSION['user']['id'] ?? $cookie->id?>">
            <label for="formFileMultiple" class="form-label">Изображения</label>
            <input class="form-control" name="images[]" type="file" id="formFileMultiple" multiple>

            <?php if (isset($_SESSION['errors']['image'])) : ?>
               <ul>
                  <?php foreach ($_SESSION['errors']['image'] as $val) : ?>
                     <li><?= $val; ?></li>
                  <?php endforeach; ?>
               </ul>
            <?php endif; ?>

         </div>
         <div class="mb-3">
            <label for="title" class="form-label">Название объявления</label>
            <input class="form-control" name="title" required type="text" placeholder="Название" id="title" multiple>
         </div>
         <?php if (isset($_SESSION['errors']['title'])) : ?>
            <div class="error"><?= $_SESSION['errors']['title'] ?></div>
         <?php endif; ?>
         <div class="mb-3">
            <p>Выбор категории:</p>
            <select class="form-select" name="categoryId" aria-label="Default select example">
               <?php foreach ($categories as $array) : ?>
                  <option value="<?= $array['id'] ?>"><?= $array['categoryName'] ?></option>
               <?php endforeach; ?>
            </select>
         </div>
         <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Описание</label>
            <textarea class="form-control" placeholder="описание" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>
         </div>
         <button type="submit" class="btn btn-primary">Готово</button>
      </form>
   </div>
</main>

<?php unset($_SESSION['errors'], $_SESSION['inputs']) ?>
<?php $view->inc('footer') ?>
<?php $view->inc('end') ?>
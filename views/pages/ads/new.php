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
            <?php if (isset($_SESSION['errors'])) : ?>
               <p><?= $_SESSION['errors'] ?></p>
            <?php endif; ?>
         </div>
         <div class="mb-3">
            <label for="formFileMultiple" class="form-label">Изображения</label>
            <input class="form-control" name="images[]" type="file" id="formFileMultiple" multiple>
         </div>
         <div class="mb-3">
            <p>Выбор категории:</p>
            <select class="form-select" name="category" aria-label="Default select example">
               <option selected value="1">Компьютеры</option>
               <option value="2">Мебель</option>
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
<?php

/**
 * @var Src\Core\View\View $view
 */
?>

<?php $view->inc('start', ['title' => 'Создать']) ?>
<?php $view->inc('header') ?>

<main class="main">
   <div class="container py-5 content">
      <form action="/ads/new" method="post" enctype="multipart/form-data">
         <div class="mb-3 error">
         </div>
         <div class="mb-3">
            <input type="hidden" name="userId" value="<?= $_SESSION['user']['id'] ?? $cookie->id ?>">
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
            <input class="form-control" name="title" required type="text" value="<?= getInput('title') ?>" placeholder="Название" id="title" multiple>
         </div>
         <?php if (isset($_SESSION['errors']['title'])) : ?>
            <div class="error"><?= $_SESSION['errors']['title'] ?></div>
         <?php endif; ?>
         <div class="mb-3">
            <label for="price" class="form-label">Цена (В рублях)</label>
            <input class="form-control" name="price" required type="number" placeholder="Цена" value="<?= getInput('price') ?>" id="price" multiple>
         </div>
         <?php if (isset($_SESSION['errors']['price'])) : ?>
            <div class="error"><?= $_SESSION['errors']['price'] ?></div>
         <?php endif; ?>
         <div class="mb-3">
            <p>Выбор категории:</p>
            <select class="form-select" name="categorySlug" aria-label="Default select example">
               <?php foreach ($categories as $array) : ?>
                  <option value="<?= "{$array['subCategorySlug']}/{$array['parentCategoryId']}" ?>"><?= $array['subCategoryName'] ?></option>
               <?php endforeach; ?>
            </select>
         </div>
         <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Описание</label>
            <textarea class="form-control" placeholder="описание" name="description" id="exampleFormControlTextarea1" rows="3"><?= getInput('description') ?? '' ?></textarea>
            <?php if (isset($_SESSION['errors']['description'])) : ?>
            <div class="error"><?= $_SESSION['errors']['description'] ?></div>
         <?php endif; ?>
         </div>
         <button type="submit" class="btn btn-primary">Готово</button>
      </form>
   </div>
</main>

<?php unset($_SESSION['errors'], $_SESSION['inputs']) ?>
<?php $view->inc('footer') ?>
<?php $view->inc('end') ?>

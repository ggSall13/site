<?php

/**
 * @var Src\Core\View\View $view
 */
?>

<?php $view->inc('start', ['title' => 'Создать']) ?>
<?php $view->inc('header') ?>

<main class="main">
   <div class="container py-5 content">
      <form action="/ads/update" method="post" enctype="multipart/form-data">
         <input type="hidden" name="id" value="<?= $ad['adInfo']['id']?>">
         <div class="mb-3 error">
         </div>
         <div class="mb-3">
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
            <?php if (!empty($ad['images'])) : ?>
               <div class="image-row">
                  <?php foreach ($ad['images'] as $index => $image) : ?>
                     <div class="image-container">
                        <img src="<?= $image['urlPath'] ?>" style="width:200px;">
                        <div class="form-check py-3">
                           <input class="form-check-input" name="imageName[]" type="checkbox" value="<?= $image['id']?>" id="flexCheckDefault<?= $index?>">
                           <label class="form-check-label" for="flexCheckDefault<?= $index?>">
                              Удалить фото?
                           </label>
                        </div>
                     </div>
                  <?php endforeach; ?>
               </div>
            <?php endif; ?>
         </div>
         <div class="mb-3">
            <label for="title" class="form-label">Название объявления</label>
            <input class="form-control" name="title" required type="text" value="<?= getInput('title') ?? h($ad['adInfo']['title']) ?>" placeholder="Название" id="title" multiple>
         </div>
         <?php if (isset($_SESSION['errors']['title'])) : ?>
            <div class="error"><?= $_SESSION['errors']['title'] ?></div>
         <?php endif; ?>
         <div class="mb-3">
            <label for="price" class="form-label">Цена (В рублях)</label>
            <input class="form-control" name="price" required type="number" placeholder="Цена" value="<?= getInput('price') ?? h($ad['adInfo']['price']) ?>" id="price" multiple>
         </div>
         <?php if (isset($_SESSION['errors']['price'])) : ?>
            <div class="error"><?= $_SESSION['errors']['price'] ?></div>
         <?php endif; ?>
         <div class="mb-3">
            <p>Выбор категории:</p>
            <select class="form-select" name="categoryId" aria-label="Default select example">
               <?php foreach ($categories as $category) : ?>
                  <?php if ($category['id'] == $ad['adInfo']['categoryId']) : ?>
                     <option selected value="<?= $category['id'] ?>"><?= $category['categoryName'] ?></option>
                  <?php else : ?>
                     <option value="<?= $category['id'] ?>"><?= $category['categoryName'] ?></option>
                  <?php endif; ?>
               <?php endforeach; ?>
            </select>
         </div>
         <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Описание</label>
            <textarea class="form-control" placeholder="описание" name="description" id="exampleFormControlTextarea1" rows="3"><?= getInput('description') ?? h($ad['adInfo']['description']) ?></textarea>
         </div>
         <button type="submit" class="btn btn-primary">Готово</button>
      </form>
   </div>
</main>

<?php unset($_SESSION['errors'], $_SESSION['inputs']) ?>
<?php $view->inc('footer') ?>
<?php $view->inc('end') ?>
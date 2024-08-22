<?php

/**
 * @var Src\Core\View\View $view
 */

?>

<?php $view->inc('start') ?>
<?php $view->inc('header') ?>

<main class="main">
   <div class="container py-5 content">
      <form method="post" action="/register">
         <div class="mb-3">
            <div class="form-floating">
               <input type="email" class="form-control" name="email" value="<?= $_SESSION['inputs']['email'] ?? '' ?>" id="exampleInputEmail1" aria-describedby="emailHelp">
               <label for="exampleInputEmail1" class="form-label">Почта</label>
               <?php if (isset($_SESSION['errors']['email'])) : ?>
                  <div class="invalid">
                     <?= $_SESSION['errors']['email'] ?>
                  </div>
               <?php endif ?>
            </div>
         </div>
         <div class="mb-3">
            <div class="form-floating">
               <input type="text" class="form-control" required id="name" name="name" value="<?= $_SESSION['inputs']['name'] ?? '' ?>" aria-describedby="emailHelp">
               <label for="name" class="form-label">Имя</label>
               <?php if (isset($_SESSION['errors']['name'])) : ?>
                  <div class="invalid">
                     <?= $_SESSION['errors']['name'] ?>
                  </div>
               <?php endif ?>
            </div>
         </div>
         <div class="mb-3">
            <div class="form-floating">
               <input type="number" maxlength="10" class="form-control" required id="phone" name="phone" value="<?= $_SESSION['inputs']['phone'] ?? '' ?>" aria-describedby="emailHelp">
               <label for="phone" class="form-label">Номер телефона</label>
               <?php if (isset($_SESSION['errors']['phone'])) : ?>
                  <div class="invalid">
                     <?= $_SESSION['errors']['phone'] ?>
                  </div>
               <?php endif ?>
            </div>
         </div>
         <div class="mb-3">
            <div class="form-floating">
               <input type="password" required name="password" class="form-control" id="exampleInputPassword1">
               <label for="exampleInputPassword1" class="form-label">Пароль</label>
               <?php if (isset($_SESSION['errors']['password'])) : ?>
                  <div class="invalid">
                     <?= $_SESSION['errors']['password'] ?>
                  </div>
               <?php endif ?>
            </div>
         </div>
         <div class="mb-3">
            <div class="form-floating">
               <input type="password" required name="passwordConfirm" class="form-control" id="passowrd_confirm">
               <label for="passowrd_confirm" class="form-label">Подтверждение пароля</label>
               <?php if (isset($_SESSION['errors']['passwordConfirm'])) : ?>
                  <div class="invalid">
                     <?= $_SESSION['errors']['passwordConfirm'] ?>
                  </div>
               <?php endif ?>
            </div>
         </div>
         <button type="submit" class="btn btn-success">Регистрация</button>
      </form>
   </div>
</main>
<?php 
// очистка сессии чтобы при обновлении страницы ошибки и ввод исчезал
unset($_SESSION['errors'], $_SESSION['inputs']);

?>

<?php $view->inc('footer') ?>
<?php $view->inc('end') ?>
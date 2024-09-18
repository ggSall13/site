<?php

/**
 * @var Src\Core\View\View $view
 * @var Src\Core\Auth\Auth $auth
 */

$cookie = $auth->cookie();


?>

<?php $view->inc('start') ?>
<?php $view->inc('header') ?>


<main class="main">
   <div class="container py-5 content">
      <?php if (isset($_SESSION['success']['edit'])) : ?>
         <p> <?= $_SESSION['success']['edit'] ?> </p>
      <?php endif; ?>
      <form method="post" action="/profile/edit">
         <div class="mb-3">
            <?php if (isset($_SESSION['errors']['dberror'])) : ?>
               <div class="error">
                  <?= $_SESSION['errors']['dberror']; ?>
               </div>
            <?php endif; ?>
         </div>
         <input type="hidden" name="id" value="<?= $cookie->id ?? $_SESSION['user']['id'] ?>">
         <div class="mb-3">
            <div class="form-floating">
               <input type="email" class="form-control" name="email" value="<?= getInput('email') ?? $cookie->email ?? $_SESSION['user']['email'] ?>" id="exampleInputEmail1" aria-describedby="emailHelp">
               <label for="exampleInputEmail1" class="form-label">Почта</label>
               <?php if (isset($_SESSION['errors']['email'])) : ?>
                  <div class="error">
                     <?= $_SESSION['errors']['email'] ?>
                  </div>
               <?php endif ?>
            </div>
         </div>
         <div class="mb-3">
            <div class="form-floating">
               <input type="text" class="form-control" required id="name" name="name" value="<?= getInput('name') ?? $cookie->name ?? $_SESSION['user']['name']  ?>" aria-describedby="emailHelp">
               <label for="name" class="form-label">Имя</label>
               <?php if (isset($_SESSION['errors']['name'])) : ?>
                  <div class="error">
                     <?= $_SESSION['errors']['name'] ?>
                  </div>
               <?php endif ?>
            </div>
         </div>
         <div class="mb-3">
            <div class="form-floating">
               <input type="text" maxlength="20" class="form-control" required id="phone" name="phone" value="<?= getInput('phone') ?? $cookie->phone ?? $_SESSION['user']['phone'] ; ?>" aria-describedby="emailHelp">
               <label for="phone" class="form-label">Номер телефона</label>
               <?php if (isset($_SESSION['errors']['phone'])) : ?>
                  <div class="error">
                     <?= $_SESSION['errors']['phone'] ?>
                  </div>
               <?php endif ?>
            </div>
         </div>
         <button type="submit" class="btn btn-success">Редактировать</button>
      </form>
   </div>
</main>
<?php
// очистка сессии чтобы при обновлении страницы ошибки и ввод исчезал
unset($_SESSION['errors'], $_SESSION['inputs'], $_SESSION['success']);

?>

<?php $view->inc('footer') ?>
<?php $view->inc('end') ?>
<?php

/**
 * @var Src\Core\View\View $view
 */

?>

<?php $view->inc('start') ?>
<?php $view->inc('header') ?>

<main class="main">
   <div class="container py-5 content">
      <form action="/login" method="post">
         <div class="mb-3 error">
            <?php if (isset($_SESSION['errors'])) : ?>
               <p><?= $_SESSION['errors']?></p>
            <?php endif; ?>
         </div>
         <div class="mb-3">
            <div class="form-floating">
               <input type="email" required class="form-control" name="email" value="<?= isset($_SESSION['inputs']) ?$_SESSION['inputs'] : '' ?>" id="exampleInputEmail1" aria-describedby="emailHelp">
               <label for="exampleInputEmail1" class="form-label">Email address</label>
            </div>
         </div>
         <div class="mb-3">
            <div class="form-floating">
               <input type="password" required class="form-control" name="password" id="exampleInputPassword1">
               <label for="exampleInputPassword1" class="form-label">Password</label>
            </div>
         </div>
         <div class="mb-3 form-check">
            <input type="checkbox" name="checkMe" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Запомнить меня</label>
         </div>
         <button type="submit" class="btn btn-primary">Submit</button>
      </form>
   </div>
</main>

<?php unset($_SESSION['errors'], $_SESSION['inputs'])?>
<?php $view->inc('footer') ?>
<?php $view->inc('end') ?>
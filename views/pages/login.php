<?php

/**
 * @var Src\Core\View\View $view
 */

?>

<?php $view->inc('start') ?>
<?php $view->inc('header') ?>

<main class="main">
   <div class="container py-5 content">
      <form>
         <div class="mb-3">
            <div class="form-floating">
               <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
               <label for="exampleInputEmail1" class="form-label">Email address</label>
            </div>
         </div>
         <div class="mb-3">
            <div class="form-floating">
               <input type="password" class="form-control" id="exampleInputPassword1">
               <label for="exampleInputPassword1" class="form-label">Password</label>
            </div>
         </div>
         <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
         </div>
         <button type="submit" class="btn btn-primary">Submit</button>
      </form>
   </div>
</main>

<?php $view->inc('footer') ?>
<?php $view->inc('end') ?>
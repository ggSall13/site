   <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container">
         <a class="navbar-brand" href="/">Site</a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
               <?php if (isset($_SESSION['user']) || isset($_COOKIE['user'])) : ?>
                  <li class="nav-item">
                     <a class="nav-link" href="/profile">Профиль</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="/logout">Выйти</a>
                  </li>
               <?php else : ?>
                  <li class="nav-item">
                     <a class="nav-link" href="/register">Регистрация</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="/login">Войти в аккаунт</a>
                  </li>
               <?php endif ?>
            </ul>
            <form class="d-flex" role="search">
               <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
               <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
         </div>
      </div>
   </nav>
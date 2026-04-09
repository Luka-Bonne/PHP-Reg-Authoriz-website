<header class="header">
    <div class="header__container">
        <img src="./img/logo.svg" alt="логотип" class="header__logo">

        <nav class="header__nav">
            <ul class="header__nav-list">
                <li class="header__nav-item">
                    <a href="index.php" class="header__nav-link">Главная</a>
                </li>
                <li class="header__nav-item">
                    <a href="info.php" class="header__nav-link">Информация</a>
                </li>
                <?php 
                if (isset($_COOKIE['auth'])) {
                    echo '<li class="header__nav-item">
                              <a href="profile.php" class="header__nav-link">Профиль</a>
                          </li>';
                }
                ?>
            </ul>
        </nav>

        <?php 
        if (isset($_COOKIE['auth'])) {
            echo '<div class="header__btns">
                        <button class="header__btn header__btn_registration" onclick="window.location.href=\'lib/logout.php\'">Выйти</button>
                  </div>';
        }
        else {
            echo '<div class="header__btns">
                        <button class="header__btn header__btn_login" onclick="window.location.href=\'authorization.php\'">Войти</button>
                        <button class="header__btn header__btn_registration" onclick="window.location.href=\'registration.php\'">Регистрация</button>
                  </div>';
        }
        ?>

        <button class="header__burger" aria-label="Меню">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
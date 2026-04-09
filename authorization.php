<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js" defer></script>

    <link rel="stylesheet" href="./css/style.css">

    <title>Тестовое Абу Саиф</title>
</head>
<body> 
    <div class="wrapper">
        <?php require_once "blocks/header.php"; ?>

        <main class="main">
            <div class="profile-form">
                <h1 class="profile-form__title">Авторизация</h1>
                <p class="profile-form__subtitle">Войдите в свой аккаунт</p>

                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="profile-form__success" id="successMessage">
                        <?= $_SESSION['success_message'] ?>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <form id="loginForm" method="post" action="./lib/authoriz_process.php">
                    <div class="form-group">
                        <label class="form-group__label" for="login">Email / Телефон</label>
                        <input class="form-group__input" id="login" name="login" placeholder="example@gmail.com или +79876543210" 
                        value="<?= htmlspecialchars($_SESSION['old_data']['login'] ?? '') ?>" required autocomplete="email">
                        <?php if (isset($_SESSION['form_errors']['login'])): ?>
                            <div class="form-group__error">
                                <?= $_SESSION['form_errors']['login'] ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-group__label" for="password">Пароль</label>
                        <input class="password-field__input" type="password" id="password" name="password" placeholder="Введите пароль" value="" required autocomplete="current-password">
                        <?php if (isset($_SESSION['form_errors']['password'])): ?>
                            <div class="form-group__error">
                                <?= $_SESSION['form_errors']['password'] ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="g-recaptcha" data-sitekey="МОЙ-КЛЮЧ-КАПЧИ"></div>

                    <div class="profile-form__actions">
                        <button type="submit" class="btn btn--save">Войти</button>
                    </div>
                </form>
            </div>
        </main>

        <?php require_once "blocks/footer.php"; ?>
    </div>
    <?php 
    if (isset($_SESSION['form_errors']) || isset($_SESSION['old_data'])) {
        unset($_SESSION['form_errors']);
        unset($_SESSION['old_data']);
    }
    ?>
</body>
</html>
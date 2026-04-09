<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/style.css">

    <title>Тестовое Абу Саиф</title>
</head>
<body>
    <div class="wrapper">
        <?php require_once "blocks/header.php"; ?>

        <main class="main">
            <div class="profile-form">
                <h1 class="profile-form__title">Редактирование профиля</h1>
                <p class="profile-form__subtitle">Измените свои данные</p>

                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="profile-form__success" id="successMessage">
                        <?= $_SESSION['success_message'] ?>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <form id="profileForm" method="post" action="./lib/profile_process.php">
                    <div class="form-group">
                        <label class="form-group__label" for="name">Имя</label>
                        <input class="form-group__input" type="text" id="name" name="name" placeholder="Введите ваше имя" value="<?php require_once "lib/get_name.php"; ?>">
                        <?php if (isset($_SESSION['form_errors']['name'])): ?>
                            <div class="form-group__error">
                                <?= $_SESSION['form_errors']['name'] ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-group__label" for="phone">Телефон</label>
                        <input class="form-group__input" type="tel" id="phone" name="phone" placeholder="+7 (___)-___-__-__" value="<?php require_once "lib/get_phone.php"; ?>">
                        <?php if (isset($_SESSION['form_errors']['phone'])): ?>
                            <div class="form-group__error">
                                <?= $_SESSION['form_errors']['phone'] ?>
                            </div>
                        <?php elseif (isset($_SESSION['form_errors']['existing_phone'])): ?>
                            <div class="form-group__error">
                                <?= $_SESSION['form_errors']['existing_phone'] ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-group__label" for="email">Почта</label>
                        <input class="form-group__input" type="email" id="email" name="email" placeholder="example@gmail.com" value="<?php require_once "lib/get_email.php"; ?>">
                        <?php if (isset($_SESSION['form_errors']['email'])): ?>
                            <div class="form-group__error">
                                <?= $_SESSION['form_errors']['email'] ?>
                            </div>
                        <?php elseif (isset($_SESSION['form_errors']['existing_email'])): ?>
                            <div class="form-group__error">
                                <?= $_SESSION['form_errors']['existing_email'] ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-group__label" for="password">Пароль</label>
                        <input class="password-field__input" type="password" id="password" name="password" placeholder="Введите новый пароль" value="">
                        <?php if (isset($_SESSION['form_errors']['password'])): ?>
                            <div class="form-group__error">
                                <?= $_SESSION['form_errors']['password'] ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="profile-form__actions">
                        <button type="submit" class="btn btn--save">Сохранить</button>
                        <button type="button" class="btn btn--cancel" onclick="window.location.href='index.php'">Отмена</button>
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
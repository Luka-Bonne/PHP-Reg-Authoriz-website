<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $username = trim(filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS));
    $telephone = trim(filter_var($_POST['phone'], FILTER_SANITIZE_SPECIAL_CHARS));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS));
    $confirm_password = trim(filter_var($_POST['confirm_password'], FILTER_SANITIZE_SPECIAL_CHARS));

    // Проверка полученных данных
    if (strlen($username) < 2) {
        $errors['name'] = 'Имя пользователя должно содержать минимум 2 символа';
    }
    if (!(filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE))) {
        $errors['email'] = 'Неправильно введён email';
    }
    if (!preg_match('/^\+7\d{10}$/', $telephone)) {
        $errors['phone'] = 'Номер телефона должен быть в формате +79876543210';
    }
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
        $errors['password'] = 'Пароль должен содержать минимум 8 символов, буквы разного регистра и цифры';
    }
    if ($confirm_password != $password) {
        $errors['confirm_password'] = 'Пароли не совпадают';
    }
    

    // DB
    require 'db.php';

    // Проверка на существования пользователя с таким же телефоном/почтой
    $sql_check_email = 'SELECT id FROM users WHERE email = ?';
    $sql_check_phone = 'SELECT id FROM users WHERE telephone = ?';

    $query_check_email = $pdo->prepare($sql_check_email);
    $query_check_email->execute([$email]);
    if ($query_check_email->rowCount() != 0) {
        $errors['existing_email'] = 'Пользователь с такой почтой уже существует';
    }

    $query_check_phone = $pdo->prepare($sql_check_phone);
    $query_check_phone->execute([$telephone]);
    if ($query_check_phone->rowCount() != 0) {
        $errors['existing_phone'] = 'Пользователь с таким номером телефона уже существует';
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['old_data'] = $_POST; 
        header('Location: ../registration.php');
        exit;
    }

    // Хэширование пароля
    $salt = 'UYTUI3793q/.,(_%#@zxc9sa69hHjkb223}L{><^$^#%';
    $password = md5($salt . $password);

    // Добавление пользователя в БД
    $sql = 'INSERT INTO  users(username, email, telephone, password) VALUES(?, ?, ?, ?)';
    $query = $pdo->prepare($sql);
    $query->execute([$username, $email, $telephone, $password]);

    $_SESSION['success_message'] = 'Регистрация успешно завершена';
    header('Location: ../authorization.php');
    exit;
}
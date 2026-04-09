<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // DB
    require 'db.php';

    // Нахождение нужного нам пользователя в БД
    $login = $_COOKIE['auth'];
    $login_email = false;
    if (filter_var($login, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE)) {
        $login_email = true;
    }

    $sql = '';
    if ($login_email) {
        $sql = 'SELECT email, telephone FROM users WHERE email = ?';
    }
    else {
        $sql = 'SELECT email, telephone FROM users WHERE telephone = ?';
    }

    $query = $pdo->prepare($sql);
    $query->execute([$login]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    // нашли нужный id
    $user_email = $result['email'];
    $user_telephone = $result['telephone'];


    $errors = [];

    $new_username = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $new_telephone = trim(filter_var($_POST['phone'], FILTER_SANITIZE_STRING));
    $new_email = trim(filter_var($_POST['email'], FILTER_SANITIZE_STRING));
    $new_password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));

    // Проверка полученных данных
    if (strlen($new_username) < 2) {
        $errors['name'] = 'Имя пользователя должно содержать минимум 2 символа';
    }
    if (!(filter_var($new_email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE))) {
        $errors['email'] = 'Неправильно введён email';
    }
    if (!preg_match('/^\+7\d{10}$/', $new_telephone)) {
        $errors['phone'] = 'Номер телефона должен быть в формате +79876543210';
    }
    if (strlen($new_password) < 8 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[a-z]/', $new_password) || !preg_match('/\d/', $new_password)) {
        $errors['password'] = 'Пароль должен содержать минимум 8 символов, буквы разного регистра и цифры';
    }


    // Проверка на существования пользователя с таким же телефоном/почтой
    $sql_check_email = 'SELECT id FROM users WHERE email = ?';
    $sql_check_phone = 'SELECT id FROM users WHERE telephone = ?';

    if ($new_email != $user_email) {
        $query_check_email = $pdo->prepare($sql_check_email);
        $query_check_email->execute([$new_email]);
        if ($query_check_email->rowCount() != 0) {
            $errors['existing_email'] = 'Пользователь с такой почтой уже существует';
        }
    }

    if ($new_telephone != $user_telephone) {
        $query_check_phone = $pdo->prepare($sql_check_phone);
        $query_check_phone->execute([$new_telephone]);
        if ($query_check_phone->rowCount() != 0) {
            $errors['existing_phone'] = 'Пользователь с таким номером телефона уже существует';
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['old_data'] = $_POST; 
        header('Location: ../profile.php');
        exit;
    }


    // Хэширование пароля
    $salt = 'UYTUI3793q/.,(_%#@zxc9sa69hHjkb223}L{><^$^#%';
    $new_password = md5($salt . $new_password);

    // Обновляем данные
    $sql2 = 'UPDATE users SET username = ?, email = ?, telephone = ?, password = ? WHERE id = ?';
    $query2 = $pdo->prepare($sql2);
    $query2->execute([$new_username, $new_email, $new_telephone, $new_password, $user_id]);

    header('Location: ../index.php');
}
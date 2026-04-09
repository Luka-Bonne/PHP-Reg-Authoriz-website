<?php
// НАЧАЛО КАПЧИ
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

if (empty($recaptchaResponse)) {
    die("Ошибка: Пожалуйста, подтвердите, что вы не робот.");
}

$secretKey = "МОЙ-КЛЮЧ-КАПЧИ";

$url = "https://www.google.com/recaptcha/api/siteverify";
$data = [
    'secret'   => $secretKey,
    'response' => $recaptchaResponse,
    'remoteip' => $_SERVER['REMOTE_ADDR']
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
$response = curl_exec($ch);

// Проверка ошибок CURL
if (curl_errno($ch)) {
    die("Ошибка CURL: " . curl_error($ch));
}

curl_close($ch);

$result = json_decode($response, true);

if (!$result['success']) {
    $error_codes = isset($result['error-codes']) ? implode(', ', $result['error-codes']) : 'unknown';
    die("Ошибка капчи: $error_codes");
}
// КОНЕЦ КАПЧИ

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $login = trim(filter_var($_POST['login'], FILTER_SANITIZE_STRING));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));

    // Смотрим, что ввели логином (почта или телефон)
    $login_email = true;
    if (filter_var($login, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE)) {
        $login_email = true;
    }
    elseif (preg_match('/^\+7\d{10}$/', $login)) {
        $login_email = false;
    }
    else {
        $errors['login'] = 'Неверный логин';
    }

    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
        $errors['password'] = 'Неверный пароль';
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['old_data'] = $_POST; 
        header('Location: ../authorization.php');
        exit;
    }

    // Хэширование пароля
    $salt = 'UYTUI3793q/.,(_%#@zxc9sa69hHjkb223}L{><^$^#%';
    $password = md5($salt . $password);

    // DB
    require 'db.php';

    // Ищем пользователя по логину и паролю в БД
    $sql = '';
    if ($login_email) {
        $sql = 'SELECT id FROM users WHERE email = ? AND password = ?';
    }
    else {
        $sql = 'SELECT id FROM users WHERE telephone = ? AND password = ?';
    }

    $query = $pdo->prepare($sql);
    $query->execute([$login, $password]);

    // Выводим ошибку или авторизируемся
    if ($query->rowCount() == 0) {
        $errors['login'] = 'Такого пользователя не существует';
        $errors['password'] = 'Такого пользователя не существует';
        $_SESSION['form_errors'] = $errors;
        $_SESSION['old_data'] = $_POST; 
        header('Location: ../authorization.php');
        exit;
    }
    else {
        setcookie('auth', $login, time() + 3600 * 24 * 30,'/');
        header('Location: ../profile.php');
    }
}
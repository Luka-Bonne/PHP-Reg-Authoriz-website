<?php 
$login = $_COOKIE['auth'];
$login_email = false;
if (filter_var($login, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE)) {
    $login_email = true;
}

// DB

require 'db.php';

$sql = '';
if ($login_email) {
    $sql = 'SELECT email FROM users WHERE email = ?';
}
else {
    $sql = 'SELECT email FROM users WHERE telephone = ?';
}

$query = $pdo->prepare($sql);
$query->execute([$login]);
$result = $query->fetch(PDO::FETCH_ASSOC);

echo $result['email'];

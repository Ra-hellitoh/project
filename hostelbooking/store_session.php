<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['room'] = [
        'id' => $_POST['room_id'],
        'name' => $_POST['room_name'],
        'price' => $_POST['room_price']
    ];

    header("Location: pay_now.php");
    exit;
}
?>

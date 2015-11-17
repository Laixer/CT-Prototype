<?php
session_start();

$user_id = $_SESSION['SES_User_id'];
$user_name = $_SESSION['SES_User_name'];

$_SESSION['SES_User_id'] == NULL;
session_destroy();
//unset($_COOKIE['_UTMUser_id']);
//setcookie('_UTMUser_id', null, -1, '/');

header("Location: /");
exit();
?>

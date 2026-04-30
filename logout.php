<?php
require_once __DIR__ . '/config/helpers.php';

unset($_SESSION['user_logged_in'], $_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email']);
header('Location: /index.php');
exit;
?>

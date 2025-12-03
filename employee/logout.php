<?php
require_once __DIR__ . '/../includes/auth.php';

$auth = new Auth();
$auth->logout();

header('Location: login.php?message=You have been logged out successfully');
exit();
?>

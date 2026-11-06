<?php
require_once 'includes/functions.php';
unset($_SESSION['current_user']);
header('Location: login.php');
exit;

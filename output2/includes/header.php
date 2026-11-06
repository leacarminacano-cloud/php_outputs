<?php
require_once __DIR__ . '/functions.php';
$current = basename($_SERVER['SCRIPT_NAME']);
$pageTitle = $pageTitle ?? 'PHP Output #2';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> | PHP Output #2</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header class="site">
  <h1>PHP Output #2</h1>
  <?php include __DIR__ . '/nav.php'; ?>
</header>
<main>

<?php
$pageTitle = 'Home';
require 'includes/header.php';
?>
<div class="card hero">
<?php if (!empty($_SESSION['current_user'])): ?>
  <h2>Welcome, <?= e($_SESSION['current_user']['first_name']) ?>!</h2>
  <p class="muted">You are logged in as <?= e($_SESSION['current_user']['email']) ?>.</p>
  <div class="actions" style="justify-content:center"><a class="btn" href="logout.php">Logout</a></div>
<?php else: ?>
  <h2>Welcome to our Website</h2>
  <p class="muted">Create an account or log in to continue.</p>
  <div class="actions" style="justify-content:center">
    <a class="btn" href="register.php">Register</a>
    <a class="btn secondary" href="login.php">Login</a>
  </div>
<?php endif; ?>
</div>
<?php require 'includes/footer.php'; ?>

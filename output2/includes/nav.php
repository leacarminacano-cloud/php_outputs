<nav>
  <a href="index.php"           class="<?= $current === 'index.php' ? 'active' : '' ?>">Home</a>
  <?php if (!empty($_SESSION['current_user'])): ?>
    <a href="logout.php">Logout (<?= e($_SESSION['current_user']['first_name']) ?>)</a>
  <?php else: ?>
    <a href="register.php"        class="<?= $current === 'register.php' ? 'active' : '' ?>">Register</a>
    <a href="login.php"           class="<?= $current === 'login.php' ? 'active' : '' ?>">Login</a>
    <a href="forgot_password.php" class="<?= $current === 'forgot_password.php' ? 'active' : '' ?>">Forgot Password</a>
  <?php endif; ?>
</nav>

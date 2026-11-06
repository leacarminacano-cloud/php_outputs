<?php
require_once 'includes/functions.php';
$pageTitle = 'Login';
$error = '';
$email = '';
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $user = find_user($email);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['current_user'] = ['first_name'=>$user['first_name'], 'email'=>$user['email']];
        header('Location: index.php');
        exit;
    }
    $error = 'Invalid email or password.';
}
require 'includes/header.php';
?>
<div class="card" style="max-width:420px;margin:auto">
  <h2>Login</h2>
  <?php if ($flash): ?><div class="alert ok"><?= e($flash) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert bad"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <div style="margin-bottom:12px">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?= e($email) ?>" required>
    </div>
    <div>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>
    </div>
    <div class="actions"><button class="btn" type="submit">Login</button></div>
    <p class="muted"><a href="forgot_password.php">Forgot password?</a> &middot; <a href="register.php">Create account</a></p>
  </form>
</div>
<?php require 'includes/footer.php'; ?>

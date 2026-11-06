<?php
require_once 'includes/functions.php';
$pageTitle = 'Forgot Password';
$msg = '';
$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Same message whether or not the account exists, so emails can't be probed.
        $msg = 'If that email is registered, a password reset link has been sent.';
    }
}
require 'includes/header.php';
?>
<div class="card" style="max-width:420px;margin:auto">
  <h2>Forgot Password</h2>
  <p class="muted">Enter your email and we'll send you a reset link.</p>
  <?php if ($msg): ?><div class="alert ok"><?= e($msg) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert bad"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= e($email) ?>" required>
    <div class="actions">
      <button class="btn" type="submit">Send Reset Link</button>
      <a class="btn secondary" href="login.php">Back to Login</a>
    </div>
  </form>
</div>
<?php require 'includes/footer.php'; ?>

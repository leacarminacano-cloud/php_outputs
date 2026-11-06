<?php
require_once 'includes/functions.php';
$pageTitle = 'Register';
$f = ['first_name'=>'','last_name'=>'','email'=>''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($f as $k => $_) { $f[$k] = trim($_POST[$k] ?? ''); }
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($f['first_name'] === '') $errors['first_name'] = 'First name is required.';
    if ($f['last_name'] === '')  $errors['last_name']  = 'Last name is required.';
    if (!filter_var($f['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email address.';
    elseif (find_user($f['email'])) $errors['email'] = 'This email is already registered.';
    if (strlen($password) < 8) $errors['password'] = 'Password must be at least 8 characters.';
    if ($password !== $confirm) $errors['confirm_password'] = 'Passwords do not match.';

    if (!$errors) {
        $u = users();
        $u[] = ['first_name'=>$f['first_name'], 'last_name'=>$f['last_name'], 'email'=>$f['email'],
                'password'=>password_hash($password, PASSWORD_DEFAULT)];
        save_users($u);
        $_SESSION['flash'] = 'Registration successful! You can now log in.';
        header('Location: login.php');
        exit;
    }
}
require 'includes/header.php';
?>
<div class="card" style="max-width:520px;margin:auto">
  <h2>Create an Account</h2>
  <?php if ($errors): ?><div class="alert bad">Please fix the errors below.</div><?php endif; ?>
  <form method="post" novalidate>
    <div class="grid">
      <div class="<?= isset($errors['first_name']) ? 'has-error' : '' ?>">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" value="<?= e($f['first_name']) ?>">
        <?php if (isset($errors['first_name'])): ?><span class="err"><?= e($errors['first_name']) ?></span><?php endif; ?>
      </div>
      <div class="<?= isset($errors['last_name']) ? 'has-error' : '' ?>">
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" value="<?= e($f['last_name']) ?>">
        <?php if (isset($errors['last_name'])): ?><span class="err"><?= e($errors['last_name']) ?></span><?php endif; ?>
      </div>
      <div class="full <?= isset($errors['email']) ? 'has-error' : '' ?>">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= e($f['email']) ?>">
        <?php if (isset($errors['email'])): ?><span class="err"><?= e($errors['email']) ?></span><?php endif; ?>
      </div>
      <div class="<?= isset($errors['password']) ? 'has-error' : '' ?>">
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        <?php if (isset($errors['password'])): ?><span class="err"><?= e($errors['password']) ?></span><?php endif; ?>
      </div>
      <div class="<?= isset($errors['confirm_password']) ? 'has-error' : '' ?>">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password">
        <?php if (isset($errors['confirm_password'])): ?><span class="err"><?= e($errors['confirm_password']) ?></span><?php endif; ?>
      </div>
    </div>
    <div class="actions"><button class="btn" type="submit">Register</button></div>
    <p class="muted">Already have an account? <a href="login.php">Login</a></p>
  </form>
</div>
<?php require 'includes/footer.php'; ?>

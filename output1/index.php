<?php
// PHP Output #1 - Registration form with extra fields + validation (no database)
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

$fields = ['first_name'=>'','middle_name'=>'','last_name'=>'','age'=>'','gender'=>'','email'=>'','address'=>'','contact'=>''];
$errors = [];
$submitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $k => $_) { $fields[$k] = trim($_POST[$k] ?? ''); }

    if ($fields['first_name'] === '' || !preg_match("/^[A-Za-z\s.'-]{2,50}$/", $fields['first_name']))
        $errors['first_name'] = 'First name is required (letters only, 2-50 characters).';
    if ($fields['middle_name'] !== '' && !preg_match("/^[A-Za-z\s.'-]{1,50}$/", $fields['middle_name']))
        $errors['middle_name'] = 'Middle name may contain letters only.';
    if ($fields['last_name'] === '' || !preg_match("/^[A-Za-z\s.'-]{2,50}$/", $fields['last_name']))
        $errors['last_name'] = 'Last name is required (letters only, 2-50 characters).';
    if (!ctype_digit($fields['age']) || (int)$fields['age'] < 1 || (int)$fields['age'] > 120)
        $errors['age'] = 'Age must be a whole number from 1 to 120.';
    if (!in_array($fields['gender'], ['Male','Female','Other'], true))
        $errors['gender'] = 'Please select a gender.';
    if (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Please enter a valid email address.';
    if (strlen($fields['address']) < 5)
        $errors['address'] = 'Address is required (at least 5 characters).';
    if (!preg_match('/^(09|\+639)\d{9}$/', $fields['contact']))
        $errors['contact'] = 'Enter a valid PH mobile number (e.g. 09123456789 or +639123456789).';

    $submitted = empty($errors);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PHP Output #1</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header class="site"><h1>PHP Output #1</h1></header>
<main>
<?php if ($submitted): ?>
  <div class="card">
    <h2>Submitted Information</h2>
    <div class="alert ok">Form submitted successfully!</div>
    <table class="data">
      <tr><th>First Name</th><td><?= e($fields['first_name']) ?></td></tr>
      <tr><th>Middle Name</th><td><?= e($fields['middle_name'] ?: '-') ?></td></tr>
      <tr><th>Last Name</th><td><?= e($fields['last_name']) ?></td></tr>
      <tr><th>Age</th><td><?= e($fields['age']) ?></td></tr>
      <tr><th>Gender</th><td><?= e($fields['gender']) ?></td></tr>
      <tr><th>Email</th><td><?= e($fields['email']) ?></td></tr>
      <tr><th>Address</th><td><?= nl2br(e($fields['address'])) ?></td></tr>
      <tr><th>Contact Number</th><td><?= e($fields['contact']) ?></td></tr>
    </table>
    <div class="actions"><a class="btn" href="index.php">Back to Form</a></div>
  </div>
<?php else: ?>
  <div class="card">
    <h2>Personal Information Form</h2>
    <?php if ($errors): ?><div class="alert bad">Please fix the errors below.</div><?php endif; ?>
    <form method="post" novalidate>
      <div class="grid">
        <div class="<?= isset($errors['first_name']) ? 'has-error' : '' ?>">
          <label for="first_name">First Name *</label>
          <input type="text" id="first_name" name="first_name" value="<?= e($fields['first_name']) ?>" maxlength="50" required>
          <?php if (isset($errors['first_name'])): ?><span class="err"><?= e($errors['first_name']) ?></span><?php endif; ?>
        </div>
        <div class="<?= isset($errors['middle_name']) ? 'has-error' : '' ?>">
          <label for="middle_name">Middle Name</label>
          <input type="text" id="middle_name" name="middle_name" value="<?= e($fields['middle_name']) ?>" maxlength="50">
          <?php if (isset($errors['middle_name'])): ?><span class="err"><?= e($errors['middle_name']) ?></span><?php endif; ?>
        </div>
        <div class="<?= isset($errors['last_name']) ? 'has-error' : '' ?>">
          <label for="last_name">Last Name *</label>
          <input type="text" id="last_name" name="last_name" value="<?= e($fields['last_name']) ?>" maxlength="50" required>
          <?php if (isset($errors['last_name'])): ?><span class="err"><?= e($errors['last_name']) ?></span><?php endif; ?>
        </div>
        <div class="<?= isset($errors['age']) ? 'has-error' : '' ?>">
          <label for="age">Age *</label>
          <input type="number" id="age" name="age" min="1" max="120" value="<?= e($fields['age']) ?>" required>
          <?php if (isset($errors['age'])): ?><span class="err"><?= e($errors['age']) ?></span><?php endif; ?>
        </div>
        <div class="<?= isset($errors['gender']) ? 'has-error' : '' ?>">
          <label>Gender *</label>
          <div class="radio-group">
            <?php foreach (['Male','Female','Other'] as $g): ?>
              <label><input type="radio" name="gender" value="<?= $g ?>" <?= $fields['gender'] === $g ? 'checked' : '' ?>> <?= $g ?></label>
            <?php endforeach; ?>
          </div>
          <?php if (isset($errors['gender'])): ?><span class="err"><?= e($errors['gender']) ?></span><?php endif; ?>
        </div>
        <div class="<?= isset($errors['email']) ? 'has-error' : '' ?>">
          <label for="email">Email *</label>
          <input type="email" id="email" name="email" value="<?= e($fields['email']) ?>" required>
          <?php if (isset($errors['email'])): ?><span class="err"><?= e($errors['email']) ?></span><?php endif; ?>
        </div>
        <div class="<?= isset($errors['contact']) ? 'has-error' : '' ?>">
          <label for="contact">Contact Number *</label>
          <input type="tel" id="contact" name="contact" placeholder="09123456789" value="<?= e($fields['contact']) ?>" required>
          <?php if (isset($errors['contact'])): ?><span class="err"><?= e($errors['contact']) ?></span><?php endif; ?>
        </div>
        <div class="full <?= isset($errors['address']) ? 'has-error' : '' ?>">
          <label for="address">Address *</label>
          <textarea id="address" name="address" rows="3" required><?= e($fields['address']) ?></textarea>
          <?php if (isset($errors['address'])): ?><span class="err"><?= e($errors['address']) ?></span><?php endif; ?>
        </div>
      </div>
      <div class="actions">
        <button class="btn" type="submit">Submit</button>
        <a class="btn secondary" href="index.php">Reset</a>
      </div>
    </form>
  </div>
<?php endif; ?>
</main>
<footer class="site">PHP Basics - Hands-on Exercises</footer>
</body>
</html>

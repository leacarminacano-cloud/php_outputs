<?php
// PHP Outputs #3 and #4 - Database-connected form + List of Registered Persons
require __DIR__ . '/db.php';
session_start();

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

$blank = ['first_name'=>'','middle_name'=>'','last_name'=>'','age'=>'','gender'=>'','email'=>'','address'=>'','contact'=>''];
$fields = $blank;
$errors = [];
$editId = null;

// ---- Delete ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $stmt = $pdo->prepare('DELETE FROM persons WHERE id = ?');
    $stmt->execute([(int)($_POST['id'] ?? 0)]);
    $_SESSION['flash'] = 'Record deleted.';
    header('Location: index.php'); exit;
}

// ---- Load a record into the form for editing ----
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM persons WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    if ($row = $stmt->fetch()) {
        $editId = (int)$row['id'];
        foreach ($blank as $k => $_) { $fields[$k] = (string)$row[$k]; }
    }
}

// ---- Insert / Update ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save') {
    foreach ($blank as $k => $_) { $fields[$k] = trim($_POST[$k] ?? ''); }
    $editId = (int)($_POST['id'] ?? 0) ?: null;

    if (!preg_match("/^[A-Za-z\s.'-]{2,50}$/", $fields['first_name'])) $errors['first_name'] = 'First name is required (letters only).';
    if ($fields['middle_name'] !== '' && !preg_match("/^[A-Za-z\s.'-]{1,50}$/", $fields['middle_name'])) $errors['middle_name'] = 'Letters only.';
    if (!preg_match("/^[A-Za-z\s.'-]{2,50}$/", $fields['last_name'])) $errors['last_name'] = 'Last name is required (letters only).';
    if (!ctype_digit($fields['age']) || (int)$fields['age'] < 1 || (int)$fields['age'] > 120) $errors['age'] = 'Age must be 1-120.';
    if (!in_array($fields['gender'], ['Male','Female','Other'], true)) $errors['gender'] = 'Select a gender.';
    if (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email.';
    if (strlen($fields['address']) < 5) $errors['address'] = 'Address is required.';
    if (!preg_match('/^(09|\+639)\d{9}$/', $fields['contact'])) $errors['contact'] = 'Enter a valid PH mobile number (09XXXXXXXXX).';

    if (!$errors) {
        $vals = [$fields['first_name'], $fields['middle_name'] ?: null, $fields['last_name'], (int)$fields['age'],
                 $fields['gender'], $fields['email'], $fields['address'], $fields['contact']];
        if ($editId) {
            $sql = 'UPDATE persons SET first_name=?, middle_name=?, last_name=?, age=?, gender=?, email=?, address=?, contact=? WHERE id=?';
            $pdo->prepare($sql)->execute([...$vals, $editId]);
            $_SESSION['flash'] = 'Record updated successfully!';
        } else {
            $sql = 'INSERT INTO persons (first_name, middle_name, last_name, age, gender, email, address, contact) VALUES (?,?,?,?,?,?,?,?)';
            $pdo->prepare($sql)->execute($vals);
            $_SESSION['flash'] = 'Record saved successfully!';
        }
        header('Location: index.php'); exit;   // Post/Redirect/Get
    }
}

$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

// ---- Search + sort for the list ----
$search = trim($_GET['q'] ?? '');
$sortCols = ['id'=>'id','first_name'=>'first_name','last_name'=>'last_name','age'=>'age','created_at'=>'created_at'];
$sort = $sortCols[$_GET['sort'] ?? ''] ?? 'id';
$dir  = (($_GET['dir'] ?? 'asc') === 'desc') ? 'DESC' : 'ASC';

$sql = 'SELECT * FROM persons';
$params = [];
if ($search !== '') {
    $sql .= ' WHERE first_name LIKE ? OR middle_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR address LIKE ?';
    $params = array_fill(0, 5, "%$search%");
}
$sql .= " ORDER BY $sort $dir";   // $sort/$dir come from whitelists above
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$persons = $stmt->fetchAll();

function err($errors, $k) { return isset($errors[$k]) ? '<span class="err">' . e($errors[$k]) . '</span>' : ''; }
function cls($errors, $k) { return isset($errors[$k]) ? 'has-error' : ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PHP Outputs #3 and #4</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header class="site"><h1>PHP Outputs #3 &amp; #4 - Registration with Database</h1></header>
<main>

  <div class="card">
    <h2><?= $editId ? 'Edit Record' : 'Register a Person' ?></h2>
    <?php if ($flash): ?><div class="alert ok"><?= e($flash) ?></div><?php endif; ?>
    <?php if ($errors): ?><div class="alert bad">Please fix the errors below.</div><?php endif; ?>
    <form method="post" novalidate>
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)$editId ?>">
      <div class="grid">
        <div class="<?= cls($errors,'first_name') ?>"><label for="first_name">First Name *</label>
          <input type="text" id="first_name" name="first_name" value="<?= e($fields['first_name']) ?>" maxlength="50" required><?= err($errors,'first_name') ?></div>
        <div class="<?= cls($errors,'middle_name') ?>"><label for="middle_name">Middle Name</label>
          <input type="text" id="middle_name" name="middle_name" value="<?= e($fields['middle_name']) ?>" maxlength="50"><?= err($errors,'middle_name') ?></div>
        <div class="<?= cls($errors,'last_name') ?>"><label for="last_name">Last Name *</label>
          <input type="text" id="last_name" name="last_name" value="<?= e($fields['last_name']) ?>" maxlength="50" required><?= err($errors,'last_name') ?></div>
        <div class="<?= cls($errors,'age') ?>"><label for="age">Age *</label>
          <input type="number" id="age" name="age" min="1" max="120" value="<?= e($fields['age']) ?>" required><?= err($errors,'age') ?></div>
        <div class="<?= cls($errors,'gender') ?>"><label>Gender *</label>
          <div class="radio-group">
            <?php foreach (['Male','Female','Other'] as $g): ?>
              <label><input type="radio" name="gender" value="<?= $g ?>" <?= $fields['gender'] === $g ? 'checked' : '' ?>> <?= $g ?></label>
            <?php endforeach; ?>
          </div><?= err($errors,'gender') ?></div>
        <div class="<?= cls($errors,'email') ?>"><label for="email">Email *</label>
          <input type="email" id="email" name="email" value="<?= e($fields['email']) ?>" required><?= err($errors,'email') ?></div>
        <div class="<?= cls($errors,'contact') ?>"><label for="contact">Contact Number *</label>
          <input type="tel" id="contact" name="contact" placeholder="09123456789" value="<?= e($fields['contact']) ?>" required><?= err($errors,'contact') ?></div>
        <div class="full <?= cls($errors,'address') ?>"><label for="address">Address *</label>
          <textarea id="address" name="address" rows="2" required><?= e($fields['address']) ?></textarea><?= err($errors,'address') ?></div>
      </div>
      <div class="actions">
        <button class="btn" type="submit"><?= $editId ? 'Update' : 'Save Record' ?></button>
        <?php if ($editId): ?><a class="btn secondary" href="index.php">Cancel</a><?php endif; ?>
      </div>
    </form>
  </div>

  <div class="card">
    <h2>List of Registered Persons</h2>
    <form method="get" class="toolbar">
      <input type="text" name="q" placeholder="Search name, email, address..." value="<?= e($search) ?>">
      <select name="sort">
        <option value="id" <?= $sort==='id'?'selected':'' ?>>Sort by: Order added</option>
        <option value="first_name" <?= $sort==='first_name'?'selected':'' ?>>First Name</option>
        <option value="last_name" <?= $sort==='last_name'?'selected':'' ?>>Last Name</option>
        <option value="age" <?= $sort==='age'?'selected':'' ?>>Age</option>
      </select>
      <select name="dir">
        <option value="asc" <?= $dir==='ASC'?'selected':'' ?>>Ascending</option>
        <option value="desc" <?= $dir==='DESC'?'selected':'' ?>>Descending</option>
      </select>
      <button class="btn sm" type="submit">Apply</button>
      <?php if ($search !== '' || isset($_GET['sort'])): ?><a class="btn sm secondary" href="index.php">Reset</a><?php endif; ?>
    </form>

    <?php if (!$persons): ?>
      <p class="muted">No records found.</p>
    <?php else: ?>
    <div class="table-wrap">
      <table class="data">
        <thead><tr>
          <th>#</th><th>Name</th><th>Age</th><th>Gender</th><th>Email</th><th>Address</th><th>Contact</th><th>Action</th>
        </tr></thead>
        <tbody>
        <?php foreach ($persons as $i => $p): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e(trim($p['first_name'] . ' ' . $p['middle_name'] . ' ' . $p['last_name'])) ?></td>
            <td><?= (int)$p['age'] ?></td>
            <td><?= e($p['gender']) ?></td>
            <td><?= e($p['email']) ?></td>
            <td><?= e($p['address']) ?></td>
            <td><?= e($p['contact']) ?></td>
            <td style="white-space:nowrap">
              <a class="btn sm" href="?edit=<?= (int)$p['id'] ?>">Edit</a>
              <form method="post" style="display:inline" onsubmit="return confirm('Delete this record?');">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                <button class="btn sm danger" type="submit">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <p class="muted">Total records: <?= count($persons) ?></p>
    <?php endif; ?>
  </div>
</main>
<footer class="site">PHP Basics - Hands-on Exercises</footer>
</body>
</html>

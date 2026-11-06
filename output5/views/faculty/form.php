<?php
function ferr($errors, $k) { return isset($errors[$k]) ? "<span class=\"err\">" . e($errors[$k]) . "</span>" : ""; }
function fcls($errors, $k) { return isset($errors[$k]) ? "has-error" : ""; }
$action = $id ? "index.php?action=edit&id=$id" : "index.php?action=create";
?>
<div class="card">
  <h2><?= e($title) ?></h2>
  <?php if ($errors): ?><div class="alert bad">Please fix the errors below.</div><?php endif; ?>
  <form method="post" action="<?= e($action) ?>" novalidate>
    <div class="grid">
      <div class="<?= fcls($errors,"first_name") ?>"><label for="first_name">First Name *</label>
        <input type="text" id="first_name" name="first_name" value="<?= e($data["first_name"]) ?>" maxlength="50" required><?= ferr($errors,"first_name") ?></div>
      <div class="<?= fcls($errors,"middle_name") ?>"><label for="middle_name">Middle Name</label>
        <input type="text" id="middle_name" name="middle_name" value="<?= e($data["middle_name"]) ?>" maxlength="50"><?= ferr($errors,"middle_name") ?></div>
      <div class="<?= fcls($errors,"last_name") ?>"><label for="last_name">Last Name *</label>
        <input type="text" id="last_name" name="last_name" value="<?= e($data["last_name"]) ?>" maxlength="50" required><?= ferr($errors,"last_name") ?></div>
      <div class="<?= fcls($errors,"age") ?>"><label for="age">Age *</label>
        <input type="number" id="age" name="age" min="18" max="100" value="<?= e($data["age"]) ?>" required><?= ferr($errors,"age") ?></div>
      <div class="<?= fcls($errors,"gender") ?>"><label for="gender">Gender *</label>
        <select id="gender" name="gender" required>
          <option value="">-- Select --</option>
          <?php foreach (["Male","Female","Other"] as $g): ?>
            <option value="<?= $g ?>" <?= $data["gender"] === $g ? "selected" : "" ?>><?= $g ?></option>
          <?php endforeach; ?>
        </select><?= ferr($errors,"gender") ?></div>
      <div class="<?= fcls($errors,"position") ?>"><label for="position">Position *</label>
        <input type="text" id="position" name="position" value="<?= e($data["position"]) ?>" maxlength="100" placeholder="e.g. Instructor" required><?= ferr($errors,"position") ?></div>
      <div class="<?= fcls($errors,"salary") ?>"><label for="salary">Salary (&#8369;) *</label>
        <input type="number" id="salary" name="salary" min="0" step="0.01" value="<?= e($data["salary"]) ?>" required><?= ferr($errors,"salary") ?></div>
      <div class="full <?= fcls($errors,"address") ?>"><label for="address">Address *</label>
        <textarea id="address" name="address" rows="2" required><?= e($data["address"]) ?></textarea><?= ferr($errors,"address") ?></div>
    </div>
    <div class="actions">
      <button class="btn" type="submit"><?= $id ? "Update" : "Save" ?></button>
      <a class="btn secondary" href="index.php">Cancel</a>
    </div>
  </form>
</div>

<div class="card">
  <h2>Faculty Members</h2>
  <?php if ($flash): ?><div class="alert ok"><?= e($flash) ?></div><?php endif; ?>

  <form method="get" class="toolbar">
    <input type="text" name="q" placeholder="Search name or position..." value="<?= e($search) ?>">
    <button class="btn sm" type="submit">Search</button>
    <?php if ($search !== ""): ?><a class="btn sm secondary" href="index.php">Reset</a><?php endif; ?>
    <a class="btn sm" style="margin-left:auto" href="index.php?action=create">+ Add Faculty</a>
  </form>

  <?php if (!$records): ?>
    <p class="muted">No faculty records found.</p>
  <?php else: ?>
  <div class="table-wrap">
    <table class="data">
      <thead><tr>
        <th>ID</th><th>Name</th><th>Age</th><th>Gender</th><th>Address</th><th>Position</th><th>Salary</th><th>Action</th>
      </tr></thead>
      <tbody>
      <?php foreach ($records as $r): ?>
        <tr>
          <td><?= (int)$r["id"] ?></td>
          <td><?= e(trim($r["first_name"] . " " . $r["middle_name"] . " " . $r["last_name"])) ?></td>
          <td><?= (int)$r["age"] ?></td>
          <td><?= e($r["gender"]) ?></td>
          <td><?= e($r["address"]) ?></td>
          <td><?= e($r["position"]) ?></td>
          <td>&#8369;<?= number_format((float)$r["salary"], 2) ?></td>
          <td style="white-space:nowrap">
            <a class="btn sm" href="index.php?action=edit&amp;id=<?= (int)$r["id"] ?>">Edit</a>
            <form method="post" action="index.php?action=delete" style="display:inline" onsubmit="return confirm('Delete this faculty record?');">
              <input type="hidden" name="id" value="<?= (int)$r["id"] ?>">
              <button class="btn sm danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p class="muted">Total: <?= count($records) ?></p>
  <?php endif; ?>
</div>

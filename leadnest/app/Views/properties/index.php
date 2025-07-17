<?php include __DIR__ . '/../../../shared/header.php'; ?>

<h1>Properties</h1>

<?php if (!empty($_SESSION['flash'])): ?>
  <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
    <?= htmlspecialchars($_SESSION['flash']['message']) ?>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<a href="<?= BASE_URL ?>/public/index.php?mod=properties&action=create"
   class="btn btn-primary mb-3">New Property</a>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>UUID</th>
      <th>Contact</th>
      <th>Address</th>
      <th>Motivation</th>
      <th>Timeline</th>
      <th>Condition</th>
      <th>Price</th>
      <th>ARV</th>
      <th>MAO</th>
      <th>Created At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($properties)): ?>
      <?php foreach ($properties as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p->id) ?></td>
          <td><?= htmlspecialchars($p->uuid) ?></td>
          <td>
            <?php
              $c = \App\Models\Contact::find((int)$p->contact_id);
              echo htmlspecialchars("{$c->first_name} {$c->last_name}");
            ?>
          </td>
          <td><?= htmlspecialchars($p->address) ?></td>
          <td><?= htmlspecialchars($p->motivation) ?></td>
          <td><?= htmlspecialchars($p->timeline) ?></td>
          <td><?= htmlspecialchars($p->condition) ?></td>
          <td><?= htmlspecialchars(number_format($p->price,2)) ?></td>
          <td><?= htmlspecialchars(number_format($p->arv,2)) ?></td>
          <td><?= htmlspecialchars(number_format($p->mao,2)) ?></td>
          <td><?= htmlspecialchars($p->created_at) ?></td>
          <td>
            <a href="<?= BASE_URL ?>/public/index.php?mod=properties&action=edit&id=<?= $p->id ?>"
               class="btn btn-sm btn-secondary">Edit</a>
            <a href="<?= BASE_URL ?>/public/index.php?mod=properties&action=destroy&id=<?= $p->id ?>"
               class="btn btn-sm btn-danger"
               onclick="return confirm('Delete this property?');">
              Delete
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="12" class="text-center text-muted fst-italic">
          No properties yet.
        </td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

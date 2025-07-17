<?php include __DIR__ . '/../../../shared/header.php'; ?>

<h1>Contacts</h1>

<?php if (!empty($_SESSION['flash'])): ?>
  <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
    <?= htmlspecialchars($_SESSION['flash']['message']) ?>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<a href="<?= BASE_URL ?>/public/index.php?mod=contacts&action=create"
   class="btn btn-primary mb-3">+ New Contact</a>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Properties</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($contacts as $ct): ?>
      <tr>
        <td><?= htmlspecialchars($ct->first_name . ' ' . $ct->last_name) ?></td>
        <td><?= htmlspecialchars($ct->email) ?></td>
        <td><?= htmlspecialchars($ct->phone) ?></td>
        <td>
          <?= $ct->property_id
            ? 'Assigned'
            : '<span class="text-muted">None</span>' ?>
        </td>
        <td>
          <a href="<?= BASE_URL ?>/public/index.php?mod=contacts&action=edit&id=<?= $ct->id ?>"
             class="btn btn-sm btn-outline-primary">Edit</a>
          <a href="<?= BASE_URL ?>/public/index.php?mod=contacts&action=destroy&id=<?= $ct->id ?>"
             class="btn btn-sm btn-outline-danger"
             onclick="return confirm('Delete this contact?');">
            Delete
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

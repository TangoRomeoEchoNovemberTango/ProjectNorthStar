<?php include __DIR__ . '/../../../shared/header.php'; ?>

<h1>Contacts</h1>

<?php if (! empty($_SESSION['flash'])): ?>
  <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
    <?= htmlspecialchars($_SESSION['flash']['message']) ?>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<a href="<?= BASE_URL ?>/public/index.php?mod=contacts&action=create"
   class="btn btn-primary mb-3">
  New Contact
</a>

<div class="mb-3">
  <input type="text"
         id="search-input"
         class="form-control"
         placeholder="🔍 Search contacts…">
</div>

<table id="contacts-table" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (! empty($contacts)): ?>
      <?php foreach ($contacts as $c): ?>
        <tr>
          <td><?= htmlspecialchars($c->id) ?></td>
          <td>
            <?= htmlspecialchars("{$c->first_name} {$c->last_name}") ?>
          </td>
          <td><?= htmlspecialchars($c->email) ?></td>
          <td><?= htmlspecialchars($c->phone) ?></td>
          <td>
            <a href="<?= BASE_URL ?>
               /public/index.php?mod=contacts&action=edit&id=<?= $c->id ?>"
               class="btn btn-sm btn-secondary">Edit</a>
            <a href="<?= BASE_URL ?>
               /public/index.php?mod=contacts&action=destroy&id=<?= $c->id ?>"
               class="btn btn-sm btn-danger"
               onclick="return confirm('Delete this contact?');">
              Delete
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="5"
            class="text-center text-muted fst-italic">
          No contacts yet.
        </td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<script>
// Simple client-side filter: hides rows that don't match the query
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('search-input');
  const rows  = Array.from(
    document.querySelectorAll('#contacts-table tbody tr')
  );

  input.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(q) ? '' : 'none';
    });
  });
});
</script>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

<?php include __DIR__ . '/../../../shared/header.php'; ?>

<!-- LIVE SEARCH ENABLED: contacts final -->

<h1>Contacts</h1>

<a href="<?= BASE_URL ?>/public/index.php?mod=contacts&action=create"
   class="btn btn-primary mb-3">
  + New Contact
</a>

<div class="mb-3">
  <input
    type="text"
    id="search-input"
    class="form-control"
    placeholder="🔍 Search contacts…"
  >
</div>

<table id="contacts-table" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Properties</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($contacts as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c->id) ?></td>
        <td><?= htmlspecialchars("{$c->first_name} {$c->last_name}") ?></td>
        <td><?= htmlspecialchars($c->email) ?></td>
        <td><?= htmlspecialchars($c->phone) ?></td>
        <td>
          <?php
            $count = count($c->properties);
            if ($count > 0) {
                echo $count . ' property' . ($count !== 1 ? 'ies' : '');
            } else {
                echo 'None';
            }
          ?>
        </td>
        <td>
          <a href="<?= BASE_URL ?>/public/index.php?mod=contacts&action=edit&id=<?= $c->id ?>"
             class="btn btn-sm btn-secondary">Edit</a>
          <a href="<?= BASE_URL ?>/public/index.php?mod=contacts&action=destroy&id=<?= $c->id ?>"
             class="btn btn-sm btn-danger"
             onclick="return confirm('Delete this contact?');">
            Delete
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  console.log('live-search contacts final loaded');
  document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('search-input');
    const rows = Array.from(
      document.querySelectorAll('#contacts-table tbody tr')
    );
    input.addEventListener('input', function() {
      const q = this.value.toLowerCase();
      rows.forEach(row => {
        row.style.display =
          row.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  });
</script>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

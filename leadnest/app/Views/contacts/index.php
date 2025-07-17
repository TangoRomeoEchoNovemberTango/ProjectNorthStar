<?php include __DIR__ . '/../../../shared/header.php'; ?>

<!-- LIVE SEARCH ENABLED: contacts v1 -->

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

<table id="contacts-table" class="table table-bordered">
  <thead>
    <tr>
      <th>Name</th><th>Email</th><th>Phone</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($contacts as $c): ?>
      <tr>
        <td><?= htmlspecialchars("{$c->first_name} {$c->last_name}") ?></td>
        <td><?= htmlspecialchars($c->email) ?></td>
        <td><?= htmlspecialchars($c->phone) ?></td>
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
  console.log('live-search contacts v1 loaded');
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('search-input');
    const rows = Array.from(document.querySelectorAll('#contacts-table tbody tr'));
    input.addEventListener('input', () => {
      const q = input.value.toLowerCase();
      rows.forEach(r => r.style.display = 
        r.textContent.toLowerCase().includes(q) ? '' : 'none'
      );
    });
  });
</script>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

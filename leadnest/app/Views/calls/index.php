<?php include __DIR__ . '/../../../shared/header.php'; ?>

<h1>Calls</h1>

<?php if (! empty($_SESSION['flash'])): ?>
  <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
    <?= htmlspecialchars($_SESSION['flash']['message']) ?>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<a href="<?= BASE_URL ?>/public/index.php?mod=calls&action=create"
   class="btn btn-primary mb-3">
  Log New Call
</a>

<div class="mb-3">
  <input type="text"
         id="search-input"
         class="form-control"
         placeholder="🔍 Search calls…">
</div>

<table id="calls-table" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Contact</th>
      <th>Type</th>
      <th>Date/Time</th>
      <th>Notes</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (! empty($calls)): ?>
      <?php foreach ($calls as $cl): ?>
        <tr>
          <td><?= htmlspecialchars($cl->id) ?></td>
          <td>
            <?php
              $contact = \App\Models\Contact::find((int)$cl->contact_id);
              echo htmlspecialchars("{$contact->first_name} {$contact->last_name}");
            ?>
          </td>
          <td><?= htmlspecialchars($cl->type) ?></td>
          <td><?= htmlspecialchars($cl->call_datetime) ?></td>
          <td><?= htmlspecialchars($cl->notes) ?></td>
          <td>
            <a href="<?= BASE_URL ?>
               /public/index.php?mod=calls&action=edit&id=<?= $cl->id ?>"
               class="btn btn-sm btn-secondary">Edit</a>
            <a href="<?= BASE_URL ?>
               /public/index.php?mod=calls&action=destroy&id=<?= $cl->id ?>"
               class="btn btn-sm btn-danger"
               onclick="return confirm('Delete this call?');">
              Delete
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="6"
            class="text-center text-muted fst-italic">
          No calls logged yet.
        </td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<script>
// Client-side search on the Calls table
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('search-input');
  const rows  = Array.from(
    document.querySelectorAll('#calls-table tbody tr')
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

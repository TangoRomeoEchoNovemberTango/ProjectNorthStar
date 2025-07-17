<?php include __DIR__ . '/../../../shared/header.php'; ?>

<h1>Contacts</h1>

<?php if (! empty($_SESSION['flash'])): ?>
  <div class="alert alert-<?php echo $_SESSION['flash']['type']; ?>">
    <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<a href="<?php echo BASE_URL; ?>/public/index.php?mod=contacts&action=create"
   class="btn btn-primary mb-3">
  + New Contact
</a>

<!-- Live-search input -->
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
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Properties</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (! empty($contacts)): ?>
      <?php foreach ($contacts as $c): ?>
        <tr>
          <td><?php echo htmlspecialchars("{$c->first_name} {$c->last_name}"); ?></td>
          <td><?php echo htmlspecialchars($c->email); ?></td>
          <td><?php echo htmlspecialchars($c->phone); ?></td>
          <td>
            <?php
              // if you have a relationship, list property count or names
              echo isset($c->properties) 
                ? count($c->properties) . ' property(ies)'
                : 'None';
            ?>
          </td>
          <td>
            <a href="<?php echo BASE_URL; ?>/public/index.php?mod=contacts&action=edit&id=<?php echo $c->id; ?>"
               class="btn btn-sm btn-secondary">Edit</a>
            <a href="<?php echo BASE_URL; ?>/public/index.php?mod=contacts&action=destroy&id=<?php echo $c->id; ?>"
               class="btn btn-sm btn-danger"
               onclick="return confirm('Delete this contact?');">
              Delete
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="5" class="text-center text-muted fst-italic">
          No contacts yet.
        </td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<script>
// Client-side live search for contacts table
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('search-input');
  const rows  = Array.from(
    document.querySelectorAll('#contacts-table tbody tr')
  );

  input.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    rows.forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(q)
        ? ''
        : 'none';
    });
  });
});
</script>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

<?php include __DIR__ . '/../../../shared/header.php'; ?>

<!-- LIVE SEARCH ENABLED: properties final -->

<h1>Properties</h1>

<?php if (! empty($_SESSION['flash'])): ?>
  <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
    <?= htmlspecialchars($_SESSION['flash']['message']) ?>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<a href="<?= BASE_URL ?>/public/index.php?mod=properties&action=create"
   class="btn btn-primary mb-3">
  + New Property
</a>

<div class="mb-3">
  <input
    type="text"
    id="search-input"
    class="form-control"
    placeholder="🔍 Search properties…"
  >
</div>

<table id="properties-table" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Contact</th>
      <th>Address</th>
      <th>Motivation</th>
      <th>Timeline</th>
      <th>Price</th>
      <th>ARV</th>
      <th>MAO</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($properties as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p->id) ?></td>
        <td>
          <?php
            $ct = \App\Models\Contact::find((int)$p->contact_id);
            echo htmlspecialchars("{$ct->first_name} {$ct->last_name}");
          ?>
        </td>
        <td><?= htmlspecialchars($p->address) ?></td>
        <td><?= htmlspecialchars($p->motivation) ?></td>
        <td><?= htmlspecialchars($p->timeline) ?></td>
        <td><?= htmlspecialchars(number_format($p->price,2)) ?></td>
        <td><?= htmlspecialchars(number_format($p->arv,2)) ?></td>
        <td><?= htmlspecialchars(number_format($p->mao,2)) ?></td>
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
  </tbody>
</table>

<script>
  console.log('live-search properties final loaded');
  document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('search-input');
    const rows = Array.from(
      document.querySelectorAll('#properties-table tbody tr')
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

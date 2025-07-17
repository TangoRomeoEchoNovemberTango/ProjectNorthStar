<?php include __DIR__ . '/../../../shared/header.php'; ?>

<!-- LIVE SEARCH ENABLED: calls final -->

<h1>Calls</h1>

<a href="<?= BASE_URL ?>/public/index.php?mod=calls&action=create"
   class="btn btn-primary mb-3">
  + Log New Call
</a>

<div class="mb-3">
  <input
    type="text"
    id="search-input"
    class="form-control"
    placeholder="🔍 Search calls…"
  >
</div>

<table id="calls-table" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Contact</th>
      <th>Date/Time</th>
      <th>Duration (min)</th>
      <th>Summary</th>
      <th>Outcome</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($calls as $cl): ?>
      <tr>
        <td><?= htmlspecialchars($cl->id) ?></td>
        <td>
          <?php 
            $ct = \App\Models\Contact::find((int)$cl->contact_id);
            echo htmlspecialchars("{$ct->first_name} {$ct->last_name}");
          ?>
        </td>
        <td><?= htmlspecialchars($cl->call_time) ?></td>
        <td><?= htmlspecialchars((string)$cl->duration) ?></td>
        <td><?= htmlspecialchars($cl->summary) ?></td>
        <td><?= htmlspecialchars($cl->outcome) ?></td>
        <td>
          <a href="<?= BASE_URL ?>/public/index.php?mod=calls&action=edit&id=<?= $cl->id ?>"
             class="btn btn-sm btn-secondary">Edit</a>
          <a href="<?= BASE_URL ?>/public/index.php?mod=calls&action=destroy&id=<?= $cl->id ?>"
             class="btn btn-sm btn-danger"
             onclick="return confirm('Delete this call?');">
            Delete
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  console.log('live-search calls final loaded');
  document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('search-input');
    const rows = Array.from(
      document.querySelectorAll('#calls-table tbody tr')
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

<?php include __DIR__ . '/../../../shared/header.php'; ?>

<!-- LIVE SEARCH ENABLED: calls v1 -->

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

<table id="calls-table" class="table table-bordered">
  <thead>
    <tr>
      <th>Contact</th><th>Type</th><th>Date/Time</th><th>Notes</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($calls as $cl): ?>
      <tr>
        <td>
          <?php 
            $ct = \App\Models\Contact::find((int)$cl->contact_id);
            echo htmlspecialchars("{$ct->first_name} {$ct->last_name}");
          ?>
        </td>
        <td><?= htmlspecialchars($cl->type) ?></td>
        <td><?= htmlspecialchars($cl->call_datetime) ?></td>
        <td><?= htmlspecialchars($cl->notes) ?></td>
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
  console.log('live-search calls v1 loaded');
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('search-input');
    const rows = Array.from(
      document.querySelectorAll('#calls-table tbody tr')
    );
    input.addEventListener('input', () => {
      const q = input.value.toLowerCase();
      rows.forEach(r => {
        r.style.display = 
          r.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  });
</script>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

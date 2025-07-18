<?php include __DIR__ . '/../../../shared/header.php'; ?>

<!-- LIVE SEARCH + AJAX DELETE: contacts --> 

<h1>Contacts</h1>

<a href="<?= BASE_URL ?>/public/index.php?mod=contacts&action=create"
   class="btn btn-primary mb-3">+ New Contact</a>

<div class="mb-3">
  <input id="search-input" type="text" class="form-control"
         placeholder="🔍 Search contacts…" />
</div>

<table id="contacts-table" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Properties</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($contacts as $c): ?>
      <tr data-id="<?= $c->id ?>">
        <td><?= $c->id ?></td>
        <td><?= htmlspecialchars("$c->first_name $c->last_name") ?></td>
        <td><?= htmlspecialchars($c->email) ?></td>
        <td><?= htmlspecialchars($c->phone) ?></td>
        <td>
          <?php
            $cnt = count($c->properties());
            echo $cnt ? $cnt.' property'.($cnt>1?'ies':'') : 'None';
          ?>
        </td>
        <td>
          <a href="<?= BASE_URL ?>/public/index.php?mod=contacts&action=edit&id=<?= $c->id ?>"
             class="btn btn-sm btn-secondary">Edit</a>
          <a href="<?= BASE_URL ?>/public/index.php?mod=contacts&action=destroy&id=<?= $c->id ?>"
             class="btn btn-sm btn-danger btn-delete-contact">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
document.addEventListener('DOMContentLoaded', ()=> {
  // live search
  const rows = Array.from(document.querySelectorAll('#contacts-table tbody tr'));
  document.getElementById('search-input').addEventListener('input', e=>{
    const q = e.target.value.toLowerCase();
    rows.forEach(r=> r.style.display = r.textContent.toLowerCase().includes(q)? '' : 'none');
  });

  // ajax delete
  document.querySelectorAll('.btn-delete-contact').forEach(btn=>{
    btn.addEventListener('click', async ev=>{
      ev.preventDefault();
      if (!confirm('Delete this contact?')) return;
      const url = btn.href;
      const tr  = btn.closest('tr');
      const res = await fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}
      });
      if (res.ok) tr.remove();
      else console.error('Delete failed', await res.text());
    });
  });
});
</script>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>
?>

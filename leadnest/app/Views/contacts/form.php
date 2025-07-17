<?php include __DIR__ . '/../../../shared/header.php'; ?>

<?php
  $isEdit = isset($contact);
  $c      = $contact ?? null;
  $props  = $properties ?? [];

  // preserve POST on validation
  $fn = $_POST['first_name']  ?? $c->first_name  ?? '';
  $mn = $_POST['middle_name'] ?? $c->middle_name ?? '';
  $ln = $_POST['last_name']   ?? $c->last_name   ?? '';
  $em = $_POST['email']       ?? $c->email       ?? '';
  $ph = $_POST['phone']       ?? $c->phone       ?? '';
  $no = $_POST['notes']       ?? $c->notes       ?? '';
  $ty = $_POST['type']        ?? $c->type        ?? 'other';
?>

<h1><?= $isEdit ? 'Edit' : 'New' ?> Contact</h1>

<form method="post"
      action="<?= BASE_URL ?>/public/index.php?mod=contacts&action=<?= $isEdit ? "update&id={$c->id}" : 'store' ?>">

  <div class="row mb-3">
    <div class="col-md-4">
      <label class="form-label">First Name</label>
      <input type="text" name="first_name" class="form-control" required
             value="<?= htmlspecialchars($fn) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Middle Name</label>
      <input type="text" name="middle_name" class="form-control"
             value="<?= htmlspecialchars($mn) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Last Name</label>
      <input type="text" name="last_name" class="form-control" required
             value="<?= htmlspecialchars($ln) ?>">
    </div>
  </div>

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control"
           value="<?= htmlspecialchars($em) ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Phone</label>
    <input type="tel" name="phone" class="form-control"
           value="<?= htmlspecialchars($ph) ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Type</label>
    <select name="type" class="form-select">
      <?php foreach ([
        'seller','buyer','buyers_agent','sellers_agent',
        'cash_buyer','title_company','municipality','other'
      ] as $t): ?>
        <option value="<?= $t ?>" <?= $t === $ty ? 'selected' : '' ?>>
          <?= ucfirst(str_replace('_',' ',$t)) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Notes</label>
    <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($no) ?></textarea>
  </div>

  <button type="submit" class="btn btn-primary mb-3">
    <?= $isEdit ? 'Save Changes' : 'Create Contact' ?>
  </button>

  <?php if ($isEdit): ?>
    <button type="button"
            class="btn btn-outline-secondary mb-3"
            data-bs-toggle="modal"
            data-bs-target="#managePropsModal">
      Manage Properties (<?= count($props) ?>)
    </button>
  <?php endif; ?>

  <a href="<?= BASE_URL ?>/public/index.php?mod=contacts&action=index"
     class="btn btn-link">Cancel</a>
</form>

<?php if ($isEdit): ?>

<!-- Modal: Manage Properties -->
<div class="modal fade" id="managePropsModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Properties for <?= htmlspecialchars($c->first_name.' '.$c->last_name) ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <!-- Assigned Properties List -->
        <ul id="assignedPropsList" class="list-group mb-3">
          <?php if (empty($props)): ?>
            <li class="list-group-item text-muted">No properties assigned</li>
          <?php endif; ?>

          <?php foreach ($props as $p): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center"
                data-id="<?= $p->id ?>">
              <?= htmlspecialchars($p->address) ?>
              <button class="btn btn-sm btn-danger btn-delete-prop">Delete</button>
            </li>
          <?php endforeach; ?>
        </ul>

        <!-- Add New Property Form -->
        <form id="newPropForm">
          <input type="hidden" name="contact_id" value="<?= $c->id ?>">
          <div class="mb-3">
            <label class="form-label">Address</label>
            <input name="address" class="form-control" required>
          </div>
          <div class="mb-3 row">
            <div class="col-md-6">
              <label class="form-label">Price</label>
              <input name="price" type="number" step="0.01" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">ARV</label>
              <input name="arv" type="number" step="0.01" class="form-control">
            </div>
          </div>
          <button type="submit" class="btn btn-secondary">Add Property</button>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
          Done
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// Add Property via AJAX
document.getElementById('newPropForm')
  .addEventListener('submit', async e => {
    e.preventDefault();
    const res = await fetch(
      '<?= BASE_URL ?>/public/index.php?mod=properties&action=storeAjax',
      {
        method: 'POST',
        credentials: 'same-origin',
        headers: {'Accept':'application/json'},
        body: new FormData(e.target)
      }
    );
    if (!res.ok) return console.error(await res.text());
    const {id,address} = await res.json();

    // update list
    const ul = document.getElementById('assignedPropsList');
    ul.querySelectorAll('.text-muted').forEach(el => el.remove());
    const li = document.createElement('li');
    li.className = 'list-group-item d-flex justify-content-between align-items-center';
    li.dataset.id = id;
    li.innerHTML = `
      ${address}
      <button class="btn btn-sm btn-danger btn-delete-prop">Delete</button>
    `;
    ul.appendChild(li);
    e.target.reset();
  });

// Delete Property via AJAX
document.getElementById('assignedPropsList')
  .addEventListener('click', async e => {
    if (!e.target.classList.contains('btn-delete-prop')) return;
    const li = e.target.closest('li');
    const id = li.dataset.id;
    const res = await fetch(
      '<?= BASE_URL ?>/public/index.php?mod=properties&action=deleteAjax',
      {
        method: 'POST',
        credentials: 'same-origin',
        headers: {'Accept':'application/json','Content-Type':'application/x-www-form-urlencoded'},
        body: `id=${encodeURIComponent(id)}`
      }
    );
    if (res.ok) li.remove();
  });
</script>

<?php endif; ?>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

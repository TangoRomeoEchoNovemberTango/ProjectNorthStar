<?php include __DIR__ . '/../../../shared/header.php'; ?>

<?php
  use App\Models\Property;

  $isEdit    = isset($contact);
  $c         = $contact   ?? null;
  $props     = $properties ?? [];
  $allProps  = Property::all();
  $assignedIds = array_map(fn($p)=>$p->id, $props);

  // Sticky Contact inputs
  $fn = $_POST['first_name']  ?? $c->first_name  ?? '';
  $mn = $_POST['middle_name'] ?? $c->middle_name ?? '';
  $ln = $_POST['last_name']   ?? $c->last_name   ?? '';
  $em = $_POST['email']       ?? $c->email       ?? '';
  $ph = $_POST['phone']       ?? $c->phone       ?? '';
  $ty = $_POST['type']        ?? $c->type        ?? 'other';
  $no = $_POST['notes']       ?? $c->notes       ?? '';
?>

<h1><?= $isEdit ? 'Edit' : 'New' ?> Contact</h1>

<form method="post"
      action="<?= BASE_URL ?>/public/index.php?mod=contacts&action=<?= $isEdit ? "update&id={$c->id}" : 'store' ?>">

  <div class="row mb-3">
    <div class="col-md-4">
      <label class="form-label">First Name</label>
      <input name="first_name" required class="form-control"
             value="<?= htmlspecialchars($fn) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Middle Name</label>
      <input name="middle_name" class="form-control"
             value="<?= htmlspecialchars($mn) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Last Name</label>
      <input name="last_name" required class="form-control"
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
     class="btn btn-link mb-3">Cancel</a>
</form>

<?php if ($isEdit): ?>
<div class="modal fade" id="managePropsModal" tabindex="-1"
     aria-labelledby="managePropsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"><div class="modal-content">

    <div class="modal-header">
      <h5 class="modal-title">
        Properties for <?= htmlspecialchars($c->first_name.' '.$c->last_name) ?>
      </h5>
      <button type="button" class="btn-close"
              data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
      <ul id="assignedPropsList" class="list-group mb-3">
        <?php if (empty($props)): ?>
          <li class="list-group-item text-muted">
            No properties assigned
          </li>
        <?php endif; ?>
        <?php foreach ($props as $p): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center"
              data-id="<?= $p->id ?>">
            <?= htmlspecialchars($p->address) ?>
            <div>
              <button class="btn btn-sm btn-warning btn-unassign-prop">
                Unassign
              </button>
              <button class="btn btn-sm btn-danger btn-delete-prop">
                Delete
              </button>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>

      <select id="assignPropsSelect" name="property_ids[]" multiple
              class="form-select mb-3"></select>
      <button id="assignPropsBtn" type="button" class="btn btn-secondary mb-3">
        Assign Selected
      </button>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-primary"
              data-bs-dismiss="modal">Done</button>
    </div>

  </div></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const contactId   = <?= (int)$c->id ?>;
  const assignedIds = <?= json_encode($assignedIds) ?>;

  $('#assignPropsSelect').select2({
    theme: 'bootstrap-5',
    placeholder: 'Search properties to assign…',
    width: '100%',
    ajax: {
      url: '<?= BASE_URL ?>/public/index.php?mod=properties&action=searchAjax',
      dataType: 'json',
      delay: 250,
      data: params => ({ q: params.term, contact_id: contactId }),
      processResults: data => ({ results: data.results })
    }
  });

  document.getElementById('assignPropsBtn').onclick = async () => {
    const ids = $('#assignPropsSelect').val() || [];
    if (!ids.length) return alert('Select at least one property');

    const res = await fetch(
      '<?= BASE_URL ?>/public/index.php?mod=properties&action=assignAjax', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Accept':'application/json',
        'Content-Type':'application/json'
      },
      body: JSON.stringify({ contact_id: contactId, property_ids: ids })
    });
    if (!res.ok) return alert('Assign failed');
    const { assigned } = await res.json();

    document.querySelectorAll('#assignedPropsList .text-muted').forEach(x=>x.remove());
    assigned.forEach(p => {
      const li = document.createElement('li');
      li.className = 'list-group-item d-flex justify-content-between align-items-center';
      li.dataset.id = p.id;
      li.innerHTML = `
        ${p.address}
        <div>
          <button class="btn btn-sm btn-warning btn-unassign-prop">Unassign</button>
          <button class="btn btn-sm btn-danger btn-delete-prop">Delete</button>
        </div>`;
      document.getElementById('assignedPropsList').appendChild(li);
    });

    document.querySelector('[data-bs-target="#managePropsModal"]')
      .textContent = `Manage Properties (${document.querySelectorAll('#assignedPropsList li').length})`;
    $('#assignPropsSelect').val(null).trigger('change');
  };

  document.getElementById('assignedPropsList')
    .addEventListener('click', async e => {
      const btn = e.target;
      if (!btn.matches('.btn-unassign-prop, .btn-delete-prop')) return;
      const li = btn.closest('li');
      const pid = li.dataset.id;
      const action = btn.matches('.btn-unassign-prop') ? 'unassignAjax' : 'deleteAjax';
      const params = new URLSearchParams({ id: pid });
      if (action === 'unassignAjax') params.append('contact_id', contactId);

      const res = await fetch(
        '<?= BASE_URL ?>/public/index.php?mod=properties&action=' + action, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {'Accept':'application/json'},
        body: params.toString()
      });
      if (res.ok) {
        li.remove();
        document.querySelector('[data-bs-target="#managePropsModal"]')
          .textContent = `Manage Properties (${document.querySelectorAll('#assignedPropsList li').length})`;
      } else {
        alert('Operation failed');
      }
    });
});
</script>
<?php endif; ?>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

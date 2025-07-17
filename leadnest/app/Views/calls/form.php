<?php include __DIR__ . '/../../../shared/header.php'; ?>

<?php
  $c        = $call ?? null;
  $isEdit   = $c !== null;
  $selId    = $c->contact_id ?? '';
  $selName  = $c && $c->contact
               ? "{$c->contact->first_name} {$c->contact->last_name}"
               : '';
  $contacts = $contacts ?? [];
  // preserve POST on validation
  $callTime = $_POST['call_time'] ?? $c->call_time ?? '';
  $duration = $_POST['duration']  ?? $c->duration  ?? '';
  $summary  = $_POST['summary']   ?? $c->summary   ?? '';
  $outcome  = $_POST['outcome']   ?? $c->outcome   ?? '';
?>

<h1><?= $isEdit ? 'Edit' : 'New' ?> Call</h1>

<form method="post"
      action="<?= BASE_URL ?>/public/index.php?mod=calls&action=<?= $isEdit ? "update&id={$c->id}" : 'store' ?>">

  <!-- Contact Picker / Add Contact -->
  <div class="mb-3">
    <label class="form-label">Contact</label>
    <input type="hidden" name="contact_id" id="contactId" value="<?= htmlspecialchars($selId) ?>">
    <input type="text"
           id="contactSearch"
           class="form-control mb-1"
           placeholder="Search contacts…"
           value="<?= htmlspecialchars($selName) ?>"
           autocomplete="off"
           required>
    <div id="contactResults" class="list-group position-relative" style="z-index:1000;"></div>
    <button type="button"
            class="btn btn-outline-secondary mt-2"
            data-bs-toggle="modal"
            data-bs-target="#contactModal">
      + Add Contact
    </button>
  </div>

  <!-- Call Details -->
  <div class="mb-3">
    <label class="form-label">Call Time</label>
    <input type="datetime-local" name="call_time" class="form-control"
           value="<?= htmlspecialchars($callTime) ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Duration</label>
    <input type="number" name="duration" class="form-control"
           value="<?= htmlspecialchars($duration) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Summary</label>
    <textarea name="summary" class="form-control" rows="3"><?= htmlspecialchars($summary) ?></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Outcome</label>
    <input type="text" name="outcome" class="form-control"
           value="<?= htmlspecialchars($outcome) ?>">
  </div>

  <button type="submit" class="btn btn-primary">
    <?= $isEdit ? 'Save Changes' : 'Log Call' ?>
  </button>
  <a href="<?= BASE_URL ?>/public/index.php?mod=calls&action=index" class="btn btn-link">
    Cancel
  </a>
</form>

<!-- Modal #1: Add Contact -->
<div class="modal fade" id="contactModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form id="contactForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Contact</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">First Name</label>
            <input name="first_name" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Middle Name</label>
            <input name="middle_name" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label">Last Name</label>
            <input name="last_name" class="form-control" required>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input name="email" type="email" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Phone</label>
          <input name="phone" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Type</label>
          <select name="type" class="form-select">
            <?php foreach ([
              'seller','buyer','buyers_agent','sellers_agent',
              'cash_buyer','title_company','municipality','other'
            ] as $t): ?>
              <option value="<?= $t ?>"><?= ucfirst(str_replace('_',' ',$t)) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Contact</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal #2: Add Property -->
<div class="modal fade" id="propertyModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form id="propertyForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Property</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="contact_id" id="propContactId">

        <div class="mb-3">
          <label class="form-label">Address</label>
          <input name="address" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Motivation</label>
          <textarea name="motivation" class="form-control" rows="2"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Timeline</label>
          <input name="timeline" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Condition</label>
          <textarea name="condition" class="form-control" rows="2"></textarea>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">Price</label>
            <input name="price" type="number" step="0.01" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label">ARV</label>
            <input name="arv" type="number" step="0.01" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label">MAO</label>
            <input name="mao" type="number" step="0.01" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-secondary">Save Property</button>
        <button type="button" id="propDoneBtn" class="btn btn-primary" data-bs-dismiss="modal">
          Done
        </button>
      </div>
    </form>
  </div>
</div>

<script>
// Build contacts array for live-search
const contacts = <?= json_encode(array_map(
  fn($ct) => ['id'=>$ct->id,'name'=>$ct->first_name.' '.$ct->last_name],
  $contacts
)); ?>;
const searchInput = document.getElementById('contactSearch');
const resultsDiv  = document.getElementById('contactResults');
const idInput      = document.getElementById('contactId');

// Live‐search existing contacts
searchInput.addEventListener('input', () => {
  const q = searchInput.value.toLowerCase().trim();
  resultsDiv.innerHTML = '';
  if (!q) return;
  contacts
    .filter(ct => ct.name.toLowerCase().includes(q))
    .slice(0,10)
    .forEach(ct => {
      const el = document.createElement('div');
      el.className = 'list-group-item list-group-item-action';
      el.textContent = ct.name;
      el.addEventListener('mousedown', () => {
        searchInput.value = ct.name;
        idInput.value     = ct.id;
        resultsDiv.innerHTML = '';
      });
      resultsDiv.appendChild(el);
    });
});

// 1) Save Contact via AJAX, then open Property modal
document.getElementById('contactForm').addEventListener('submit', async e => {
  e.preventDefault();
  const res = await fetch(
    '<?= BASE_URL ?>/public/index.php?mod=contacts&action=storeAjax',
    {
      method: 'POST',
      credentials: 'same-origin',
      headers: {'Accept':'application/json'},
      body: new FormData(e.target)
    }
  );
  if (!res.ok) {
    console.error('Contact save failed:', await res.text());
    return;
  }
  const {id,label} = await res.json();

  // set in Call form
  contacts.push({id,name:label});
  searchInput.value = label;
  idInput.value     = id;

  // switch modals
  bootstrap.Modal.getInstance(document.getElementById('contactModal')).hide();
  document.getElementById('propContactId').value = id;
  new bootstrap.Modal(document.getElementById('propertyModal')).show();
  e.target.reset();
});

// 2) Save Property via AJAX (repeatable)
document.getElementById('propertyForm').addEventListener('submit', async e => {
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
  if (!res.ok) {
    console.error('Property save failed:', await res.text());
    return;
  }
  const {address} = await res.json();
  alert('Property added: ' + address);
  e.target.reset();
});

// “Done” just closes modal #2
document.getElementById('propDoneBtn').addEventListener('click', () => {
  // back to Call form with everything in place
});
</script>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>
```
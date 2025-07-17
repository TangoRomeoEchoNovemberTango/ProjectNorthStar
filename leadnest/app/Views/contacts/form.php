<?php include __DIR__ . '/../../../shared/header.php'; ?>

<?php
  $isEdit = isset($contact);
  $c      = $contact ?? null;
  // now contains only this contact’s properties
  $props  = $properties ?? [];

  // sticky values
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

  <!-- name, email, phone, type, notes fields omitted for brevity -->

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
// Add/Delete property AJAX handlers remain unchanged
</script>
<?php endif; ?>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

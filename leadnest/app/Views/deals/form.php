<?php include __DIR__ . '/../../../shared/header.php'; ?>

<?php
  $isEdit     = isset($d);
  $contacts   = $contacts  ?? [];
  $properties = $properties ?? [];
  $stages     = $stages     ?? [];
  $cId        = $_POST['contact_id']  ?? $d->contact_id  ?? '';
  $pId        = $_POST['property_id'] ?? $d->property_id ?? '';
  $stage      = $_POST['stage']       ?? $d->stage       ?? 'lead';
  $amount     = $_POST['amount']      ?? $d->amount      ?? '';
  $offerDate  = $_POST['offer_date']  ?? $d->offer_date  ?? '';
  $closeDate  = $_POST['close_date']  ?? $d->close_date  ?? '';
  $notes      = $_POST['notes']       ?? $d->notes       ?? '';
?>

<h1><?= $isEdit ? 'Edit' : 'New' ?> Deal</h1>

<form method="post"
      action="<?= BASE_URL ?>/public/index.php?mod=deals&action=<?= $isEdit ? "update&id={$d->id}" : 'store' ?>">

  <div class="row mb-3">
    <div class="col-md-6">
      <label class="form-label">Contact</label>
      <select name="contact_id" class="form-control" required>
        <option value="">— Select Contact —</option>
        <?php foreach ($contacts as $c): ?>
          <option value="<?= $c->id ?>" <?= $c->id == $cId ? 'selected' : '' ?>>
            <?= htmlspecialchars($c->name) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Property</label>
      <select name="property_id" class="form-control" required>
        <option value="">— Select Property —</option>
        <?php foreach ($properties as $p): ?>
          <option value="<?= $p->id ?>" <?= $p->id == $pId ? 'selected' : '' ?>>
            <?= htmlspecialchars($p->address) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-4">
      <label class="form-label">Stage</label>
      <select name="stage" class="form-control">
        <?php foreach ($stages as $s): ?>
          <option value="<?= $s ?>" <?= $s === $stage ? 'selected' : '' ?>>
            <?= htmlspecialchars(str_replace('_',' ', ucfirst($s))) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Amount</label>
      <input type="number" step="0.01" name="amount"
             class="form-control" value="<?= htmlspecialchars($amount) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Offer Date</label>
      <input type="date" name="offer_date"
             class="form-control" value="<?= htmlspecialchars($offerDate) ?>">
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-4">
      <label class="form-label">Close Date</label>
      <input type="date" name="close_date"
             class="form-control" value="<?= htmlspecialchars($closeDate) ?>">
    </div>
    <div class="col-md-8">
      <label class="form-label">Notes</label>
      <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($notes) ?></textarea>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">
    <?= $isEdit ? 'Save Changes' : 'Create Deal' ?>
  </button>
  <a href="<?= BASE_URL ?>/public/index.php?mod=deals&action=index"
     class="btn btn-link">Cancel</a>
</form>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

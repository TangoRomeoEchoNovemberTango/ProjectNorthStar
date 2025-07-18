<?php include __DIR__ . '/../../../shared/header.php'; ?>

<?php
  $isEdit   = isset($property);
  $p        = $property   ?? null;
  // sticky inputs
  $address     = $_POST['address']     ?? $p->address     ?? '';
  $description = $_POST['description'] ?? $p->description ?? '';
  $motivation  = $_POST['motivation']  ?? $p->motivation  ?? '';
  $timeline    = $_POST['timeline']    ?? $p->timeline    ?? '';
  $condition   = $_POST['condition']   ?? $p->condition   ?? '';
  $price       = $_POST['price']       ?? $p->price       ?? '';
  $arv         = $_POST['arv']         ?? $p->arv         ?? '';
  $mao         = $_POST['mao']         ?? $p->mao         ?? '';
  $cid         = $_POST['contact_id']  ?? $p->contact_id  ?? '';
?>

<h1><?= $isEdit ? 'Edit' : 'New' ?> Property</h1>

<form method="post"
      action="<?= BASE_URL ?>/public/index.php?mod=properties&action=<?= $isEdit ? "update&id={$p->id}" : 'store' ?>">

  <div class="mb-3">
    <label class="form-label">Contact</label>
    <select id="contact-select" name="contact_id" class="form-select">
      <option value="">Unassigned</option>
      <?php if ($cid):
        $sel = \App\Models\Contact::find((int)$cid);
        if ($sel): ?>
          <option value="<?= $sel->id ?>" selected>
            <?= htmlspecialchars("{$sel->first_name} {$sel->last_name}") ?>
          </option>
      <?php endif; endif; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Address</label>
    <input name="address" class="form-control" required
           value="<?= htmlspecialchars($address) ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($description) ?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Motivation</label>
    <textarea name="motivation" class="form-control" rows="2"><?= htmlspecialchars($motivation) ?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Timeline</label>
    <input name="timeline" class="form-control"
           value="<?= htmlspecialchars($timeline) ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Condition</label>
    <textarea name="condition" class="form-control" rows="2"><?= htmlspecialchars($condition) ?></textarea>
  </div>

  <div class="row mb-3">
    <div class="col-md-4">
      <label class="form-label">Price</label>
      <input name="price" type="number" step="0.01" class="form-control"
             value="<?= htmlspecialchars($price) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">ARV</label>
      <input name="arv" type="number" step="0.01" class="form-control"
             value="<?= htmlspecialchars($arv) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">MAO</label>
      <input name="mao" type="number" step="0.01" class="form-control"
             value="<?= htmlspecialchars($mao) ?>">
    </div>
  </div>

  <button type="submit" class="btn btn-primary mb-3">
    <?= $isEdit ? 'Save Changes' : 'Create Property' ?>
  </button>

  <a href="<?= BASE_URL ?>/public/index.php?mod=properties&action=index"
     class="btn btn-link mb-3">Cancel</a>
</form>

<script>
  // AJAX‐powered Select2 for contacts
  $(function() {
    $('#contact-select').select2({
      theme: 'bootstrap-5',
      placeholder: 'Search or select a contact',
      allowClear: true,
      width: '100%',
      ajax: {
        url: '<?= BASE_URL ?>/public/index.php?mod=contacts&action=searchAjax',
        dataType: 'json',
        delay: 250,
        data: params => ({ q: params.term }),
        processResults: data => ({ results: data.results })
      }
    });
  });
</script>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

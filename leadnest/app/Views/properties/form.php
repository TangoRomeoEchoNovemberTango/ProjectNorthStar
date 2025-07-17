<?php include __DIR__ . '/../../../shared/header.php'; ?>

<?php
  $isEdit   = isset($p);
  $prop     = $p ?? null;
  $contacts = $contacts ?? [];

  // helper to keep form sticky
  $getVal = fn($field, $default='') => 
      htmlspecialchars($_POST[$field] ?? $prop->{$field} ?? $default);
?>

<h1><?= $isEdit ? 'Edit' : 'Create' ?> Property</h1>

<form
  method="post"
  action="<?=
    BASE_URL
    . '/public/index.php?mod=properties&action='
    . ($isEdit ? 'update' : 'store')
    . ($isEdit ? '&id=' . $prop->id : '')
  ?>"
>
  <div class="mb-3">
    <label class="form-label">Contact</label>
    <select name="contact_id" class="form-select" required>
      <option value="">— Select Contact —</option>
      <?php foreach ($contacts as $ct): ?>
        <option value="<?= $ct->id ?>"
          <?= $ct->id == ($_POST['contact_id'] ?? $prop->contact_id ?? '') ? 'selected' : '' ?>>
          <?= htmlspecialchars("{$ct->first_name} {$ct->last_name}") ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Address</label>
    <input
      type="text"
      name="address"
      class="form-control"
      required
      value="<?= $getVal('address') ?>"
    >
  </div>

  <div class="mb-3">
    <label class="form-label">Motivation</label>
    <textarea
      name="motivation"
      class="form-control"
      rows="2"
    ><?= $getVal('motivation') ?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Timeline</label>
    <input
      type="text"
      name="timeline"
      class="form-control"
      value="<?= $getVal('timeline') ?>"
    >
  </div>

  <div class="row mb-3">
    <div class="col-md-4">
      <label class="form-label">Price</label>
      <input
        type="number"
        step="0.01"
        name="price"
        class="form-control"
        value="<?= $getVal('price') ?>"
      >
    </div>
    <div class="col-md-4">
      <label class="form-label">ARV</label>
      <input
        type="number"
        step="0.01"
        name="arv"
        class="form-control"
        value="<?= $getVal('arv') ?>"
      >
    </div>
    <div class="col-md-4">
      <label class="form-label">MAO</label>
      <input
        type="number"
        step="0.01"
        name="mao"
        class="form-control"
        value="<?= $getVal('mao') ?>"
      >
    </div>
  </div>

  <button type="submit" class="btn btn-primary">
    <?= $isEdit ? 'Save Changes' : 'Save Property' ?>
  </button>
  <a href="<?= BASE_URL ?>/public/index.php?mod=properties&action=index"
     class="btn btn-link">Cancel</a>
</form>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

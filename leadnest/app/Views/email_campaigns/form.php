<?php include __DIR__ . '/../../../shared/header.php'; ?>

<?php
  $isEdit      = isset($campaign);
  $oldTitle    = $title       ?? ($campaign->title       ?? '');
  $oldDesc     = $description ?? ($campaign->description ?? '');
  $oldType     = $type        ?? ($campaign->campaign_type ?? '');
?>

<h1><?= $isEdit ? 'Edit' : 'New' ?> Email Campaign</h1>

<form method="post"
      action="<?= BASE_URL ?>/public/index.php?mod=email_campaigns&action=<?= $isEdit ? "update&id={$campaign->id}" : 'store' ?>">

  <div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" id="title" name="title"
           class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>"
           value="<?= htmlspecialchars($oldTitle) ?>" required>
    <?php if (isset($errors['title'])): ?>
      <div class="invalid-feedback"><?= htmlspecialchars($errors['title']) ?></div>
    <?php endif; ?>
  </div>

  <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea id="description" name="description"
              class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>"
              rows="5" required><?= htmlspecialchars($oldDesc) ?></textarea>
    <?php if (isset($errors['description'])): ?>
      <div class="invalid-feedback"><?= htmlspecialchars($errors['description']) ?></div>
    <?php endif; ?>
  </div>

  <div class="mb-3">
    <label for="campaign_type" class="form-label">Type</label>
    <input type="text" id="campaign_type" name="campaign_type"
           class="form-control"
           value="<?= htmlspecialchars($oldType) ?>">
  </div>

  <button type="submit" class="btn btn-primary">
    <?= $isEdit ? 'Save Changes' : 'Create Campaign' ?>
  </button>
  <a href="<?= BASE_URL ?>/public/index.php?mod=email_campaigns&action=index"
     class="btn btn-link">Cancel</a>
</form>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

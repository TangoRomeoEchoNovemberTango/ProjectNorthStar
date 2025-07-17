<?php include __DIR__ . '/../../../shared/header.php'; ?>

<?php
  $isEdit = isset($post);
  $p      = $post ?? null;
  $cont   = $_POST['content']      ?? $p->content      ?? '';
  $img    = $_POST['image_url']    ?? $p->image_url    ?? '';
  $sched  = $_POST['scheduled_at'] ?? $p->scheduled_at ?? '';
?>

<h1><?= $isEdit ? 'Edit' : 'New' ?> Social Post</h1>

<form method="post"
      action="<?= BASE_URL ?>/public/index.php?mod=social_posts&action=<?= $isEdit ? "update&id={$p->id}" : 'store' ?>">

  <div class="mb-3">
    <label class="form-label">Content</label>
    <textarea name="content" class="form-control" rows="3"><?= htmlspecialchars($cont) ?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Image URL (optional)</label>
    <input type="url" name="image_url"
           class="form-control" value="<?= htmlspecialchars($img) ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Scheduled At</label>
    <input type="datetime-local" name="scheduled_at"
           class="form-control" value="<?= htmlspecialchars($sched) ?>">
  </div>

  <button type="submit" class="btn btn-primary">
    <?= $isEdit ? 'Save Changes' : 'Schedule Post' ?>
  </button>
  <a href="<?= BASE_URL ?>/public/index.php?mod=social_posts&action=index"
     class="btn btn-link">Cancel</a>
</form>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

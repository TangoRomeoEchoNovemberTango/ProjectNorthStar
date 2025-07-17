<?php include __DIR__ . '/../../../shared/header.php'; ?>

<h2 class="mb-4">Upload Document</h2>

<form
  method="POST"
  action="<?= BASE_URL ?>/public/index.php?mod=documents&action=store"
  enctype="multipart/form-data"
>
  <div class="mb-3">
    <label class="form-label">Title</label>
    <input
      name="title"
      class="form-control"
      required
    >
  </div>

  <div class="mb-3">
    <label class="form-label">Description</label>
    <textarea
      name="description"
      class="form-control"
      rows="3"
    ></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">File</label>
    <input
      type="file"
      name="file"
      class="form-control"
      required
    >
  </div>

  <button class="btn btn-success">Upload</button>
  <a
    href="<?= BASE_URL ?>/public/index.php?mod=documents"
    class="btn btn-secondary ms-2"
  >
    Cancel
  </a>
</form>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

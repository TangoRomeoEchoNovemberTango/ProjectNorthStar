<?php include __DIR__ . '/../../../shared/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2>Documents</h2>
  <a
    href="<?= BASE_URL ?>/public/index.php?mod=documents&action=create"
    class="btn btn-primary"
  >
    + Upload Document
  </a>
</div>

<?php if (count($docs)): ?>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Filename</th>
        <th>Uploaded At</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($docs as $d): ?>
      <tr>
        <td><?= htmlspecialchars($d->title) ?></td>
        <td><?= htmlspecialchars($d->description) ?></td>
        <td><?= htmlspecialchars($d->file_name) ?></td>
        <td><?= date('M j, Y g:ia', strtotime($d->created_at)) ?></td>
        <td class="text-end">
          <a
            href="<?= BASE_URL ?>/public/index.php?mod=documents&action=download&id=<?= $d->id ?>"
            class="btn btn-sm btn-success me-1"
          >
            Download
          </a>
          <a
            href="<?= BASE_URL ?>/public/index.php?mod=documents&action=destroy&id=<?= $d->id ?>"
            class="btn btn-sm btn-danger"
            onclick="return confirm('Delete this document?');"
          >
            Delete
          </a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <div class="alert alert-info">
    No documents uploaded yet.
    <a href="<?= BASE_URL ?>/public/index.php?mod=documents&action=create">
      Upload one now
    </a>.
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

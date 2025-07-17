<?php include __DIR__ . '/../../../shared/header.php'; ?>

<h1>Email Campaigns</h1>

<a href="<?= BASE_URL ?>/public/index.php?mod=email_campaigns&action=create"
   class="btn btn-primary mb-3">New Campaign</a>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Description</th>
      <th>Type</th>
      <th>Created At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($campaigns)): ?>
      <?php foreach ($campaigns as $c): ?>
        <tr>
          <td><?= $c->id ?></td>
          <td><?= htmlspecialchars($c->title) ?></td>
          <td><?= htmlspecialchars($c->description) ?></td>
          <td><?= htmlspecialchars($c->campaign_type) ?></td>
          <td><?= $c->created_at ?></td>
          <td>
            <a href="<?= BASE_URL ?>/public/index.php?mod=email_campaigns&action=edit&id=<?= $c->id ?>"
               class="btn btn-sm btn-secondary">Edit</a>
            <a href="<?= BASE_URL ?>/public/index.php?mod=email_campaigns&action=destroy&id=<?= $c->id ?>"
               class="btn btn-sm btn-danger" onclick="return confirm('Delete?');">Delete</a>
            <a href="<?= BASE_URL ?>/public/index.php?mod=email_campaigns&action=send_test&id=<?= $c->id ?>"
               class="btn btn-sm btn-info">Send Test</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="6" class="text-center text-muted fst-italic">
          No campaigns found.
        </td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

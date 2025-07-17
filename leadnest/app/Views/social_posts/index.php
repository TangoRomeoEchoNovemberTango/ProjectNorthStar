<?php include __DIR__ . '/../../../shared/header.php'; ?>

<h1>Social Posts</h1>
<a href="<?= BASE_URL ?>/public/index.php?mod=social_posts&action=create"
   class="btn btn-primary mb-3">New Post</a>

<table class="table">
  <thead>
    <tr>
      <th>UUID</th><th>Content</th><th>Scheduled</th><th>Status</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($posts as $p): ?>
      <tr class="<?= $p->status !== 'pending' ? 'table-secondary' : '' ?>">
        <td><?= htmlspecialchars($p->uuid) ?></td>
        <td><?= htmlspecialchars($p->content) ?></td>
        <td><?= htmlspecialchars($p->scheduled_at) ?></td>
        <td><?= htmlspecialchars($p->status) ?></td>
        <td>
          <?php if ($p->status === 'pending'): ?>
            <a href="<?= BASE_URL ?>/public/index.php?mod=social_posts&action=edit&id=<?= $p->id ?>"
               class="btn btn-sm btn-secondary">Edit</a>
            <a href="<?= BASE_URL ?>/public/index.php?mod=social_posts&action=destroy&id=<?= $p->id ?>"
               class="btn btn-sm btn-danger"
               onclick="return confirm('Delete this scheduled post?');">Delete</a>
          <?php else: ?>
            <span class="text-muted">Locked</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

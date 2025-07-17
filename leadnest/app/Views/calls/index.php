<?php include __DIR__ . '/../../../shared/header.php'; ?>

<h1>Calls</h1>
<a href="<?= BASE_URL ?>/public/index.php?mod=calls&action=create"
   class="btn btn-primary mb-3">Log New Call</a>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Contact</th>
      <th>Call Time</th>
      <th>Duration</th>
      <th>Summary</th>
      <th>Outcome</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($calls as $c): ?>
      <tr>
        <td>
          <?= $c->contact
                ? htmlspecialchars("{$c->contact->first_name} {$c->contact->last_name}")
                : '<em>Unknown</em>' ?>
        </td>
        <td><?= htmlspecialchars($c->call_time) ?></td>
        <td><?= htmlspecialchars($c->duration) ?></td>
        <td><?= nl2br(htmlspecialchars($c->summary)) ?></td>
        <td><?= htmlspecialchars($c->outcome) ?></td>
        <td>
          <a href="<?= BASE_URL ?>/public/index.php?mod=calls&action=edit&id=<?= $c->id ?>"
             class="btn btn-sm btn-secondary">Edit</a>
          <a href="<?= BASE_URL ?>/public/index.php?mod=calls&action=destroy&id=<?= $c->id ?>"
             class="btn btn-sm btn-danger"
             onclick="return confirm('Delete this call?');">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

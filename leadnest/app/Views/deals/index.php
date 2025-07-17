<?php include __DIR__ . '/../../../shared/header.php'; ?>

<h1>Deals</h1>
<a href="<?= BASE_URL ?>/public/index.php?mod=deals&action=create"
   class="btn btn-primary mb-3">New Deal</a>

<table class="table table-striped">
  <thead>
    <tr>
      <th>UUID</th>
      <th>Contact</th>
      <th>Property</th>
      <th>Stage</th>
      <th>Amount</th>
      <th>Offer Date</th>
      <th>Close Date</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($deals as $d): ?>
      <tr>
        <td><?= htmlspecialchars($d->uuid) ?></td>
        <td><?= htmlspecialchars($d->contact_name) ?></td>
        <td><?= htmlspecialchars($d->property_address) ?></td>
        <td><?= htmlspecialchars(str_replace('_',' ', ucfirst($d->stage))) ?></td>
        <td><?= $d->amount!==null ? '$'.number_format($d->amount,2) : '' ?></td>
        <td><?= htmlspecialchars($d->offer_date ?? '') ?></td>
        <td><?= htmlspecialchars($d->close_date ?? '') ?></td>
        <td>
          <a href="<?= BASE_URL ?>/public/index.php?mod=deals&action=edit&id=<?= $d->id ?>"
             class="btn btn-sm btn-secondary">Edit</a>
          <a href="<?= BASE_URL ?>/public/index.php?mod=deals&action=destroy&id=<?= $d->id ?>"
             class="btn btn-sm btn-danger"
             onclick="return confirm('Remove this deal?');">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

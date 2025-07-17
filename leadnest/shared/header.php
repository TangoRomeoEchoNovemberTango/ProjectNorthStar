<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars(SITE_NAME) ?></title>

  <!-- Bootstrap 5 CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >

  <!-- Select2 core CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    rel="stylesheet"
  >

  <!-- Select2 Bootstrap-5 theme CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet"
  >
</head>
<body class="container py-4">

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
  <a class="navbar-brand" href="<?= BASE_URL ?>/public/index.php">
    <?= htmlspecialchars(SITE_NAME) ?>
  </a>
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="<?= BASE_URL ?>/public/index.php?mod=contacts&action=index">Contacts</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?= BASE_URL ?>/public/index.php?mod=calls&action=index">Calls</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?= BASE_URL ?>/public/index.php?mod=documents&action=index">Documents</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?= BASE_URL ?>/public/index.php?mod=email_campaigns&action=index">Email Campaigns</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?= BASE_URL ?>/public/index.php?mod=properties&action=index">Properties</a>
    </li>
  </ul>
</nav>

<?php if (isset($_SESSION['flash'])):
  $flash = $_SESSION['flash'];
  unset($_SESSION['flash']);
?>
  <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>" role="alert">
    <?= htmlspecialchars($flash['message']) ?>
  </div>
<?php endif; ?>

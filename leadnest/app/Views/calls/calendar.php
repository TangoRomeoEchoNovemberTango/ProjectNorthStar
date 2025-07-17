<?php include __DIR__ . '/../../../shared/header.php'; ?>

<link
  href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/index.global.min.css"
  rel="stylesheet"
/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/index.global.min.js">
</script>

<div id="calendar"></div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    new FullCalendar.Calendar(document.getElementById('calendar'), {
      initialView: 'dayGridMonth',
      events: 'https://theredsquid.com/leadnest/public/index.php?mod=calls&action=events'
    }).render();
  });
</script>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

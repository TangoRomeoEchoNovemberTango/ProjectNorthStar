<?php include __DIR__ . '/../../../shared/header.php'; ?>

<h1>Metrics Dashboard</h1>

<!-- CALL SUMMARY CARDS -->
<div class="row mb-4">
  <!-- Calls Today -->
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">Calls Today</h5>
        <p class="display-4">
          <?php
            $today = array_filter($dayStats,
              fn($r)=> $r['period'] === date('Y-m-d')
            );
            echo $today
              ? (int)$today[array_key_last($today)]['total']
              : 0;
          ?>
        </p>
      </div>
    </div>
  </div>
  <!-- Calls This Month -->
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">Calls This Month</h5>
        <p class="display-4">
          <?php
            $curMonth = date('Y-m');
            $m = array_filter($monthStats,
              fn($r)=> $r['period'] === $curMonth
            );
            echo $m
              ? (int)$m[array_key_last($m)]['total']
              : 0;
          ?>
        </p>
      </div>
    </div>
  </div>
  <!-- Calls This Year -->
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">Calls This Year</h5>
        <p class="display-4">
          <?php
            $curYear = date('Y');
            $y = array_filter($yearStats,
              fn($r)=> $r['period'] === $curYear
            );
            echo $y
              ? (int)$y[array_key_last($y)]['total']
              : 0;
          ?>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- CALL CHARTS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="row mb-5">
  <div class="col-12 mb-4">
    <canvas id="dailyChart"></canvas>
  </div>
  <div class="col-md-6 mb-4">
    <canvas id="monthlyChart"></canvas>
  </div>
  <div class="col-md-6 mb-4">
    <canvas id="yearlyChart"></canvas>
  </div>
</div>

<!-- DEAL SUMMARY CARDS -->
<div class="row mb-4">
  <!-- Deals Today -->
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">Deals Today</h5>
        <p class="display-4">
          <?php
            $today = array_filter($dealsDayStats,
              fn($r)=> $r['period'] === date('Y-m-d')
            );
            echo $today
              ? (int)$today[array_key_last($today)]['total']
              : 0;
          ?>
        </p>
      </div>
    </div>
  </div>
  <!-- Deals This Month -->
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">Deals This Month</h5>
        <p class="display-4">
          <?php
            $curMonth = date('Y-m');
            $m = array_filter($dealsMonthStats,
              fn($r)=> $r['period'] === $curMonth
            );
            echo $m
              ? (int)$m[array_key_last($m)]['total']
              : 0;
          ?>
        </p>
      </div>
    </div>
  </div>
  <!-- Deals This Year -->
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">Deals This Year</h5>
        <p class="display-4">
          <?php
            $curYear = date('Y');
            $y = array_filter($dealsYearStats,
              fn($r)=> $r['period'] === $curYear
            );
            echo $y
              ? (int)$y[array_key_last($y)]['total']
              : 0;
          ?>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- DEAL CHARTS -->
<div class="row">
  <div class="col-md-6 mb-4">
    <canvas id="dealsDailyChart"></canvas>
  </div>
  <div class="col-md-6 mb-4">
    <canvas id="dealsStageChart"></canvas>
  </div>
</div>

<script>
  // --- Calls Data for Chart.js ---
  const dayLabels   = <?= json_encode(array_column($dayStats,'period')) ?>;
  const dayTotals   = <?= json_encode(array_column($dayStats,'total')) ?>;
  const monthLabels = <?= json_encode(array_column($monthStats,'period')) ?>;
  const monthTotals = <?= json_encode(array_column($monthStats,'total')) ?>;
  const yearLabels  = <?= json_encode(array_column($yearStats,'period')) ?>;
  const yearTotals  = <?= json_encode(array_column($yearStats,'total')) ?>;

  // --- Deals Data for Chart.js ---
  const dealsDayLabels   = <?= json_encode(array_column($dealsDayStats,'period')) ?>;
  const dealsDayTotals   = <?= json_encode(array_column($dealsDayStats,'total')) ?>;
  const dealsStageLabels = <?= json_encode(array_column($dealsStageStats,'stage')) ?>;
  const dealsStageTotals = <?= json_encode(array_column($dealsStageStats,'total')) ?>;

  // Reusable line chart creator
  function createLineChart(ctxId, labels, data, title) {
    new Chart(document.getElementById(ctxId), {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: title,
          data,
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          fill: true,
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });
  }

  // Draw Charts
  createLineChart('dailyChart',   dayLabels,   dayTotals,   'Calls / Day');
  createLineChart('monthlyChart', monthLabels, monthTotals, 'Calls / Month');
  createLineChart('yearlyChart',  yearLabels,  yearTotals,  'Calls / Year');
  createLineChart('dealsDailyChart', dealsDayLabels, dealsDayTotals, 'Deals / Day');

  // Doughnut for Deals by Stage
  new Chart(document.getElementById('dealsStageChart'), {
    type: 'doughnut',
    data: {
      labels: dealsStageLabels,
      datasets: [{
        data: dealsStageTotals,
        backgroundColor: [
          '#36A2EB','#FF6384','#FFCE56',
          '#4BC0C0','#9966FF','#FF9F40'
        ]
      }]
    },
    options: { responsive: true }
  });
</script>

<?php include __DIR__ . '/../../../shared/footer.php'; ?>

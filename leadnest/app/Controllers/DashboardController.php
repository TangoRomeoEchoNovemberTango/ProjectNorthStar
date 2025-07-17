<?php
namespace App\Controllers;

use PDO;

class DashboardController
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function index(): void
    {
        // --- CALL METRICS ---

        // Calls per day (last 30 days)
        $stmt = $this->db->prepare("
            SELECT DATE(call_time) AS period, COUNT(*) AS total
              FROM calls
             WHERE call_time >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)
             GROUP BY DATE(call_time)
             ORDER BY DATE(call_time)
        ");
        $stmt->execute();
        $dayStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calls per month (last 12 months)
        $stmt = $this->db->prepare("
            SELECT DATE_FORMAT(call_time, '%Y-%m') AS period, COUNT(*) AS total
              FROM calls
             WHERE call_time >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
             GROUP BY DATE_FORMAT(call_time, '%Y-%m')
             ORDER BY DATE_FORMAT(call_time, '%Y-%m')
        ");
        $stmt->execute();
        $monthStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calls per year (all time)
        $stmt = $this->db->query("
            SELECT YEAR(call_time) AS period, COUNT(*) AS total
              FROM calls
             GROUP BY YEAR(call_time)
             ORDER BY YEAR(call_time)
        ");
        $yearStats = $stmt->fetchAll(PDO::FETCH_ASSOC);


        // --- DEALS METRICS ---

        // Deals per day (last 30 days)
        $stmt = $this->db->prepare("
            SELECT DATE(created_at) AS period, COUNT(*) AS total
              FROM deals
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)
             GROUP BY DATE(created_at)
             ORDER BY DATE(created_at)
        ");
        $stmt->execute();
        $dealsDayStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Deals per month (last 12 months)
        $stmt = $this->db->prepare("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS period, COUNT(*) AS total
              FROM deals
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
             GROUP BY DATE_FORMAT(created_at, '%Y-%m')
             ORDER BY DATE_FORMAT(created_at, '%Y-%m')
        ");
        $stmt->execute();
        $dealsMonthStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Deals per year (all time)
        $stmt = $this->db->query("
            SELECT YEAR(created_at) AS period, COUNT(*) AS total
              FROM deals
             GROUP BY YEAR(created_at)
             ORDER BY YEAR(created_at)
        ");
        $dealsYearStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Deals by current stage
        $stmt = $this->db->query("
            SELECT stage, COUNT(*) AS total
              FROM deals
             GROUP BY stage
        ");
        $dealsStageStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Render view with both Call and Deal data
        $this->view('dashboard/index', compact(
            'dayStats','monthStats','yearStats',
            'dealsDayStats','dealsMonthStats','dealsYearStats','dealsStageStats'
        ));
    }

    private function view(string $path, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        include __DIR__ . "/../Views/{$path}.php";
    }
}

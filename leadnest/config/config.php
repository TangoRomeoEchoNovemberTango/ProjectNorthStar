<?php
// config/config.php

// 1) Database connection via PDO
try {
    $pdo = new PDO(
        'mysql:host=50.6.109.204;dbname=kgcljxte_copilot;charset=utf8mb4',
        'kgcljxte_copilot',
        'txlPRU-I7u6P',
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    // On error, show message and stop
    exit('DB Connection failed: ' . $e->getMessage());
}

// 2) Base URL (no trailing slash)
define('BASE_URL', '/leadnest');

// 3) Site name
define('SITE_NAME', 'LeadNest');

<?php
namespace App\Controllers;

use PDO;

class Controller
{
    protected PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    protected function view(string $viewPath, array $data = []): void
    {
        extract($data);
        require __DIR__ . "/../Views/{$viewPath}.php";
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}

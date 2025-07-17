<?php
namespace App\Models;

use PDO;

class EmailCampaign
{
    public ?int    $id            = null;
    public ?string $title         = null;
    public ?string $description   = null;
    public ?string $campaign_type = null;
    public ?string $created_at    = null;

    private static PDO $db;

    public static function init(PDO $pdo): void
    {
        self::$db = $pdo;
    }

    public static function all(): array
    {
        $sql  = "
            SELECT id, title, description, campaign_type, created_at
            FROM email_campaigns
            ORDER BY created_at DESC
        ";
        $stmt = self::$db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function find(int $id): ?self
    {
        $sql  = "
            SELECT id, title, description, campaign_type, created_at
            FROM email_campaigns
            WHERE id = ?
            LIMIT 1
        ";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch() ?: null;
    }

    public function save(): void
    {
        if ($this->id === null) {
            $sql  = "
                INSERT INTO email_campaigns
                    (title, description, campaign_type)
                VALUES (?, ?, ?)
            ";
            $stmt = self::$db->prepare($sql);
            $stmt->execute([
                $this->title,
                $this->description,
                $this->campaign_type,
            ]);
            $this->id = (int) self::$db->lastInsertId();
        } else {
            $sql  = "
                UPDATE email_campaigns
                SET title         = ?,
                    description   = ?,
                    campaign_type = ?
                WHERE id = ?
            ";
            $stmt = self::$db->prepare($sql);
            $stmt->execute([
                $this->title,
                $this->description,
                $this->campaign_type,
                $this->id,
            ]);
        }
    }

    public function delete(): void
    {
        if ($this->id !== null) {
            $stmt = self::$db->prepare(
                "DELETE FROM email_campaigns WHERE id = ?"
            );
            $stmt->execute([$this->id]);
        }
    }
}

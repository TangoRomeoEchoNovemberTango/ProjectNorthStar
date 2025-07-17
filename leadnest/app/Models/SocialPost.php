<?php
namespace App\Models;

use PDO;

class SocialPost
{
    public ?int    $id           = null;
    public ?string $uuid         = null;
    public ?string $content      = null;
    public ?string $image_url    = null;
    public ?string $scheduled_at = null;
    public ?string $status       = null;
    public ?string $result       = null;
    public ?string $created_at   = null;
    public ?string $updated_at   = null;

    private static PDO $db;

    public static function init(PDO $pdo): void
    {
        self::$db = $pdo;
    }

    public static function all(): array
    {
        $stmt = self::$db->query(
            "SELECT * FROM social_posts ORDER BY scheduled_at DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function find(int $id): ?self
    {
        $stmt = self::$db->prepare(
            "SELECT * FROM social_posts WHERE id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch() ?: null;
    }

    public static function due(): array
    {
        $stmt = self::$db->prepare(
          "SELECT * FROM social_posts
            WHERE status='pending' AND scheduled_at <= NOW()
            ORDER BY scheduled_at ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public function save(): void
    {
        if ($this->id === null) {
            $this->uuid = substr(md5((string)microtime(true)), 0, 12);
            $stmt = self::$db->prepare(
              "INSERT INTO social_posts
                (uuid, content, image_url, scheduled_at, status)
               VALUES (?, ?, ?, ?, 'pending')"
            );
            $stmt->execute([
                $this->uuid,
                $this->content,
                $this->image_url,
                $this->scheduled_at,
            ]);
            $this->id = (int)self::$db->lastInsertId();
        } else {
            $stmt = self::$db->prepare(
              "UPDATE social_posts SET
                 content      = ?,
                 image_url    = ?,
                 scheduled_at = ?,
                 status       = ?,
                 result       = ?
               WHERE id = ?"
            );
            $stmt->execute([
                $this->content,
                $this->image_url,
                $this->scheduled_at,
                $this->status,
                $this->result,
                $this->id,
            ]);
        }
    }

    public function delete(): void
    {
        if ($this->id !== null) {
            $stmt = self::$db->prepare(
              "DELETE FROM social_posts WHERE id = ?"
            );
            $stmt->execute([$this->id]);
        }
    }
}

<?php
namespace App\Models;

use PDO;

class Property
{
    public ?int    $id          = null;
    public string  $uuid;               // NOT NULL in DB
    public ?int    $contact_id  = null;
    public string  $address     = '';
    public ?string $description = null;
    public ?string $motivation  = null;
    public string  $timeline    = '';
    public ?string $condition   = null;
    public float   $price       = 0.0;
    public float   $arv         = 0.0;
    public float   $mao         = 0.0;
    public string  $created_at  = '';

    private static PDO $db;

    public static function init(PDO $pdo): void
    {
        self::$db = $pdo;
    }

    public static function all(): array
    {
        $stmt = self::$db->query("
            SELECT * FROM properties
            ORDER BY created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function find(int $id): ?self
    {
        $stmt = self::$db->prepare("
            SELECT * FROM properties
            WHERE id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch() ?: null;
    }

    public function save(): void
    {
        if ($this->id === null) {
            $this->uuid = bin2hex(random_bytes(16));  // satisfy NOT NULL

            $stmt = self::$db->prepare("
                INSERT INTO properties
                  (uuid, contact_id, address, description, motivation,
                   timeline, `condition`, price, arv, mao)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $this->uuid,
                $this->contact_id,
                $this->address,
                $this->description,
                $this->motivation,
                $this->timeline,
                $this->condition,
                $this->price,
                $this->arv,
                $this->mao,
            ]);
            $this->id = (int) self::$db->lastInsertId();
        } else {
            $stmt = self::$db->prepare("
                UPDATE properties SET
                  contact_id  = ?,
                  address     = ?,
                  description = ?,
                  motivation  = ?,
                  timeline    = ?,
                  `condition` = ?,
                  price       = ?,
                  arv         = ?,
                  mao         = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $this->contact_id,
                $this->address,
                $this->description,
                $this->motivation,
                $this->timeline,
                $this->condition,
                $this->price,
                $this->arv,
                $this->mao,
                $this->id,
            ]);
        }
    }

    public function delete(): void
    {
        if ($this->id !== null) {
            $stmt = self::$db->prepare("
                DELETE FROM properties
                WHERE id = ?
            ");
            $stmt->execute([$this->id]);
        }
    }
}

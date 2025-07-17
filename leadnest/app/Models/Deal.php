<?php
namespace App\Models;

use PDO;

class Deal
{
    public ?int    $id          = null;
    public ?string $uuid        = null;
    public int     $contact_id;
    public int     $property_id;
    public string  $stage       = 'lead';
    public ?float  $amount      = null;
    public ?string $offer_date  = null;
    public ?string $close_date  = null;
    public ?string $notes       = null;
    public ?string $created_at  = null;
    public ?string $updated_at  = null;

    private static PDO $db;

    public static function init(PDO $pdo): void
    {
        self::$db = $pdo;
    }

    public static function all(): array
    {
        $stmt = self::$db->query("
            SELECT
              d.*,
              CONCAT_WS(' ',
                c.first_name,
                c.middle_name,
                c.last_name
              ) AS contact_name,
              p.address AS property_address
            FROM deals d
            JOIN contacts c ON d.contact_id = c.id
            JOIN properties p ON d.property_id = p.id
            ORDER BY d.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function find(int $id): ?self
    {
        $stmt = self::$db->prepare(
            "SELECT * FROM deals WHERE id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch() ?: null;
    }

    public function save(): void
    {
        if ($this->id === null) {
            $this->uuid = substr(md5((string)microtime(true)), 0, 12);
            $stmt = self::$db->prepare("
                INSERT INTO deals
                  (uuid, contact_id, property_id, stage,
                   amount, offer_date, close_date, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $this->uuid,
                $this->contact_id,
                $this->property_id,
                $this->stage,
                $this->amount,
                $this->offer_date,
                $this->close_date,
                $this->notes,
            ]);
            $this->id = (int) self::$db->lastInsertId();
        } else {
            $stmt = self::$db->prepare("
                UPDATE deals SET
                  contact_id  = ?,
                  property_id = ?,
                  stage       = ?,
                  amount      = ?,
                  offer_date  = ?,
                  close_date  = ?,
                  notes       = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $this->contact_id,
                $this->property_id,
                $this->stage,
                $this->amount,
                $this->offer_date,
                $this->close_date,
                $this->notes,
                $this->id,
            ]);
        }
    }

    public function delete(): void
    {
        if ($this->id !== null) {
            $stmt = self::$db->prepare("DELETE FROM deals WHERE id = ?");
            $stmt->execute([$this->id]);
        }
    }
}

<?php
namespace App\Models;

use PDO;
use App\Models\Property;

class Contact
{
    public ?int    $id             = null;
    public ?int    $user_id        = null;
    public ?string $first_name     = null;
    public ?string $middle_name    = null;
    public ?string $last_name      = null;
    public ?string $street_address = null;
    public ?string $city           = null;
    public ?string $state          = null;
    public ?string $zip            = null;
    public ?string $role           = null;
    public ?string $phone          = null;
    public ?string $email          = null;
    public ?string $notes          = null;
    public string  $type           = 'other';
    public ?int    $property_id    = null;
    public ?string $created_at     = null;

    private static PDO $db;

    public static function init(PDO $pdo): void
    {
        self::$db = $pdo;
    }

    public static function all(): array
    {
        $stmt = self::$db->prepare("
            SELECT * FROM contacts
            WHERE user_id = ?
            ORDER BY last_name, first_name
        ");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function find(int $id): ?self
    {
        $stmt = self::$db->prepare("
            SELECT * FROM contacts
            WHERE id = ? AND user_id = ?
            LIMIT 1
        ");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch() ?: null;
    }

    public function save(): void
    {
        if ($this->id === null) {
            $stmt = self::$db->prepare("
                INSERT INTO contacts
                  (user_id, first_name, middle_name, last_name,
                   street_address, city, state, zip, role,
                   phone, email, notes, type, property_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $this->user_id,
                $this->first_name,
                $this->middle_name,
                $this->last_name,
                $this->street_address,
                $this->city,
                $this->state,
                $this->zip,
                $this->role,
                $this->phone,
                $this->email,
                $this->notes,
                $this->type,
                $this->property_id,
            ]);
            $this->id = (int) self::$db->lastInsertId();
        } else {
            $stmt = self::$db->prepare("
                UPDATE contacts SET
                  first_name     = ?,
                  middle_name    = ?,
                  last_name      = ?,
                  street_address = ?,
                  city           = ?,
                  state          = ?,
                  zip            = ?,
                  role           = ?,
                  phone          = ?,
                  email          = ?,
                  notes          = ?,
                  type           = ?,
                  property_id    = ?
                WHERE id = ? AND user_id = ?
            ");
            $stmt->execute([
                $this->first_name,
                $this->middle_name,
                $this->last_name,
                $this->street_address,
                $this->city,
                $this->state,
                $this->zip,
                $this->role,
                $this->phone,
                $this->email,
                $this->notes,
                $this->type,
                $this->property_id,
                $this->id,
                $this->user_id,
            ]);
        }
    }

    public function delete(): void
    {
        if ($this->id !== null) {
            $stmt = self::$db->prepare("
                DELETE FROM contacts
                WHERE id = ? AND user_id = ?
            ");
            $stmt->execute([$this->id, $this->user_id]);
        }
    }

    /**
     * Fetch all properties assigned to this contact.
     */
    public function properties(): array
    {
        $stmt = self::$db->prepare("
            SELECT * FROM properties
            WHERE contact_id = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Property::class);
    }
}

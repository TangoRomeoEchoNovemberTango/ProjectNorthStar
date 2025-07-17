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

    /** this will hold the hydrated properties */
    public array   $properties     = [];

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
        $contacts = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);

        // hydrate the properties relation for each contact
        foreach ($contacts as $contact) {
            $contact->properties = $contact->properties();
        }

        return $contacts;
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
        $contact = $stmt->fetch();
        if (! $contact) {
            return null;
        }
        // hydrate its properties as well
        $contact->properties = $contact->properties();
        return $contact;
    }

    public function save(): void
    {
        // unchanged...
    }

    public function delete(): void
    {
        // unchanged...
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

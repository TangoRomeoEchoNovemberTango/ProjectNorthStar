<?php
namespace App\Models;

use App\Models\Property;
use PDO;

class Contact extends \Core\Model  // adjust this to your actual base Model namespace
{
    protected static $table = 'contacts';
    protected static $primaryKey = 'id';

    // ... any other properties or methods ...

    /**
     * Load properties via the contact_property pivot table.
     */
    protected function loadProperties(): array
    {
        $sql = "
            SELECT p.*
            FROM properties AS p
            INNER JOIN contact_property AS cp
              ON cp.property_id = p.id
            WHERE cp.contact_id = ?
            ORDER BY p.address
        ";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([$this->id]);

        return $stmt->fetchAll(PDO::FETCH_CLASS, Property::class);
    }

    // ... any other properties or methods ...
}

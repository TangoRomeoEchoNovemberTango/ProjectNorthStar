<?php
namespace App\Models;

use PDO;

class PropertyDocument
{
    public ?int    $id          = null;
    public int     $property_id;
    public string  $file_name;
    public string  $mime_type;
    public string  $file_data;
    public string  $created_at;

    private static PDO $db;

    public static function init(PDO $pdo): void
    {
        self::$db = $pdo;
    }

    public static function getByProperty(int $pid): array
    {
        $stmt = self::$db->prepare(
            "SELECT * 
               FROM property_documents 
              WHERE property_id = ? 
           ORDER BY created_at DESC"
        );
        $stmt->execute([$pid]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function find(int $id): ?self
    {
        $stmt = self::$db->prepare(
            "SELECT * 
               FROM property_documents 
              WHERE id = ? 
              LIMIT 1"
        );
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch() ?: null;
    }

    public function save(): void
    {
        $stmt = self::$db->prepare(
            "INSERT INTO property_documents
               (property_id, file_name, mime_type, file_data)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $this->property_id,
            $this->file_name,
            $this->mime_type,
            $this->file_data,
        ]);
        $this->id = (int) self::$db->lastInsertId();
    }

    public function delete(): void
    {
        if ($this->id !== null) {
            $stmt = self::$db->prepare(
                "DELETE FROM property_documents WHERE id = ?"
            );
            $stmt->execute([$this->id]);
        }
    }
}

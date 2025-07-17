<?php
namespace App\Models;

use PDO;

class Document
{
    public ?int    $id          = null;
    public ?string $title       = null;
    public ?string $description = null;
    public ?string $file_name   = null;
    public ?string $file_type   = null;
    public ?int    $file_size   = null;
    public         $file_data   = null;
    public ?string $created_at  = null;

    private static PDO $db;

    public static function init(PDO $pdo): void
    {
        self::$db = $pdo;
    }

    /**
     * Fetch all documents (no BLOB data)
     */
    public static function all(): array
    {
        $sql  = "
            SELECT
                id,
                title,
                description,
                file_name,
                file_type,
                file_size,
                created_at
              FROM documents
          ORDER BY created_at DESC
        ";
        $stmt = self::$db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Fetch one document, including the BLOB
     */
    public static function find(int $id): ?self
    {
        $sql  = "
            SELECT
                id,
                title,
                description,
                file_name,
                file_type,
                file_size,
                file_data,
                created_at
              FROM documents
             WHERE id = ?
             LIMIT 1
        ";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch() ?: null;
    }

    /**
     * Insert new document (BLOB)
     */
    public function save(): void
    {
        if (empty($this->id)) {
            $stmt = self::$db->prepare("
                INSERT INTO documents
                    (title, description, file_name, file_type, file_size, file_data)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $this->title,
                $this->description,
                $this->file_name,
                $this->file_type,
                $this->file_size,
                $this->file_data,
            ]);
            $this->id = (int) self::$db->lastInsertId();
        }
    }

    /**
     * Delete a document by ID
     */
    public function delete(): void
    {
        if ($this->id) {
            $stmt = self::$db->prepare("DELETE FROM documents WHERE id = ?");
            $stmt->execute([$this->id]);
        }
    }
}
?>

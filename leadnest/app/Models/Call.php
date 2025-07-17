<?php
namespace App\Models;

use PDO;
use App\Models\Contact;

class Call
{
    public ?int     $id          = null;
    public ?int     $user_id     = null;
    public ?int     $contact_id  = null;
    public ?string  $call_time   = null;
    public ?int     $duration    = null;
    public ?string  $summary     = null;
    public ?string  $outcome     = null;
    public ?string  $created_at  = null;
    public ?string  $updated_at  = null;

    // hydrated relation
    public ?Contact $contact     = null;

    private static PDO $db;

    public static function init(PDO $pdo): void
    {
        self::$db = $pdo;
    }

    public static function all(): array
    {
        $stmt = self::$db->prepare("
            SELECT * FROM calls
            WHERE user_id = ?
            ORDER BY call_time DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $calls = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);

        // hydrate each call's Contact
        foreach ($calls as $call) {
            $call->contact = Contact::find((int)$call->contact_id);
        }

        return $calls;
    }

    public static function find(int $id): ?self
    {
        $stmt = self::$db->prepare("
            SELECT * FROM calls
            WHERE id = ? AND user_id = ?
            LIMIT 1
        ");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $call = $stmt->fetch();
        if (! $call) {
            return null;
        }

        // hydrate its Contact
        $call->contact = Contact::find((int)$call->contact_id);
        return $call;
    }

    public function save(): void
    {
        if ($this->id === null) {
            $stmt = self::$db->prepare("
                INSERT INTO calls
                  (user_id, contact_id, call_time, duration, summary, outcome)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $this->user_id,
                $this->contact_id,
                $this->call_time,
                $this->duration,
                $this->summary,
                $this->outcome,
            ]);
            $this->id = (int) self::$db->lastInsertId();
        } else {
            $stmt = self::$db->prepare("
                UPDATE calls SET
                  contact_id = ?,
                  call_time  = ?,
                  duration   = ?,
                  summary    = ?,
                  outcome    = ?
                WHERE id = ? AND user_id = ?
            ");
            $stmt->execute([
                $this->contact_id,
                $this->call_time,
                $this->duration,
                $this->summary,
                $this->outcome,
                $this->id,
                $this->user_id,
            ]);
        }
    }

    public function delete(): void
    {
        if ($this->id !== null) {
            $stmt = self::$db->prepare("
                DELETE FROM calls
                WHERE id = ? AND user_id = ?
            ");
            $stmt->execute([$this->id, $this->user_id]);
        }
    }
}

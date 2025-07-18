<?php
namespace App\Controllers;

use App\Models\Property;
use PDO;

class PropertiesController extends Controller
{
    public function index(): void
    {
        $properties = Property::all();
        $this->view('properties/index', compact('properties'));
    }

    public function create(): void
    {
        $contacts = Contact::all();
        $this->view('properties/form', compact('contacts'));
    }

    public function store(): void
    {
        $p = new Property();
        $cid = $_POST['contact_id'] ?? '';
        $p->contact_id = ($cid === '') ? null : (int)$cid;
        $p->address     = trim($_POST['address']    ?? '');
        $p->description = trim($_POST['description']?? '');
        $p->motivation  = trim($_POST['motivation'] ?? '');
        $p->timeline    = trim($_POST['timeline']   ?? '');
        $p->condition   = trim($_POST['condition']  ?? '');
        $p->price       = (float)($_POST['price']   ?? 0);
        $p->arv         = (float)($_POST['arv']     ?? 0);
        $p->mao         = (float)($_POST['mao']     ?? 0);
        $p->save();

        $_SESSION['flash'] = ['type'=>'success','message'=>'Property added'];
        $this->redirect(BASE_URL . '/public/index.php?mod=properties&action=index');
    }

    public function edit(int $id): void
    {
        $property = Property::find($id);
        if (! $property) {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Not found'];
            $this->redirect(BASE_URL . '/public/index.php?mod=properties&action=index');
            return;
        }
        $contacts = Contact::all();
        $this->view('properties/form', compact('property','contacts'));
    }

    public function update(int $id): void
    {
        $p = Property::find($id);
        if (! $p) {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Not found'];
            $this->redirect(BASE_URL . '/public/index.php?mod=properties&action=index');
            return;
        }
        $cid = $_POST['contact_id'] ?? '';
        $p->contact_id = ($cid === '') ? null : (int)$cid;
        $p->address     = trim($_POST['address']    ?? $p->address);
        $p->description = trim($_POST['description']?? $p->description);
        $p->motivation  = trim($_POST['motivation'] ?? $p->motivation);
        $p->timeline    = trim($_POST['timeline']   ?? $p->timeline);
        $p->condition   = trim($_POST['condition']  ?? $p->condition);
        $p->price       = (float)($_POST['price']   ?? $p->price);
        $p->arv         = (float)($_POST['arv']     ?? $p->arv);
        $p->mao         = (float)($_POST['mao']     ?? $p->mao);
        $p->save();

        $_SESSION['flash'] = ['type'=>'success','message'=>'Property updated'];
        $this->redirect(
            BASE_URL . "/public/index.php?mod=properties&action=edit&id={$p->id}"
        );
    }

    public function destroy(int $id): void
    {
        $p = Property::find($id);
        if ($p) {
            $p->delete();
            $_SESSION['flash'] = ['type'=>'danger','message'=>'Property removed'];
        }
        $this->redirect(BASE_URL . '/public/index.php?mod=properties&action=index');
    }

    /**
     * AJAX search: exclude already‐linked properties for this contact.
     */
    public function searchAjax(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $q          = trim($_GET['q'] ?? '');
        $contact_id = (int)($_GET['contact_id'] ?? 0);

        $sql = "
            SELECT id, address
            FROM properties AS p
            WHERE p.address LIKE ?
              AND NOT EXISTS (
                SELECT 1
                FROM contact_property AS cp
                WHERE cp.contact_id  = ?
                  AND cp.property_id = p.id
              )
            ORDER BY p.address
            LIMIT 20
        ";

        $stmt = Property::$db->prepare($sql);
        $stmt->execute(["%{$q}%", $contact_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $results = array_map(fn($r) => [
            'id'   => $r['id'],
            'text' => $r['address']
        ], $rows);

        echo json_encode(['results'=>$results]);
        exit;
    }

    /**
     * AJAX assign: insert into pivot, ignore duplicates.
     */
    public function assignAjax(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $payload = json_decode(file_get_contents('php://input'), true);
            $cid     = (int)($payload['contact_id'] ?? 0);
            $ids     = $payload['property_ids'] ?? [];
            if (! $cid || ! is_array($ids)) {
                throw new \Exception('Invalid input');
            }

            $db  = Property::$db;
            $ins = $db->prepare("
                INSERT IGNORE INTO contact_property
                  (contact_id, property_id)
                VALUES (?, ?)
            ");

            $assigned = [];
            foreach ($ids as $pid) {
                $ins->execute([$cid, (int)$pid]);
                $row = $db
                  ->query("SELECT address FROM properties WHERE id=".(int)$pid)
                  ->fetch(PDO::FETCH_ASSOC);
                if ($row && isset($row['address'])) {
                    $assigned[] = ['id'=>(int)$pid,'address'=>$row['address']];
                }
            }

            echo json_encode(['assigned'=>$assigned]);
        } catch (\Throwable $e) {
            http_response_code(422);
            echo json_encode(['error'=>$e->getMessage()]);
        }
        exit;
    }

    /**
     * AJAX unassign: remove link from pivot.
     */
    public function unassignAjax(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $pid = (int)($_POST['id'] ?? 0);
            $cid = (int)($_POST['contact_id'] ?? 0);
            if (! $pid || ! $cid) {
                throw new \Exception('Missing IDs');
            }

            $stmt = Property::$db->prepare("
                DELETE FROM contact_property
                WHERE property_id = ? AND contact_id = ?
            ");
            $stmt->execute([$pid, $cid]);

            echo json_encode(['success'=>true]);
        } catch (\Throwable $e) {
            http_response_code(422);
            echo json_encode(['error'=>$e->getMessage()]);
        }
        exit;
    }
}

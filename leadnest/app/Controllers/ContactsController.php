<?php
namespace App\Controllers;

use App\Models\Contact;
use App\Models\Property;

class ContactsController extends Controller
{
    public function index(): void
    {
        $contacts = Contact::all();
        $this->view('contacts/index', compact('contacts'));
    }

    public function create(): void
    {
        $properties = Property::all();
        $this->view('contacts/form', compact('properties'));
    }

    public function store(): void
    {
        $c = new Contact();
        $c->user_id     = $_SESSION['user_id'];
        $c->first_name  = trim($_POST['first_name']   ?? '');
        $c->middle_name = trim($_POST['middle_name']  ?? '');
        $c->last_name   = trim($_POST['last_name']    ?? '');
        $c->email       = trim($_POST['email']        ?? '');
        $c->phone       = trim($_POST['phone']        ?? '');
        $c->type        = $_POST['type']              ?? 'other';
        $c->notes       = trim($_POST['notes']        ?? '');
        $c->save();

        $_SESSION['flash'] = ['type'=>'success','message'=>'Contact added'];
        $this->redirect(BASE_URL . '/public/index.php?mod=contacts&action=index');
    }

    public function edit(int $id): void
    {
        $contact = Contact::find($id);
        if (! $contact) {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Contact not found'];
            $this->redirect(BASE_URL . '/public/index.php?mod=contacts&action=index');
            return;
        }

        $properties = $contact->properties();
        $this->view('contacts/form', compact('contact','properties'));
    }

    public function update(int $id): void
    {
        $c = Contact::find($id);
        if (! $c) {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Contact not found'];
            $this->redirect(BASE_URL . '/public/index.php?mod=contacts&action=index');
            return;
        }

        $c->user_id     = $_SESSION['user_id'];
        $c->first_name  = trim($_POST['first_name']   ?? $c->first_name);
        $c->middle_name = trim($_POST['middle_name']  ?? $c->middle_name);
        $c->last_name   = trim($_POST['last_name']    ?? $c->last_name);
        $c->email       = trim($_POST['email']        ?? $c->email);
        $c->phone       = trim($_POST['phone']        ?? $c->phone);
        $c->type        = $_POST['type']              ?? $c->type;
        $c->notes       = trim($_POST['notes']        ?? $c->notes);
        $c->save();

        $_SESSION['flash'] = ['type'=>'success','message'=>'Contact updated'];
        $this->redirect(
            BASE_URL . "/public/index.php?mod=contacts&action=edit&id={$c->id}"
        );
    }

    public function destroy(int $id): void
    {
        $c = Contact::find($id);
        if ($c) {
            $c->delete();
            $_SESSION['flash'] = ['type'=>'danger','message'=>'Contact removed'];
        }
        $this->redirect(BASE_URL . '/public/index.php?mod=contacts&action=index');
    }

    /**
     * NEW: AJAX endpoint that your call‐form script posts to.
     * Returns {"id":123,"label":"First Last"}.
     */
    public function storeAjax(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $c = new Contact();
            $c->user_id     = $_SESSION['user_id'] ?? 1;
            $c->first_name  = trim($_POST['first_name']   ?? '');
            $c->middle_name = trim($_POST['middle_name']  ?? '');
            $c->last_name   = trim($_POST['last_name']    ?? '');
            $c->email       = trim($_POST['email']        ?? '');
            $c->phone       = trim($_POST['phone']        ?? '');
            $c->type        = $_POST['type']              ?? 'other';
            $c->notes       = trim($_POST['notes']        ?? '');
            $c->save();

            echo json_encode([
                'id'    => $c->id,
                'label' => trim("{$c->first_name} {$c->last_name}")
            ]);
        } catch (\Throwable $e) {
            http_response_code(422);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    // your existing AJAX search
    public function searchAjax(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $q = trim($_GET['q'] ?? '');
        $stmt = Contact::$db->prepare("
            SELECT id, first_name, last_name
            FROM contacts
            WHERE user_id = ?
              AND (
                first_name LIKE ?
                OR last_name LIKE ?
                OR CONCAT(first_name,' ',last_name) LIKE ?
              )
            ORDER BY last_name, first_name
            LIMIT 20
        ");
        $like = "%{$q}%";
        $stmt->execute([
            $_SESSION['user_id'],
            $like, $like, $like
        ]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $results = array_map(fn($r) => [
            'id'   => $r['id'],
            'text' => trim($r['first_name'].' '.$r['last_name'])
        ], $rows);

        echo json_encode(['results'=>$results]);
        exit;
    }
}
?>

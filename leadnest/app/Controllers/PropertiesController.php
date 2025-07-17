<?php
namespace App\Controllers;

use App\Models\Property;
use App\Models\Contact;

class PropertiesController extends Controller
{
    /**
     * Show list of properties
     */
    public function index(): void
    {
        $properties = Property::all();
        $this->view('properties/index', compact('properties'));
    }

    /**
     * Show blank form to create a property
     */
    public function create(): void
    {
        $contacts = Contact::all();
        $this->view('properties/form', compact('contacts'));
    }

    /**
     * Persist a new property
     */
    public function store(): void
    {
        $p = new Property();
        $p->contact_id  = (int) ($_POST['contact_id']  ?? 0);
        $p->address     = trim($_POST['address']     ?? '');
        $p->description = trim($_POST['description'] ?? null);
        $p->motivation  = trim($_POST['motivation']  ?? null);
        $p->timeline    = trim($_POST['timeline']    ?? '');
        $p->condition   = trim($_POST['condition']   ?? null);
        $p->price       = (float) ($_POST['price']   ?? 0);
        $p->arv         = (float) ($_POST['arv']     ?? 0);
        $p->mao         = (float) ($_POST['mao']     ?? 0);
        $p->save();

        $_SESSION['flash'] = [
            'type'    => 'success',
            'message' => 'Property created'
        ];
        $this->redirect(BASE_URL . '/public/index.php?mod=properties&action=index');
    }

    /**
     * Show form to edit a property
     */
    public function edit(int $id): void
    {
        $p = Property::find($id);
        if (! $p) {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Property not found'];
            $this->redirect(BASE_URL . '/public/index.php?mod=properties&action=index');
            return;
        }
        $contacts = Contact::all();
        $this->view('properties/form', compact('p','contacts'));
    }

    /**
     * Update an existing property
     */
    public function update(int $id): void
    {
        $p = Property::find($id);
        if (! $p) {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Property not found'];
            $this->redirect(BASE_URL . '/public/index.php?mod=properties&action=index');
            return;
        }
        $p->contact_id  = (int) ($_POST['contact_id']  ?? $p->contact_id);
        $p->address     = trim($_POST['address']     ?? $p->address);
        $p->description = trim($_POST['description'] ?? $p->description);
        $p->motivation  = trim($_POST['motivation']  ?? $p->motivation);
        $p->timeline    = trim($_POST['timeline']    ?? $p->timeline);
        $p->condition   = trim($_POST['condition']   ?? $p->condition);
        $p->price       = (float) ($_POST['price']   ?? $p->price);
        $p->arv         = (float) ($_POST['arv']     ?? $p->arv);
        $p->mao         = (float) ($_POST['mao']     ?? $p->mao);
        $p->save();

        $_SESSION['flash'] = [
            'type'    => 'success',
            'message' => 'Property updated'
        ];
        $this->redirect(
            BASE_URL . "/public/index.php?mod=properties&action=edit&id={$p->id}"
        );
    }

    /**
     * Delete a property
     */
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
     * AJAX: create a property for a contact
     */
    public function storeAjax(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $p = new Property();
            $p->contact_id  = (int) ($_POST['contact_id'] ?? 0);
            $p->address     = trim($_POST['address']     ?? '');
            $p->description = trim($_POST['description'] ?? '');
            $p->motivation  = trim($_POST['motivation']  ?? '');
            $p->timeline    = trim($_POST['timeline']    ?? '');
            $p->condition   = trim($_POST['condition']   ?? '');
            $p->price       = (float) ($_POST['price']   ?? 0);
            $p->arv         = (float) ($_POST['arv']     ?? 0);
            $p->mao         = (float) ($_POST['mao']     ?? 0);
            $p->save();

            echo json_encode(['id'=>$p->id,'address'=>$p->address]);
        } catch (\Throwable $e) {
            http_response_code(422);
            echo json_encode(['error'=>$e->getMessage()]);
        }
        exit;
    }

    /**
     * AJAX: delete a property by ID
     */
    public function deleteAjax(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $id = (int) ($_POST['id'] ?? 0);
            $p  = Property::find($id);
            if ($p) $p->delete();
            echo json_encode(['success'=>true]);
        } catch (\Throwable $e) {
            http_response_code(422);
            echo json_encode(['error'=>$e->getMessage()]);
        }
        exit;
    }
}

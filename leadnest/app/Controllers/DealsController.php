<?php
namespace App\Controllers;

use App\Models\Deal;
use App\Models\Contact;
use App\Models\Property;

class DealsController extends Controller
{
    public function index(): void
    {
        $deals = Deal::all();
        $this->view('deals/index', compact('deals'));
    }

    public function create(): void
    {
        $contacts   = Contact::all();
        $properties = Property::all();
        $stages     = [
          'lead','offer_made','under_contract',
          'due_diligence','closed','lost'
        ];
        $this->view('deals/form', compact('contacts','properties','stages'));
    }

    public function store(): void
    {
        $d = new Deal();
        $d->contact_id  = (int)($_POST['contact_id']  ?? 0);
        $d->property_id = (int)($_POST['property_id'] ?? 0);
        $d->stage       = $_POST['stage']            ?? 'lead';
        $d->amount      = $_POST['amount'] !== ''
                          ? (float)$_POST['amount']
                          : null;
        $d->offer_date  = $_POST['offer_date'] ?: null;
        $d->close_date  = $_POST['close_date'] ?: null;
        $d->notes       = trim($_POST['notes'] ?? '');
        $d->save();

        $_SESSION['flash'] = [
          'type'    => 'success',
          'message' => 'Deal created'
        ];
        $this->redirect(BASE_URL . '/public/index.php?mod=deals&action=index');
    }

    public function edit(int $id): void
    {
        $d = Deal::find($id);
        if (! $d) {
            $_SESSION['flash'] = [
              'type'    => 'warning',
              'message' => 'Deal not found'
            ];
            $this->redirect(BASE_URL . '/public/index.php?mod=deals&action=index');
            return;
        }

        $contacts   = Contact::all();
        $properties = Property::all();
        $stages     = [
          'lead','offer_made','under_contract',
          'due_diligence','closed','lost'
        ];
        $this->view('deals/form', compact('d','contacts','properties','stages'));
    }

    public function update(int $id): void
    {
        $d = Deal::find($id);
        if (! $d) {
            $_SESSION['flash'] = [
              'type'    => 'warning',
              'message' => 'Deal not found'
            ];
            $this->redirect(BASE_URL . '/public/index.php?mod=deals&action=index');
            return;
        }

        $d->contact_id  = (int)($_POST['contact_id']  ?? $d->contact_id);
        $d->property_id = (int)($_POST['property_id'] ?? $d->property_id);
        $d->stage       = $_POST['stage']            ?? $d->stage;
        $d->amount      = $_POST['amount'] !== ''
                          ? (float)$_POST['amount']
                          : $d->amount;
        $d->offer_date  = $_POST['offer_date'] ?: $d->offer_date;
        $d->close_date  = $_POST['close_date'] ?: $d->close_date;
        $d->notes       = trim($_POST['notes'] ?? $d->notes);
        $d->save();

        $_SESSION['flash'] = [
          'type'    => 'success',
          'message' => 'Deal updated'
        ];
        $this->redirect(
          BASE_URL . "/public/index.php?mod=deals&action=edit&id={$d->id}"
        );
    }

    public function destroy(int $id): void
    {
        $d = Deal::find($id);
        if ($d) {
            $d->delete();
            $_SESSION['flash'] = [
              'type'    => 'danger',
              'message' => 'Deal removed'
            ];
        }
        $this->redirect(BASE_URL . '/public/index.php?mod=deals&action=index');
    }
}

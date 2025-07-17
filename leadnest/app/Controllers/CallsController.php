<?php
namespace App\Controllers;

use App\Models\Call;
use App\Models\Contact;
use App\Models\Property;

class CallsController extends Controller
{
    public function index(): void
    {
        $calls = Call::all();
        $this->view('calls/index', compact('calls'));
    }

    public function create(): void
    {
        $contacts   = Contact::all();
        $properties = Property::all();   // pass properties into the view
        $this->view('calls/form', compact('contacts','properties'));
    }

    public function store(): void
    {
        $call = new Call();
        $call->user_id    = $_SESSION['user_id'];
        $call->contact_id = (int)($_POST['contact_id'] ?? 0);
        $call->call_time  = trim($_POST['call_time']  ?? '');
        $call->duration   = (int)($_POST['duration']  ?? 0);
        $call->summary    = trim($_POST['summary']   ?? '');
        $call->outcome    = trim($_POST['outcome']   ?? '');
        $call->save();

        $_SESSION['flash'] = ['type'=>'success','message'=>'Call logged'];
        $this->redirect(BASE_URL . '/public/index.php?mod=calls&action=index');
    }

    public function edit(int $id): void
    {
        $call       = Call::find($id);
        $contacts   = Contact::all();
        $properties = Property::all();
        if (! $call) {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Call not found'];
            $this->redirect(BASE_URL . '/public/index.php?mod=calls&action=index');
            return;
        }
        $this->view('calls/form', compact('call','contacts','properties'));
    }

    public function update(int $id): void
    {
        $call = Call::find($id);
        if (! $call) {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Call not found'];
            $this->redirect(BASE_URL . '/public/index.php?mod=calls&action=index');
            return;
        }
        $call->contact_id = (int)($_POST['contact_id'] ?? $call->contact_id);
        $call->call_time  = trim($_POST['call_time']  ?? $call->call_time);
        $call->duration   = (int)($_POST['duration']  ?? $call->duration);
        $call->summary    = trim($_POST['summary']   ?? $call->summary);
        $call->outcome    = trim($_POST['outcome']   ?? $call->outcome);
        $call->save();

        $_SESSION['flash'] = ['type'=>'success','message'=>'Call updated'];
        $this->redirect(
            BASE_URL . "/public/index.php?mod=calls&action=edit&id={$call->id}"
        );
    }

    public function destroy(int $id): void
    {
        $call = Call::find($id);
        if ($call) {
            $call->delete();
            $_SESSION['flash'] = ['type'=>'danger','message'=>'Call removed'];
        }
        $this->redirect(BASE_URL . '/public/index.php?mod=calls&action=index');
    }
}

<?php
namespace App\Controllers;

use App\Models\EmailCampaign;

class EmailCampaignsController extends Controller
{
    public function index(): void
    {
        $campaigns = EmailCampaign::all();
        $this->view('email_campaigns/index', compact('campaigns'));
    }

    public function create(): void
    {
        $this->view('email_campaigns/form');
    }

    public function store(): void
    {
        $errors      = [];
        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $type        = trim($_POST['campaign_type'] ?? '');

        if ($title === '')      $errors['title']       = 'Title is required';
        if ($description === '') $errors['description'] = 'Description is required';

        if ($errors) {
            // re-render form with errors and old input
            $this->view('email_campaigns/form', compact('errors','title','description','type'));
            return;
        }

        $c = new EmailCampaign();
        $c->title         = $title;
        $c->description   = $description;
        $c->campaign_type = $type;
        $c->save();

        $_SESSION['flash'] = ['type'=>'success','message'=>'Campaign created'];
        $this->redirect(BASE_URL . '/public/index.php?mod=email_campaigns&action=index');
    }

    public function edit(int $id): void
    {
        $campaign = EmailCampaign::find($id);
        if (! $campaign) {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Campaign not found'];
            $this->redirect(BASE_URL . '/public/index.php?mod=email_campaigns&action=index');
        }
        $this->view('email_campaigns/form', compact('campaign'));
    }

    public function update(int $id): void
    {
        $c = EmailCampaign::find($id);
        if (! $c) {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Campaign not found'];
            $this->redirect(BASE_URL . '/public/index.php?mod=email_campaigns&action=index');
        }

        $errors      = [];
        $title       = trim($_POST['title'] ?? $c->title);
        $description = trim($_POST['description'] ?? $c->description);
        $type        = trim($_POST['campaign_type'] ?? $c->campaign_type);

        if ($title === '')      $errors['title']       = 'Title is required';
        if ($description === '') $errors['description'] = 'Description is required';

        if ($errors) {
            $this->view('email_campaigns/form', compact('errors','c','title','description','type'));
            return;
        }

        $c->title         = $title;
        $c->description   = $description;
        $c->campaign_type = $type;
        $c->save();

        $_SESSION['flash'] = ['type'=>'success','message'=>'Campaign updated'];
        $this->redirect(BASE_URL . '/public/index.php?mod=email_campaigns&action=index');
    }

    public function destroy(int $id): void
    {
        $c = EmailCampaign::find($id);
        if ($c) {
            $c->delete();
            $_SESSION['flash'] = ['type'=>'danger','message'=>'Campaign deleted'];
        } else {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Campaign not found'];
        }
        $this->redirect(BASE_URL . '/public/index.php?mod=email_campaigns&action=index');
    }

    public function send_test(int $id): void
    {
        $c = EmailCampaign::find($id);
        if (! $c) {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Campaign not found'];
        } else {
            // simple mail() fallback
            $sent = mail('test@yourdomain.com', $c->title, $c->description, 'From: no-reply@yourdomain.com');
            $_SESSION['flash'] = $sent
                ? ['type'=>'info','message'=>'Test email sent']
                : ['type'=>'danger','message'=>'Test email failed'];
        }
        $this->redirect(BASE_URL . '/public/index.php?mod=email_campaigns&action=index');
    }
}

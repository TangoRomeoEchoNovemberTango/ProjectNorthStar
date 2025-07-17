<?php
namespace App\Controllers;

use App\Models\SocialPost;
use App\Services\SocialMediaService;

class SocialPostsController extends Controller
{
    private array $socialConfig;

    public function __construct($pdo, array $socialConfig)
    {
        parent::__construct($pdo);
        $this->socialConfig = $socialConfig;
    }

    public function index(): void
    {
        $posts = SocialPost::all();
        $this->view('social_posts/index', compact('posts'));
    }

    public function create(): void
    {
        $this->view('social_posts/form');
    }

    public function store(): void
    {
        $p = new SocialPost();
        $p->content      = trim($_POST['content'] ?? '');
        $p->image_url    = trim($_POST['image_url'] ?? '');
        $p->scheduled_at = $_POST['scheduled_at'] ?? '';
        $p->save();

        $_SESSION['flash'] = ['type'=>'success','message'=>'Post scheduled'];
        $this->redirect(BASE_URL . '/public/index.php?mod=social_posts&action=index');
    }

    public function edit(int $id): void
    {
        $post = SocialPost::find($id);
        if (! $post || $post->status !== 'pending') {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Cannot edit this post'];
            $this->redirect(BASE_URL . '/public/index.php?mod=social_posts&action=index');
        }
        $this->view('social_posts/form', compact('post'));
    }

    public function update(int $id): void
    {
        $p = SocialPost::find($id);
        if (! $p || $p->status !== 'pending') {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Cannot update a sent post'];
            $this->redirect(BASE_URL . '/public/index.php?mod=social_posts&action=index');
        }

        $p->content      = trim($_POST['content'] ?? $p->content);
        $p->image_url    = trim($_POST['image_url'] ?? $p->image_url);
        $p->scheduled_at = $_POST['scheduled_at'] ?? $p->scheduled_at;
        $p->save();

        $_SESSION['flash'] = ['type'=>'success','message'=>'Post updated'];
        $this->redirect(BASE_URL . '/public/index.php?mod=social_posts&action=index');
    }

    public function destroy(int $id): void
    {
        $p = SocialPost::find($id);
        if (! $p || $p->status !== 'pending') {
            $_SESSION['flash'] = ['type'=>'warning','message'=>'Cannot delete a sent post'];
            $this->redirect(BASE_URL . '/public/index.php?mod=social_posts&action=index');
        }

        $p->delete();
        $_SESSION['flash'] = ['type'=>'danger','message'=>'Post removed'];
        $this->redirect(BASE_URL . '/public/index.php?mod=social_posts&action=index');
    }
}

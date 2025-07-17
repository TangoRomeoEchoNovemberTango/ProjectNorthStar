<?php
namespace App\Controllers;

use App\Models\Document;

class DocumentsController extends Controller
{
    // List all documents
    public function index(): void
    {
        $docs = Document::all();
        $this->view('documents/index', compact('docs'));
    }

    // Show upload form
    public function create(): void
    {
        $this->view('documents/form');
    }

    // Handle upload POST
    public function store(): void
    {
        $title       = $_POST['title']       ?? '';
        $description = $_POST['description'] ?? '';
        $file        = $_FILES['file']       ?? null;

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $data = file_get_contents($file['tmp_name']);

            $doc = new Document();
            $doc->title       = $title;
            $doc->description = $description;
            $doc->file_name   = $file['name'];
            $doc->file_type   = $file['type'];
            $doc->file_size   = (int)$file['size'];
            $doc->file_data   = $data;
            $doc->save();
        }

        $this->redirect(BASE_URL . '/public/index.php?mod=documents');
    }

    // Stream file_data for download
    public function download(int $id): void
    {
        $doc = Document::find($id);
        if (! $doc) {
            http_response_code(404);
            exit('Not found');
        }

        header('Content-Type: ' . $doc->file_type);
        header('Content-Length: ' . $doc->file_size);
        header('Content-Disposition: attachment; filename="'.basename($doc->file_name).'"');

        echo $doc->file_data;
        exit;
    }

    // Delete a document
    public function destroy(int $id): void
    {
        $doc = Document::find($id);
        if ($doc) {
            $doc->delete();
        }
        $this->redirect(BASE_URL . '/public/index.php?mod=documents');
    }
}
?>

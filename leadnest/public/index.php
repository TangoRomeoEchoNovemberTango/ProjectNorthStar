<?php
declare(strict_types=1);

session_start();

// DEV HACK: ensure we always have a user_id in session
if (! isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // make sure user #1 exists in your users table
}

ini_set('display_errors','1');
ini_set('display_startup_errors','1');
error_reporting(E_ALL);

require __DIR__ . '/../config/config.php';
$socialConfig = file_exists(__DIR__ . '/../config/social.php')
              ? require __DIR__ . '/../config/social.php'
              : [];

// PSR-4 autoloader …
spl_autoload_register(function(string $class) {
    $prefix   = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $file = $base_dir
          . str_replace('\\','/', substr($class, strlen($prefix)))
          . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

$allowedModules = [
    'contacts','calls','documents','email_campaigns',
    'properties','social_posts','dashboard','deals'
];

$mod    = $_GET['mod']    ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

if (! in_array($mod, $allowedModules, true)) {
    http_response_code(404);
    exit("Module \"{$mod}\" not found");
}

// Initialize all models
\App\Models\Contact::init($pdo);
\App\Models\Call::init($pdo);
\App\Models\Document::init($pdo);
\App\Models\EmailCampaign::init($pdo);
\App\Models\Property::init($pdo);
\App\Models\PropertyDocument::init($pdo);
\App\Models\SocialPost::init($pdo);
\App\Models\Deal::init($pdo);

// Dispatch
$ctrlName  = str_replace(' ', '', ucwords(str_replace('_',' ', $mod)));
$ctrlClass = "App\\Controllers\\{$ctrlName}Controller";

if (! class_exists($ctrlClass)) {
    http_response_code(404);
    exit("Controller \"{$ctrlClass}\" not found");
}

$ref    = new ReflectionClass($ctrlClass);
$params = $ref->getConstructor()?->getParameters() ?? [];
$controller = (count($params) === 2)
    ? new $ctrlClass($pdo, $socialConfig)
    : new $ctrlClass($pdo);

if (! method_exists($controller, $action)) {
    http_response_code(404);
    exit("Action \"{$action}\" not found");
}

try {
    if (isset($_GET['id'])) {
        $controller->{$action}((int) $_GET['id']);
    } else {
        $controller->{$action}();
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Error: ' . htmlspecialchars($e->getMessage());
}

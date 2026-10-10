<?php

define('ROOT', dirname(__DIR__));

// Base URL path (supports hosting in a subdirectory, e.g. /BadmintonBooking/public)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$scriptDir = rtrim($scriptDir, '/');
define('BASE_URL', $scriptDir);

// Simple autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to full file path
    $file = ROOT . '/' . str_replace('\\', '/', lcfirst($class)) . '.php';
    // For App namespace, check app/ directory
    if (strpos($class, 'App\\') === 0) {
        $file = ROOT . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    }
    // For Core namespace, check core/ directory
    elseif (strpos($class, 'Core\\') === 0) {
        $file = ROOT . '/core/' . str_replace('\\', '/', substr($class, 5)) . '.php';
    }
    
    if (file_exists($file)) {
        require $file;
    }
});

// Auto-provision .env on first run (keeps the project portable across machines)
\Core\Installer::ensureEnvFile(ROOT);

// Load environment variables (simple parser)
if (file_exists(ROOT . '/.env')) {
    $lines = file(ROOT . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Auto-provision database on first run (create DB + import schema)
try {
    \Core\Installer::install(ROOT);
} catch (\Throwable $e) {
    // Không làm sập app; Database sẽ báo lỗi kết nối cụ thể nếu cần.
    $GLOBALS['__install_error'] = $e->getMessage();
}

// Start Session
\Core\Session::start();

// Initialize Router
$router = new \Core\Router();

// Load Routes
require ROOT . '/routes/web.php';

// Dispatch Request
$router->dispatch();

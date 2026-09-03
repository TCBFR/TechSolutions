<?php
/**
 * Autoloader personnalisé
 */

// Charger AltoRouter EN PREMIER
require_once BASE_PATH . 'vendor/AltoRouter/AltoRouter.php';

spl_autoload_register(function ($class) {
    $base_dir = BASE_PATH;
    $relative_class = $class;
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
        return;
    }
});

<?php
spl_autoload_register(function ($class) {
    
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    error_log("FILE: " . $class);
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
});

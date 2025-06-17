<?php
/**
 * Environment variable loader
 * Loads variables from .env file into $_ENV and $_SERVER
 */
function loadEnv($path = null) {
    $path = $path ?: __DIR__ . '/.env';

    if (!file_exists($path)) {
        die('.env file not found. Please create one based on .env.example');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // Remove quotes if present
        if (strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) {
            $value = substr($value, 1, -1);
        } elseif (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1) {
            $value = substr($value, 1, -1);
        }

        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
        putenv("$name=$value");
    }
}

// Load environment variables
loadEnv();
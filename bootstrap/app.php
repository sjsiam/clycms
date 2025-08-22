<?php
$envFile = dirname(__DIR__) . '/.env';
if (!file_exists($envFile)) {
    throw new Exception('.env file not found. Please create one based on .env.example.');
}
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, "\"'");
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $file = $backtrace['file'] ?? 'unknown file';
        $line = $backtrace['line'] ?? 'unknown line';

        echo '<pre style="background:#f8f9fa; color:#333; padding:15px; border-radius:5px;">';
        echo "<strong>Dump called at:</strong> {$file}:{$line}\n\n";

        foreach ($vars as $var) {
            var_dump($var);
            echo "\n";
        }
        echo '</pre>';
        exit;
    }
}


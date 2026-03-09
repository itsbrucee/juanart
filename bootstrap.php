<?php
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/Auth.php';
require_once __DIR__ . '/src/RoleGuard.php';

use JuanArt\Auth;
use JuanArt\Database;

Auth::init();

function env(string $key, string $default = ''): string
{
    $v = getenv($key);
    return $v !== false ? $v : $default;
}

function base_url(string $path = ''): string
{
    $config = require __DIR__ . '/config/app.php';
    $base = rtrim($config['base_url'] ?: '', '/');
    $path = ltrim($path, '/');
    return $base . ($path ? '/' . $path : '');
}

function view(string $name, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require __DIR__ . '/views/' . $name . '.php';
}

function redirect(string $url, int $code = 302): void
{
    header('Location: ' . $url, true, $code);
    exit;
}

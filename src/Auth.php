<?php
namespace JuanArt;

class Auth
{
    private const SESSION_USER = 'user_id';
    private const SESSION_ROLE = 'user_role';
    private const SESSION_NAME = 'user_name';

    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $config = require __DIR__ . '/../config/app.php';
            session_set_cookie_params([
                'lifetime' => $config['session_lifetime'],
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    public static function login(int $userId, string $roleName, string $name): void
    {
        session_regenerate_id(true);
        $_SESSION[self::SESSION_USER] = $userId;
        $_SESSION[self::SESSION_ROLE] = $roleName;
        $_SESSION[self::SESSION_NAME] = $name;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    public static function id(): ?int
    {
        return isset($_SESSION[self::SESSION_USER]) ? (int) $_SESSION[self::SESSION_USER] : null;
    }

    public static function role(): ?string
    {
        return $_SESSION[self::SESSION_ROLE] ?? null;
    }

    public static function name(): ?string
    {
        return $_SESSION[self::SESSION_NAME] ?? null;
    }

    public static function check(): bool
    {
        return self::id() !== null;
    }

    public static function isClient(): bool { return self::role() === 'client'; }
    public static function isArtist(): bool { return self::role() === 'artist'; }
    public static function isAdmin(): bool { return self::role() === 'admin'; }
}

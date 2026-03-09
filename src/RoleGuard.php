<?php
namespace JuanArt;

class RoleGuard
{
    /** Require any logged-in user; redirect to login if not. */
    public static function requireAuth(string $loginUrl = '/?page=login'): void
    {
        Auth::init();
        if (!Auth::check()) {
            header('Location: ' . $loginUrl);
            exit;
        }
    }

    /** Require admin; redirect if not admin. */
    public static function requireAdmin(string $fallbackUrl = '/'): void
    {
        self::requireAuth();
        if (!Auth::isAdmin()) {
            header('Location: ' . $fallbackUrl);
            exit;
        }
    }

    /** Require artist (user with artist profile); redirect if not. */
    public static function requireArtist(string $fallbackUrl = '/'): void
    {
        self::requireAuth();
        if (!Auth::isArtist()) {
            header('Location: ' . $fallbackUrl);
            exit;
        }
    }

    /** Require client (or artist acting as buyer). For cart/checkout/orders. */
    public static function requireClient(string $fallbackUrl = '/'): void
    {
        self::requireAuth();
        // Both client and artist can be buyers
    }
}

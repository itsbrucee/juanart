<?php
// Simulate the login process
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/Auth.php';

use JuanArt\Auth;
use JuanArt\Database;

echo "=== Testing Admin Login Flow ===\n\n";

// Simulate form submission
$email = 'admin@juanart.dev';
$password = 'admin123';

$pdo = Database::get();

// This is the exact query from actions.php
$stmt = $pdo->prepare("SELECT u.id, u.name, u.password_hash, ur.role_name FROM users u JOIN user_roles ur ON u.role_id = ur.role_id WHERE u.email = ? AND u.status = 'active'");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "ERROR: User not found or not active!\n";
    exit;
}

echo "1. User found:\n";
echo "   - ID: " . $user['id'] . "\n";
echo "   - Name: " . $user['name'] . "\n";
echo "   - Role: " . $user['role_name'] . "\n\n";

// Check password
if (!password_verify($password, $user['password_hash'])) {
    echo "ERROR: Invalid password!\n";
    exit;
}

echo "2. Password verified successfully!\n\n";

// Now test Auth::login and redirects
Auth::init();
Auth::login((int)$user['id'], $user['role_name'], $user['name']);

echo "3. Auth::login() called\n";
echo "   - Session user_id: " . $_SESSION['user_id'] . "\n";
echo "   - Session user_role: " . $_SESSION['user_role'] . "\n";
echo "   - Session user_name: " . $_SESSION['user_name'] . "\n\n";

// Check role methods
echo "4. Role checks:\n";
echo "   - Auth::id(): " . Auth::id() . "\n";
echo "   - Auth::role(): " . Auth::role() . "\n";
echo "   - Auth::isAdmin(): " . (Auth::isAdmin() ? 'true' : 'false') . "\n";
echo "   - Auth::isArtist(): " . (Auth::isArtist() ? 'true' : 'false') . "\n";
echo "   - Auth::isClient(): " . (Auth::isClient() ? 'true' : 'false') . "\n\n";

// Test redirect logic (from actions.php)
echo "5. Redirect logic:\n";
if (Auth::isAdmin()) {
    echo "   -> Would redirect to: admin/dashboard\n";
} elseif (Auth::isArtist()) {
    echo "   -> Would redirect to: artist/dashboard\n";
} else {
    echo "   -> Would redirect to: dashboard\n";
}

echo "\n=== All tests passed! Admin login should work. ===\n";

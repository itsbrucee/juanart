<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/src/Database.php';

use JuanArt\Database;

$pdo = Database::get();

// Test login for admin
$email = 'admin@juanart.dev';
$password = 'admin123';

$stmt = $pdo->prepare("SELECT u.id, u.name, u.password_hash, ur.role_name FROM users u JOIN user_roles ur ON u.role_id = ur.role_id WHERE u.email = ? AND u.status = 'active'");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found or not active!\n";
} else {
    echo "User found: \n";
    print_r($user);
    
    echo "\nVerifying password...\n";
    if (password_verify($password, $user['password_hash'])) {
        echo "Password VERIFIED!\n";
        echo "Role: " . $user['role_name'] . "\n";
    } else {
        echo "PASSWORD FAILED!\n";
        echo "Expected hash: " . password_hash($password, PASSWORD_DEFAULT) . "\n";
        echo "Actual hash: " . $user['password_hash'] . "\n";
    }
}

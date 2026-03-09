<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/src/Database.php';

use JuanArt\Database;

$pdo = Database::get();

// Check admin users
$stmt = $pdo->query("SELECT id, name, email, role_id, status FROM users WHERE role_id = 3");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Admin users found: " . count($admins) . "\n";
print_r($admins);

// Reset password for admin
$hash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password_hash = ?, status = 'active' WHERE role_id = 3");
$stmt->execute([$hash]);

echo "\nAdmin password reset to 'admin123' and status set to 'active'!\n";

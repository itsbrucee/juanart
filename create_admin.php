<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/src/Database.php';

use JuanArt\Database;

$pdo = Database::get();

// Check if admin already exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute(['admin@juanart.dev']);
if ($stmt->fetch()) {
    echo "Admin user already exists!\n";
    exit;
}

// Create admin user with password 'admin123'
$hash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role_id, status) VALUES (?, ?, ?, 3, 'active')");
$stmt->execute(['Admin', 'admin@juanart.dev', $hash]);

echo "Admin user created successfully!\n";
echo "Email: admin@juanart.dev\n";
echo "Password: admin123\n";

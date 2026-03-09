<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/src/Database.php';

use JuanArt\Database;

$pdo = Database::get();

// Check user_roles table
$stmt = $pdo->query("SELECT * FROM user_roles");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "User Roles:\n";
print_r($roles);

// Check all users
$stmt = $pdo->query("SELECT u.id, u.name, u.email, u.role_id, u.status, ur.role_name FROM users u JOIN user_roles ur ON u.role_id = ur.role_id");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "\nAll Users:\n";
print_r($users);

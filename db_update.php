<?php
/**
 * Database Update Script
 * Adds view_count column to artworks table for tracking artwork visits
 */

require_once __DIR__ . '/bootstrap.php';

use JuanArt\Database;

$pdo = Database::get();

// Check if view_count column already exists
$columns = $pdo->query("SHOW COLUMNS FROM artworks LIKE 'view_count'")->fetch();

if (!$columns) {
    // Add view_count column
    $pdo->exec("ALTER TABLE artworks ADD COLUMN view_count INT DEFAULT 0 AFTER status");
    echo "Successfully added view_count column to artworks table.\n";
} else {
    echo "view_count column already exists.\n";
}

// Also ensure we have the artwork_type column for separating for_sale and gallery_only
$artworkTypeCol = $pdo->query("SHOW COLUMNS FROM artworks LIKE 'artwork_type'")->fetch();
if (!$artworkTypeCol) {
    $pdo->exec("ALTER TABLE artworks ADD COLUMN artwork_type VARCHAR(20) DEFAULT 'for_sale' AFTER view_count");
    echo "Successfully added artwork_type column to artworks table.\n";
} else {
    echo "artwork_type column already exists.\n";
}

echo "Database update complete.\n";

<?php
// Test inserting a gallery_only artwork
$pdo = new PDO('mysql:host=localhost;dbname=juanart', 'root', '');

// First check if there's an artist user
$stmt = $pdo->query('SELECT id FROM users LIMIT 1');
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "No users found\n";
    exit;
}

$artistId = $user['id'];

// Try inserting a gallery_only artwork with NULL price
try {
    $stmt = $pdo->prepare("INSERT INTO artworks (artist_id, title, description, price, category_id, image_url, artwork_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $result = $stmt->execute([$artistId, 'Test Gallery Artwork', 'Test description', null, 1, 'test.jpg', 'gallery_only']);
    echo "SUCCESS: Gallery-only artwork inserted with NULL price!\n";
    
    // Get the inserted ID
    $id = $pdo->lastInsertId();
    echo "Inserted ID: $id\n";
    
    // Verify
    $stmt = $pdo->query("SELECT * FROM artworks WHERE id = $id");
    $artwork = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($artwork);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>

<?php
// Verify the database fix
$pdo = new PDO('mysql:host=localhost;dbname=juanart', 'root', '');

// Check the column definition
$stmt = $pdo->query("DESCRIBE artworks");
echo "Artworks table structure:\n";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($row['Field'] === 'price') {
        echo "price column: " . $row['Type'] . " - Null: " . $row['Null'] . "\n";
    }
    if ($row['Field'] === 'artwork_type') {
        echo "artwork_type column: " . $row['Type'] . " - Null: " . $row['Null'] . "\n";
    }
}

// Check existing artworks
echo "\nExisting artworks:\n";
$stmt = $pdo->query('SELECT id, title, price, artwork_type FROM artworks');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']}, Title: {$row['title']}, Price: {$row['price']}, Type: {$row['artwork_type']}\n";
}

echo "\nFix verification complete!\n";
?>

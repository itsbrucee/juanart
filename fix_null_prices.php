<?php
$pdo = new PDO('mysql:host=localhost;dbname=juanart', 'root', '');
$pdo->exec('UPDATE artworks SET price = 0 WHERE price IS NULL');
echo "Updated null prices to 0\n";

// Also make sure price column allows null
$pdo->exec('ALTER TABLE artworks MODIFY COLUMN price DECIMAL(10,2) NULL');
echo "Modified price column to allow NULL\n";

// Verify
$stmt = $pdo->query('SELECT id, title, price, artwork_type FROM artworks LIMIT 5');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']}, Title: {$row['title']}, Price: {$row['price']}, Type: {$row['artwork_type']}\n";
}
?>

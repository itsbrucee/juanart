<?php
$pdo = new PDO('mysql:host=localhost;dbname=juanart', 'root', '');
$stmt = $pdo->query('SELECT id, title, price, artwork_type FROM artworks');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . ': ' . $row['title'] . ' - Price: ' . $row['price'] . ' - Type: ' . $row['artwork_type'] . "\n";
}
?>

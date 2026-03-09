<?php
$pdo = new PDO('mysql:host=localhost;dbname=juanart', 'root', '');
$artworks = $pdo->query("SELECT id, title, image_url FROM artworks LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

echo "Artworks in database:\n";
foreach ($artworks as $a) {
    echo "ID: {$a['id']}, Title: {$a['title']}, URL: {$a['image_url']}\n";
}

// Check if uploads folder exists
echo "\n\nUploads folder check:\n";
$uploadDir = __DIR__ . '/uploads/artworks/';
if (is_dir($uploadDir)) {
    echo "Folder exists: $uploadDir\n";
    $files = scandir($uploadDir);
    echo "Files count: " . count($files) . "\n";
    foreach (array_slice($files, 0, 10) as $f) {
        echo "  - $f\n";
    }
} else {
    echo "Folder does NOT exist: $uploadDir\n";
}
?>

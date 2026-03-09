<?php
$pdo = new PDO('mysql:host=localhost;dbname=juanart', 'root', '');
$cols = $pdo->query('DESCRIBE artworks');
echo "Artworks table columns:\n";
while($c = $cols->fetch(PDO::FETCH_ASSOC)) {
    echo $c['Field'] . " - " . $c['Type'] . " - " . ($c['Null'] === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
}
?>

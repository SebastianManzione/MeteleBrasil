<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil;charset=utf8mb4', 'root', '');
    $stmt = $pdo->query('SHOW TABLES');
    echo "Tablas en metelebrasil:\n";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "- " . $row[0] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

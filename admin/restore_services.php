<?php
// Restaurar servicios desde backup en servidor

$backupFile = '/home/u925692129/servicios_compat.sql';
$dbUser = 'u925692129_metelebrasil';
$dbPass = 'Cambiar2026';
$dbName = 'u925692129_metelebrasil';

echo "🔄 Restaurando servicios desde backup\n";

// Leer el archivo SQL
$sql = file_get_contents($backupFile);

// Limpiar caracteres problemáticos
$sql = preg_replace('/\x00+/', '', $sql);

// Ejecutar con PDO
try {
    $pdo = new PDO("mysql:host=localhost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_THROW);
    
    // Dividir por ; y ejecutar cada statement
    $statements = explode(";\n", $sql);
    $count = 0;
    
    foreach ($statements as $stmt) {
        $stmt = trim($stmt);
        if (!empty($stmt) && strpos($stmt, 'INSERT INTO') === 0) {
            try {
                $pdo->exec($stmt);
                $count++;
            } catch (Exception $e) {
                echo "⚠️  Error en línea: " . substr($stmt, 0, 80) . "\n";
            }
        }
    }
    
    // Verificar
    $result = $pdo->query("SELECT COUNT(*) FROM servicio");
    $total = $result->fetchColumn();
    
    echo "✅ Importación completada\n";
    echo "📊 Servicios restaurados: $total\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>

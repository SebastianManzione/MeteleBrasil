<?php
require_once("admin/classes/conexion.php");

try {
    // 1. Eliminar FK existente si existe
    $pdo->exec("ALTER TABLE ruta_paradas DROP FOREIGN KEY ruta_paradas_ibfk_2");
    echo "✓ FK anterior eliminada<br>";
    
    // 2. Modificar columna idTerminal para permitir NULL
    $pdo->exec("ALTER TABLE ruta_paradas MODIFY idTerminal INT NULL");
    echo "✓ Columna idTerminal ahora es nullable<br>";
    
    // 3. Recrear FK con ON DELETE SET NULL
    $pdo->exec("ALTER TABLE ruta_paradas 
               ADD CONSTRAINT ruta_paradas_ibfk_2 
               FOREIGN KEY (idTerminal) REFERENCES terminal_transporte(idTerminal) 
               ON DELETE SET NULL");
    echo "✓ FK recreada con ON DELETE SET NULL<br>";
    
    // 4. Verificar estructura
    $resultado = $pdo->query("DESCRIBE ruta_paradas")->fetchAll();
    echo "<br><strong>Estructura de ruta_paradas:</strong><br>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($resultado as $fila) {
        echo "<tr>";
        echo "<td>" . $fila['Field'] . "</td>";
        echo "<td>" . $fila['Type'] . "</td>";
        echo "<td>" . $fila['Null'] . "</td>";
        echo "<td>" . $fila['Key'] . "</td>";
        echo "<td>" . $fila['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><strong>✓ Base de datos actualizada correctamente</strong>";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage();
}
?>
<script>setTimeout(() => window.location.href = 'admin/rutasTransporteLista.php', 2000);</script>

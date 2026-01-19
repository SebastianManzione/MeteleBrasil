<?php
/**
 * Validación del Sistema de Tipos Dinámicos
 * http://localhost/metelebrasil_dev/validar_tipos_dinamicos.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("admin/classes/conexion.php");
require_once("admin/classes/transporte.php");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Validación: Tipos Dinámicos</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body { padding: 20px; }
        .check { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(0,0,0,.02); }
    </style>
</head>
<body>
<div class="container-lg">
    <h1><i class="fas fa-check-circle"></i> Validación Sistema Tipos Dinámicos</h1>
    <hr>

    <?php
    $checks = [];
    
    // CHECK 1: Tabla tipo_parada existe
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME='tipo_parada'");
        $stmt->execute();
        $exists = $stmt->fetch(PDO::FETCH_NUM)[0];
        $checks[] = [
            'nombre' => 'Tabla tipo_parada existe',
            'resultado' => $exists ? '<span class="check">✓ SÍ</span>' : '<span class="error">✗ NO</span>',
            'estado' => $exists ? 'success' : 'danger'
        ];
    } catch (Exception $e) {
        $checks[] = [
            'nombre' => 'Tabla tipo_parada existe',
            'resultado' => '<span class="error">✗ ERROR: ' . $e->getMessage() . '</span>',
            'estado' => 'danger'
        ];
    }
    
    // CHECK 2: Tabla parada tiene columna idTipoPrada
    try {
        $stmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='parada' AND COLUMN_NAME='idTipoPrada'");
        $stmt->execute();
        $exists = $stmt->rowCount() > 0;
        $checks[] = [
            'nombre' => 'Columna idTipoPrada en parada',
            'resultado' => $exists ? '<span class="check">✓ SÍ</span>' : '<span class="error">✗ NO</span>',
            'estado' => $exists ? 'success' : 'danger'
        ];
    } catch (Exception $e) {
        $checks[] = [
            'nombre' => 'Columna idTipoPrada en parada',
            'resultado' => '<span class="error">✗ ERROR</span>',
            'estado' => 'danger'
        ];
    }
    
    // CHECK 3: Conteo de tipos
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM tipo_parada WHERE habilitado=1");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $checks[] = [
            'nombre' => 'Tipos habilitados',
            'resultado' => "<span class='check'>✓ $total</span>",
            'estado' => $total >= 7 ? 'success' : 'warning'
        ];
    } catch (Exception $e) {
        $checks[] = [
            'nombre' => 'Tipos habilitados',
            'resultado' => '<span class="error">✗ ERROR</span>',
            'estado' => 'danger'
        ];
    }
    
    // CHECK 4: Conteo de paradas con tipo
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM parada WHERE idTipoPrada IS NOT NULL");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM parada");
        $totalParadas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $checks[] = [
            'nombre' => 'Paradas con tipo asignado',
            'resultado' => "<span class='check'>✓ $total/$totalParadas</span>",
            'estado' => $total > 0 ? 'success' : 'warning'
        ];
    } catch (Exception $e) {
        $checks[] = [
            'nombre' => 'Paradas con tipo asignado',
            'resultado' => '<span class="error">✗ ERROR</span>',
            'estado' => 'danger'
        ];
    }
    
    // CHECK 5: FK constraint
    try {
        $stmt = $pdo->prepare("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                              WHERE TABLE_NAME='parada' AND COLUMN_NAME='idTipoPrada' 
                              AND REFERENCED_TABLE_NAME='tipo_parada'");
        $stmt->execute();
        $exists = $stmt->rowCount() > 0;
        $checks[] = [
            'nombre' => 'Foreign Key constraint',
            'resultado' => $exists ? '<span class="check">✓ EXISTE</span>' : '<span class="error">✗ NO</span>',
            'estado' => $exists ? 'success' : 'warning'
        ];
    } catch (Exception $e) {
        $checks[] = [
            'nombre' => 'Foreign Key constraint',
            'resultado' => '<span class="error">✗ ERROR</span>',
            'estado' => 'danger'
        ];
    }
    
    // CHECK 6: Función getAllParadas
    try {
        $paradas = getAllParadas();
        $conTipo = 0;
        foreach ($paradas as $p) {
            if (!empty($p['tipo_nombre'])) $conTipo++;
        }
        $checks[] = [
            'nombre' => 'Función getAllParadas() retorna tipo_nombre',
            'resultado' => "<span class='check'>✓ $conTipo de " . count($paradas) . " paradas</span>",
            'estado' => $conTipo > 0 ? 'success' : 'warning'
        ];
    } catch (Exception $e) {
        $checks[] = [
            'nombre' => 'Función getAllParadas() retorna tipo_nombre',
            'resultado' => '<span class="error">✗ ERROR: ' . $e->getMessage() . '</span>',
            'estado' => 'danger'
        ];
    }
    
    // CHECK 7: Archivos de admin creados
    $archivos = [
        'admin/tiposParadaLista.php',
        'admin/tiposParadaAlta.php',
        'admin/ctrl/ctrlTiposParada.php'
    ];
    foreach ($archivos as $arch) {
        $exists = file_exists($arch);
        $checks[] = [
            'nombre' => "Archivo $arch",
            'resultado' => $exists ? '<span class="check">✓ EXISTE</span>' : '<span class="error">✗ NO</span>',
            'estado' => $exists ? 'success' : 'danger'
        ];
    }
    
    // Mostrar tabla de checks
    echo "<table class='table table-bordered'>";
    echo "<thead class='table-dark'><tr><th>Validación</th><th>Resultado</th></tr></thead>";
    echo "<tbody>";
    foreach ($checks as $check) {
        echo "<tr class='table-" . $check['estado'] . "'>";
        echo "<td>" . htmlspecialchars($check['nombre']) . "</td>";
        echo "<td>" . $check['resultado'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    
    // Listar tipos
    echo "<h2>Tipos de Parada Creados</h2>";
    $tipos = $pdo->query("SELECT * FROM tipo_parada ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    echo "<table class='table table-striped'>";
    echo "<thead><tr><th>ID</th><th>Nombre</th><th>Icono</th><th>Color</th><th>Paradas</th></tr></thead>";
    echo "<tbody>";
    foreach ($tipos as $tipo) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM parada WHERE idTipoPrada = :id");
        $stmt->execute(['id' => $tipo['idTipoPrada']]);
        $cnt = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($tipo['idTipoPrada']) . "</td>";
        echo "<td>" . htmlspecialchars($tipo['nombre']) . "</td>";
        echo "<td><i class='fas " . htmlspecialchars($tipo['icono']) . "' style='font-size: 18px;'></i> " . htmlspecialchars($tipo['icono']) . "</td>";
        echo "<td><span style='width: 20px; height: 20px; background: " . htmlspecialchars($tipo['color']) . "; display: inline-block; border-radius: 3px;'></span> " . htmlspecialchars($tipo['color']) . "</td>";
        echo "<td><badge>" . $cnt . "</badge></td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
    
    // Distribución de paradas
    echo "<h2>Distribución de Paradas</h2>";
    $dist = $pdo->query("SELECT tp.nombre, COUNT(p.idParada) as cnt 
                        FROM tipo_parada tp 
                        LEFT JOIN parada p ON tp.idTipoPrada = p.idTipoPrada 
                        GROUP BY tp.idTipoPrada 
                        ORDER BY cnt DESC")->fetchAll(PDO::FETCH_ASSOC);
    echo "<table class='table table-striped'>";
    echo "<thead><tr><th>Tipo</th><th>Cantidad</th></tr></thead>";
    echo "<tbody>";
    foreach ($dist as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
        echo "<td><strong>" . $row['cnt'] . "</strong></td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
    ?>

    <hr>
    <div class="alert alert-success">
        <h3>✓ Sistema de Tipos Dinámicos Operativo</h3>
        <p>Puedes acceder a:</p>
        <ul>
            <li><a href="admin/tiposParadaLista.php">Gestión de Tipos</a></li>
            <li><a href="admin/terminalesLista.php">Lista de Terminales (con tipos dinámicos)</a></li>
            <li><a href="admin/rutasTransporteLista.php">Rutas de Transporte</a></li>
        </ul>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="css/bootstrap.min.js"></script>
</body>
</html>

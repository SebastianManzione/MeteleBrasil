<?php
// Archivo de diagnóstico - NO usar en producción permanentemente
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnóstico de Sistema</h1>";

// Test 1: Sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo "<h2>1. Estado de Sesión</h2>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Session Status: " . session_status() . "\n";
echo "Session Data:\n";
print_r($_SESSION);
echo "</pre>";

// Test 2: Conexión BD
echo "<h2>2. Conexión Base de Datos</h2>";
try {
    require_once(__DIR__ . '/classes/conexion.php');
    echo "<p style='color:green'>✓ Conexión exitosa</p>";
    
    // Test 3: Usuario admin
    echo "<h2>3. Usuarios Admin</h2>";
    $stmt = $pdo->query("SELECT idUsuario, usuario, email, rol FROM usuario WHERE rol = 1 LIMIT 5");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($admins) {
        echo "<table border='1'><tr><th>ID</th><th>Usuario</th><th>Email</th><th>Rol</th></tr>";
        foreach ($admins as $admin) {
            echo "<tr>";
            echo "<td>{$admin['idUsuario']}</td>";
            echo "<td>{$admin['usuario']}</td>";
            echo "<td>{$admin['email']}</td>";
            echo "<td>{$admin['rol']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:red'>✗ No hay usuarios admin</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>✗ Error: " . $e->getMessage() . "</p>";
}

// Test 4: Variables de entorno
echo "<h2>4. Variables de Entorno</h2>";
echo "<pre>";
echo "APP_ENV: " . getenv('APP_ENV') . "\n";
echo "Host: " . gethostname() . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "</pre>";

echo "<hr><p><a href='login.php'>Ir a Login</a></p>";
?>

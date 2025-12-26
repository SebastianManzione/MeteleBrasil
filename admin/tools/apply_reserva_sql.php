<?php
// Aplica un bloque de SQL INSERTs para una reserva, de forma segura e idempotente.
// Se espera que el payload incluya los INSERTs generados por export_reserva_sql.php.
// Uso: POST con campos 'sql' (texto) y 'codigo' (codigoAmigable, para chequear existencia previa).

declare(strict_types=1);

$credsLoaderPath = __DIR__ . '/../../config/creds_loader.php';
if (!file_exists($credsLoaderPath)) {
    http_response_code(500);
    echo "Falta config/creds_loader.php";
    exit;
}

/** @var array $creds */
$creds = require $credsLoaderPath;

// Para producción usar 'prod'; para local/dev, el loader debe proveer 'dev' o por defecto.
$dbCfg = $creds['db']['prod'] ?? ($creds['db']['dev'] ?? [
    'host' => 'localhost',
    'name' => 'metelebrasil',
    'user' => 'root',
    'pass' => '',
    'port' => 3306,
    'charset' => 'utf8mb4',
]);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo 'Sólo POST';
    exit;
}

$codigo = isset($_POST['codigo']) ? trim((string)$_POST['codigo']) : '';
$sqlPayload = isset($_POST['sql']) ? trim((string)$_POST['sql']) : '';

if ($codigo === '' || $sqlPayload === '') {
    http_response_code(400);
    echo "Faltan parametros: 'codigo' y 'sql' son requeridos";
    exit;
}

try {
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $dbCfg['host'], $dbCfg['port'], $dbCfg['name'], $dbCfg['charset'] ?? 'utf8mb4');
    $pdo = new PDO($dsn, $dbCfg['user'], $dbCfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Error de conexión BD: ' . $e->getMessage();
    exit;
}

// Chequeo idempotente: aborta si ya existe.
$stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM reservas WHERE LOWER(codigoAmigable) = LOWER(?)');
$stmt->execute([$codigo]);
$row = $stmt->fetch();
if (($row['c'] ?? 0) > 0) {
    http_response_code(409);
    echo "Reserva ya existe con codigoAmigable={$codigo}, no se aplican INSERTs";
    exit;
}

// Ejecuta en transacción.
$pdo->beginTransaction();
try {
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');

    // Divide por ';' cuidando líneas vacías; asumimos INSERTs simples sin delimitadores internos.
    $parts = preg_split('/;\s*\n/', $sqlPayload);
    $applied = 0;
    foreach ($parts as $stmtSql) {
        $stmtSql = trim($stmtSql);
        if ($stmtSql === '') {
            continue;
        }
        // Asegura terminar en ';' para logging claro
        if (substr($stmtSql, -1) !== ';') {
            $stmtSql .= ';';
        }
        $pdo->exec($stmtSql);
        $applied++;
    }

    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
    $pdo->commit();

    header('Content-Type: application/json');
    echo json_encode([
        'ok' => true,
        'applied_statements' => $applied,
        'codigo' => $codigo,
    ]);
} catch (Throwable $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo 'Error aplicando SQL: ' . $e->getMessage();
}
?>

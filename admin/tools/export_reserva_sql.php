<?php
// Exporta una reserva y sus tablas hijas como SQL INSERTs.
// Uso (en servidor): /admin/tools/export_reserva_sql.php?codigo=bgm382

declare(strict_types=1);

// Carga credenciales centralizadas
$credsLoaderPath = __DIR__ . '/../../config/creds_loader.php';
if (!file_exists($credsLoaderPath)) {
    http_response_code(500);
    echo "Falta config/creds_loader.php";
    exit;
}
/** @var array $creds */
$creds = require $credsLoaderPath;

// Detecta entorno: usa 'db'->'dev' si existe, de lo contrario intenta parámetros por defecto
$dbCfg = $creds['db']['prod'] ?? ($creds['db']['dev'] ?? [
    'host' => 'localhost',
    'name' => 'metelebrasil',
    'user' => 'root',
    'pass' => '',
    'port' => 3306,
    'charset' => 'utf8mb4',
]);

$codigo = isset($_GET['codigo']) ? trim((string)$_GET['codigo']) : '';
if ($codigo === '') {
    http_response_code(400);
    echo "Parámetro 'codigo' requerido";
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
    echo "Error conexión BD: " . $e->getMessage();
    exit;
}

function quoteVal(PDO $pdo, $val): string {
    if ($val === null) return 'NULL';
    return $pdo->quote((string)$val);
}

function buildInsert(string $table, array $row, PDO $pdo): string {
    $cols = array_keys($row);
    $vals = [];
    foreach ($cols as $c) {
        $vals[] = quoteVal($pdo, $row[$c]);
    }
    $colsSql = '`' . implode('`,`', $cols) . '`';
    $valsSql = implode(',', $vals);
    return "INSERT INTO `{$table}` ({$colsSql}) VALUES ({$valsSql});";
}

// Obtiene reserva
$stmt = $pdo->prepare('SELECT * FROM `reservas` WHERE LOWER(`codigoAmigable`) = LOWER(?) LIMIT 1');
$stmt->execute([$codigo]);
$reserva = $stmt->fetch();

if (!$reserva) {
    http_response_code(404);
    echo "Reserva no encontrada para codigoAmigable={$codigo}";
    exit;
}

$idReserva = (int)$reserva['idReserva'];

// Tablas hijas relacionadas por idReserva
$tablasHijas = [
    'reserva_horarios',
    'reserva_tarifas',
    'reserva_pasajeros',
    'reserva_adicionales',
    'reserva_notas',
];

$sqlOutput = [];
$sqlOutput[] = 'SET FOREIGN_KEY_CHECKS=0;';
$sqlOutput[] = buildInsert('reservas', $reserva, $pdo);

foreach ($tablasHijas as $t) {
    $stmtH = $pdo->prepare("SELECT * FROM `{$t}` WHERE `idReserva` = ?");
    $stmtH->execute([$idReserva]);
    while ($row = $stmtH->fetch()) {
        $sqlOutput[] = buildInsert($t, $row, $pdo);
    }
}

$sqlOutput[] = 'SET FOREIGN_KEY_CHECKS=1;';

header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store');
header('X-Export-Reserva: ' . $codigo);
echo implode("\n", $sqlOutput);
exit;
?>

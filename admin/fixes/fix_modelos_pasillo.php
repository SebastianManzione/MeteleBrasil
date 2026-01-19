<?php
// Actualiza distribuciones de modelos marcando pasillos centrales por piso
// Heurística: si columnas impar -> columna central; si par -> dos columnas centrales
// Doble piso detectado por nombre contiene "doble", capacidad > 45 o filas >= 10

require_once __DIR__ . '/../classes/conexion.php';

function fetchModelos(PDO $pdo) {
    $sql = "SELECT * FROM modelo_vehiculo_transporte";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function safeDecode($json) {
    if (!$json) return null;
    $d = json_decode($json, true);
    return is_array($d) ? $d : null;
}

function crearMatriz($filas, $cols) {
    $mat = [];
    for ($r = 0; $r < $filas; $r++) {
        $fila = [];
        for ($c = 0; $c < $cols; $c++) { $fila[] = 1; }
        $mat[] = $fila;
    }
    return $mat;
}

function normalizarState($state) {
    $tienePisos = isset($state['pisos']) && is_array($state['pisos']) && count($state['pisos']) > 0;
    $doble = !empty($state['doblePiso']) || ($tienePisos && count($state['pisos']) > 1);
    if ($tienePisos && isset($state['pisos'][0]['filas']) && isset($state['pisos'][0]['columnas'])) {
        $pisos = [];
        $totalFilas = 0; $maxCols = 0;
        foreach ($state['pisos'] as $idx => $p) {
            $filasP = max(1, intval($p['filas'] ?? 0));
            $colsP = max(1, intval($p['columnas'] ?? 0));
            $asientos = isset($p['asientos']) && is_array($p['asientos']) ? $p['asientos'] : crearMatriz($filasP, $colsP);
            $pisos[] = [
                'nombre' => $p['nombre'] ?? ('Piso ' . ($idx+1)),
                'filas' => $filasP,
                'columnas' => $colsP,
                'asientos' => $asientos
            ];
            $totalFilas += $filasP;
            $maxCols = max($maxCols, $colsP);
        }
        return [
            'filas' => $totalFilas,
            'columnas' => $maxCols,
            'doblePiso' => $doble,
            'pisos' => $pisos
        ];
    }
    $filas = max(1, intval($state['filas'] ?? 0));
    $cols = max(1, intval($state['columnas'] ?? 0));
    $pisos = [];
    if ($doble) {
        // dividir en dos con misma cantidad si es posible
        $f1 = intdiv($filas + 1, 2);
        $f2 = max(1, $filas - $f1);
        $pisos[] = ['nombre'=>'Piso 1','filas'=>$f1,'columnas'=>$cols,'asientos'=>crearMatriz($f1,$cols)];
        $pisos[] = ['nombre'=>'Piso 2','filas'=>$f2,'columnas'=>$cols,'asientos'=>crearMatriz($f2,$cols)];
    } else {
        $pisos[] = ['nombre'=>'Piso 1','filas'=>$filas,'columnas'=>$cols,'asientos'=>crearMatriz($filas,$cols)];
    }
    return [
        'filas' => $filas,
        'columnas' => $cols,
        'doblePiso' => $doble,
        'pisos' => $pisos
    ];
}

function determinarPasillos($cols) {
    if ($cols <= 1) return [];
    if ($cols % 2 === 1) {
        // impar: columna central
        return [intdiv($cols+1, 2)];
    }
    // par: dos columnas centrales
    return [intdiv($cols,2), intdiv($cols,2)+1];
}

function marcarPasillos(&$matriz, $colsPasillo) {
    foreach ($colsPasillo as $col) {
        $idx = $col - 1;
        if ($idx < 0) continue;
        for ($r = 0; $r < count($matriz); $r++) {
            if (!isset($matriz[$r][$idx])) continue;
            $matriz[$r][$idx] = 'P';
        }
    }
}

function updateDistribucion(PDO $pdo, $idModelo, $json) {
    $sql = "UPDATE modelo_vehiculo_transporte SET distribucion_json = :dist WHERE idModelo = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['dist' => $json, 'id' => $idModelo]);
}

$modelos = fetchModelos($pdo);
$actualizados = 0;
foreach ($modelos as $m) {
    $nombre = $m['nombre'] ?? '';
    $filas = intval($m['filas'] ?? 0);
    $cols = intval($m['columnas'] ?? 0);
    $cap = intval($m['capacidad_total'] ?? 0);
    $esDoble = (stripos($nombre,'doble') !== false) || ($cap > 45) || ($filas >= 10);
    $state = safeDecode($m['distribucion_json']);
    if (!$state) {
        $state = ['filas'=>$filas,'columnas'=>$cols,'doblePiso'=>$esDoble];
    }
    $norm = normalizarState($state);

    // marcar pasillos por piso
    foreach ($norm['pisos'] as $idx => &$piso) {
        $colsPasillo = determinarPasillos(intval($piso['columnas']));
        if (!empty($colsPasillo)) {
            marcarPasillos($piso['asientos'], $colsPasillo);
        }
    }
    unset($piso);

    // recomputar totales
    $norm['filas'] = array_sum(array_map(fn($p)=>intval($p['filas']), $norm['pisos']));
    $norm['columnas'] = max(array_map(fn($p)=>intval($p['columnas']), $norm['pisos']));

    $ok = updateDistribucion($pdo, intval($m['idModelo']), json_encode($norm, JSON_UNESCAPED_UNICODE));
    if ($ok) $actualizados++;
    echo "Modelo #{$m['idModelo']} ({$nombre}): pasillos aplicados.\n";
}

echo "\nTotal modelos actualizados: {$actualizados}\n";

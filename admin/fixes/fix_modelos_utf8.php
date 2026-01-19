<?php
// Ajusta acentos en modelo_vehiculo_transporte usando la base experimental
// Seguro: usa UPDATE por id conocido. Ejecutar una sola vez.

require_once __DIR__ . '/../classes/db.php';

if (!isset($pdo) || !($pdo instanceof PDO)) {
    echo "[ERROR] No hay conexión PDO\n";
    exit(1);
}

$pdo->exec("SET NAMES utf8mb4");

$modelos = [
    1 => [
        'nombre' => 'Chevallier King Premium',
        'descripcion' => 'Micro semicama de lujo. Asientos reclinables, baño, TV, aire acondicionado. Muy popular en rutas largas Argentina.'
    ],
    2 => [
        'nombre' => 'Marcopolo Paradiso 1350',
        'descripcion' => 'Micro ejecutivo brasileño. 40 asientos distribuidos, baño, aire, muy cómodo.'
    ],
    3 => [
        'nombre' => 'Scania K340',
        'descripcion' => 'Micro estándar semi-cama. 50 asientos, baño, aire acondicionado.'
    ],
    4 => [
        'nombre' => 'Boeing 737-800',
        'descripcion' => 'Avión comercial. 6 asientos por fila (3-3 con pasillo central), 30 filas.'
    ],
    5 => [
        'nombre' => 'Ferry Estándar - Bac3000',
        'descripcion' => 'Ferry para travesías fluviales. Capacidad 400 pasajeros, múltiples cubiertas.'
    ],
    6 => [
        'nombre' => 'Marcopolo Doble Piso G7',
        'descripcion' => 'Doble piso 50 asientos. Aire, baño, butacas reclinables, ideal rutas largas.'
    ],
    7 => [
        'nombre' => 'Mercedes Doble Piso Comfort',
        'descripcion' => 'Doble piso 48 asientos. Configuración confort, baño, aire, butacas semi-cama.'
    ],
];

$actualizados = 0;
foreach ($modelos as $id => $data) {
    $stmt = $pdo->prepare("UPDATE modelo_vehiculo_transporte SET nombre = :n, descripcion = :d WHERE idModelo = :id");
    $ok = $stmt->execute([
        'n' => $data['nombre'],
        'd' => $data['descripcion'],
        'id' => $id
    ]);
    if ($ok) {
        $actualizados++;
        echo "[OK] idModelo {$id} -> {$data['nombre']}\n";
    } else {
        echo "[WARN] No se pudo actualizar id {$id}\n";
    }
}

echo "Actualizados: {$actualizados}\n";

<?php
require_once "classes/transporte.php";
require_once "classes/conexion.php";

function actualizarModeloConElementos($idModelo, $distribucion_json) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE modelo_vehiculo_transporte SET distribucion_json = ? WHERE idModelo = ?");
        $stmt->execute([$distribucion_json, $idModelo]);
        return true;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        return false;
    }
}

// Modelos faltantes
$actualizaciones = [
    // ID 5: Ferry Estándar
    [
        'idModelo' => 5,
        'config' => [
            'filas' => 14,
            'columnas' => 5,
            'doblePiso' => false,
            'pisos' => [
                [
                    'nombre' => 'Cubierta Principal',
                    'filas' => 14,
                    'columnas' => 5,
                    'letraInicial' => 'A',
                    'numeroInicial' => 1,
                    'asientos' => [
                        ['E', 'W', 'P', 'W', 1],     // Escalera, panorámicos, pasillo central
                        [1, 'W', 'P', 'W', 1],
                        [1, 'W', 'P', 'W', 1],
                        [1, 1, 'P', 1, 1],
                        [1, 1, 'P', 1, 1],
                        [1, 1, 'P', 1, 1],
                        [1, 1, 'P', 1, 1],
                        [1, 'C', 'P', 'C', 1],      // Cafetera
                        [1, 1, 'P', 1, 1],
                        [1, 1, 'P', 1, 1],
                        [1, 1, 'P', 1, 1],
                        ['F', 1, 'P', 1, 'F'],      // Cerca cafetera
                        ['F', 1, 'P', 1, 'F'],
                        ['F', 1, 'P', 1, 'F']
                    ]
                ]
            ]
        ]
    ],
    // ID 8: Micro Ejecutivo Brasileño
    [
        'idModelo' => 8,
        'config' => [
            'filas' => 8,
            'columnas' => 5,
            'doblePiso' => false,
            'pisos' => [
                [
                    'nombre' => 'Cabina',
                    'filas' => 8,
                    'columnas' => 5,
                    'letraInicial' => 'A',
                    'numeroInicial' => 1,
                    'asientos' => [
                        ['E', 'W', 'W', 'W', 1],
                        [1, 'W', 'W', 'W', 1],
                        [1, 1, 1, 1, 1],
                        [1, 1, 1, 1, 1],
                        [1, 'C', 'C', 'C', 1],
                        [1, 1, 1, 1, 1],
                        ['F', 1, 1, 1, 'F'],
                        ['F', 1, 1, 1, 'F']
                    ]
                ]
            ]
        ]
    ],
    // ID 9: Ferry Fluvial
    [
        'idModelo' => 9,
        'config' => [
            'filas' => 20,
            'columnas' => 20,
            'doblePiso' => false,
            'pisos' => [
                [
                    'nombre' => 'Cubierta Fluvial',
                    'filas' => 20,
                    'columnas' => 20,
                    'letraInicial' => 'A',
                    'numeroInicial' => 1,
                    'asientos' => [
                        ['E', 'W', 'W', 'W', 'W', 'W', 'W', 'W', 'W', 'P', 'W', 'W', 'W', 'W', 'W', 'W', 'W', 'W', 'W', 1],
                        [1, 'W', 'W', 'W', 'W', 'W', 'W', 'W', 'W', 'P', 'W', 'W', 'W', 'W', 'W', 'W', 'W', 'W', 'W', 1],
                        [1, 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                        [1, 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                        [1, 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                        [1, 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                        [1, 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                        [1, 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                        [1, 1, 1, 1, 1, 'C', 'C', 'C', 'C', 'P', 'C', 'C', 'C', 'C', 1, 1, 1, 1, 1, 1],  // Cafetera central
                        [1, 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                        [1, 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                        [1, 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                        [1, 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                        [1, 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                        [1, 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                        ['F', 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 'F'],
                        ['F', 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 'F'],
                        ['F', 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 'F'],
                        ['F', 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 'F'],
                        ['F', 1, 1, 1, 1, 1, 1, 1, 1, 'P', 1, 1, 1, 1, 1, 1, 1, 1, 1, 'F']
                    ]
                ]
            ]
        ]
    ]
];

foreach ($actualizaciones as $actualización) {
    $json = json_encode($actualización['config']);
    if (actualizarModeloConElementos($actualización['idModelo'], $json)) {
        echo "✓ Modelo " . $actualización['idModelo'] . " actualizado correctamente\n";
    } else {
        echo "✗ Error actualizando modelo " . $actualización['idModelo'] . "\n";
    }
}

echo "\n¡Actualización de modelos faltantes completada!\n";
?>

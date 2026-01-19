<?php
require_once "classes/transporte.php";
require_once "classes/conexion.php";

// Función para agregar elementos a un modelo existente
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

// Modelos a actualizar con sus configuraciones
$actualizaciones = [
    // ID 1: Chevallier King Premium - Micro ejecutivo
    [
        'idModelo' => 1,
        'config' => [
            'filas' => 12,
            'columnas' => 3,
            'doblePiso' => false,
            'pisos' => [
                [
                    'nombre' => 'Único',
                    'filas' => 12,
                    'columnas' => 3,
                    'letraInicial' => 'A',
                    'numeroInicial' => 1,
                    'asientos' => [
                        [1, 'W', 1],      // Fila 1: asiento, panorámico, asiento
                        [1, 'W', 1],      // Fila 2
                        ['E', 'W', 1],    // Fila 3: escalera, panorámico, asiento
                        [1, 'C', 1],      // Fila 4: asiento, cafetera, asiento
                        [1, 1, 1],        // Fila 5
                        [1, 1, 1],        // Fila 6
                        [1, 1, 1],        // Fila 7
                        [1, 1, 1],        // Fila 8
                        [1, 1, 1],        // Fila 9
                        [1, 1, 1],        // Fila 10
                        [1, 1, 1],        // Fila 11
                        ['F', 1, 'F']     // Fila 12: cerca cafetera
                    ]
                ]
            ]
        ]
    ],
    // ID 2: Marcopolo Paradiso 1350
    [
        'idModelo' => 2,
        'config' => [
            'filas' => 10,
            'columnas' => 4,
            'doblePiso' => false,
            'pisos' => [
                [
                    'nombre' => 'Único',
                    'filas' => 10,
                    'columnas' => 4,
                    'letraInicial' => 'A',
                    'numeroInicial' => 1,
                    'asientos' => [
                        ['E', 'W', 'W', 1],     // Fila 1: escalera, panorámicos
                        [1, 'W', 'W', 1],      // Fila 2
                        [1, 1, 1, 1],          // Fila 3
                        [1, 1, 1, 1],          // Fila 4
                        [1, 'C', 'C', 1],      // Fila 5: cafetera
                        [1, 1, 1, 1],          // Fila 6
                        [1, 1, 1, 1],          // Fila 7
                        [1, 1, 1, 1],          // Fila 8
                        ['F', 1, 1, 'F'],      // Fila 9: cerca cafetera
                        ['F', 1, 1, 'F']       // Fila 10
                    ]
                ]
            ]
        ]
    ],
    // ID 3: Scania K340
    [
        'idModelo' => 3,
        'config' => [
            'filas' => 16,
            'columnas' => 3,
            'doblePiso' => false,
            'pisos' => [
                [
                    'nombre' => 'Único',
                    'filas' => 16,
                    'columnas' => 3,
                    'letraInicial' => 'A',
                    'numeroInicial' => 1,
                    'asientos' => [
                        ['E', 'W', 1],      // Fila 1
                        [1, 'W', 1],       // Fila 2
                        [1, 'W', 1],       // Fila 3
                        [1, 1, 1],         // Fila 4
                        [1, 1, 1],         // Fila 5
                        [1, 1, 1],         // Fila 6
                        [1, 1, 1],         // Fila 7
                        [1, 1, 1],         // Fila 8
                        [1, 'C', 1],       // Fila 9: cafetera
                        [1, 'C', 1],       // Fila 10
                        [1, 1, 1],         // Fila 11
                        [1, 1, 1],         // Fila 12
                        [1, 1, 1],         // Fila 13
                        [1, 1, 1],         // Fila 14
                        ['F', 1, 'F'],     // Fila 15: cerca cafetera
                        ['F', 1, 'F']      // Fila 16
                    ]
                ]
            ]
        ]
    ],
    // ID 4: Boeing 737-800
    [
        'idModelo' => 4,
        'config' => [
            'filas' => 12,
            'columnas' => 5,
            'doblePiso' => false,
            'pisos' => [
                [
                    'nombre' => 'Cabina',
                    'filas' => 12,
                    'columnas' => 5,
                    'letraInicial' => 'A',
                    'numeroInicial' => 1,
                    'asientos' => [
                        ['E', 'W', 'P', 'W', 1],     // Fila 1: escalera, panorámicos, pasillo
                        [1, 'W', 'P', 'W', 1],      // Fila 2
                        [1, 'W', 'P', 'W', 1],      // Fila 3
                        [1, 1, 'P', 1, 1],          // Fila 4
                        [1, 1, 'P', 1, 1],          // Fila 5
                        [1, 1, 'P', 1, 1],          // Fila 6
                        [1, 1, 'P', 1, 1],          // Fila 7
                        [1, 'C', 'P', 'C', 1],      // Fila 8: cafetera
                        [1, 1, 'P', 1, 1],          // Fila 9
                        [1, 1, 'P', 1, 1],          // Fila 10
                        ['F', 1, 'P', 1, 'F'],      // Fila 11: cerca cafetera
                        ['F', 1, 'P', 1, 'F']       // Fila 12
                    ]
                ]
            ]
        ]
    ],
    // ID 6: Marcopolo Doble Piso G7 (DOBLE PISO)
    [
        'idModelo' => 6,
        'config' => [
            'filas' => 27,
            'columnas' => 6,
            'doblePiso' => true,
            'pisos' => [
                [
                    'nombre' => 'Planta Superior',
                    'filas' => 13,
                    'columnas' => 6,
                    'letraInicial' => 'A',
                    'numeroInicial' => 1,
                    'asientos' => [
                        ['E', 'W', 'W', 'P', 'W', 'W'],
                        [1, 'W', 'W', 'P', 'W', 'W'],
                        [1, 'W', 'W', 'P', 'W', 'W'],
                        [1, 1, 1, 'P', 1, 1],
                        [1, 1, 1, 'P', 1, 1],
                        [1, 1, 1, 'P', 1, 1],
                        [1, 1, 1, 'P', 1, 1],
                        [1, 'C', 'C', 'P', 'C', 'C'],
                        [1, 1, 1, 'P', 1, 1],
                        [1, 1, 1, 'P', 1, 1],
                        [1, 1, 1, 'P', 1, 1],
                        ['F', 1, 1, 'P', 1, 'F'],
                        ['F', 1, 1, 'P', 1, 'F']
                    ]
                ],
                [
                    'nombre' => 'Planta Inferior',
                    'filas' => 14,
                    'columnas' => 6,
                    'letraInicial' => 'N',
                    'numeroInicial' => 1,
                    'asientos' => [
                        [1, 'W', 'W', 'P', 'W', 'W'],
                        [1, 'W', 'W', 'P', 'W', 'W'],
                        [1, 'W', 'W', 'P', 'W', 'W'],
                        [1, 1, 1, 'P', 1, 1],
                        [1, 1, 1, 'P', 1, 1],
                        [1, 1, 1, 'P', 1, 1],
                        [1, 1, 1, 'P', 1, 1],
                        [1, 'C', 'C', 'P', 'C', 'C'],
                        [1, 1, 1, 'P', 1, 1],
                        [1, 1, 1, 'P', 1, 1],
                        [1, 1, 1, 'P', 1, 1],
                        [1, 1, 1, 'P', 1, 1],
                        ['F', 1, 1, 'P', 1, 'F'],
                        ['F', 1, 1, 'P', 1, 'F']
                    ]
                ]
            ]
        ]
    ],
    // ID 7: Mercedes Doble Piso Comfort
    [
        'idModelo' => 7,
        'config' => [
            'filas' => 10,
            'columnas' => 5,
            'doblePiso' => true,
            'pisos' => [
                [
                    'nombre' => 'Planta Superior',
                    'filas' => 5,
                    'columnas' => 5,
                    'letraInicial' => 'A',
                    'numeroInicial' => 1,
                    'asientos' => [
                        ['E', 'W', 'D', 'W', 1],     // D = Cama doble
                        [1, 'W', 'D', 'W', 1],
                        [1, 1, 1, 1, 1],
                        ['C', 1, 1, 1, 'C'],
                        ['F', 1, 1, 1, 'F']
                    ]
                ],
                [
                    'nombre' => 'Planta Inferior',
                    'filas' => 5,
                    'columnas' => 5,
                    'letraInicial' => 'F',
                    'numeroInicial' => 1,
                    'asientos' => [
                        [1, 'W', 'S', 'W', 1],       // S = Semicama
                        [1, 'W', 'S', 'W', 1],
                        [1, 1, 1, 1, 1],
                        ['C', 1, 1, 1, 'C'],
                        ['F', 1, 1, 1, 'F']
                    ]
                ]
            ]
        ]
    ]
];

// Ejecutar actualizaciones
foreach ($actualizaciones as $actualización) {
    $json = json_encode($actualización['config']);
    if (actualizarModeloConElementos($actualización['idModelo'], $json)) {
        echo "✓ Modelo " . $actualización['idModelo'] . " actualizado correctamente\n";
    } else {
        echo "✗ Error actualizando modelo " . $actualización['idModelo'] . "\n";
    }
}

echo "\n¡Actualización completada!\n";
?>

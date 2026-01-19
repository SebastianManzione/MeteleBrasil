<?php
/**
 * Script para corregir encoding UTF-8 de ruta internacional y terminal Rosario
 * Fecha: 2026-01-16
 */

require_once("config/config.php");
require_once("admin/classes/conexion.php");

// Configurar conexión UTF-8
$pdo->exec("SET NAMES utf8mb4");
$pdo->exec("SET CHARACTER SET utf8mb4");

echo "Corrigiendo encoding UTF-8 de ruta internacional y terminal...\n\n";

// 1. Corregir terminal de Rosario
$sqlTerminal = "UPDATE terminal_transporte 
                SET nombre = 'Terminal de Ómnibus Mariano Moreno',
                    ciudad = 'Rosario'
                WHERE idTerminal = 66";
$pdo->exec($sqlTerminal);
echo "✓ Terminal de Rosario corregida\n";

// 2. Corregir ruta internacional
$sqlRuta = "UPDATE ruta_transporte 
            SET nombre = 'Rosario - Florianópolis - Río de Janeiro',
                nombre_en = 'Rosario - Florianopolis - Rio de Janeiro',
                nombre_pt = 'Rosário - Florianópolis - Rio de Janeiro',
                nombre_it = 'Rosario - Florianopolis - Rio de Janeiro',
                descripcion = 'Ruta internacional de larga distancia conectando Argentina y Brasil. Pasa por Florianópolis con opción de descenso intermedio.',
                descripcion_en = 'Long-distance international route connecting Argentina and Brazil. Passes through Florianopolis with intermediate stop option.',
                descripcion_pt = 'Rota internacional de longa distância conectando Argentina e Brasil. Passa por Florianópolis com opção de parada intermediária.',
                descripcion_it = 'Percorso internazionale di lunga distanza che collega Argentina e Brasile. Passa per Florianopolis con opzione di fermata intermedia.',
                duracion_estimada = '2 días 6h 00min'
            WHERE idRuta = 8";
$pdo->exec($sqlRuta);
echo "✓ Ruta internacional corregida\n";

// 3. Corregir paradas con tiempo
$sqlParada = "UPDATE ruta_paradas 
              SET tiempo_desde_inicio = '1 día 8h 00min'
              WHERE idRutaParada = 2";
$pdo->exec($sqlParada);
echo "✓ Tiempos de paradas corregidos\n\n";

// Verificar resultado
echo "--- Verificación ---\n";
$verificarRuta = $pdo->query("SELECT idRuta, nombre, duracion_estimada, distancia_km FROM ruta_transporte WHERE idRuta = 8");
foreach ($verificarRuta as $row) {
    echo "Ruta: {$row['nombre']}\n";
    echo "Duración: {$row['duracion_estimada']}\n";
    echo "Distancia: {$row['distancia_km']} km\n\n";
}

$verificarTerminal = $pdo->query("SELECT idTerminal, nombre, ciudad FROM terminal_transporte WHERE idTerminal = 66");
foreach ($verificarTerminal as $row) {
    echo "Terminal: {$row['nombre']} ({$row['ciudad']})\n\n";
}

echo "¡Corrección UTF-8 completada!\n";
?>

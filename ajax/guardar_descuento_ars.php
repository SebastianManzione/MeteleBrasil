<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descuento_aceptado = isset($_POST['descuento_aceptado']) ? $_POST['descuento_aceptado'] : '0';
    $descuento_ganado = isset($_POST['descuento_ganado']) ? floatval($_POST['descuento_ganado']) : 0;

    // Guardar en sesión que se aceptó el descuento
    $_SESSION['descuento_ars_aceptado'] = $descuento_aceptado;
    $_SESSION['descuento_ars_monto'] = $descuento_ganado;

    echo json_encode(array(
        'success' => true,
        'descuento_aceptado' => $descuento_aceptado,
        'descuento_ganado' => $descuento_ganado
    ));
} else {
    echo json_encode(array(
        'success' => false,
        'error' => 'Método no permitido'
    ));
}
?>

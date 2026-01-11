<?php
session_start();
$_SESSION['moneda_sel'] = 270;
$_SESSION['moneda_sel_sym'] = 'AR$';

include('admin/classes/reserva.php');

$reserva = getReserva('OJW990');

echo "=== RESERVA OJW990 ===\n";
echo "descuento_redondeo: ";
var_dump($reserva[0]['descuento_redondeo'] ?? 'NO EXISTE');
echo "\nmoneda_redondeo: ";
var_dump($reserva[0]['moneda_redondeo'] ?? 'NO EXISTE');
echo "\nmonedaSel: ";
var_dump($reserva[0]['monedaSel'] ?? 'NO EXISTE');
echo "\ntotal: ";
var_dump($reserva[0]['total'] ?? 'NO EXISTE');
echo "\n\n=== TODOS LOS CAMPOS ===\n";
print_r(array_keys($reserva[0]));

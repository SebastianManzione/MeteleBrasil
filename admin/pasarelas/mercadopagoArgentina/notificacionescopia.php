<?php 



include("config.php");

include("../../classes/functions.php");

include("../../classes/comprobantes.php");

include("../../classes/reservaEmail.php");

include("../../classes/reserva.php");

include("../../classes/moneda.php");

include("../../classes/usuario.php");

include("../../classes/convierte_monedas.php");

include("../../classes/parametros.php");

header('Access-Control-Allow-Origin: *');

header('Content-type: application/json;  charset=UTF-8');
$idReserva=687;
$total=1000;
$origenComprobante=33;
$monedaComprobante=188;
$compOrigen="asdff123";
$total_dolares=1000;


$resu=insertaComprobante($idReserva, $total, $origenComprobante, $monedaComprobante, $compOrigen, $total_dolares);
echo "************************************************************************** ";
print_r($resu);

    

 ?>
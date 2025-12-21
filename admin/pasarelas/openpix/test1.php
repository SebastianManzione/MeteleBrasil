<?php 


include("../../classes/functions.php");
include("../../classes/comprobantes.php");
include("../../classes/reservaEmail.php");
include("../../classes/reserva.php");
include("../../classes/moneda.php");
include("../../classes/usuario.php");
include("../../classes/convierte_monedas.php");


$seba='
{"event":"OPENPIX:CHARGE_COMPLETED","charge":{"customer":null,"value":23230,"identifier":"a26b176584ed44a9b90b57b780e8c5c3","correlationID":"PXR755","transactionID":"a26b176584ed44a9b90b57b780e8c5c3","status":"COMPLETED","giftbackAppliedValue":0,"discount":0,"valueWithDiscount":23230,"createdAt":"2022-09-06T05:08:46.799Z","paymentLinkID":"f0caa55a-a23f-4232-8cbb-1b3568e45539","additionalInfo":[],"updatedAt":"2022-09-06T05:09:32.879Z","expiresIn":900,"pixKey":"c527feae-8b39-46a5-b665-ff03bcdfd30c","brCode":"000201010212261030014br.gov.bcb.pix2581api.openpix.com.br/openpix/testing?transactionID=a26b176584ed44a9b90b57b780e8c5c35204000053039865406232.305802BR5913METELE_BRASIL6009Sao_Paulo62290525a26b176584ed44a9b90b57b786304BC53","paymentLinkUrl":"https://openpix.com.br/pay/f0caa55a-a23f-4232-8cbb-1b3568e45539","qrCodeImage":"https://api.openpix.com.br/openpix/charge/brcode/image/f0caa55a-a23f-4232-8cbb-1b3568e45539.png","globalID":"Q2hhcmdlOjYzMTZkNWRlMTlkMTM0NGNmMjk0MjhkZA=="},"pix":{"customer":null,"payer":{"name":"Sibelius Seraphini","taxID":{"taxID":"74786881015","type":"BR:CPF"},"email":"sibelius@entria.com.br","phone":"+5511940468989","correlationID":"2b4f553c-5bc4-4910-8046-1d85b597af96"},"charge":{"customer":null,"value":23230,"identifier":"a26b176584ed44a9b90b57b780e8c5c3","correlationID":"PXR755","transactionID":"a26b176584ed44a9b90b57b780e8c5c3","status":"COMPLETED","giftbackAppliedValue":0,"discount":0,"valueWithDiscount":23230,"createdAt":"2022-09-06T05:08:46.799Z","paymentLinkID":"f0caa55a-a23f-4232-8cbb-1b3568e45539","additionalInfo":[],"updatedAt":"2022-09-06T05:09:32.879Z","expiresIn":900,"brCode":"000201010212261030014br.gov.bcb.pix2581api.openpix.com.br/openpix/testing?transactionID=a26b176584ed44a9b90b57b780e8c5c35204000053039865406232.305802BR5913METELE_BRASIL6009Sao_Paulo62290525a26b176584ed44a9b90b57b786304BC53","paymentLinkUrl":"https://openpix.com.br/pay/f0caa55a-a23f-4232-8cbb-1b3568e45539","qrCodeImage":"https://api.openpix.com.br/openpix/charge/brcode/image/f0caa55a-a23f-4232-8cbb-1b3568e45539.png","globalID":"Q2hhcmdlOjYzMTZkNWRlMTlkMTM0NGNmMjk0MjhkZA=="},"value":23230,"time":"2022-09-06T05:09:32.857Z","endToEndId":"40cc34eefb0c417886782fcf9853bb9f","transactionID":"a26b176584ed44a9b90b57b780e8c5c3","infoPagador":"OpenPix testing","createdAt":"2022-09-06T05:09:32.862Z","globalID":"UGl4VHJhbnNhY3Rpb246NjMxNmQ2MGNhMmI2NjBmNmViYzY4ZGFm"},"company":{"id":"630d0002a81a3d5d7027bdd0","name":"Metele Brasil","taxID":"15484954000194"},"account":{}}


';

$resul=( json_decode($seba,true));

$value=$resul['charge']['value'];

if ($resul['charge']['status']=="COMPLETED") {
	$total=substr($value, 0,-2).".".substr($value, -2,2);
	$total=floatval($total);
	$correlationID=$resul['charge']['correlationID'];
		$identifier=$resul['charge']['identifier'];
	
		echo "correlationID ".$correlationID."<br>";
	echo "value crudeli ".$value."<br>";
echo "total ".$total."<br>";
echo "identifier ".$identifier."<br>";

$idReserva=getReserva($correlationID)[0]['idReserva'];
echo "idReserva".$idReserva;
$total_dolares=  $total_dolares=ConvierteMoneda(283,188, $total);
$comprobante=insertaComprobante($idReserva, $total, 7, 283, $identifier, $total_dolares, 1);
echo "string".$comprobante;
echo "1";
}

 ?>
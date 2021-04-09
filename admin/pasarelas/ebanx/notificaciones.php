<?php 
include("../functions.php");
include("config.php");





    
$phpInput=file_get_contents('php://input');
//echo "phpInput".$phpInput;


//print_r($phpInput["payment"]["hash"]);



//consultar

$ch = curl_init();
// definimos la URL a la que hacemos la petición
curl_setopt($ch, CURLOPT_URL,"https://sandbox.ebanxpay.com/ws/query");
// indicamos el tipo de petición: POST
curl_setopt($ch, CURLOPT_POST, TRUE);
// definimos cada uno de los parámetros
curl_setopt($ch, CURLOPT_POSTFIELDS, 
"integration_key=test_ik_BSWornvqoXPL1wFfy89olQ&
operation=request&hash=".$_POST["hash_codes"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$remote_server_output = curl_exec ($ch);
// cerramos la sesión cURL
curl_close ($ch);
 // hacemos lo que queramos con los datos recibidos
// por ejemplo, los mostramos
$remote_server_output=json_decode($remote_server_output,true);
$status=$remote_server_output["payment"]["status"];
$amount_br=$remote_server_output["payment"]["amount_br"];
$merchant_payment_code=$remote_server_output["payment"]["merchant_payment_code"];

$file = fopen("archivo.txt", "a+");
fwrite($file,"**********INICIO*********". PHP_EOL);
fwrite($file,InsertaPago($merchant_payment_code,$amount_br,5, 283,$merchant_payment_code). PHP_EOL);

fwrite($file,json_encode($_POST["hash_codes"]). PHP_EOL);
fwrite($file,"**********FIN*********". PHP_EOL);
fclose($file);
echo $remote_server_output["payment"];
?>
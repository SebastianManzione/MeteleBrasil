<?php 

function ConvierteMoneda($idMonedaOrigen,$idMonedaDestino, $valor){

//echo "or".$idMonedaOrigen."de".$idMonedaDestino;

// Validar que los parámetros sean válidos
if (empty($idMonedaOrigen) || empty($idMonedaDestino) || !is_numeric($valor)) {
    error_log("ConvierteMoneda: Parámetros inválidos - origen: $idMonedaOrigen, destino: $idMonedaDestino, valor: $valor");
    return 0;
}

 require("conexion.php");
 $idMonedaCambio=1;
    $data=["idMonedaCambio"=>$idMonedaCambio];
    $consulta = "select * from moneda_cambio WHERE idMonedaCambio=:idMonedaCambio";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    // Validar que la consulta retornó datos
    if (empty($resultado)) {
        error_log("ConvierteMoneda: No se encontraron tasas de cambio");
        return 0;
    }
    
    // Imprimir en pantalla
        $USS=$resultado[0]["dolar"];
     $ARS=$resultado[0]["pesoArg"];
     $BRL=$resultado[0]["rs"];
     $GUAR=$resultado[0]["guarani"];
     $PCH=$resultado[0]["pesoCh"];
     $EURR=$resultado[0]["euro"];

// Inicializar todas las variables de moneda
$dolares = $euros = $reales = $guaranis = $pesoArg = $pesoCh = 0;

switch ($idMonedaOrigen) {
case 188:
		# dolar americano
	$dolares=$valor;
	$euros=$valor*$EURR;

	$reales=($valor)*$BRL;
	$guaranis=($valor)*$GUAR;
	$pesoArg=($valor)*$ARS;
	$pesoCh=($valor)*$PCH;

		break;
		#fin dolares
case 213:
		# euro
	$dolares=$valor/$EURR;
	$euros=$valor;
	$reales=($valor/$EURR)*$BRL;
	$guaranis=($valor/$EURR)*$GUAR;
	$pesoArg=($valor/$EURR)*$ARS;
	$pesoCh=($valor/$EURR)*$PCH;

		break;
		#fin euro

case 270:
		# Peso argentino
	$reales=($valor/$ARS)*$BRL;
	$guaranis=($valor/$ARS)*$GUAR;
	$dolares=($valor/$ARS);
	$euros=($valor/$ARS)*$EURR;
	$pesoArg=$valor;
	$pesoCh=($valor/$ARS)*$PCH;

		break;
		#fin peso arg
case 283:
		# Real brasilero
	$reales=$valor;
	$guaranis=($valor/$BRL)*$GUAR;
	$dolares=($valor/$BRL)*$USS;
	$euros=($valor/$BRL)*$EURR;
	$pesoArg=($valor/$BRL)*$ARS;
	$pesoCh=($valor/$BRL)*$PCH;
	
		break;
		#fin real
case 225:
		# Guaranis
	$reales=($valor/$GUAR)*$BRL;
	$guaranis=($valor);
	$dolares=($valor/$GUAR)*$USS;
	$euros=($valor/$GUAR)*$EURR;
	$pesoArg=($valor/$GUAR)*$ARS;
		$pesoCh=($valor/$GUAR)*$PCH;
	
		break;
		#fin guaranis
case 271:
		# Peso chileno
	$chilenos=($valor/$PCH)*$PCH;
	$reales=($valor/$PCH)*$BRL;
	$guaranis=($valor/$PCH);
	$dolares=($valor/$PCH)*$USS;
	$euros=($valor/$PCH)*$EURR;
	$pesoArg=($valor/$PCH)*$ARS;
	$pesoCh=($valor);
		break;

}
	switch ($idMonedaDestino) {
		case 188:
			return round($dolares,2, PHP_ROUND_HALF_UP);

		break;
		
		case 213:
		return round($euros,2, PHP_ROUND_HALF_UP);
		
		break;	
		
		case 225:
		return round($guaranis,2, PHP_ROUND_HALF_UP);
		
		break;
		
		case 270:
		return round($pesoArg,2, PHP_ROUND_HALF_UP);
		
		break;
		
		case 271:
		return round($pesoCh,2, PHP_ROUND_HALF_UP);
			
		break;

		case 283:
		return round($reales,2, PHP_ROUND_HALF_UP);
			
		break;	
		default: 
		error_log("ConvierteMoneda: Moneda destino no soportada: $idMonedaDestino (origen: $idMonedaOrigen, valor: $valor)");
		return 0; // Retornar 0 en lugar de string
		break;
	
					}

 
}

function getCotizacionMonedas(){

    require("conexion.php");
  
    $consulta = "select * from moneda_cambio ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function updateCotizacionMonedas($pesoArg, $rs, $guarani, $pesoCh, $euro){



require("conexion.php");



 $data=[

"pesoArg"=>$pesoArg, "rs"=>$rs, "guarani"=>$guarani, "pesoCh"=>$pesoCh, "euro"=>$euro ];

$consulta = "UPDATE moneda_cambio SET pesoArg=:pesoArg, rs=:rs, guarani=:guarani, pesoCh=:pesoCh, euro=:euro WHERE idMonedaCambio = 1 ";



$comando = $pdo->prepare($consulta);



$comando->execute($data);

$cuenta_col = $comando->columnCount();



$resultado = $comando->rowCount();







return $resultado;





}

 ?>
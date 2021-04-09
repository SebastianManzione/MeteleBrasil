<?php 

function ConvierteMoneda($idMonedaOrigen,$idMonedaDestino, $valor){


 require("conexion.php");
 $idMonedaCambio=1;
    $data=["idMonedaCambio"=>$idMonedaCambio];
    $consulta = "select * from moneda_cambio WHERE idMonedaCambio=:idMonedaCambio";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
        $USS=$resultado[0]["dolar"];
     $ARS=$resultado[0]["pesoArg"];
     $BRL=$resultado[0]["rs"];
     $GUAR=$resultado[0]["guarani"];
     $PCH=$resultado[0]["pesoCh"];
     $EURR=$resultado[0]["euro"];

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
		return " moneda no soportada aun";
		break;
	
					}

 
}

 ?>
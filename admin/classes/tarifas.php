<?php







    function getTarifa($idServicioSalidasTarifas){


    require("conexion.php");
    $data=["idServicioSalidasTarifas"=>$idServicioSalidasTarifas];
    $consulta = "select * from servicio_salidas_tarifas WHERE idServicioSalidasTarifas=:idServicioSalidasTarifas";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;

    }





    function getTarifas($idServicioSalidas){

    require("conexion.php");
    $data=["idServicioSalidas"=>$idServicioSalidas];
    $consulta = "select * from servicio_salidas_tarifas WHERE idServicioSalidas=:idServicioSalidas";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    return $resultado;


    }



  



  function altaTarifa($idServicioSalidas,$nombre, $idFromEdad,$idToEdad,$idTipoTarifa,$valor,$minimo, $idCancelaciones, $comisiona){

    require("conexion.php");

        $data=["idServicioSalidas"=> $idServicioSalidas, "nombre"=>$nombre, "idFromEdad" => $idFromEdad,"idToEdad"=> $idToEdad, "idTipoTarifa"=>$idTipoTarifa, "valor"=>$valor,"minimo"=>$minimo, "idCancelaciones"=>$idCancelaciones, "comisiona"=>  $comisiona];

        $consulta = "INSERT INTO servicio_salidas_tarifas (idServicioSalidas,nombre, idFromEdad, idToEdad, idTipoTarifa, valor, minimo, idCancelaciones,  comisiona) VALUES (:idServicioSalidas, :nombre, :idFromEdad, :idToEdad, :idTipoTarifa,:valor,:minimo,:idCancelaciones, :comisiona) ";

        $comando = $pdo->prepare($consulta);
        $comando->execute($data);
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;

        }



function getComisionTarifa($idServicioSalidasTarifas){

    require("conexion.php");
    $data=["idServicioSalidasTarifas"=>$idServicioSalidasTarifas];
    $consulta = "select * from servicio_tarifas_comision WHERE idServicioSalidasTarifas=:idServicioSalidasTarifas";

    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


    return $resultado;



    }


    function calculaTarifa($idServicioSalidasTarifas, $cantidad){

        require("conexion.php");
            if (isset($_SESSION["cupon_descuento"]["descuentoPorcentual"]) ) {
         $descuentoCupon=$_SESSION["cupon_descuento"]["descuentoPorcentual"]/100;  
     }

        $totalDescuentos=0;
        $tarifas= getTarifa($idServicioSalidasTarifas);

            if (empty($tarifas)) {
                return [];
            }

            $salida=getSalida($tarifas[0]['idServicioSalidas']);
            $retorno=array();


            for ($i=0; $i < count($tarifas); $i++) { 
            $retorno[$i]['idServicioSalidasTarifas']=$tarifas[$i]['idServicioSalidasTarifas'];
            $retorno[$i]['idServicioSalidas']=$tarifas[$i]['idServicioSalidas'];
            $retorno[$i]['nombre']=$tarifas[$i]['nombre'];
            $retorno[$i]['idFromEdad']=$tarifas[$i]['idFromEdad'];
            $retorno[$i]['idToEdad']=$tarifas[$i]['idToEdad'];
            $retorno[$i]['menor']=isset($tarifas[$i]['menor']) ? (int)$tarifas[$i]['menor'] : 0;
            $retorno[$i]['edadFrom']=getEdad($tarifas[$i]['idFromEdad'])[0]["valor"];
            $retorno[$i]['edadTo']=getEdad($tarifas[$i]['idToEdad'])[0]["valor"];
            $retorno[$i]['idTipoTarifa']=$tarifas[$i]['idTipoTarifa'];
            $retorno[$i]['valorTarifa']=$tarifas[$i]['valor'];
            
            $descuentoTarifa=0;
            $tarifas[$i]['valor']=$tarifas[$i]['valor']*$cantidad; 



                 if (isset($_SESSION["cupon_descuento"]["descuentoPorcentual"]) ) {



         $descuentoCupon=$_SESSION["cupon_descuento"]["descuentoPorcentual"]/100;  



                 if ($_SESSION['moneda_sel_sym'] != 'AR$') {
					$descuentoTarifa=$tarifas[$i]['valor']* $descuentoCupon;
					$valorAntes = $tarifas[$i]['valor'];
					$tarifas[$i]['valor']=$tarifas[$i]['valor']-$descuentoTarifa;
					// Redondear inmediatamente después del descuento
					$tarifas[$i]['valor']=round($tarifas[$i]['valor'], 4, PHP_ROUND_HALF_UP);
					error_log("DEBUG DESCUENTO - Valor antes: $valorAntes, descuento: $descuentoTarifa, valor después: {$tarifas[$i]['valor']}");
				}



     }


			 if ($descuentoTarifa > 0) {
			 	$descuentoTarifaConvertido=convierteMoneda( $salida[0]["idMoneda"],$_SESSION['moneda_sel'],$descuentoTarifa);
			 	$impuestos_pais = isset($_SESSION["impuestos_pais"]) ? $_SESSION["impuestos_pais"] : 0;
			 	$descuentoTarifaConvertido=$descuentoTarifaConvertido*($impuestos_pais+1);
			 	$descuentoTarifaConvertido=round($descuentoTarifaConvertido,2, PHP_ROUND_HALF_UP);
			 	$totalDescuentos+=$descuentoTarifaConvertido;
			 }
			 
 $retorno[$i]['idMonedaPrestador']=$salida[0]["idMoneda"];
            $retorno[$i]['valor']=convierteMoneda( $salida[0]["idMoneda"],$_SESSION['moneda_sel'],$tarifas[$i]['valor']);
            // NO redondear aquí a 2 decimales para ARS/CLP/PYG - dejar que convierteMoneda() retorne valor completo
            // Solo redondear para monedas con centavos
            if (!in_array($_SESSION['moneda_sel'], [270, 271, 225])) {
                $retorno[$i]['valor']=round($retorno[$i]['valor'],2, PHP_ROUND_HALF_UP);
            }

            if ($tarifas[$i]['comisiona']==1) {

               $comisionVendedor=(float)getComisionIdServicioSalidasTarifas($idServicioSalidasTarifas,1);
               $comisionSistema=(float)getComisionIdServicioSalidasTarifas($idServicioSalidasTarifas,2);
               
               // Si no hay comisión específica por tarifa, usar la comisión por defecto del prestador
               if ($comisionVendedor == 0 && $comisionSistema == 0 && isset($salida[0]['idPrestador'])) {
                   $idPrestador = $salida[0]['idPrestador'];
                   
                   // Buscar comisión por defecto del prestador (la conexión ya está requerida al inicio de la función)
                   $dataComision = ["idPrestador" => $idPrestador];
                   $queryComision = "SELECT comisionVendedor, comisionSistema FROM prestador_comision WHERE idPrestador = :idPrestador LIMIT 1";
                   $cmdComision = $pdo->prepare($queryComision);
                   $cmdComision->execute($dataComision);
                   $comisionPrestador = $cmdComision->fetch(PDO::FETCH_ASSOC);
                   
                   if (!empty($comisionPrestador)) {
                       $comisionVendedor = floatval($comisionPrestador['comisionVendedor'] ?? 0) / 100;
                       $comisionSistema = floatval($comisionPrestador['comisionSistema'] ?? 0) / 100;
                   }
               }
               
               // Guardar los porcentajes para recalcular después de impuestos
               $retorno[$i]['comisionVendedorPorcentaje'] = (float)$comisionVendedor;
               $retorno[$i]['comisionSistemaPorcentaje'] = (float)$comisionSistema;

            }

            else{

                    $retorno[$i]['comisionVendedorPorcentaje']=0;
                    $retorno[$i]['comisionSistemaPorcentaje']=0;

            }

            
            $impuestos_pais = isset($_SESSION["impuestos_pais"]) ? $_SESSION["impuestos_pais"] : 0;
            $retorno[$i]['valor']=(float)$retorno[$i]['valor']*((float)$impuestos_pais+1);

            // NO redondear para monedas devaluadas - el redondeo comercial se aplica después
            // Solo redondear monedas con centavos (USD, EUR, BRL, etc)
            if (!in_array($_SESSION['moneda_sel'], [270, 271, 225])) {
                $retorno[$i]['valor']=round($retorno[$i]['valor'], 2, PHP_ROUND_HALF_UP);
            }

			if ($_SESSION['moneda_sel_sym'] == 'AR$' && isset($_SESSION["cupon_descuento"]["descuentoPorcentual"])) {
				$descuentoTarifaConvertido=$retorno[$i]['valor'] * $descuentoCupon;
				$descuentoTarifaConvertido=round($descuentoTarifaConvertido, $decimalesRedondeo, PHP_ROUND_HALF_UP);
				$retorno[$i]['valor']=$retorno[$i]['valor']-$descuentoTarifaConvertido;
		$retorno[$i]['valor']=round($retorno[$i]['valor'], $decimalesRedondeo, PHP_ROUND_HALF_UP);
		$totalDescuentos+=$descuentoTarifaConvertido;
	}

            // Redondeo especial para monedas devaluadas (ARS, CLP, PYG): al siguiente múltiplo de 1000 hacia arriba
            // IMPORTANTE: Calcular DESPUÉS de aplicar cupones para que sea correcto
            $valorOriginal = $retorno[$i]['valor'];
            $redondeoDiferencia = 0;

            if (in_array($_SESSION['moneda_sel'], [270, 271, 225])) { // ARS, CLP, PYG
                // REDONDEO: Al siguiente múltiplo de 1000 hacia arriba (máximo +1000)
                // Aplicar por persona, luego multiplicar por cantidad
                if ($cantidad > 0) {
                    $precioUnitario = $valorOriginal / $cantidad;
                    $precioUnitarioInflado = ceil($precioUnitario / 1000) * 1000;
                    $valorInflado = $precioUnitarioInflado * $cantidad;
                    $redondeoDiferencia = $valorInflado - $valorOriginal;
                    error_log("REDONDEO - Tarifa {$tarifas[$i]['idServicioSalidasTarifas']}: Cant=$cantidad | Original=$valorOriginal | Unitario=$precioUnitario | UnitInflado=$precioUnitarioInflado | TotalInflado=$valorInflado | Dif=$redondeoDiferencia");
                    $retorno[$i]['valor'] = $valorInflado;
                } else {
                    // Si cantidad es 0, aplicar redondeo al valor original
                    $valorInflado = ceil($valorOriginal / 1000) * 1000;
                    $redondeoDiferencia = $valorInflado - $valorOriginal;
                    $retorno[$i]['valor'] = $valorInflado;
                }
            }
	
            // AHORA calcular las comisiones basadas en el valor ORIGINAL (antes de redondeos)
            // Las comisiones SIEMPRE se calculan sobre el precio real, no sobre el inflado
            $retorno[$i]['comisionVendedor']=(float)$valorOriginal*(float)$retorno[$i]['comisionVendedorPorcentaje'];
            $retorno[$i]['comisionSistema']=(float)$valorOriginal*(float)$retorno[$i]['comisionSistemaPorcentaje'];
            
            // Formatear con separadores de miles y decimales según moneda
            $symMoneda = $_SESSION['moneda_sel_sym'];
            $decimales = (stripos($symMoneda, 'AR') !== false) ? 0 : 2;
            $retorno[$i]['valorFormateado'] = number_format($retorno[$i]['valor'], $decimales, ',', '.');
            $retorno[$i]['redondeoDiferencia'] = $redondeoDiferencia;
            $retorno[$i]['valorOriginal'] = $valorOriginal;  // Guardar precio original para auditoria

            $retorno[$i]['valorSym']=$_SESSION['moneda_sel_sym'].' '.$retorno[$i]['valorFormateado'];
            $retorno[$i]['valorSinIva']=$retorno[$i]['valor'];
            $retorno[$i]['valorSinIvaSym']=$_SESSION['moneda_sel_sym'].' '.number_format($retorno[$i]['valor'], $decimales, ',', '.');
            $impuestos_pais_final = isset($_SESSION["impuestos_pais"]) ? $_SESSION["impuestos_pais"] : 0;
            $retorno[$i]['valorDeIva']=$retorno[$i]['valor']*($impuestos_pais_final);
            $retorno[$i]['valorDeIvaSym']=$_SESSION['moneda_sel_sym']." ".number_format($retorno[$i]['valor']*($impuestos_pais_final), $decimales, ',', '.');
            $retorno[$i]['valor']=$retorno[$i]['valor'];            $retorno[$i]['minimo']=$tarifas[$i]['minimo'];
            $retorno[$i]['idCancelaciones']=$tarifas[$i]['idCancelaciones'];
            $retorno[$i]['cancelaciones']=getTipoCancelaciones($tarifas[$i]['idCancelaciones'])[0];
             $retorno[$i]['totalDescuentos']=$totalDescuentos;

            }

    return $retorno;

}





  function updateTarifa($idServicioSalidasTarifas, $nombre, $idFromEdad, $idToEdad, $idTipoTarifa, $valor, $minimo, $idCancelaciones, $comisiona){

        require("conexion.php");

 $data=["idServicioSalidasTarifas"=>$idServicioSalidasTarifas, "nombre"=>$nombre, "idFromEdad"=>$idFromEdad, "idToEdad"=>$idToEdad, "idTipoTarifa"=>$idTipoTarifa, "valor"=>$valor, "minimo"=>$minimo, "idCancelaciones"=>$idCancelaciones, "comisiona"=>$comisiona ];

$consulta = "UPDATE servicio_salidas_tarifas SET nombre=:nombre, idFromEdad=:idFromEdad, idToEdad=:idToEdad, idTipoTarifa=:idTipoTarifa, valor=:valor, minimo=:minimo, idCancelaciones=:idCancelaciones, comisiona=:comisiona WHERE idServicioSalidasTarifas=:idServicioSalidasTarifas";

$comando = $pdo->prepare($consulta);
$comando->execute($data);
$cuenta_col = $comando->columnCount();
$resultado = $comando->rowCount();

return $resultado;


}








    



?>
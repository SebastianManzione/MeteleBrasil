<?php

    function getTarifa($idServicioSalidasTarifas){

    require("conexion.php");
    $data=["idServicioSalidasTarifas"=>$idServicioSalidasTarifas];
    $consulta = "select * from servicio_salidas_tarifas WHERE idServicioSalidasTarifas=:idServicioSalidasTarifas";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
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
    // Imprimir en pantalla
    return $resultado;
    
    
    }
  
  function altaTarifa($idServicioSalidas,$nombre, $idFromEdad,$idToEdad,$idTipoTarifa,$valor,$minimo, $idCancelaciones, $comisiona){


        require("conexion.php");
        $data=["idServicioSalidas"=> $idServicioSalidas, "nombre"=>$nombre, "idFromEdad" => $idFromEdad,"idToEdad"=> $idToEdad, "idTipoTarifa"=>$idTipoTarifa, "valor"=>$valor,"minimo"=>$minimo, "idCancelaciones"=>$idCancelaciones, "comisiona"=>  $comisiona];

        $consulta = "INSERT INTO servicio_salidas_tarifas (idServicioSalidas,nombre, idFromEdad, idToEdad, idTipoTarifa, valor, minimo, idCancelaciones,  comisiona) VALUES (:idServicioSalidas, :nombre, :idFromEdad, :idToEdad, :idTipoTarifa,:valor,:minimo,:idCancelaciones, :comisiona) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
;
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
    // Imprimir en pantalla
    return $resultado;
    
    
    }



    function calculaTarifa($idServicioSalidasTarifas,
$cantidad){
 
        if (isset($_SESSION["cupon_descuento"]["descuentoPorcentual"]) ) {
         $descuentoCupon=$_SESSION["cupon_descuento"]["descuentoPorcentual"]/100;  
         
     }

        $totalDescuentos=0;
            $tarifas= getTarifa($idServicioSalidasTarifas);
    
            $salida=getSalida($tarifas[0]['idServicioSalidas']);
        
            $retorno=array();
            for ($i=0; $i < count($tarifas); $i++) { 
            $retorno[$i]['idServicioSalidasTarifas']=$tarifas[$i]['idServicioSalidasTarifas'];
            $retorno[$i]['idServicioSalidas']=$tarifas[$i]['idServicioSalidas'];
            $retorno[$i]['nombre']=$tarifas[$i]['nombre'];
            $retorno[$i]['idFromEdad']=$tarifas[$i]['idFromEdad'];
            $retorno[$i]['idToEdad']=$tarifas[$i]['idToEdad'];
                $retorno[$i]['edadFrom']=getEdad($tarifas[$i]['idFromEdad'])[0]["valor"];
            $retorno[$i]['edadTo']=getEdad($tarifas[$i]['idToEdad'])[0]["valor"];
            $retorno[$i]['idTipoTarifa']=$tarifas[$i]['idTipoTarifa'];
        
         
      $descuentoTarifa=0;
            $tarifas[$i]['valor']=$tarifas[$i]['valor']*$cantidad; 
                 if (isset($_SESSION["cupon_descuento"]["descuentoPorcentual"]) ) {
         $descuentoCupon=$_SESSION["cupon_descuento"]["descuentoPorcentual"]/100;  
       
            $descuentoTarifa=$tarifas[$i]['valor']* $descuentoCupon;
            $tarifas[$i]['valor']=$tarifas[$i]['valor']-$descuentoTarifa;
     }



             $totalDescuentos+=$descuentoTarifa;
            $retorno[$i]['valor']=convierteMoneda( $salida[0]["idMoneda"],$_SESSION['moneda_sel'],$tarifas[$i]['valor']);
            if ($tarifas[$i]['comisiona']==1) {
               $comisionVendedor=getComisionIdServicioSalidasTarifas($idServicioSalidasTarifas,1);
               $comisionSistema=getComisionIdServicioSalidasTarifas($idServicioSalidasTarifas,2); 
             $retorno[$i]['comisionVendedor']=$retorno[$i]['valor']*$comisionVendedor; //retornamos la suma de comisiones
             $retorno[$i]['comisionSistema']=$retorno[$i]['valor']*$comisionSistema; //retornamos la suma de comisiones
            }
            else{
                    $retorno[$i]['comisionVendedor']=0; //retornamos la suma de comisiones
                    $retorno[$i]['comisionSistema']=0; //retornamos la suma de comisiones
            }
            
            
  
            $retorno[$i]['valorSinIva']=$retorno[$i]['valor'];
            $retorno[$i]['valorSinIvaSym']=$_SESSION['moneda_sel_sym']."".$retorno[$i]['valor'];
            $retorno[$i]['valorDeIva']=$retorno[$i]['valor']*($_SESSION["impuestos_pais"]);
            $retorno[$i]['valorDeIvaSym']=$_SESSION['moneda_sel_sym']."".round($retorno[$i]['valor']*($_SESSION["impuestos_pais"]),2, PHP_ROUND_HALF_UP);
            $retorno[$i]['valor']=$retorno[$i]['valor']*($_SESSION["impuestos_pais"]+1);
            $retorno[$i]['valor']=round($retorno[$i]['valor'],2, PHP_ROUND_HALF_UP);
            $retorno[$i]['valorSym']=$_SESSION['moneda_sel_sym']."".$retorno[$i]['valor'];
            $retorno[$i]['valor']=$retorno[$i]['valor'];
            $retorno[$i]['minimo']=$tarifas[$i]['minimo'];
            $retorno[$i]['idCancelaciones']=$tarifas[$i]['idCancelaciones'];
            $retorno[$i]['cancelaciones']=getTipoCancelaciones($tarifas[$i]['idCancelaciones'])[0];
             $retorno[$i]['totalDescuentos']=$totalDescuentos;
            }
            
    return $retorno;
         
}

          /*
function borraPrestador($idPrestador){

require("conexion.php");
    $data=["idPrestador"=> $idPrestador];
    $consulta = "DELETE FROM prestadores WHERE idPrestador=:idPrestador ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $cuenta_row = $comando->rowCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    
    return $cuenta_row;
    
    
    }*/
    
?>
<?php
function getServiciosAdicionales(){

    require("conexion.php");
  
    $consulta = "select * from servicios_adicionales";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

 function getServicioAdicionalIncluidoYNoIncluido($idServiciosAdicionales, $idServicioSalidas){

        require("conexion.php");
        $data=["idServicioSalidas"=>$idServicioSalidas, "idServiciosAdicionales"=>$idServiciosAdicionales, ];
        $consulta = "select * from servicio_salidas_adicionales WHERE idServicioSalidas=:idServicioSalidas AND idServiciosAdicionales=:idServiciosAdicionales";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        $cuenta_col = $comando->columnCount();
        
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
 
        return $resultado;
        
        
        }

    function getServicioAdicionalSalida($idServicioSalidasAdicionales){

        require("conexion.php");
        $data=["idServicioSalidasAdicionales"=>$idServicioSalidasAdicionales];
        $consulta = "select * from servicio_salidas_adicionales ssad INNER JOIN servicios_adicionales sad ON ssad.idServiciosAdicionales=sad.idServiciosAdicionales WHERE idServicioSalidasAdicionales=:idServicioSalidasAdicionales";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        $cuenta_col = $comando->columnCount();
        
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
 
        return $resultado;
        
        
        }
    function getServiciosAdicionalesCategoria($idCategoria){

    require("conexion.php");
    $data=["idCategoria"=>$idCategoria];
    $consulta = "select * from servicios_adicionales_categoria SAC INNER JOIN servicios_adicionales SAD ON SAC.idServiciosAdicionales =  SAD.idServiciosAdicionales WHERE idCatSrv=:idCategoria";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }


   function getServiciosAdicionalesSalidaIncluidos($idServicioSalidas){

    require("conexion.php");
    $data=["idServicioSalidas"=>$idServicioSalidas];
    $consulta = "select * from servicio_salidas_adicionales ssa INNER JOIN servicios_adicionales SAD ON ssa.idServiciosAdicionales =  SAD.idServiciosAdicionales WHERE idServicioSalidas=:idServicioSalidas AND valor = 0";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

  function getServiciosAdicionalesAllSalidas($idServiciosAdicionales){

    require("conexion.php");
    $idServiciosAdicionales=$idServiciosAdicionales;
    $data=["idServiciosAdicionales"=>$idServiciosAdicionales];
    $consulta = "SELECT * FROM servicios_salidas_adicionales WHERE idServiciosAdicionales=:idServiciosAdicionales ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


    return $resultado;
    
    
    }

  function getServiciosAdicionalesAllCategorias($idServiciosAdicionales){

    require("conexion.php");
  
    $data=["idServiciosAdicionales"=>$idServiciosAdicionales];
    $consulta = "SELECT * FROM servicios_adicionales_categoria WHERE idServiciosAdicionales=:idServiciosAdicionales";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


    return $resultado;
    
    
    }




   function getSvAdicionalesNoIncluidosEnCategoria($idCategoria_servicio){

    require("conexion.php");
    $idCatSrv=$idCategoria_servicio;
    $data=["idCatSrv"=>$idCatSrv];
    $consulta = "SELECT * FROM servicios_adicionales sa
     where not EXISTS (select idServiciosAdicionales from servicios_adicionales_categoria sac where sac.idServiciosAdicionales = sa.idServiciosAdicionales AND sac.idCatSrv=:idCatSrv) 
    ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


    return $resultado;
    
    
    }
function eliminaSvAdicionalCategoria($idServiciosAdicionalesCategoria){

   require("conexion.php");

    $data=["idServiciosAdicionalesCategoria"=>$idServiciosAdicionalesCategoria];
    $consulta="DELETE FROM servicios_adicionales_categoria where idServiciosAdicionalesCategoria=:idServiciosAdicionalesCategoria";
$comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    return $resultado;

}

function agregaSvAdicionalCategoria($idCatSrv, $idServiciosAdicionales){
       require("conexion.php");

    $data=["idCatSrv"=>$idCatSrv,"idServiciosAdicionales"=>$idServiciosAdicionales];
 $consulta="INSERT INTO servicios_adicionales_categoria (idCatSrv, idServiciosAdicionales) VALUES(:idCatSrv, :idServiciosAdicionales)";
 $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    return $resultado;
}


   function getServiciosAdicionalesSalidaNoIncluidos($idServicioSalidas){

    require("conexion.php");

  
    $data=["idServicioSalidas"=>$idServicioSalidas];
    $consulta = "select * from servicio_salidas_adicionales ssa INNER JOIN servicios_adicionales SAD ON ssa.idServiciosAdicionales =  SAD.idServiciosAdicionales WHERE idServicioSalidas=:idServicioSalidas AND valor > 0";
   
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    $serviciosAdicionalesSalidaNoIncluidos=array();
    for ($i=0; $i < count($resultado); $i++) { 
        $idMoneda=$resultado[$i]["idMoneda"];
        $serviciosAdicionalesSalidaNoIncluidos[$i]['idMoneda']=$resultado[$i]["idMoneda"];
         $serviciosAdicionalesSalidaNoIncluidos[$i]['idServicioSalidas']=$resultado[$i]["idServicioSalidas"];
          $serviciosAdicionalesSalidaNoIncluidos[$i]['idServicioSalidasAdicionales']=$resultado[$i]["idServicioSalidasAdicionales"];
           $serviciosAdicionalesSalidaNoIncluidos[$i]['idServiciosAdicionales']=$resultado[$i]["idServiciosAdicionales"];
            $serviciosAdicionalesSalidaNoIncluidos[$i]['nombre']=$resultado[$i]["nombre"];
     $serviciosAdicionalesSalidaNoIncluidos[$i]['descripcion']=$resultado[$i]["descripcion"];
              $serviciosAdicionalesSalidaNoIncluidos[$i]['valor']=convierteMoneda( $idMoneda,$_SESSION['moneda_sel'],$resultado[$i]["valor"]);

           $serviciosAdicionalesSalidaNoIncluidos[$i]['valor']=$serviciosAdicionalesSalidaNoIncluidos[$i]['valor']*($_SESSION["impuestos_pais"]+1);

          $serviciosAdicionalesSalidaNoIncluidos[$i]['valor']= round($serviciosAdicionalesSalidaNoIncluidos[$i]['valor'],2, PHP_ROUND_HALF_UP);
         $serviciosAdicionalesSalidaNoIncluidos[$i]['valor']=    $_SESSION['moneda_sel_sym']."".  $serviciosAdicionalesSalidaNoIncluidos[$i]['valor'];
        
    }
    return $serviciosAdicionalesSalidaNoIncluidos;
    
    
    }




   function getValorServiciosAdicionalesSalida($idServicioSalidasAdicionales,$cantidad){

    require("conexion.php");

  
    $data=["idServicioSalidasAdicionales"=>$idServicioSalidasAdicionales];
    $consulta = "select * from servicio_salidas_adicionales ssa INNER JOIN servicios_adicionales SAD ON ssa.idServiciosAdicionales =  SAD.idServiciosAdicionales WHERE idServicioSalidasAdicionales=:idServicioSalidasAdicionales ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    $serviciosAdicionalesSalidaNoIncluidos=array();
    for ($i=0; $i < count($resultado); $i++) { 
        $idMoneda=$resultado[$i]["idMoneda"];
        $serviciosAdicionalesSalidaNoIncluidos[$i]['idMoneda']=$resultado[$i]["idMoneda"];
         $serviciosAdicionalesSalidaNoIncluidos[$i]['idServicioSalidas']=$resultado[$i]["idServicioSalidas"];
          $serviciosAdicionalesSalidaNoIncluidos[$i]['idServicioSalidasAdicionales']=$resultado[$i]["idServicioSalidasAdicionales"];
           $serviciosAdicionalesSalidaNoIncluidos[$i]['idServiciosAdicionales']=$resultado[$i]["idServiciosAdicionales"];
            $serviciosAdicionalesSalidaNoIncluidos[$i]['nombre']=$resultado[$i]["nombre"];
            $serviciosAdicionalesSalidaNoIncluidos[$i]['descripcion']=$resultado[$i]["descripcion"];
  $precioUnitarioSIva=$resultado[$i]["valor"];
  $precio=$resultado[$i]["valor"]*$cantidad;
$valorIva=$precio*$_SESSION["impuestos_pais"];
$precioConIva=$precio+$valorIva;
$serviciosAdicionalesSalidaNoIncluidos[$i]['precioUnitarioSIva']=$precioUnitarioSIva;
$serviciosAdicionalesSalidaNoIncluidos[$i]['precio']=$precio;
$serviciosAdicionalesSalidaNoIncluidos[$i]['valorIva']=$valorIva;
$serviciosAdicionalesSalidaNoIncluidos[$i]['valor']=convierteMoneda( $idMoneda,$_SESSION['moneda_sel'],$precioConIva);
              $serviciosAdicionalesSalidaNoIncluidos[$i]['valor']=convierteMoneda( $idMoneda,$_SESSION['moneda_sel'],$precioConIva);

          $serviciosAdicionalesSalidaNoIncluidos[$i]['valor']= round($serviciosAdicionalesSalidaNoIncluidos[$i]['valor'],2, PHP_ROUND_HALF_UP);
          $serviciosAdicionalesSalidaNoIncluidos[$i]['valorSymUnitario']=    $_SESSION['moneda_sel_sym']."".  ($serviciosAdicionalesSalidaNoIncluidos[$i]['valor']);
          if ($cantidad>0) {
            $serviciosAdicionalesSalidaNoIncluidos[$i]['valorSymUnitario']=    $_SESSION['moneda_sel_sym']."".  ($serviciosAdicionalesSalidaNoIncluidos[$i]['valor']/$cantidad);
          
        }
         
         $serviciosAdicionalesSalidaNoIncluidos[$i]['valorSym']=    $_SESSION['moneda_sel_sym']."".  $serviciosAdicionalesSalidaNoIncluidos[$i]['valor'];
    }
    return $serviciosAdicionalesSalidaNoIncluidos;
    
    
    }




  function setServiciosAdicionalesSalida($idServicioSalidas, $idServiciosAdicionales, $valor, $idMoneda, $descripcion){


        require("conexion.php");
        $data=["idServicioSalidas"=> $idServicioSalidas, "idServiciosAdicionales"=>$idServiciosAdicionales, "valor"=>$valor, "idMoneda"=>$idMoneda, "descripcion"=> $descripcion];
        $consulta = "INSERT INTO servicio_salidas_adicionales (idServicioSalidas, idServiciosAdicionales, valor, idMoneda, descripcion) VALUES (:idServicioSalidas, :idServiciosAdicionales, :valor,:idMoneda, :descripcion) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;
        
        
        }


  function setServicioAdicional($nombre, $descripcion_servicio_adicional){


        require("conexion.php");
        $data=["nombre"=> $nombre, "descripcion_servicio_adicional"=>$descripcion_servicio_adicional];
        $consulta = "INSERT INTO servicios_adicionales (nombre, descripcion_servicio_adicional) VALUES (:nombre, :descripcion_servicio_adicional) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);




              return $id;  
        }

function borraServicioAdicional($idServiciosAdicionales){

require("conexion.php");
    $data=["idServiciosAdicionales"=> $idServiciosAdicionales];
    $consulta = "DELETE FROM servicios_adicionales WHERE idServiciosAdicionales=:idServiciosAdicionales ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $cuenta_row = $comando->rowCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    
    return $cuenta_row;
    
    
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
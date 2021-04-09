<?php
function getFotosServicios(){

    require("conexion.php");
  
    $consulta = "select * from servicio_img";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getFotosServicio($idServicio){

    require("conexion.php");
    $data=["idServicio"=>$idServicio];
    $consulta = "select * from servicio_img WHERE idServicio=:idServicio";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
  function altaFotosServicio($fotos, $idServicio){

require("conexion.php");
        $cantArchivos= count($fotos['file']['name']);
        $ds          = DIRECTORY_SEPARATOR;  //1
        $storeFolder = 'imgServicio';   //2
        
            if (!empty($_FILES)) {
                for ($i=0; $i < $cantArchivos ; $i++) { 
                  
                $tempFile = $fotos['file']['tmp_name'][$i];          //3             
                  
                $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

                 $filename=$idServicio."_img". $fotos['file']['name'][$i];
                 
                $targetFile =  $targetPath.$idServicio."_img". $i.".jpg";  //5
                  $targetFileBDD=$idServicio."_img". $i.".jpg";
             
                move_uploaded_file($tempFile,$targetFile); //6

        $data=["idServicio"=> $idServicio, "ruta"=>$targetFileBDD];
        $consulta = "INSERT INTO servicio_img (idServicio, ruta) VALUES (:idServicio, :ruta) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
             //  $query_insert= mysqli_query($conection,"INSERT INTO img_servicio (idImg,  ruta) VALUES ('$idServicio','$targetFileBDD')");
//echo "REsu".$resultado;
                 
                }

     
}
        
       

        return $id;
        
        
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
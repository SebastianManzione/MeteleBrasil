<?php
function getAllCategorias(){

    require("conexion.php");
  
    $consulta = "select * from categoria_servicio WHERE idCategoria_servicio>0";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

function getCategorias(){

    require("conexion.php");
  
    $consulta = "select * from categoria_servicio WHERE idCategoria_servicio>0 AND habilitado=1";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

function getCategoriasLimit6(){

    require("conexion.php");
  
    $consulta = "select * from categoria_servicio WHERE idCategoria_servicio>0 AND habilitado=1 LIMIT 6";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
function getCategoriasLimit612(){

    require("conexion.php");
  
    $consulta = "select * from categoria_servicio WHERE idCategoria_servicio>0 AND habilitado=1 LIMIT 6,12";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
    function getCategoria($idCategoria_servicio){

    require("conexion.php");
    $data=["idCategoria_servicio"=>$idCategoria_servicio];
    $consulta = "select * from categoria_servicio WHERE idCategoria_servicio=:idCategoria_servicio ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

            function updateCategoria($idCategoria_servicio, $nombre, $descripcion, $descripcionCorta_categoria_servicio, $nViajeros){


        require("conexion.php");
        $data=["idCategoria_servicio"=> $idCategoria_servicio, "nombre"=>$nombre, "descripcion"=>$descripcion, "descripcionCorta_categoria_servicio"=>$descripcionCorta_categoria_servicio, "nViajeros"=>$nViajeros];
        $consulta = "UPDATE categoria_servicio SET nombre_categoria_servicio=:nombre, descripcion_categoria_servicio=:descripcion, descripcionCorta_categoria_servicio=:descripcionCorta_categoria_servicio, nViajeros=:nViajeros WHERE idCategoria_servicio=:idCategoria_servicio ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $cuenta_row;
        
        
        }

    function habilitarCategoria($idCategoria_servicio){


        require("conexion.php");
        $data=["idCategoria_servicio"=> $idCategoria_servicio];
        $consulta = "UPDATE categoria_servicio SET habilitado=1 WHERE idCategoria_servicio=:idCategoria_servicio ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;
        
        
        }
            function desHabilitarCategoria($idCategoria_servicio){


        require("conexion.php");
        $data=["idCategoria_servicio"=> $idCategoria_servicio];
        $consulta = "UPDATE categoria_servicio SET habilitado=0 WHERE idCategoria_servicio=:idCategoria_servicio ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;
        
        
        }



  function updateGuiaCategoria($fotos, $idCategoria_servicio){
require("conexion.php");
$data=["idCategoria_servicio"=>$idCategoria_servicio];
    $consulta = "select * from categoria_servicio WHERE idCategoria_servicio=:idCategoria_servicio ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla

    $guia=$resultado[0]["guia"];

unlink("classes/guias/".$guia);




        $cantArchivos= count($fotos['file']['name']);
        $ds          = DIRECTORY_SEPARATOR;  //1
        $storeFolder = 'guias';   //2
        
            if (!empty($_FILES)) {
                for ($i=0; $i < $cantArchivos ; $i++) { 
                  
                $tempFile = $fotos['file']['tmp_name'][$i];          //3             
                         $type = '.pdf';          //3     
                $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

                 $filename=$idCategoria_servicio.round(microtime(true) * 1000)."_img". $fotos['file']['name'][$i];
                 
                $targetFile =  $targetPath.$idCategoria_servicio.round(microtime(true) * 1000)."_img". $i.$type;  //5

                  $targetFileBDD=$idCategoria_servicio.round(microtime(true) * 1000)."_img". $i.$type;
             
                move_uploaded_file($tempFile,$targetFile); //6

        $data=["idCategoria_servicio"=> $idCategoria_servicio, "guia"=>$targetFileBDD];
        $consulta = "UPDATE categoria_servicio set guia=:guia where idCategoria_servicio=:idCategoria_servicio";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        $comando->debugDumpParams();

        return $id;
             //  $query_insert= mysqli_query($conection,"INSERT INTO img_servicio (idImg,  ruta) VALUES ('$idServicio','$targetFileBDD')");
//echo "REsu".$resultado;
                 
                }

     
}
        
       

        
        
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
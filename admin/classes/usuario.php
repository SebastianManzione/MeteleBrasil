<?php
function getUsuarios(){

    require("conexion.php");
  
    $consulta = "select * from usuario";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getUsuario($idUsuario){

    require("conexion.php");
    $data=["idUsuario"=>$idUsuario];
    $consulta = "select * from usuario WHERE idUsuario=:idUsuario";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }



    function loginUsuario($idUsuario){

    require("conexion.php");
    $data=["idUsuario"=>$idUsuario];
    $consulta = "select * from usuario WHERE idUsuario=:idUsuario WHERE status=1";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }



function habilitarUsuario($idUsuario, $habilitado){

require("conexion.php");

 $data=[
"idUsuario"=>$idUsuario, "habilitado"=>$habilitado];
$consulta = "UPDATE usuario SET status=:habilitado WHERE idUsuario = :idUsuario ";

$comando = $pdo->prepare($consulta);

$comando->execute($data);
$rowCount = $comando->rowCount();

$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



// Imprimir en pantalla


return $rowCount;


}


function habilitarCobrador($idUsuario, $idCobrador){

require("conexion.php");

 $data=[
"idUsuario"=>$idUsuario, "idCobrador"=>$idCobrador];
$consulta = "UPDATE usuario SET idCobrador=:idCobrador WHERE idUsuario = :idUsuario ";

$comando = $pdo->prepare($consulta);

$comando->execute($data);
$rowCount = $comando->rowCount();
 

$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



// Imprimir en pantalla


return $rowCount;


}

function habilitarVendedor($idUsuario, $idVendedor){

require("conexion.php");

 $data=["idUsuario"=>$idUsuario, "idVendedor"=>$idVendedor];
$consulta = "UPDATE usuario SET idVendedor=:idVendedor WHERE idUsuario = :idUsuario ";

$comando = $pdo->prepare($consulta);

$comando->execute($data);
$rowCount = $comando->rowCount();
  


$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



// Imprimir en pantalla


return $rowCount;


}

function updatePrestadorUsuario($idUsuario, $idPrestador){

require("conexion.php");

 $data=["idUsuario"=>$idUsuario, "idPrestador"=>$idPrestador];
$consulta = "UPDATE usuario SET idPrestador=:idPrestador WHERE idUsuario = :idUsuario ";

$comando = $pdo->prepare($consulta);

$comando->execute($data);
$rowCount = $comando->rowCount();
 

$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



// Imprimir en pantalla


return $rowCount;


}

  function altaUsuario($usuario, $email, $clave, $status, $idVendedor, $idCobrador, $idPrestador){


        require("conexion.php");
        $data=["usuario"=> $usuario, "email"=>$email, "clave"=>$clave, "status"=>$status, "idVendedor"=> $idVendedor, "idCobrador"=> $idCobrador,"idPrestador"=>$idPrestador];
        $consulta = "INSERT INTO usuario (usuario, email,clave, status, idVendedor,  idCobrador,idPrestador) VALUES (:usuario, :email, :clave,:status,:idVendedor,:idCobrador,:idPrestador) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



        return $id;
        
        
        }



          function updateFotoUsuario($fotos, $idUsuario){


        $cantArchivos= count($fotos['file']['name']);
        $ds          = DIRECTORY_SEPARATOR;  //1
        $storeFolder = 'imgUsuario';   //2
        
            if (!empty($fotos)) {
                for ($i=0; $i < $cantArchivos ; $i++) { 
                  
                $tempFile = $fotos['file']['tmp_name'][$i];          //3             
                  
                $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

                 $filename=$idUsuario."_img". $fotos['file']['name'][$i];
                 
                $targetFile =  $targetPath.$idUsuario."_img". $i.".jpg";  //5
                  $targetFileBDD=$idUsuario."_img". $i.".jpg";
             
                move_uploaded_file($tempFile,$targetFile); //6
require("conexion.php");
        $data=["idUsuario"=> $idUsuario, "ruta"=>$targetFileBDD];
        $consulta = "UPDATE usuario SET fotoUsuario=:ruta WHERE idUsuario=:idUsuario";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

   echo "\nPDO::errorInfo():\n";
    print_r($comando->errorInfo());

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
<?php
function getFotosBlog(){

    require("conexion.php");
  
    $consulta = "select * from blog_img";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getFotosBlogIdPost($idPost){

    require("conexion.php");
    $data=["idPost"=>$idPost];
    $consulta = "select * from blog_img WHERE idPost=:idPost";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
  function altaFotosBlog($fotos, $idPost){

require("conexion.php");
        $cantArchivos= count($fotos['file']['name']);
        $ds          = DIRECTORY_SEPARATOR;  //1
        $storeFolder = 'imgBlog';   //2
        
            if (!empty($_FILES)) {
                for ($i=0; $i < $cantArchivos ; $i++) { 
                  
                $tempFile = $fotos['file']['tmp_name'][$i];          //3             
                  
                $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

                 $filename=$idPost."_img". $fotos['file']['name'][$i];
                 
                $targetFile =  $targetPath.$idPost."_img". $i.".jpg";  //5
                  $targetFileBDD=$idPost."_img". $i.".jpg";
             
                move_uploaded_file($tempFile,$targetFile); //6

        $data=["idPost"=> $idPost, "ruta"=>$targetFileBDD];
        $consulta = "INSERT INTO blog_img (idPost, ruta) VALUES (:idPost, :ruta) ";
        
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
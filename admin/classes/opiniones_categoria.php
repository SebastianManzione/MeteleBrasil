<?php 

function OpinionesCategoria($idCategoria_servicio){

 require("conexion.php");
				
	$data=["idCategoria_servicio"=>$idCategoria_servicio];
    $consulta = "select * from opiniones_categoria WHERE idCategoria_Servicio=:idCategoria_servicio";


				    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla


				
			
		
		return $resultado;
	
}
function getEstrellasCategoria($idCategoria_servicio){

 require("conexion.php");
				
	$data=["idCategoria_servicio"=>$idCategoria_servicio];
    $consulta = "select * from opiniones_categoria WHERE idCategoria_Servicio=:idCategoria_servicio";


				    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla

$estrellas=0;
for ($i=0; $i < count($resultado); $i++) { 
	$estrellas+=$resultado[$i]['estrellas'];
}
		if ($i>0) {
					$estrellas=	($estrellas*2)/count($resultado);
				}
				else{
					$estrellas="9.8";
				}		

		
		return $estrellas;
	
}

 function setOpinionCategoria($idCategoria_servicio, $nombre, $opinion, $estrellas, $pais){


        require("conexion.php");
        $data=["idCategoria_servicio"=> $idCategoria_servicio, "nombre"=>$nombre, "opinion"=>$opinion, "estrellas"=>$estrellas, "pais"=>$pais];
        $consulta = "INSERT INTO opiniones_categoria (idCategoria_servicio, nombre, opinion, estrellas, pais) VALUES (:idCategoria_servicio, :nombre, :opinion,:estrellas, :pais) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


        return $id;
        
        
        }

        function borraOpinionCategoria($idOpinionCategoria){

require("conexion.php");
    $data=["idOpinionCategoria"=> $idOpinionCategoria];
    $consulta = "DELETE FROM opiniones_categoria WHERE idOpinionCategoria=:idOpinionCategoria ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $cuenta_row = $comando->rowCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    
    return $cuenta_row;
    
    
    }
 ?>
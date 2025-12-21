<?php 









function getOpinionesServicio($idServicio){

 require("conexion.php");
				
	$data=["idServicio"=>$idServicio];
    $consulta = "select * from opiniones_servicio WHERE idServicio=:idServicio";


				    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla


				
			
		
		return $resultado;
	
}


function GetOpinionAleatoria($idServicio){

 require("conexion.php");
				
	$data=["idServicio"=>$idServicio];
    $consulta = "select * from opiniones_servicio WHERE idServicio=:idServicio ORDER BY rand() LIMIT 1";


				    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla

if(count($resultado)>0){
	return $resultado;
}
else{
	$resultado=array();
	$resultado[0]["opinion"]="Sin Opiniones";
	$resultado[0]["estrellas"]=9.8;
	return $resultado;
}
				
			
		
		
	
}



function getEstrellasServicio($idServicio){

 require("conexion.php");
				
	$data=["idServicio"=>$idServicio];
    $consulta = "select * from opiniones_servicio WHERE idServicio=:idServicio";


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

		
		return round($estrellas, 1);
	
}


 function setOpinionServicio($idServicio, $nombre, $opinion, $estrellas, $selPasajeros, $fechaAlta){


        require("conexion.php");
        $data=["idServicio"=> $idServicio, "nombre"=>$nombre, "opinion"=>$opinion, "estrellas"=>$estrellas, "selPasajeros"=>$selPasajeros, "fechaAlta"=>$fechaAlta];
        $consulta = "INSERT INTO opiniones_servicio (idServicio, nombre, opinion, estrellas,selPasajeros, fechaAlta) VALUES (:idServicio, :nombre, :opinion,:estrellas,:selPasajeros,:fechaAlta) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $id;
        
        
        }



                function borraOpinionServicio($idOpinionServicio){

require("conexion.php");
    $data=["idOpinionServicio"=> $idOpinionServicio];
    $consulta = "DELETE FROM opiniones_servicio WHERE idOpinionServicio=:idOpinionServicio ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $cuenta_row = $comando->rowCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    
    return $cuenta_row;
    
    
    }
 ?>
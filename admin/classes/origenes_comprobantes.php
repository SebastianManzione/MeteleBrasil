<?php 

function getOrigenComprobante($idOrigen){

 require("conexion.php");
				
	$data=["idOrigen"=>$idOrigen];
    $consulta = "select * from origenes WHERE idOrigen=:idOrigen";


	$comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla


				
			
		
		return $resultado;
	
}

function getTotalComprobantes($idOrigenComprobante){

 require("conexion.php");
				
	$data=["idOrigenComprobante"=>$idOrigenComprobante];
    $consulta = "select * from origenes WHERE idOrigenComprobante=:idOrigenComprobante";


				    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla


				
			
		
		return $resultado;
	
}

 ?>
<?php 

function getCuponesDescuento(){

 require("conexion.php");
				

    $consulta = "select * from cupones_descuento ";


				    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla


				
			
		
		return $resultado;
	
}
function CuponValidFo($CodigoAmigable){

 require("conexion.php");
			$CodigoAmigable="NRP475";	
	$data=["CodigoAmigable"=>$CodigoAmigable];
    $consulta = "select * from cupones_descuento WHERE CodigoAmigable LIKE '%:CodigoAmigable%'";

echo "con".$consulta;
print_r($data);
	 $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
     

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla



	
		
		return $resultado;
	
}

 function CuponValido($CodigoAmigable){

    require("conexion.php");
    $CodigoAmigable=trim($CodigoAmigable);
    $data=["CodigoAmigable"=>$CodigoAmigable];
    $consulta = 'select * from cupones_descuento WHERE CodigoAmigable LIKE :CodigoAmigable';
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
   

     return $resultado;
    
    }



 ?>
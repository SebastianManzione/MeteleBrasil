<?php 



function getCuponesDescuento(){



 require("conexion.php");

				



    $consulta = "select * from cupones_descuento ";





				    $comando = $pdo->prepare($consulta);

    

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla





				

			

		

		return $resultado;

	

}

 function getCupon($idCupon){



    require("conexion.php");


    $data=["idCupon"=>$idCupon];

    $consulta = 'select * from cupones_descuento WHERE idCuponDescuento=:idCupon';

    

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


function GeneraCupones($cantidad, $idUsuario, $descuentoPorcentual){


        require("conexion.php");
            require("conexion.php");
        
        
    
$cupones=Array();

for ($i=0; $i < $cantidad; $i++) { 
   do {
    
  $codigoAmigable= GeneradorAleatorio(2,4);

$data=["codigoAmigable"=> $codigoAmigable, "idUsuario"=>$idUsuario, "descuentoPorcentual"=>$descuentoPorcentual];
        $consulta = "INSERT INTO cupones_descuento (codigoAmigable, idUsuario, descuentoPorcentual) VALUES (:codigoAmigable,:idUsuario,:descuentoPorcentual)";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

      
       
       array_push($cupones, $id);
   } while ($id<1);
   



   }
  return $cupones;
}




 ?>
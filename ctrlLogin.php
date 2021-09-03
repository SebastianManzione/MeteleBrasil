<?php 

//print_r($_POST);

include("includes/headPagos.php");


	    require("admin/classes/conexion.php");

		require_once "admin/classes/functions.php";





	if(!empty($_POST) && isset($_POST["login"]) && $_POST["login"]==1){

			$email=$_POST['email'];

			$clave=$_POST['clave'];

			$clave64 = md5($clave);

	$data=["email"=>$email];	

 $consulta = "select * from usuario WHERE email=:email";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla




			if (count($resultado)==1 && $resultado[0]["clave"]== $clave64 ) {



			

			

	$_SESSION["login"]['active']=true;

	$_SESSION["login"]['idUsuario']= $resultado[0]['idUsuario'];

	$_SESSION["login"]['usuario']=$resultado[0]['usuario'];

	$_SESSION["login"]['foto']=$resultado[0]['fotoUsuario'];

	$_SESSION["login"]['rol']=$resultado[0]['rol'];

$_SESSION["login"]['idVendedor']=$resultado[0]['idVendedor'];

$_SESSION["login"]['idCobrador']=$resultado[0]['idCobrador'];

$_SESSION["login"]['idPrestador']=$resultado[0]['idPrestador'];

		alertar($lang["bienvenido_a_metelebrasil"].$_SESSION["login"]['usuario'],"success");
	redireccionar('index.php');  //bienvenido a metele
		exit();

			}

			else{

			alertar($lang["usuario_contrasena_incorrectos"],"warning"); //usuario contrasenha incorrectos

				  session_destroy();

	 redireccionarLento("index.php");

				exit();

			

			}

		

	}



	

	if(!empty($_POST) && isset($_POST["loginGoogle"])){

		$loginGoogle=json_decode($_POST["loginGoogle"]["data"], true);

		//print_r($loginGoogle);

		

			$email=$loginGoogle['email'];

			$nombre=$loginGoogle['name'];

			$foto=$loginGoogle['foto'];

			$query= mysqli_query($conection, "SELECT * FROM usuario WHERE email = '$email'");

			$result = mysqli_num_rows($query);

			

			if ($result>0) {



			

				$data = mysqli_fetch_array($query);

			

				

				$query= mysqli_query($conection, "SELECT * FROM usuario  WHERE email = '$email'");

					$data = mysqli_fetch_array($query);

				



				

	$_SESSION["login"]['active']=true;

	$_SESSION["login"]['idUsuario']= $data['idUsuario'];

	$_SESSION["login"]['usuario']=$data['usuario'];

	$_SESSION["login"]['foto']=$data['fotoUsuario'];

	$_SESSION["login"]['rol']=$data['rol'];



			echo 1;

			}

			else{





			$alta=AltaUsuarioGoogle($email, $nombre, $foto);





					$query= mysqli_query($conection, "SELECT * FROM usuario  WHERE email = '$email'");

					$data = mysqli_fetch_array($query);

				



				

	$_SESSION["login"]['active']=true;

	$_SESSION["login"]['idUsuario']= $data['idUsuario'];

	$_SESSION["login"]['usuario']=$data['usuario'];

	$_SESSION["login"]['foto']=$data['fotoUsuario'];

	$_SESSION["login"]['rol']=$data['rol'];



	echo 2;

//Swal.fire("hola","success");

//				header('location: index.php');

			

			}

		

	}



	else if(isset($_POST["login"])&& $_POST["login"]==0){



session_destroy();

alertar($lang["gracias_por_usar"],"success"); //gracias por usar reservate

  redireccionar("index.php");

	}



if(isset($_POST["logout"])){

unset($_SESSION["login"]);



echo 1;

	}

 ?>
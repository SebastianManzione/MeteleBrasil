<?php 

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
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    if (count($resultado)==1 && $resultado[0]["clave"]== $clave64 ) {

        $_SESSION["login"]['active']=true;
        $_SESSION["login"]['idUsuario']= $resultado[0]['idUsuario'];
        $_SESSION["login"]['usuario']=$resultado[0]['usuario'];
        $_SESSION["login"]['foto']=$resultado[0]['fotoUsuario'];
        $_SESSION["login"]['rol']=$resultado[0]['rol'];
        $_SESSION["login"]['idVendedor']=$resultado[0]['idVendedor'];
        $_SESSION["login"]['idCobrador']=$resultado[0]['idCobrador'];
        $_SESSION["login"]['idPrestador']=$resultado[0]['idPrestador'];

        alertar($lang["bienvenido_a_metelebrasil"]." ".$_SESSION["login"]['usuario'],"success");
        redireccionar('/login_panel_inicio.php'); // 🔹 Cambiar 'panel' por la página deseada
        exit();

    } else {

        session_destroy();

        // 🔹 Mostramos modal con formulario de reintento y botón Volver al sitio
        echo "
 <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({
        title: 'Usuario o contraseña incorrectos',
        html: `
            <form id='retryLogin' method='POST' action='' style='text-align:left;'>
                <input type='hidden' name='login' value='1'>
                <div style='margin-bottom:10px;'>
                    <label>Email:</label>
                    <input type='email' name='email' required class='swal2-input' placeholder='Tu correo'>
                </div>
                <div style='margin-bottom:10px;'>
                    <label>Contraseña:</label>
                    <input type='password' name='clave' required class='swal2-input' placeholder='Tu contraseña'>
                </div>
                <div style='display:flex; justify-content:center; gap:10px; flex-wrap:wrap;'>
                    <button type='submit' class='swal2-confirm swal2-styled' style='display:inline-block;'>Reintentar</button>
                    <a href='index.php' class='swal2-styled' style='background-color:#28a745; color:#fff; padding:10px 25px; border-radius:5px; text-decoration:none; display:inline-block;'>Volver al sitio</a>
                </div>
            </form>
        `,
        icon: 'warning',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        background: '#fff',
        didOpen: () => {
            const form = document.getElementById('retryLogin');
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                Swal.close();
                form.submit(); // vuelve a intentar el login
            });
        }
    });
    </script>
        ";
        exit();
    }
}


if(!empty($_POST) && isset($_POST["loginGoogle"])){

    $loginGoogle=json_decode($_POST["loginGoogle"]["data"], true);
    $email=$loginGoogle['email'];
    $nombre=$loginGoogle['name'];
    $foto=$loginGoogle['foto'];

    $query= mysqli_query($conection, "SELECT * FROM usuario WHERE email = '$email'");
    $result = mysqli_num_rows($query);

    if ($result>0) {
        $data = mysqli_fetch_array($query);

        $_SESSION["login"]['active']=true;
        $_SESSION["login"]['idUsuario']= $data['idUsuario'];
        $_SESSION["login"]['usuario']=$data['usuario'];
        $_SESSION["login"]['foto']=$data['fotoUsuario'];
        $_SESSION["login"]['rol']=$data['rol'];

        echo 1;
    } else {
        $alta=AltaUsuarioGoogle($email, $nombre, $foto);
        $query= mysqli_query($conection, "SELECT * FROM usuario  WHERE email = '$email'");
        $data = mysqli_fetch_array($query);

        $_SESSION["login"]['active']=true;
        $_SESSION["login"]['idUsuario']= $data['idUsuario'];
        $_SESSION["login"]['usuario']=$data['usuario'];
        $_SESSION["login"]['foto']=$data['fotoUsuario'];
        $_SESSION["login"]['rol']=$data['rol'];

        echo 2;
    }
}

else if(isset($_POST["login"])&& $_POST["login"]==0){
    session_destroy();
    alertar($lang["gracias_por_usar"],"success");
    redireccionar("index");
}

if(isset($_POST["logout"])){
    unset($_SESSION["login"]);
}

?>

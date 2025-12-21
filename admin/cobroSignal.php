<?php 




include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/prestador.php");
require("classes/usuario.php");




if($_SERVER['REQUEST_METHOD'] == 'POST'){

include("classes/convierte_monedas.php");


include("classes/comprobantes.php");
include("classes/reserva.php");
include("classes/generador_aleatorio.php");





$site=($parametros[0]["site"]);



  if($_SESSION["login"]["idCobrador"]>0){


$idMoneda=$_SESSION["moneda_sel"];



$idReserva=$_POST["idReserva"];



$idUsuario=$_SESSION["login"]["idUsuario"];



$total=$_POST["total_cobrado"];

$total_dolares= ConvierteMoneda($idMoneda,188, $total);

do {

 $aleatorio= GeneradorAleatorio(3,6);

$comprobante=insertaComprobante($idReserva, $total, 6, $idMoneda, $aleatorio, $total_dolares, $idUsuario);  // code...

} while ($comprobante==0);







if ($comprobante>0) {



  # code...







 $reserva=getReservaId($idReserva);



$moneda=getMoneda($idMoneda);



$symbolo=$moneda[0]["Symbol"];



$codigoAmigable=($reserva[0]["codigoAmigable"]);



$usuario=getUsuario($idUsuario);



$nombre_cobrador=($usuario[0]["usuario"]);



include("classes/email_pago_recibido.php");



$cuerpo=getCuerpoEmailPagoRecibido($codigoAmigable, $nombre_cobrador);







$resumail=enviaMail($reserva[0]["emailResponsable"], $lang["si_recibimos_su_pago_correctamente"], $cuerpo, $parametros[0]["site"]);







  alertar($lang["si_tu_cobro_se_realizo"],"success");

  redireccionarLento('cobroSignal?reserva='.$codigoAmigable);

  exit();



}}



else



{



  alertar($lang["ups_infelizmente_su_usuario"],"warning");



}

}










$codigoAmigable="";
if (isset($_GET['reserva'])) {
  $codigoAmigable=$_GET['reserva'];
}


 ?>



  <!-- Content Wrapper. Contains page content -->



  <div class="content-wrapper">



    <!-- Content Header (Page header) -->



    <div class="content-header">



      <div class="container-fluid">



        <div class="row mb-2">



          <div class="col-sm-6">



            <h1 class="m-0 text-dark">Cobro Signal</h1>



          </div><!-- /.col -->



          <div class="col-sm-6">



            <ol class="breadcrumb float-sm-right">



              <li class="breadcrumb-item"><a href="#">Home</a></li>



              <li class="breadcrumb-item active">Cobro Signal</li>



            </ol>



          </div><!-- /.col -->



        </div><!-- /.row -->



      </div><!-- /.container-fluid -->



    </div>



    <section class="content">



      <div class="container-fluid">



        <!-- SELECT2 EXAMPLE -->











        <!-- SELECT2 EXAMPLE -->



        <div class="card card-default">



  
        


<form method="post" onsubmit="return validaRecibo();">



      <div class="modal-body">



  <div class="form-group">

    <label for="exampleInputEmail1">Código da reserva</label>

    <input type="text" class="form-control" onkeyup="consultaReserva(this.value)" value="<?=$codigoAmigable;?>">

    <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->

  </div>
   <div id="cuponOk"></div>







      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>

        <button  type="submit" class="btn btn-primary">Cobrar</button>

      </div>





          <!-- /.card-header -->



          <div class="card-body"></div>



          <!-- /.card-body -->



          <div class="card-footer"></div>


    </form>

        </div>




<?php 

$idMonedaSel=$_SESSION['moneda_sel'];
 ?>

<script>



    window.onload = function () {

        $("#exampleModalCenter").modal('show');

    };

  var codCupon;

var idMonedaSel='<?=$idMonedaSel?>';

  const empty = {};



function consultaReserva(texto){

  $.post('ctrl/ctrlCobroSignal.php', {

    data:{    'codigoAmigable' : JSON.stringify(texto), 'idMonedaSel': idMonedaSel }

  }, function(response) {

console.log(response);

try{

  JSON.parse(str)

} catch (e) {


}




    if (response.length>10) { 



          responser=JSON.parse(response);



      aPagar=responser['aPagar'];

 $("#cuponOk").text("");

      $("#cuponOk").removeClass("text-danger");

         //$("#cuponOk").addClass("text-success");

    campo =responser['html'];

$("#cuponOk").append(campo);

    }



    else{



     $("#cuponOk").text(" ");

    $("#cuponOk").removeClass("text-success");

      $("#cuponOk").addClass("text-danger");







   



    }



   });



}
consultaReserva("<?=$codigoAmigable?>")
</script>


<script type="text/javascript">



  function validaRecibo() {



   if (confirm("Realmente desea cobrar??")) {



return true;







   }



   else



   {



    return false;



   }



  }







</script>



        <!-- /.card -->







        <!-- /.row -->



      </div><!-- /.container-fluid -->



    </section>



        </div>



        <!-- /.row (main row) -->



      </div><!-- /.container-fluid -->



    </section>



    <!-- /.content -->



  </div>



  <!-- /.content-wrapper -->



  <?php 



  include("includes/footer.php"); ?>
<!-- Button trigger modal -->

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">

  Launch demo modal

</button>



<!-- Modal -->

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered" role="document">

    <div class="modal-content">

      <div class="modal-header">

        

    

    <h4 class="modal-title" ><img src="img/favicon.png" sizes="20x20" style="color: #007bff; width: 35px; height: 38px;"> METELE BRASIL </h4>Cobro Signal

      </div>
<form method="post">

      <div class="modal-body">



  <div class="form-group">

    <label for="exampleInputEmail1">Código da reserva</label>

    <input type="text" class="form-control" onkeyup="consultaReserva(this.value)" value="ldq386">

    <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->

  </div>
   <div id="cuponOk"></div>







      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>

        <button type="button" class="btn btn-primary">Cobrar</button>

      </div>
</form>
    </div>

  </div>

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

  $.post('admin/ctrl/ctrlCobroSignal.php', {

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



     $("#cuponOk").text("Reserva inválida.");

    $("#cuponOk").removeClass("text-success");

      $("#cuponOk").addClass("text-danger");







   



    }



   });



}
consultaReserva("ldq386")
</script>


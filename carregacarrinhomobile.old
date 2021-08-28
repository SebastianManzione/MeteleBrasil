<?php
 session_start();
 include('sistema/functions.php');
  
  if(isset($_SESSION["carrito"]["actividades"]) && sizeof($_SESSION["carrito"]["actividades"]) > 0){
			 $totalReservaActividades = 0;	
             foreach ($_SESSION["carrito"]["actividades"]  as $key => $value) {
			  echo '<div class="col-md-12 col-12" id="carrinhonovoactividades' . $key . '"><div class="row">'; 		 
			  $id=$value["idServicio"];
              $foto = DevuelveFotosServicio($id)[0]; 
			  if(isset($_SESSION["valorcarrinho"]["actividades"][$key])){
			   $subTotalReserva = $_SESSION["valorcarrinho"]["actividades"][$key]['valor'];
			  } else {
			   $subTotalReserva = 0;
			  }
			  $totalReservaActividades += $subTotalReserva;
			  
              echo '<div class="col-md-4 col-4"><img src="../img/uploads/'.$foto.'" class="imgcarrinhonav"></div>';
              echo '<div class="col-md-8 col-8"><p class="vacio2" id="carrinhonovoactividades' . $key . '">'.NombreServicio($id).' &nbsp;&nbsp;<i class="fa fa-times excluircarrinhoactividades" onclick="excluircarrinhoactividades(' . $key . ')" data-id="' . $key . '"></i></p></div>';
			  echo '<div class="col-md-12 col-12"><p class="vacio2" style="text-align:right; font-weight:bold; font-size:20px;" id="carrinhonovoactividades' . $key . '">' . $subTotalReserva . '</p></div>';
              echo '</div></div>';
			 }
			 
			 $txtactividade = count($_SESSION["carrito"]["actividades"]) > 1 ? 'Actividades' : 'Actividade'; 
			 echo '<div class="col-md-12 col-12 divcoractividade" style="background-color:#f3f3f3; color:#000; padding:20px">
			 <div class="row">
			  <div class="col-md-10 col-6">
			   <span style="font-size:20px !important; text-align:left" class="qdtactividade" id="qdtactividade">' . count($_SESSION["carrito"]["actividades"]) . ' ' . $txtactividade . '</span>
			  </div>
			  <div class="col-md-2 col-6">
			   <span style="font-size:20px !important; text-align:right !important" class="totalactividade"><b style="font-size:20px !important; text-align:right !important" id="valoractividades' . $key . '">' . $totalReservaActividades . '</b></span>
			  </div>
			 </div>
			 </div>';
  }
  
  
  if(isset($_SESSION["carrito"]["paquetes"]) && sizeof($_SESSION["carrito"]["paquetes"]) > 0){
			 $totalReservaPaquetes = 0;	
		     foreach ($_SESSION["carrito"]["paquetes"]  as $key => $value) {
			  echo '<div class="col-md-12 col-12" id="carrinhonovopaquetes' . $key . '"><div class="row">'; 		
			  $id=$value["idServicio"];	 
			  $foto = DevuelveFotosServicio($id)[0]; 
			  if(isset($_SESSION["valorcarrinho"]["paquetes"][$key])){
			   $subTotalReserva = $_SESSION["valorcarrinho"]["paquetes"][$key]['valor'];
			  } else {
			   $subTotalReserva = 0;
			  }
			  
			  $totalReservaPaquetes += $subTotalReserva;
              echo '<div class="col-md-4 col-4"><img src="../img/uploads/'.$foto.'" class="imgcarrinhonav"></div>';
              echo '<div class="col-md-8 col-8"><p class="vacio2" id="carrinhonovopaquetes' . $key . '">'.NombreServicio($id).' &nbsp;&nbsp;<i class="fa fa-times excluircarrinhopaquetes" onclick="excluircarrinhopaquetes(' . $key . ')" data-id="' . $key . '"></i></p></div>';
			  echo '<div class="col-md-12 col-12"><p class="vacio2" style="text-align:right; font-weight:bold; font-size:20px;" id="carrinhonovopaquetes' . $key . '">' . $subTotalReserva . '</p></div>';
			  echo '</div></div>';
             }
			 
			 $txtpaquete = count($_SESSION["carrito"]["paquetes"]) > 1 ? 'Paquetes' : 'Paquete'; 
			 echo '<div class="col-md-12 col-12 divcorpaquete" style="background-color:#f3f3f3; color:#000; padding:20px; margin-bottom:50px !important">
			 <div class="row">
			  <div class="col-md-10 col-6">
			   <span style="font-size:20px !important; text-align:left" class="qdtpaquete" id="qdtpaquete">' . count($_SESSION["carrito"]["paquetes"]) . ' ' . $txtpaquete . '</span>
			  </div>
			  <div class="col-md-2 col-6">
			   <span style="font-size:20px !important; text-align:right !important" class="totalpaquete"><b style="font-size:20px !important; text-align:right !important" id="valorpaquetes' . $key . '">' . $totalReservaPaquetes . '</b></span>
			  </div>
			 </div>
			 </div>';
  }
  
  if (count($_SESSION["carrito"]["actividades"])>0 || count($_SESSION["carrito"]["paquetes"])>0){
	echo '<div class="col-md-12 col-12 carrinhovazio" style="margin-top:15px !important; margin-bottom:10px !important">
             <div class="custom-control custom-checkbox mr-sm-2">
               <center><a href="carrito.php"><button class="btn btn-danger btn-radius btn-sm">Finalizar Reserva</button></a></center>
             </div>
			</div>';
  } 
  
?>


<script>
 function excluircarrinhoactividades(id){
	$.ajax({
     url: "limparSession.php",
     type: "POST",
     data: "excluir=carrinho&tipo=actividades&id=" + id,
     dataType: "html"
    }).done(function(resposta) {
	  var objRetorno = $.parseJSON(resposta);	
	  $("#carrinhonovoactividades" + id).css({'display':'none'});
      if (objRetorno.sucesso) {
	   $(".menu-civa").load('carregacarrinhomobile.php');
	  }
    });
  }
  
  
  function excluircarrinhopaquetes(id){
	$.ajax({
     url: "limparSession.php",
     type: "POST",
     data: "excluir=carrinho&tipo=paquetes&id=" + id,
     dataType: "html"
    }).done(function(resposta) {
	  var objRetorno = $.parseJSON(resposta);	
	  $("#carrinhonovopaquetes" + id).css({'display':'none'});
	  if (objRetorno.sucesso) {
	   $(".menu-civa").load('carregacarrinhomobile.php');
	  }
    });
  }
</script>
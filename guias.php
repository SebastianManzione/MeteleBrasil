<?php

 include('includes/navbar.php'); 

 include('admin/classes/categoria.php'); 
  include('admin/classes/fotos_categoria.php'); 
   include('admin/classes/guias.php'); 
 include('admin/classes/opiniones_categoria.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
if (isset($_GET["idCategoria"])) {

$idCategoria=$_GET["idCategoria"];

}
else{


 $categorias=getCategorias();
 $nViajeros=rand(690,1200); 
$nombre_categoria=" Todas Las Categorías";
$opiniones_categoria=array();
 $cantidad_opiniones_categoria=rand(100,500); ;
    $fotos="sinCategoria.jpg";
}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
$nombre=$_POST["nombre"];
$email=$_POST["email"];
$telefono=$_POST["telefono"];
$mensaje=$_POST["mensaje"];
$idCategoria=$_POST["idCategoria"];
 $categorias=getCategoria($idCategoria);
 $nombre_categoria=$categorias[0]["nombre_categoria_servicio"];
  $guia=$categorias[0]["guia"];
 include('admin/classes/email_guias.php'); 
 $cuerpo=cuerpoEmailGuia($_POST["nombre"], $nombre_categoria);
insertaEmailGuia($nombre,$email, $idCategoria, $telefono, $mensaje);

 enviaMailAdjunto($email, "guia de ".$nombre_categoria, $cuerpo ,"metelebrasil.com", "admin/classes/guias/".$guia);
 alertar("o guia de ".$nombre_categoria." foi enviado para seu e-mail", "success");
redireccionarLento("index");
exit();
}

$categorias=getCategoria($idCategoria);
   $id=$categorias[0]["idCategoria_servicio"];
   $nombre_categoria=$categorias[0]["nombre_categoria_servicio"];
   $opiniones_categoria=OpinionesCategoria($id);
   $cantidad_opiniones_categoria=count($opiniones_categoria);
   $fotos=$categorias[0]["img_categoria_servicio"];
?>



 <!--SECCION HEADER-->
<section id="header-visitas" class="menu-h" style="background-image: url('admin/img/categoria_servicio/<?=$fotos;?>');">
  <div class="container d-md-block d-none">
    <div class="row">
      <div class="col-lg-12">
          
          <!--BUCLE DE LOS RESULTADOS AQUI--> 
        <div class="badge badge-primary badge-ciudad"></div>
         <!--FIN BUCLE DE LOS RESULTADOS AQUI-->
         
          <!--TITULO-->
        <h1 class="text-white texto-shadow py-2 bold" style=" text-shadow: -1px 0px 6px #000000;"></h1>
         <!--TITULO-->
        
      
      </div>
    </div>
  </div>
 <!--header-->
  
 <!-- CONTENEDOR DE CARACTERISTICAS-->



  <br>
  
  <!-- CONTENEDOR DE FRANJA TRANSPARENTE-->
  <div class="container  d-md-block d-none ">
     <div class="row">
      <div class="col-lg-12">
        <div class="div-fondo-visita-">
          <ul class="lista-visitas text-white">
          </ul>
        </div>
      </div>
    </div>
  </div>
    <!-- FIN CONTENEDOR DE FRANJA TRANSPARENTE-->
    
    
    <!-- CONTENEDOR DE BOTONES EN MOVIL-->
  <div class="container-fluid  d-md-none">
     <div class="row">
      <div class="col-6">
      </div>
      <div class="col-6">
          <a  data-toggle="collapse" class="btn-reservar" href="#compartir" role="button" aria-expanded="false" aria-controls="collapseExample" ><i class="text-dark fa fa-share-alt"></i></a>
                 <div class="collapse" id="compartir">
                  <div class="card card-body">
                   <a class="btn btn-outline-light btn-social facebook mx-1 mb-2" href="#">
                    <i class="fab fa-fw fa-facebook-f"></i>
                  </a>
                  <a class="btn btn-outline-light btn-social linkedin mx-1 mb-2" href="#">
                    <i class="fab fa-fw fa-instagram"></i>
                  </a>
                  </div>
                </div>
      </div>
    </div>
  </div>
    <!-- FIN CONTENEDOR DE BOTONES EN MOVIL-->
  
</section>
 <!--FIN SECCION HEADER-->




<!--FIN MENU FIJO-->


<!--SECCION INFORMACION DE VISITA MOVIL-->


<!--FIN SECCION INFORMACION DE VISITA MOVIL-->


<section class="py-5 d-md-block d-none">
  <div class="container container_r clearfix">
      <div class="row">
           <!--COL INFORMACION IZQUIERDA-->
          <div class="col-lg-12">
             <div id="content">
                 
            <div class="o-container-work-us">
<h1 class="a-title-empleo afiliados" style="text-align: center;">Guia de <?=$nombre_categoria; ?></h1>
<span class="py-4 text-primary">Envienos sus datos y le enviaremos la guia de <?=$nombre_categoria; ?></span>

       
        <form method="post" class="form-buscar" style="padding-top: 30px;">
        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;">Nombre</h5>
          
                  <div class="input-group">
                <input class="field form-control" id="nombre" name="nombre" type="text" placeholder="Escribe tu nombre" value="" required>
              </div>
            
        </div>
        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;">Email</h5>
         
                  <div class="input-group">
                <input class="field form-control" id="email" name="email" type="text" placeholder="Escribe tu mail" value="" required>
              </div>
           
        </div>
        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;">Teléfono</h5>
         
                  <div class="input-group">
                <input class="field form-control" id="phone" name="telefono" type="text" placeholder="Escribe tu teléfono" value="">
              </div>
            
        </div>
        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;">Mensaje</h5>
                <div class="input-group">
                <textarea class="field form-control" id="mensaje" name="mensaje"></textarea>
              </div>
            
        </div>
        <button class="submit btn btn-primary" id="searchsubmit" name="idCategoria" value="<?=$idCategoria;?>" type="submit">Enviar <i class="fa fa-arrow-right"></i></button>
        </form>
       

            </div>

        </div>
        
            </div>
        <!--FIN PRIVACIDAD-->
        </div> 
           </div>
          </section>
<div class="container py-2">
                          <div class="row">
                              <div class="col-12">
<h1 class="a-title-empleo afiliados" style="text-align: center;">Guia de <?= NombreCategoria($id); ?></h1>
<span class="py-4 text-primary">Envienos sus datos y le enviaremos la guia de <?= NombreCategoria($id); ?></span>

       
        <form class="form-buscar" style="padding-top: 30px;">
        
          <h5 class="text-uppercase mb-4" style="text-align: center; padding-top: 10px;">Nombre</h5>
          
                  <div class="input-group">
                <input class="field form-control" id="nombre" name="nombre" type="text" placeholder="Escribe tu nombre" value="">
              </div>
            
        
       
          <h5 class="text-uppercase mb-4" style="text-align: center; padding-top: 10px;">Email</h5>
         
                  <div class="input-group">
                <input class="field form-control" id="email" name="email" type="text" placeholder="Escribe tu mail" value="">
              </div>
           
        
        
          <h5 class="text-uppercase mb-4" style="text-align: center; padding-top: 10px;">Teléfono</h5>
         
                  <div class="input-group">
                <input class="field form-control" id="phone" name="phone" type="text" placeholder="Escribe tu teléfono" value="">
              </div>
            
        
        
          <h5 class="text-uppercase mb-4" style="text-align: center; padding-top: 10px;">Mensaje</h5>
         
              <div class="input-group">
                <textarea class="field form-control" id="mensaje" name="mensaje">
                
                </textarea>
              </div>
            
        
        </form>
                              </div>
                          </div>
</div>
  
 <!-- Footer -->
<?php include "footer.php"; ?>

  
  
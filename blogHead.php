  

 <!--SECCION HEADER-->

<section id="header-visitas" class="menu-h" style="background-image: url('admin/classes/imgBlog/<?=$fotos[0]["ruta"];?>');" >

  <div class="container d-md-block d-none">

    <div class="row">

      <div class="col-lg-12">

          

          <!--BUCLE DE LOS RESULTADOS AQUI--> 

        <div class="badge badge-primary badge-ciudad"><?=$destino[0]["nombre"]?></div>

         <!--FIN BUCLE DE LOS RESULTADOS AQUI-->

         

          <!--TITULO-->

        <h1 class="text-white texto-shadow py-2 bold" style=" text-shadow: -1px 0px 6px #000000;"><?=$titulo;?></h1>

         <!--TITULO-->

        

      

      </div>

    </div>

  </div>

 <!--header-->

  <!-- CONTENEDOR DE OPINIONES IDIOMA Y DURACION-->





 



  <div class="container z-index-b d-md-block d-none ">

    <div class="row">

      <div class="col-lg-4 col-md-6"> 

        <div class="resumen-visitas text-white">



          <h2 class="semibold" ><small></small></h2>

          <p class="mb-0"></p>

          <p>





            

          

            



</p>

        </div>

      </div>

    </div>

  </div>

  <br>

  <div class="container-fluid d-md-block d-none">

    <div class="row">

      <div class="col-lg-12">

        <div class="img-gris" style="display: none;"></div>

      </div>

    </div>

</div>

  <!-- CONTENEDOR DE FRANJA TRANSPARENTE-->

  <div class="container  d-md-block d-none ">

     <div class="row">

      <div class="col-lg-12">

        <div class="div-fondo-visita-">

          

        </div>

      </div>

    </div>

  </div>

    <!-- FIN CONTENEDOR DE FRANJA TRANSPARENTE-->

   <!-- FIN CONTENEDOR DE OPINIONES IDIOMA Y DURACION-->



    <!-- CONTENEDOR DE BOTONES EN MOVIL-->

  <div class="container-fluid  d-md-none">

     <div class="row">

      <div class="col-6">

        <a href="categorias.php?id=<?= $idcatsrv;?>" class="btn btn-movil-header  text-primary "><i class="fa fa-arrow-left"></i><?=$destino[0]["nombre"]?></a>

      </div>

      

    </div>

  </div>

    <!-- FIN CONTENEDOR DE BOTONES EN MOVIL-->

  

</section>

 <!--FIN SECCION HEADER-->



 





<!--MENU FIJO-->



 



<!--FIN MENU FIJO-->
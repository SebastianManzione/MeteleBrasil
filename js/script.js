(function($) {
  "use strict"; // Start of use strict

  // Smooth scrolling using jQuery easing
  $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: (target.offset().top - 71)
        }, 1000, "easeInOutExpo");
        return false;
      }
    }
  });

  // Scroll to top button appear
  $(document).scroll(function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Closes responsive menu when a scroll trigger link is clicked
  $('.js-scroll-trigger').click(function() {
    $('.navbar-collapse').collapse('hide');
  });
  
  /*
  $("#txtIdiomaSelMovil").click(function(){
    $("#idioma").css('display','block');
    $("#moneda").css('display','none');
  });
*/

  $("#clickLoginMovil").click(function(){
    if ($("#usuario-movil").css('display')=="none"){
    $("#usuario-movil").css('display','block');
    }
    else{
    $("#usuario-movil").css('display','none');
    }
  


  });


 /* $("#txtMonedaSelMovil").click(function(){
    $("#moneda").css('display','block');
    $("#idioma").css('display','none');
  });
*/
  // Activate scrollspy to add active class to navbar items on scroll
  $('body').scrollspy({
    target: '#mainNav',
    offset: 80
  });

  // Collapse Navbar
  var navbarCollapse = function() {
    if ($("#mainNav").offset().top > 100) {
      $("#mainNav").addClass("navbar-shrink");
     
    } else {
      $("#mainNav").removeClass("navbar-shrink");
     
    }
  };
  // Collapse now if page is not at top
  navbarCollapse();
  // Collapse the navbar when page is scrolled
  $(window).scroll(navbarCollapse);

  // Floating label headings for the contact form
  $(function() {
    $("body").on("input propertychange", ".floating-label-form-group", function(e) {
      $(this).toggleClass("floating-label-form-group-with-value", !!$(e.target).val());
    }).on("focus", ".floating-label-form-group", function() {
      $(this).addClass("floating-label-form-group-with-focus");
    }).on("blur", ".floating-label-form-group", function() {
      $(this).removeClass("floating-label-form-group-with-focus");
    });
  });
  
  
  /**/
  
  new WOW().init();


   $("#buscar-pc").click(function () {
     //$("#mostrar-pc").slideDown();
     //$("#search-pc").slideDown();
     $("#buscar-pc").hide();
     $("#cerrar-buscar-pc").show();
  });

    $("#cerrar-buscar-pc").click(function () {
     //$("#mostrar-pc").slideUp();
     //$("#search-pc").slideUp();
     $("#buscar-pc").show();
     $("#cerrar-buscar-pc").hide();
  });


  $("#buscar").click(function () {
     $("#destinos").toggleClass('d-none');
  });



  $("#buscar-movil").click(function () {
     $("#destinos-movil").css({'display':'none'});
    // $("#destinos-movil").slideDown();
    // $("#buscar-movil").addClass("cerrar");
  });
  
  $(".menumobile .excluircarrinhoactividades").click(function () {
	var id = $(this).attr('data-id');
	
	$.ajax({
     url: "limparSession.php",
     type: "POST",
     data: "excluir=carrinho&tipo=actividades&id=" + id,
    }).done(function(resposta) {
	  location.reload();
    });  
  });
  
  $(".excluircarrinhoactividades").click(function () {
    var id = $(this).attr('data-id');
	$.ajax({
     url: "limparSession.php",
     type: "POST",
     data: "excluir=carrinho&tipo=actividades&id=" + id,
    }).done(function(resposta) {
	  var objRetorno = $.parseJSON(resposta);	
	  $("#carrinhonovoactividades" + id).css({'display':'none'});
	  $(".carrinhonovoactividades" + id).css({'display':'none'});
      if (objRetorno.sucesso) {
	   if(objRetorno.vazio == 1){
	    $(".vacionovo").css({'display':'none'});
		$(".menu-civa").load('carregacarrinho.php');
	   } else if(objRetorno.vazio == 0){
		$(".carrinhovazio").css({'display':'none'});    
		$(".divcoractividade").css({'display':'none'});   
		$(".qdtactividade").css({'display':'none'});  
		$(".totalactividade").css({'display':'none'}); 
	    $(".vacionovo").css({'display':'block'});  
	   }
	  }
    });
  });
  
  $(".menumobile .excluircarrinhopaquetes").click(function () {
	var id = $(this).attr('data-id');
	$.ajax({
     url: "limparSession.php",
     type: "POST",
     data: "excluir=carrinho&tipo=paquetes&id=" + id,
    }).done(function(resposta) {
	  location.reload();
    });
  });
  
  $(".excluircarrinhopaquetes").click(function () {
    var id = $(this).attr('data-id');
	$.ajax({
     url: "limparSession.php",
     type: "POST",
     data: "excluir=carrinho&tipo=paquetes&id=" + id,
    }).done(function(resposta) {
	  var objRetorno = $.parseJSON(resposta);	
	  $("#carrinhonovopaquetes" + id).css({'display':'none'});
	  $(".carrinhonovopaquetes" + id).css({'display':'none'});
	  if (objRetorno.sucesso) {
	   if(objRetorno.vazio == 1){
	    $(".vacionovo").css({'display':'none'});
		$(".menu-civa").load('carregacarrinho.php');
	   } else if(objRetorno.vazio == 0){
		$(".carrinhovazio").css({'display':'none'}); 
		$(".divcorpaquete").css({'display':'none'});    
		$(".qdtpaquete").css({'display':'none'});  
        $(".totalpaquete").css({'display':'none'}); 		
	    $(".vacionovo").css({'display':'block'});  
	   }
	  }
    });
  });
  

  /*dropdown Reclamar*/
  $("#abrir-dropdown-menu").click(function () {
     $("#drop-menu").slideUp();
     $("#drop-menu-c").slideUp();
      $("#drop-menu-cl").slideUp();
     $("#abrir-dropdown-menu").hide();
     $("#cerrar-dropdown-menu").show();
  });

    $("#cerrar-dropdown-menu").click(function () {
     $("#drop-menu").slideDown();
     $("#abrir-dropdown-menu").show();
     $("#cerrar-dropdown-menu").hide();
     
  });
  
  	  $("#abrir-menu").click(function () {
             $('.menu-mobile').slideDown();
             $("#abrir-menu").hide();
              $('#cerrar-menu').show();
          });
          
          
         $("#cerrar-menu").click(function () {
              $('.menu-mobile').slideUp();
             $("#abrir-menu").show();
              $('#cerrar-menu').hide();
          });
  
  /* Posiconar Menu top*/
  
  posicionarMenu();

$(window).scroll(function() {    
    posicionarMenu();
});

function posicionarMenu() {
    var altura_del_header = $('.menu-h').outerHeight(true);
    var altura_del_menu = $('.menu-fixed').outerHeight(true);

    if ($(window).scrollTop() >= altura_del_header){
        $('.menu-fixed').addClass('fixed');
        $("#btn-share-nav").show();
        $("#precio-nav").show();
        $("#btn-reservar-nav").hide();
        $("#btn-share").hide();
        
      
    } else {
        $('.menu-fixed').removeClass('fixed');
          $("#btn-share-nav").hide();
        $("#precio-nav").hide();
        $("#btn-reservar-nav").show();
        $("#btn-share").show();
    }
}

/* Posiconar Menu top*/


$(window).scroll(function() {   
     posicionarCalendario();
   
});


function posicionarCalendario() {
    var altura_del_div = $('.hasta-aqui').outerHeight(true);
    var altura_del_header_do = $('.menu-h').outerHeight(true);
    var altura_del_menu_do = $('.menu-fixed').outerHeight(true);
    
     if ($(window).scrollTop() >= altura_del_div ){
       //  $("#calendario-fijo").removeClass("calendar-fixed");
     }
     
}




/*Calendario Visita - inicializado con idioma y weekOffset*/
$(function() {
  var idioma = (typeof window.idiomaSistema !== 'undefined' && window.idiomaSistema) ? window.idiomaSistema : 'ES';
  var diasSemanaCortosMap = {
    ES: ['D','L','M','X','J','V','S'],
    EN: ['S','M','T','W','T','F','S'],
    PT: ['D','S','T','Q','Q','S','S'],
    IT: ['D','L','M','M','G','V','S']
  };
  var mesesMap = {
    ES: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    EN: ['January','February','March','April','May','June','July','August','September','October','November','December'],
    PT: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    IT: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre']
  };
  var weekOffsetMap = { ES: 1, PT: 1, IT: 1, EN: 0 };
  var diasSemanaHeader = diasSemanaCortosMap[idioma] || diasSemanaCortosMap['ES'];
  var weekOffset = (typeof weekOffsetMap[idioma] !== 'undefined') ? weekOffsetMap[idioma] : 0;

  function initClndr(selector, templateId) {
    if (!$(selector).length || !$(templateId).length) return;
    $(selector).clndr({
      template: $(templateId).html(),
      daysOfTheWeek: diasSemanaHeader,
      showAdjacentMonths: false,
      weekOffset: weekOffset,
      doneRendering: function() {
        try {
          var idxMes = this.month.month();
          var anio = this.month.year();
          var nombreMes = (mesesMap[idioma] || mesesMap['ES'])[idxMes];
          $(selector + ' .month').text(nombreMes + ' ' + anio);
        } catch(e) { /* silent */ }
      }
    });
  }

  initClndr('.calendario-visitas', '#calendar-template');
  initClndr('.calendario-visitas-movil', '#calendar-template-movil');
});
/*Calendario Visita*/


/*COOKIES*/

    $(document).on('click', '#cerrar-cookies, #cerrar-cookies-movil', function (e) {
      e.preventDefault();
      var cookiev = '1';
      // Persistir cookie un año, accesible en todo el sitio
      document.cookie = 'politicaCookies=' + cookiev + ';path=/;max-age=' + (60 * 60 * 24 * 365);
      $("#cookies, #cookies-movil").slideUp().hide();
    });

    /*SERVICIO CIUDAD Y PAIS*/

      $("#abrir-pais").click(function () {
    $("#div-pais").toggleClass('d-none');
     $("#div-ciudad").addClass('d-none');
  });



       $("#abrir-ciudad").click(function () {
     $("#div-ciudad").toggleClass('d-none');
     $("#div-pais").addClass('d-none');
  });



       /*ACORDEON MOVIL*/




         $( "#btn-descripcion" ).on( "click", function() {
             $("#btn-descripcion").toggleClass('btn-accordion-dark-show');
             $("#btn-precio").removeClass('btn-accordion-dark');
             $("#btn-fechaMovil").removeClass('btn-accordion-dark');
             $("#btn-detalles").removeClass('btn-accordion-dark');
              $("#btn-encuentro").removeClass('btn-accordion-dark');
              $("#btn-cancelaciones").removeClass('btn-accordion-dark');
              $("#btn-opiniones").removeClass('btn-accordion-dark');

          });

           $("#btn-precio").click(function () {
            $("#btn-descripcion").removeClass('btn-accordion-dark-show');
             $("#btn-precio").toggleClass('btn-accordion-dark');
             $("#fechaMovil").removeClass('btn-accordion-dark');
             $("#btn-detalles").removeClass('btn-accordion-dark');
              $("#btn-encuentro").removeClass('btn-accordion-dark');
              $("#btn-cancelaciones").removeClass('btn-accordion-dark');
              $("#btn-opiniones").removeClass('btn-accordion-dark');
          });

             $("#fechaMovil").click(function () {
             $("#btn-descripcion").removeClass('btn-accordion-dark-show');
             $("#btn-precio").removeClass('btn-accordion-dark');
             $("#fechaMovil").toggleClass('btn-accordion-dark');
           $("#btn-detalles").removeClass('btn-accordion-dark');
              $("#btn-encuentro").removeClass('btn-accordion-dark');
              $("#btn-cancelaciones").removeClass('btn-accordion-dark');
              $("#btn-opiniones").removeClass('btn-accordion-dark');
          });

               $("#btn-detalles").click(function () {
             $("#btn-descripcion").removeClass('btn-accordion-dark-show');
             $("#btn-precio").removeClass('btn-accordion-dark');
             $("#fechaMovil").removeClass('btn-accordion-dark');
             $("#btn-detalles").toggleClass('btn-accordion-dark');
            $("#btn-encuentro").removeClass('btn-accordion-dark');
              $("#btn-cancelaciones").removeClass('btn-accordion-dark');
              $("#btn-opiniones").removeClass('btn-accordion-dark');
          });

                 $("#btn-encuentro").click(function () {
           $("#btn-descripcion").removeClass('btn-accordion-dark-show');
             $("#btn-precio").removeClass('btn-accordion-dark');
             $("#fechaMovil").removeClass('btn-accordion-dark');
             $("#btn-detalles").removeClass('btn-accordion-dark');
              $("#btn-encuentro").toggleClass('btn-accordion-dark');
             $("#btn-cancelaciones").removeClass('btn-accordion-dark');
              $("#btn-opiniones").removeClass('btn-accordion-dark');
          });

                   $("#btn-cancelaciones").click(function () {
          $("#btn-descripcion").removeClass('btn-accordion-dark-show');
             $("#btn-precio").removeClass('btn-accordion-dark');
             $("#fechaMovil").removeClass('btn-accordion-dark');
             $("#btn-detalles").removeClass('btn-accordion-dark');
              $("#btn-encuentro").removeClass('btn-accordion-dark');
              $("#btn-cancelaciones").toggleClass('btn-accordion-dark');
              $("#btn-opiniones").removeClass('btn-accordion-dark');
          });

                     $("#btn-opiniones").click(function () {
             $("#btn-descripcion").removeClass('btn-accordion-dark-show');
             $("#btn-precio").removeClass('btn-accordion-dark');
             $("#fechaMovil").removeClass('btn-accordion-dark');
             $("#btn-detalles").removeClass('btn-accordion-dark');
              $("#btn-encuentro").removeClass('btn-accordion-dark');
              $("#btn-cancelaciones").removeClass('btn-accordion-dark');
              $("#btn-opiniones").toggleClass('btn-accordion-dark');
          });



           /* Posiconar acordeon top*/
  
/*  posicionaracordeon();

$(window).scroll(function() {    
    posicionaracordeon();
});

function posicionaracordeon() {
    var altura_del_header = $('.seccion-arriba').outerHeight(true);
    var altura_del_menu = $('.btn-accordion-dark').outerHeight(true);
    var altura_del_footer = $('.footer').outerHeight(true);


    if ($(window).scrollTop() >= altura_del_header && $(window).scrollTop() <= altura_del_footer){
       $(".btn-accordion-dark").addClass('btn-accordion-fijo');

    } else {
         $(".btn-accordion-dark").removeClass('btn-accordion-fijo');
    }


      $( "#btn-descripcion" ).on( "click", function() {
          $(".btn-accordion-dark").removeClass('btn-accordion-fijo');
       });

}*/



/* Posiconar acordeon top*/

       /*FIN ACORDEON MOVIL*/	   
	   
	   

})(jQuery); // End of use strict


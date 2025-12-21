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
  
  
  $("#txtIdiomaSelMovil").click(function(){
    $("#idioma").css('display','block');
    $("#moneda").css('display','none');
  });

  $("#txtMonedaSelMovil").click(function(){
    $("#moneda").css('display','block');
    $("#idioma").css('display','none');
  });

  // Activate scrollspy to add active class to navbar items on scroll
  /*$('body').scrollspy({
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
  $(window).scroll(navbarCollapse);*/

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
     $("#mostrar-pc").slideDown();
     $("#search-pc").slideDown();
     $("#buscar-pc").hide();
     $("#cerrar-buscar-pc").show();
  });

    $("#cerrar-buscar-pc").click(function () {
     $("#mostrar-pc").slideUp();
     $("#search-pc").slideUp();
        $("#buscar-pc").show();
     $("#cerrar-buscar-pc").hide();
  });


  $("#buscar").click(function () {
     $("#destinos").toggleClass('d-none');
  });



  $("#buscar-movil").click(function () {
     $("#destinos-movil").toggleClass('d-none');
    // $("#destinos-movil").slideDown();
    // $("#buscar-movil").addClass("cerrar");
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




/*Calendario Visita*/

 /* $(document).ready(function(){
        
    $('.calendario-visitas').clndr({
        template: $('#calendar-template').html()
    });
  });*/
/*Calendario Visita*/


/*Calendario Visita movil*/

  /*$(document).ready(function(){
        
    $('.calendario-visitas-movil').clndr({
        template: $('#calendar-template-movil').html()
    });
  });
/*Calendario Visita movil*/


/*COOKIES*/

  $("#cerrar-cookies").click(function () {
	 var cookiev = '1'; 
	 document.cookie = 'politicaCookies='+cookiev; 
     $("#cookies").slideUp();
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

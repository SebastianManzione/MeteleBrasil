


 var rol=<?= $rol ?>;
/*<?php echo($eventArray); ?>*/
idServicio='<?php echo($id) ?>';
var calendars = {};

var respuesta;
var impuestosPais=<?= $impuestosPais ?>;
selectorDeFechas(eventArray[0]);
var idHorarioSel="";

function selectorDeFechas(target){ 
  $.post('ctrlfechas.php', {

    data:{    'datos' : JSON.stringify(target), 'id' : JSON.stringify(idServicio), 'money' : JSON.stringify(money), 'impuestosPais' : impuestosPais }
  }, function(response) {
 

     if (respuesta){
      $("#divhora").show();
      $('#divhora').empty();
       $('#divLugares').empty();
         $('#divLugares-movil').empty();
      $("#divhora-movil").show();
      $('#divhora-movil').empty();
     }

     respuesta=JSON.parse(response); 
     cantDatos=respuesta.length;
     txthorario="";
     for (var i = 0 ; i < cantDatos; i++) {
      txthorario=respuesta[i][0]; 
      lugaresLibres=respuesta[i][1];
      txtfecha=respuesta[i][2]; 
      $('#divhora').append(
        '<button type="button" class="btn btn-light btn-block mt-2" id="habilitacant'+i+'" onClick="habilitaCantidades('+i+')">'+txthorario.substring(0,5)+" | "+txtfecha+'</button>');


      $('#divhora-movil').append(
        '<button type="button" class="btn btn-light btn-block mt-2" id="habilitacantmov'+i+'" onClick="habilitaCantidades('+i+')">'+txthorario.substring(0,5)+" | "+txtfecha+'</button>');
if (rol==1||rol==5) {
      $('#divLugares').append(
        '<button type="button" class="btn btn-light btn-block mt-2" ><h2>'+" Disponibilidad: "+lugaresLibres+'</h2></button>');
        $('#divLugares-movil').append(
        '<button type="button" class="btn btn-light btn-block mt-2" ><h2>'+" Disponibilidad: "+lugaresLibres+'<h2></button>');
}
      $("#habilitacant").click(function(){
      
      });
      idHorarioSel=respuesta[i][8];
      txtFechaSeleccionada = respuesta[i][2];
      $('#txtFechaSeleccionada').val(txtFechaSeleccionada);  
      $('#txtFechaSeleccionadaMovil').val(txtFechaSeleccionada); 

     }


     habilitaCantidades('0')

   });
}
   
var claseDiaSel;
var traigoDiaPorClase;
var targetOld="";

$(document).ready( function() {
idServicio='<?php echo($id) ?>'



  $('#txtLugaresLibres').val("No selecciono ninguna fecha");

    var thisMonth = moment().format('YYYY-MM');
    moment().locale('<?php echo $siglax?>');
 //vor ein paar Sekunden
//**********************************calendario pc***
   calendars.clndr1 = $('.cal1').clndr({
        events: eventArray,
        clickEvents: {
            click: function (target) {
             
            //  alert(JSON.stringify(target));
                if (target['events'].length>0) {
  //colorea del clickeado*************************           
                   if(targetOld!==target["events"][0]["title"]){
        
             targetOld=target["events"][0]["title"];

if (traigoDiaPorClase !== undefined ) 
{
for (var i = 0; i < document.getElementsByClassName(claseDiaSel).length; i++) {
traigoDiaPorClase = document.getElementsByClassName(claseDiaSel)[i]
traigoDiaPorClase.style.color = "black";
traigoDiaPorClase.style.background = "#FFF";
traigoDiaPorClase.style.borderRadius = "0px";

}



}

claseDiaSel = (target["element"].getAttribute('class'));

for (var i = 0; i < document.getElementsByClassName(claseDiaSel).length; i++) {
traigoDiaPorClase = document.getElementsByClassName(claseDiaSel)[i];
console.log(traigoDiaPorClase);
traigoDiaPorClase.style.color = "#FFF";
traigoDiaPorClase.style.background = "#029ce2";
traigoDiaPorClase.style.borderRadius = "50%";
}



                   }  

// fin  colorea dia clickeado************************* 


selectorDeFechas(target);

        }
     },
  },
        multiDayEvents: {
            singleDay: 'date',
            endDate: 'endDate',
            startDate: 'startDate'
        },  showAdjacentMonths: false,
        adjacentDaysChangeMonth: false
 });
  




//*******************************************calendario movil

   calendars.clndr2 = $('.cal2').clndr({
        events: eventArray,
        clickEvents: {
            click: function (target) {
            //  alert(JSON.stringify(target));
                if (target['events'].length>0) {
                
  //colorea del clickeado*************************           
                   if(targetOld!==target["events"][0]["title"]){
        
             targetOld=target["events"][0]["title"];

if (traigoDiaPorClase !== undefined ) 
{
for (var i = 0; i < document.getElementsByClassName(claseDiaSel).length; i++) {
traigoDiaPorClase = document.getElementsByClassName(claseDiaSel)[i];
traigoDiaPorClase.style.color = "black";
traigoDiaPorClase.style.background = "#FFF";
traigoDiaPorClase.style.borderRadius = "0px";

}


}

claseDiaSel = (target["element"].getAttribute('class'));

for (var i = 0; i < document.getElementsByClassName(claseDiaSel).length; i++) {
traigoDiaPorClase = document.getElementsByClassName(claseDiaSel)[i];
traigoDiaPorClase.style.color = "#fff";
traigoDiaPorClase.style.background = "#029ce2";
traigoDiaPorClase.style.borderRadius = "50%";


}
       }  

// fin  colorea dia clickeado************************* 

selectorDeFechas(target);

        }
     },
  },
        multiDayEvents: {
            singleDay: 'date',
            endDate: 'endDate',
            startDate: 'startDate'
        },  showAdjacentMonths: false,
        adjacentDaysChangeMonth: false
 });
var fechaSel=eventArray[0]["date"];
var añoSel=moment(fechaSel).year();
var mesSel=moment(fechaSel).month();
var diaSel=moment(fechaSel).day();

calendars.clndr1.setYear(añoSel);
calendars.clndr1.setMonth(mesSel);
calendars.clndr2.setYear(añoSel);
calendars.clndr2.setMonth(mesSel);

 $(document).keydown( function(e) {
    // Left arrow
        if (e.keyCode == 37) {
            calendars.clndr1.back();
      
        }
// Right arrow
        if (e.keyCode == 39) {
            calendars.clndr1.forward();
          
           
        }
    });

});   //******************FIN DE CALENDARIO y document.ready***************

var txthorario;
var habilitacant;
var habilitacantmov;
function habilitaCantidades(i){
//$("#divhora-movil").hide();
//$("#divpersona-movil").show();
//$("#divhora").hide();
//$("#divpersona").show();

$(habilitacant).css('background-color','rgb(248, 249, 250)');
$(habilitacantmov).css('background-color','rgb(248, 249, 250)');
habilitacant= '#habilitacant'+i;
habilitacantmov= '#habilitacantmov'+i;

$(habilitacant).css('background-color','rgb(93, 173, 226)');  
$(habilitacantmov).css('background-color','rgb(93, 173, 226)'); 




 
          txthorario=respuesta[i][0]; 
         lugaresLibres = respuesta[i][1];
         txtFechaSeleccionada = respuesta[i][2];
          pAdulto = respuesta[i][3];
          pMenor12 =respuesta[i][4];
          pMenor5 =respuesta[i][5];
          pMenor3 =respuesta[i][6];
          idMoneda =respuesta[i][7];
 $('#seleccionar_personas').empty();
$('#seleccionar_personasMovil').empty();

 $('#seleccionar_personas').
 append('<div class="container py-3"><div class="row">                                  <div class="col-md-3">                                      <p class="counter-label mb-2 text-left">Adultos</p>                                  </div>                                  <div class="col-md-3">                                     <span class="counter-label_span"><label id="pAdul">'+sym+pAdulto+'</label></span>                                  </div>                                  <div class="col-md-1 " style=" padding-left: 0px !important;padding-right: 0px !important;"  >       <a  onclick="decrementa(1)"><i class="fa fa-minus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-4"><input class="form-control" type="number" id="cantAdul" onchange="calcula(this.value, 1)" min="0" value="0" >                                   </div>                                   <div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;">     <a  onclick="incrementa(1)"><i class="fa fa-plus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-3">         <small class="counter-label_span_precio"><label id=totalAdul></label></small>                                   </div>                              </div>                          </div>');

 $('#seleccionar_personasMovil').
 append('<div class="container py-3"><div class="row">                                  <div class="col-md-3 col-3">                                      <p class="counter-label mb-2 text-left">Adultos</p>                                  </div>                                  <div class="col-md-3 col-3">                                     <span class="counter-label_span"><label id="pAdulMov">'+sym+pAdulto+'</label></span>                                  </div>                                  <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;"  >       <a  onclick="decrementa(1)"><i class="fa fa-minus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-4 col-3"><input class="form-control" type="number" id="cantAdulMov" onchange="calcula(this.value, 1)" min="0" value="0" >                                   </div>                                   <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;">     <a  onclick="incrementa(1)"><i class="fa fa-plus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-3 col-3">         <small class="counter-label_span_precio"><label id=totalAdulMov></label></small>                                   </div>                              </div>                          </div>');
if ( pMenor12>0) {

   $('#seleccionar_personas').append(' <div class="container py-3">                              <div class="row">                                 <div class="col-md-3">                                      <p class="counter-label mb-2 text-left">Menores de 12 años</p>                                  </div>                                  <div class="col-md-3">                                     <span class="counter-label_span"><label id=pMen12>'+sym+ pMenor12+'</label></span>                                  </div>                                  <div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >       <a  onclick="decrementa(2)"><i class="fa fa-minus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-4"><input class="form-control" type="number" id="cant12" onchange="calcula(this.value, 2)" min="0" value="0">                                   </div>                                   <div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;" > <a  onclick="incrementa(2)"><i class="fa fa-plus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-3">               <small class="counter-label_span_precio"><label id=totalMen12></label></small>                                   </div>                              </div>                          </div>');

    $('#seleccionar_personasMovil').append(' <div class="container py-3">                              <div class="row">                                 <div class="col-md-3 col-3">                                      <p class="counter-label mb-2 text-left">Menores de 12 años</p>                                  </div>                                  <div class="col-md-3 col-3">                                     <span class="counter-label_span"><label id=pMen12Mov>'+sym+ pMenor12+'</label></span>                                  </div>                                  <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >       <a  onclick="decrementa(2)"><i class="fa fa-minus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-4 col-3"><input class="form-control" type="number" id="cant12Mov" onchange="calcula(this.value, 2)" min="0" value="0">                                   </div>                                   <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" > <a  onclick="incrementa(2)"><i class="fa fa-plus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-3 col-3">               <small class="counter-label_span_precio"><label id=totalMen12Mov></label></small>                                   </div>                              </div>                          </div>');
}

 if ( pMenor5>=0) {
$('#seleccionar_personas').append(' <div class="container py-3">                              <div class="row">                                  <div class="col-md-3">                                      <p class="counter-label mb-2 text-left">Menores de 5 años</p>                                  </div>                                  <div class="col-md-3">                <span class="counter-label_span"><label id=pMen5>'+sym+pMenor5+'</label></span>                                  </div>                                  <div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;" > <a  onclick="decrementa(3)"><i class="fa fa-minus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-4"><input class="form-control" type="number" id="cant5" onchange="calcula(this.value, 3)" min="0" value="0" >                                   </div>                                   <div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;" > <a  onclick="incrementa(3)"><i class="fa fa-plus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-3">           <small class="counter-label_span_precio"><label id=totalMen5></label></small>                                   </div>                              </div>                          </div>');

$('#seleccionar_personasMovil').append(' <div class="container py-3">                              <div class="row">                                  <div class="col-md-3 col-3">                                      <p class="counter-label mb-2 text-left">Menores de 5 años</p>                                  </div>                                  <div class="col-md-3 col-3">                <span class="counter-label_span"><label id=pMen5Mov>'+sym+pMenor5+'</label></span>                                  </div>                                  <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" > <a  onclick="decrementa(3)"><i class="fa fa-minus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-4 col-3"><input class="form-control" type="number" id="cant5Mov" onchange="calcula(this.value, 3)" min="0" value="0" >                                   </div>                                   <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" > <a  onclick="incrementa(3)"><i class="fa fa-plus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-3 col-3" >           <small class="counter-label_span_precio" ><label id=totalMen5Mov></label></small>                                   </div>                              </div>                          </div>');

 }

  
 if ( pMenor3>0) {

 $('#seleccionar_personas').append(' <div class="container py-3">                              <div class="row">                                  <div class="col-md-3">                                      <p class="counter-label mb-2 text-left">Menores de 3 años</p>                                  </div>                                  <div class="col-md-3">                                     <span class="counter-label_span"><label id=pMen3>'+sym+pMenor3+'</label></span>                                  </div>                                  <div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >  <a  onclick="decrementa(4)"><i class="fa fa-minus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-4"><input class="form-control" type="number" id="cant3" onchange="calcula(this.value, 4)" min="0" value="0"  >                                   </div>                                   <div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;" > <a  onclick="incrementa(4)"><i class="fa fa-plus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-3">                   <small class="counter-label_span_precio" style="margin-right: 10px;"><label id=totalMen3></label></small>                                   </div>                              </div>                          </div>');

$('#seleccionar_personasMovil').append(' <div class="container py-3">                              <div class="row">                                  <div class="col-md-3 col-3">                                      <p class="counter-label mb-2 text-left">Menores de 3 años</p>                                  </div>                                  <div class="col-md-3 col-3">                                     <span class="counter-label_span"><label id=pMen3Mov>'+sym+pMenor3+'</label></span>                                  </div>                                  <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >  <a  onclick="decrementa(4)"><i class="fa fa-minus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-4 col-3"><input class="form-control" type="number" id="cant3Mov" onchange="calcula(this.value, 4)" min="0" value="0"  >                                   </div>                                   <div class="col-md-1 col-3" style=" padding-left: 0px !important;padding-right: 0px !important;" > <a  onclick="incrementa(4)"><i class="fa fa-plus-circle fa-2x"></i></a>                                   </div>                                   <div class="col-md-3 col-3">                   <small class="counter-label_span_precio"><label id=totalMen3Mov></label></small>                                   </div>                              </div>                          </div>');

 }
 
                           


}


 totalPersonas=0;
    cantidadAdul=0;
    cantidad12=0;
    cantidad5=0;
    cantidad3=0;
total=0;

    totalPrecioAdulto=0;
    totalPrecioMenor12=0;
    totalPrecioMenor5=0;
    totalPrecioMenor3=0;

function incrementa(tipo){
switch(tipo){
case 1:
cantidadAdul+=1;
calcula(cantidadAdul, 1);

break;

case 2:
cantidad12+=1;
calcula(cantidad12, 2);
break;

case 3:
cantidad5+=1;

calcula(cantidad5, 3);
break;

case 4:
cantidad3+=1;
calcula(cantidad3, 4);
break;


}


}


function decrementa(tipo){
  
switch(tipo){
case 1:
if (cantidadAdul>0) {
  cantidadAdul-=1;
calcula(cantidadAdul, 1);}

break;

case 2:
if (cantidad12>0) {
cantidad12-=1;
calcula(cantidad12, 2);

}

break;

case 3:
if (cantidad5>0) {
cantidad5-=1;
calcula(cantidad5, 3);
  
}
break;

case 4:
if (cantidad3>0) {
cantidad3-=1;
calcula(cantidad3, 4);
  
}
break;


}
}
function calcula(multiplicador, tipo){
totalAdicionales=0;
totalAdicionalesTMP=Array();

multiplicador=parseInt(multiplicador);

switch (tipo){
    case 1:
    if (true) {
      
    $('#totalAdul').text(simboloMonetario+(pAdulto*multiplicador).toFixed(2));  
    $('#totalAdulMov').text(simboloMonetario+(pAdulto*multiplicador).toFixed(2));

    $('#cantAdul').val(cantidadAdul);   
    $('#cantAdulMov').val(cantidadAdul);

    totalPrecioAdulto=pAdulto*multiplicador;
    cantidadAdul=multiplicador;
    totalPersonas=cantidadAdul+cantidad12+cantidad5+cantidad3;
    while (totalPersonas>lugaresLibres) {
      //alert("No se puede reservar mas lugares que los libres");
      cantidadAdul-=1;
      totalPersonas=cantidadAdul+cantidad12+cantidad5+cantidad3;
      totalPrecioAdulto=pAdulto*cantidadAdul;
      
      $('#cantAdul').val(cantidadAdul);   
      $('#cantAdulMov').val(cantidadAdul);
      
      total=totalPrecioAdulto+totalPrecioMenor12+totalPrecioMenor5+totalPrecioMenor3;
      $('#total').text(total);
      
      $('#totalAdul').text(simboloMonetario+(pAdulto*cantidadAdul).toFixed(2)); 
      $('#totalAdulMov').text(simboloMonetario+(pAdulto*cantidadAdul).toFixed(2));

    }

    }
    
  
    else{

      $('#totalAdul').text("gratis");    
      $('#totalAdulMov').text("gratis");


    }
  
    break;
    case 2:
    if (true) {
    $('#totalMen12').text(simboloMonetario+(pMenor12*multiplicador).toFixed(2));    
    $('#totalMen12Mov').text(simboloMonetario+(pMenor12*multiplicador).toFixed(2));

  $('#cant12').val(cantidad12);   
      $('#cant12Mov').val(cantidad12);
    totalPrecioMenor12=pMenor12*multiplicador;
    cantidad12=multiplicador;
    totalPersonas=cantidadAdul+cantidad12+cantidad5+cantidad3;
      while (totalPersonas>lugaresLibres) {
      //alert("No se puede reservar mas lugares que los libres");
      cantidad12-=1;
      totalPersonas=cantidadAdul+cantidad12+cantidad5+cantidad3;
      totalPrecioMenor12=pMenor12*cantidad12;
     
      $('#cant12').val(cantidad12);   
      $('#cant12Mov').val(cantidad12);
     
      total=totalPrecioAdulto+totalPrecioMenor12+totalPrecioMenor5+totalPrecioMenor3;
    
        $('#total').text(total);
        $('#totalMen12').text(simboloMonetario+(pMenor12*cantidad12).toFixed(2));    
        $('#totalMen12Mov').text(simboloMonetario+(pMenor12*cantidad12).toFixed(2));
    }
  }
    else{
       $('#cant12').val(cantidad12);   
      $('#cant12Mov').val(cantidad12);
          $('#totalMen12').text(simboloMonetario+(pMenor5*multiplicador).toFixed(2).toFixed(2));
           $('#totalMen12Mov').text(simboloMonetario+(pMenor5*multiplicador).toFixed(2));

        }
    
    break;

    case 3:
    if (true) {
    $('#totalMen5').text(simboloMonetario+(pMenor5*multiplicador).toFixed(2));   
    $('#totalMen5Mov').text(simboloMonetario+(pMenor5*multiplicador).toFixed(2));

      $('#cant5').val(cantidad5);
        $('#cant5Mov').val(cantidad5);
    totalPrecioMenor5=pMenor5*multiplicador;
    cantidad5=multiplicador;
    totalPersonas=cantidadAdul+cantidad12+cantidad5+cantidad3;
        while (totalPersonas>lugaresLibres) {
      //alert("No se puede reservar mas lugares que los libres");
      cantidad5-=1;
      totalPersonas=cantidadAdul+cantidad12+cantidad5+cantidad3;
      totalPrecioMenor5=pMenor5*cantidad5;
      $('#cant5').val(cantidad5);
        $('#cant5Mov').val(cantidad5);
      total=totalPrecioAdulto+totalPrecioMenor12+totalPrecioMenor5+totalPrecioMenor3;
     
      $('#total').text(total);
      $('#totalMen5').text(simboloMonetario+(pMenor5*cantidad5).toFixed(2));
      $('#totalMen5Mov').text(simboloMonetario+(pMenor5*cantidad5).toFixed(2));
    }


  }
    else{
       $('#cant5').val(cantidad5);
        $('#cant5Mov').val(cantidad5);
        $('#totalMen5').text(simboloMonetario+(pMenor5*multiplicador).toFixed(2));
        $('#totalMen5Mov').text(simboloMonetario+(pMenor5*multiplicador).toFixed(2));
    }
    break;

    case 4:
    if (true) {
    $('#totalMen3').text(simboloMonetario+(pMenor3*multiplicador).toFixed(2));
    $('#totalMen3Mov').text(simboloMonetario+(pMenor3*multiplicador).toFixed(2));

      $('#cant3').val(cantidad3);
      $('#cant3Mov').val(cantidad3);
    totalPrecioMenor3=pMenor3*multiplicador;
    cantidad3=multiplicador;
    totalPersonas=cantidadAdul+cantidad12+cantidad5+cantidad3;
   

  }
    else{
           $('#cant3').val(cantidad3);
               $('#cant3Mov').val(cantidad3);
    $('#totalMen3').text(simboloMonetario+(pMenor3*multiplicador).toFixed(2));
    $('#totalMen3Mov').text(simboloMonetario+(pMenor3*multiplicador).toFixed(2));

      }

       while (totalPersonas>lugaresLibres) {
    //  alert("No se puede reservar mas lugares que los libres");
      cantidad3-=1;
      totalPersonas=cantidadAdul+cantidad12+cantidad5+cantidad3;
      totalPrecioMenor3=pMenor3*cantidad3;
      $('#cant3').val(cantidad3);
      $('#cant3Mov').val(cantidad3);
      total=totalPrecioAdulto+totalPrecioMenor12+totalPrecioMenor5+totalPrecioMenor3;
      $('#total').text(total);
      $('#totalMen3').text(simboloMonetario+(pMenor3*cantidad3));
      $('#totalMen3Mov').text(simboloMonetario+(pMenor3*cantidad3));
    }

    break;

    }

  totalPersonas=parseInt(cantidadAdul+cantidad12+cantidad5+cantidad3);
  total=totalPrecioAdulto+totalPrecioMenor12+totalPrecioMenor5+totalPrecioMenor3;
  $('#total').text(total);

  $('.totalReserva').text(totalAdicionales+total);

}



var totalAdicionalesTMP=Array();
var adicionales=Array();

function IncrementaAdicional(idS){
  
  if (adicionales[idS] === undefined) {

adicionales[idS]=Array();
adicionales[idS][1]=0;

  }
  
adicionales[idS][1]+=1;
InsertarAdicional(adicionales[idS][1], idS);
}
function DecrementaAdicional(idS){
if(adicionales[idS][1]>0){
  adicionales[idS][1]-=1;
  InsertarAdicional(adicionales[idS][1], idS);
}

}


function InsertarAdicional(valor, idServicio){


    adicionales[idServicio][0]=(idServicio);
    adicionales[idServicio][1]=valor;


        campo="#cantPersAdc"+idServicio;
       campoPrecio="#txtPrecio"+idServicio;
      campoTotal="lblTotal"+idServicio; 
      campoValPrecio="#precio"+idServicio;
      precio = $(campoValPrecio).val();
   
          campoMovil="#cantPersAdcMovil"+idServicio;
       campoPrecioMovil="#txtPrecioMovil"+idServicio;
      campoTotalMovil="lblTotalMovil"+idServicio;
          campoValPrecioMovil="#precioMovil"+idServicio;
 if (totalPersonas<valor) {
while (totalPersonas<valor) {

    valor--;

   adicionales[idServicio][1]=valor;
    }
    adicionales[idServicio][1]=valor;

}
  
total=valor*precio;

totalAdicionales=0;

  



 document.getElementById(campoTotal).innerHTML=sym+total;

  $(campoMovil).val(valor);



 document.getElementById(campoTotalMovil).innerHTML=sym+total;

  $(campo).val(valor);




  }
adicionalesLimpio=Array();
  function enviar(){

//confirm("esta seguro?");
if (true) {

if (totalPersonas<=0) {
  alert("Agregue al menos una persona para poder continuar con su reserva");
}
else{

contador=0;
for (var i = 0; i < adicionales.length; i++) {
  if(adicionales[i]==null){

  }
  else{
    if (adicionales[i][1]>0) {
      adicionalesLimpio[contador]=Array();
      adicionalesLimpio[contador].push(adicionales[i][0]);
      adicionalesLimpio[contador].push(adicionales[i][1]);
      console.log(adicionales[i][0]+"|"+adicionales[i][1]);
      contador++;
    }
    
  }

}
var impuestosPais='<?= $impuestosPais; ?>';
$.redirect('datos-p.php', 
  {'idHorarioSel': idHorarioSel, 
  'adicionalesLimpio': adicionalesLimpio,
'totalPersonas': totalPersonas,
'cantidadAdul': cantidadAdul,
'cantidad12': cantidad12,
'cantidad5': cantidad5,
'cantidad3': cantidad3,
'idServicio': idServicio,
'codCupon': codCupon,
'totalReserva': total,
'money':money,
'sym':sym,
"impuestosPais": impuestosPais,
},'POST');




}

  }

      }

 <script>
  	idServicio='<?php echo($id) ?>'
     var eventArray=<?php echo($eventArray); ?>;
</script
><script>
  
var calendars = {};

$(document).ready( function() {



  $('#txtLugaresLibres').val("No selecciono ninguna fecha");

    var thisMonth = moment().format('YYYY-MM');





</script>
<!-- FIN SCRIPTS NECESARIOS-->
<script>
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
adicionales=Array();
multiplicador=parseInt(multiplicador);
switch (tipo){
    case 1:
    if (pAdulto>=1) {
      
    $('#totalAdul').text(simboloMonetario+(pAdulto*multiplicador));  
    $('#totalAdulMov').text(simboloMonetario+(pAdulto*multiplicador));

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
      
      $('#totalAdul').text(simboloMonetario+(pAdulto*cantidadAdul)); 
      $('#totalAdulMov').text(simboloMonetario+(pAdulto*cantidadAdul));

    }

    }
    
  
    else{

      $('#totalAdul').text("gratis");    
      $('#totalAdulMov').text("gratis");


    }
  
    break;
    case 2:
    if (pMenor12>=1) {
    $('#totalMen12').text(simboloMonetario+(pMenor12*multiplicador));    
    $('#totalMen12Mov').text(simboloMonetario+(pMenor12*multiplicador));

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
        $('#totalMen12').text(simboloMonetario+(pMenor12*cantidad12));    
        $('#totalMen12Mov').text(simboloMonetario+(pMenor12*cantidad12));
    }
  }
    else{
          $('#totalMen12').text("gratis");  
           $('#totalMen12Mov').text("gratis");

        }
    
    break;

    case 3:
    if (pMenor5>=1) {
    $('#totalMen5').text(simboloMonetario+(pMenor5*multiplicador));   
    $('#totalMen5Mov').text(simboloMonetario+(pMenor5*multiplicador));

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
      $('#totalMen5').text(simboloMonetario+(pMenor5*cantidad5));
      $('#totalMen5Mov').text(simboloMonetario+(pMenor5*cantidad5));
    }


  }
    else{
      $('#totalMen5').text("gratis");
        $('#totalMen5Mov').text("gratis");
    }
    break;

    case 4:
    if (pMenor3>=1) {
    $('#totalMen3').text(simboloMonetario+(pMenor3*multiplicador));
    $('#totalMen3Mov').text(simboloMonetario+(pMenor3*multiplicador));

      $('#cant3').val(cantidad3);
      $('#cant3Mov').val(cantidad3);
    totalPrecioMenor3=pMenor3*multiplicador;
    cantidad3=multiplicador;
    totalPersonas=cantidadAdul+cantidad12+cantidad5+cantidad3;
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





  }
    else{
        $('#totalMen3').text("gratis");
        $('#totalMen3Mov').text("gratis");

      }
    break;

    }

  totalPersonas=parseInt(cantidadAdul+cantidad12+cantidad5+cantidad3);
  total=totalPrecioAdulto+totalPrecioMenor12+totalPrecioMenor5+totalPrecioMenor3;
  $('#total').text(total);

  $('.totalReserva').text(totalAdicionales+total);
}
adicionales=Array();
totalAdicionalesTMP=Array();
function InsertarAdicional(valor, idServicio, precio){

  if (totalPersonas<valor) {
while (totalPersonas<valor) {
      //alert("No se puede reservar mas lugares que los libres");
    
    alert("No pueden alquilar mas adicionales que personas reservadas");
    campo="txtSrvAdc"+idServicio;
    //alert(campo);
    valor--;
    document.getElementById(campo).value=valor;
    }
    
    

  }


    adicionales[idServicio]=Array();
  adicionales[idServicio].push(idServicio,valor,precio);

totalAdicionalesTMP[idServicio]=(valor*precio);
totalAdicionales=0;
for (var i = 0 ; i <= totalAdicionalesTMP.length; i++) {
  //console.log(totalAdicionales[i]+"papa");
  if (totalAdicionalesTMP[i]==null) {

  }else{
    totalAdicionales+=(totalAdicionalesTMP[i]);
  }
  
  $('#totalAdicionales').text(totalAdicionales);
  $('.totalReserva').text(totalAdicionales+total);
  
}

  }

</script>
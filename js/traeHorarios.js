// -*- coding: utf-8 -*-
// Asegurar que el s�mbolo de moneda exista para evitar ReferenceError
if (typeof symMoneda === 'undefined') {
  var symMoneda = '';
}

var reserva = new Array();
var reservaAdicionales = new Array();
var idServicioSeleccionado = 0;
var cantidadPersonas = 1;
var precioTotal = 0;
var disponibilidad = 0;
var disponibilidadPorTarifa = {}; // Objeto para almacenar disponibilidad de cada tarifa
var intentoActual = 0;
var salidas;
var salidastotales;

// Formatea precios con separador de miles y dos decimales
function toNumeric(amount) {
  if (typeof amount === 'number') return amount;
  if (amount === null || typeof amount === 'undefined') return 0;
  var s = String(amount).trim();
  // Quitar separadores de miles (.) y normalizar coma decimal a punto
  s = s.replace(/\./g, '').replace(/,/g, '.');
  var n = parseFloat(s);
  return isNaN(n) ? 0 : n;
}

// Obtiene valor num�rico robusto de tarifas/adicionales con campos alternativos
function getPrecioValor(item) {
  if (!item) return 0;
  
  // Intentar con valor - puede ser n�mero o string con s�mbolo
  var v = null;
  if (item.hasOwnProperty('valor')) {
    if (typeof item['valor'] === 'string' && item['valor'].match(/[^0-9,.-]/)) {
      // Tiene caracteres no num�ricos (s�mbolos), extraer solo n�meros
      var stripped = item['valor'].replace(/[^0-9,.-]/g, '');
      v = toNumeric(stripped);
    } else {
      v = toNumeric(item['valor']);
    }
  }
  
  // Solo buscar en otros campos si NO se encontr� el valor (null), no si es 0
  if (v === null && item['valorFormateado']) v = toNumeric(item['valorFormateado']);
  if (v === null && item['valorSym']) {
    var stripped = String(item['valorSym']).replace(/[^0-9,.-]/g, '');
    v = toNumeric(stripped);
  }
  return v === null ? 0 : v;
}

function formatPrice(amount) {
  var numeric = toNumeric(amount);
  var symbol = (typeof symMoneda !== 'undefined' && symMoneda !== null) ? String(symMoneda).trim() : '';
  
  // Si es AR$ (pesos argentinos), no mostrar decimales
  var decimals = (symbol.toUpperCase().indexOf('AR') !== -1) ? 0 : 2;
  
  return symbol + ' ' + numeric.toLocaleString('es-ES', {
    minimumFractionDigits: decimals,
    maximumFractionDigits: decimals
  });
}

function actualizaPrecios(){
  if (precioTotal >= 0) {
    var total = Number(precioTotal) || 0;

    $('#precioTotal0').text(formatPrice(total));
    $('#precio-nav').text(formatPrice(total));
    $('#precioTotalFooterNavCelular').text(formatPrice(total));

    var precioTotalSinDescuento = total * 1.1356987;
    $('#precioTotalSinDescuento').text(formatPrice(precioTotalSinDescuento));
  }
}

function limpiarTarifaYAdicionales(){
  $('#divAdicionalesNoIncluidos').empty();
  // En móvil el contenedor usado es "divAdicionalesNoIncluidosCelular"
  $('#divAdicionalesNoIncluidosCelular').empty();
  $('#seleccionar_adicionales_a').hide();
  $('#divhora').empty();
  $('#divhora-movil').empty();
  $('#seleccionar_personas').empty();
  $('#seleccionar_personasMovil').empty();
  $('#ulIncluidos').empty();
  $('#ulNoIncluidos').empty();
  reserva=[];
  reservaAdicionales=[];
  cantidadPersonas=1;
  precioTotal=0;
}

function enviar(){
  if (cantidadPersonas>0) {
    $.post("admin/ctrl/ctrlHorarios", {reserva : reserva ,reservaAdicionales: reservaAdicionales}, function(data, status){
      window.location="carrito";
    });
  } else {
    alert("Seleccione al menos una persona");
  }
}

function traeHorarios($fecha, $idServicio){
  limpiarTarifaYAdicionales();
  
  $.post("admin/ctrl/ctrlHorarios", {fecha: $fecha, idServicio: $idServicio}, function(data, status){
    salidas = JSON.parse(data);
    
    // Filtrar solo salidas con disponibilidad
    var salidasDisponibles = [];
    for (var i = 0; i < salidas.length; i++) {
      if (salidas[i]["disponibilidad"] && parseInt(salidas[i]["disponibilidad"]) > 0) {
        salidasDisponibles.push(salidas[i]);
      }
    }
    
    salidastotales = salidasDisponibles.length;
    
    // Si no hay salidas disponibles en esta fecha, buscar la siguiente fecha con disponibilidad
    if (salidastotales === 0 && typeof eventArray !== 'undefined' && eventArray.length > 0) {
      // Buscar la siguiente fecha disponible en el array
      for (var j = 0; j < eventArray.length; j++) {
        if (eventArray[j]["date"] > $fecha) {
          // Intentar con la siguiente fecha
          traeHorarios(eventArray[j]["date"], $idServicio);
          return;
        }
      }
      
      // Si no hay fechas futuras, mostrar mensaje
      $('#divhora').html('<p class="text-center text-muted p-3">No hay salidas disponibles</p>');
      $('#divhora-movil').html('<p class="text-center text-muted p-3">No hay salidas disponibles</p>');
      return;
    }
    
    for (var i = 0; i < salidasDisponibles.length; i++) {
      var nombre = salidasDisponibles[i]["nombre"] || '';
      var horaSalida = salidasDisponibles[i]["horaSalida"] || '';
      var sinHorario = parseInt(salidasDisponibles[i]["sinHorario"] || 0);
      var sinHorarioTexto = salidasDisponibles[i]["sinHorarioTexto"] || '';
      var idSalida = salidasDisponibles[i]["idServicioSalidas"];
      
      var textoBoton = nombre;
      if (sinHorario === 1 && sinHorarioTexto) {
        textoBoton = sinHorarioTexto;
      } else {
        if (horaSalida && horaSalida !== '00:00:00') {
          textoBoton += ' - ' + horaSalida.substring(0, 5);
        }
      }
      
      var botonHora = '<button id="btnSalida' + idSalida + '" class="btn btn-outline-primary btn-hora m-2" data-idsalida="' + idSalida + '" onclick="traeTarifas(' + idSalida + ')">' + textoBoton + '</button>';
      var botonHoraMov = '<button id="btnSalidaMov' + idSalida + '" class="btn btn-outline-primary btn-hora m-2" data-idsalida="' + idSalida + '" onclick="traeTarifas(' + idSalida + ')">' + textoBoton + '</button>';
      
      $('#divhora').append(botonHora);
      $('#divhora-movil').append(botonHoraMov);
    }
    
    // Seleccionar primera salida disponible autom�ticamente
    // Agregar un peque�o delay para asegurar que los botones est�n en el DOM
    if (salidastotales > 0) {
      setTimeout(function() {
        traeTarifas(salidasDisponibles[0]["idServicioSalidas"]);
      }, 100);
    }
  });
}

function traeTarifas($idSalida){
  // Limpiar selectores de personas (desktop y m�vil)
  $('#seleccionar_personas').empty();
  $('#seleccionar_personasMovil').empty();
  $('#divAdicionalesNoIncluidos').empty();
  $('#divAdicionalesNoIncluidosCelular').empty();
  $('#seleccionar_adicionales_a').hide();
  
  // Resetear variables globales
  reserva=[];
  reservaAdicionales=[];
  cantidadPersonas=0;
  precioTotal=0;
  
  // Resetear displays de precio
  $('#precioTotal').text('$0');
  $('#precioTotalMovil').text('$0');
  $('#cantidadPersonasSeleccionadas').text('0');
  $('#cantidadPersonasSeleccionadasMovil').text('0');
  
  $.post("admin/ctrl/ctrlHorarios", {idSalida: $idSalida}, function(data, status){
    var tarifas = JSON.parse(data);
    disponibilidad=tarifas[0]["disponibilidad"];
    
    $('#ulIncluidos').html(''); 
    $('#ulNoIncluidos').html('');
    $('#ulIncluidosMovil').html(''); 
    $('#ulNoIncluidosMovil').html('');
    $('#rowCirculosPrecios').html('');
    $('#rowCirculosPreciosMovil').html('');
    
    cancelaciones=new Array();
    
    for (var i = 0; i < tarifas.length; i++) {
      cancelaciones.push(tarifas[i]['cancelaciones']);
      tipoTarifa=tarifas[i]['tipoTarifaNombre'];
      var tarifaDisponibilidad = parseInt(tarifas[i]['disponibilidad']) || 0;
      
      // Almacenar disponibilidad por tarifa para validación posterior
      disponibilidadPorTarifa[tarifas[i]['idServicioSalidasTarifas']] = tarifaDisponibilidad;
      
      var badgeAgotado = tarifaDisponibilidad <= 0 ? '<span class="badge badge-danger ml-2">AGOTADO</span>' : '';
      var textoDisponibilidad = tarifaDisponibilidad > 0 ? '<small class="text-muted d-block">Disponibles: ' + tarifaDisponibilidad + '</small>' : '';
      
      var lineaPrecios='   <div class="col-lg-2 col-5" style="margin-left: 20px">'+
        '<p class="text-center ">'+tarifas[i]['nombre']+' ('+tarifas[i]['edadFrom']+' a '+tarifas[i]['edadTo']+' años) '+tipoTarifa+badgeAgotado+'</p>'+
        textoDisponibilidad+
        '<div class="circulo-b">'+
                '<p class="text-center text-primary">'+formatPrice(getPrecioValor(tarifas[i]))+'</p>'+
               '</div>'+
             '</div>';











// HTML unificado responsivo para PC y móvil
var disabledClass = tarifaDisponibilidad <= 0 ? ' opacity-50' : '';
var disabledAttr = tarifaDisponibilidad <= 0 ? ' disabled' : '';
var cursorStyle = tarifaDisponibilidad <= 0 ? 'not-allowed' : 'pointer';

var linea='	<div class="container py-3">'+
                            '  <div class="row">'+
                                  '<div class="col-12">'+
                                     ' <p class="counter-label mb-2 text-left">'+tarifas[i]['nombre']+' ('+tarifas[i]['edadFrom']+' a '+tarifas[i]['edadTo']+' años) '+tipoTarifa+badgeAgotado+'<br><small class="text-muted">Disponibles: '+tarifaDisponibilidad+'</small></p>'+
                                  '</div>'+
                                  '<div class="col-md-3 col-3">'+
  '<span class="counter-label_span"><label class="txtPrecio tarifa-'+tarifas[i]['idServicioSalidasTarifas']+'">'+formatPrice(getPrecioValor(tarifas[i]))+'</label></span>'+
                                  '</div>'+
                                  '<div class="col-md-1 col-1" style="padding-left:0 !important;padding-right:0 !important;">'+
                                 '<a href="javascript:void(0);" onclick="CalculaPersonas('+tarifas[i]['idServicioSalidasTarifas']+',0); return false;" style="cursor:'+cursorStyle+';" class="'+disabledClass+'"> '+
                                '  <i class="fa fa-minus-circle fa-2x"></i>'+ 
                                  ' </a></div>'+
                                   '<div class="col-md-4 col-4">'+
'<input type="number" class="form-control cantPers tarifa-'+tarifas[i]['idServicioSalidasTarifas']+'" value="0" data-id="'+tarifas[i]['idServicioSalidasTarifas']+'" disabled>'+
                                   '</div>'+
   '<div class="col-md-1 col-1" style="padding-left:0 !important;padding-right:0 !important;">'+
'  <a href="javascript:void(0);" onclick="CalculaPersonas('+tarifas[i]['idServicioSalidasTarifas']+',1); return false;" style="cursor:'+cursorStyle+';" class="'+disabledClass+'">'+
' <i id="btnMas'+tarifas[i]['idServicioSalidasTarifas']+'" class="fa fa-plus-circle fa-2x"></i>'+
'</a>'+
                                 '  </div>'+
     '  <div class="col-md-3 col-3">'+
       '<small class="counter-label_span_precio"><label class="lblTotal tarifa-'+tarifas[i]['idServicioSalidasTarifas']+'"></label></small>'+
                                   '</div>'+
                              '</div>'+
                          '</div>';

// Usamos el mismo HTML para ambas versiones
var lineaCelular = linea;



                              '</div>'+



                          '</div>';











 $('#divCancelaciones').html("<p></p>");



$('#pIdiomas').text(tarifas[0]["idiomas"]);



$('#pIdiomasMovil').text(tarifas[0]["idiomas"]);



$('#pIdiomasNav').text(tarifas[0]["idiomas"]);
$('#pIdiomasNavCelular').text(tarifas[0]["idiomas"]);

// Agregar a ambos contenedores (PC y móvil) - ahora usan clases en lugar de IDs
$('#seleccionar_personas').append(linea);
$('#seleccionar_personasMovil').append(linea);











 $('#rowCirculosPrecios').append(lineaPrecios);



 $('#rowCirculosPreciosMovil').append(lineaPrecios);



 $('#seleccionar_personasMovil').show();



 $('#seleccionar_personas').show();







}  



CalculaPersonas(tarifas[0]['idServicioSalidasTarifas'], 1);







repetidos=new Array();



for (var x = 0; x < cancelaciones.length; x++) {





if (repetidos.includes(parseInt(cancelaciones[x]["idCancelacion"])) == false) {



  $('#divCancelaciones').append('<p>'+cancelaciones[x]["texto"]+'</p>'); 

   $('#divCancelacionesMovil').append('<p>'+cancelaciones[x]["texto"]+'</p>');

}

  repetidos.push(parseInt(cancelaciones[x]["idCancelacion"]));



}

if (repetidos.includes(1)) {

  $("#textoCancelacionGratuita").css("display", "block");

  

    $("#textoCancelacionGratuitaCelular").css("display", "block");

}

else{

  $("#textoCancelacionGratuitaCelularReservaAhora").css("display", "block");

}



  adicionalesIncluidos=tarifas[0]["adicionalesIncluidos"];







for (var j = 0; j < adicionalesIncluidos.length; j++) {



  



  $('#ulIncluidos').append('<li>'+tarifas[0]["adicionalesIncluidos"][j]["nombre"]+'</li>');



   $('#ulIncluidosMovil').append('<li>'+tarifas[0]["adicionalesIncluidos"][j]["nombre"]+'</li>');



}



    adicionalesNoIncluidos=tarifas[0]["adicionalesNoIncluidos"];



                          



  if (adicionalesNoIncluidos.length<1) { //esto es para ocultar el acordeon de adicionales si no tenemos ningun servicio adicional



    $('#Seleccionar_adicionales_b').hide();    

     $('#divNoIncluidosCuerpo').hide(); 

     $('#divNoIncluidosCuerpoCelular').hide(); 



    $('#Seleccionar_adicionales_b_celular').hide();

    



}



else{



  $('#Seleccionar_adicionales_b').show(); 

  $('#divNoIncluidosCuerpo').show(); 

 $('#divNoIncluidosCuerpoCelular').show(); 



    $('#Seleccionar_adicionales_b_celular').show();



}          



//console.log(adicionalesNoIncluidos)

for (var k = 0; k < adicionalesNoIncluidos.length; k++) { //llenamos el acordeon de adicionales con los adicionales







  var lineaAdicionalesNoIncluidos='             <div class="row">'+



                                  '<div class="col-md-12">'+



                                      '<p class="counter-label mb-2 text-left">'+adicionalesNoIncluidos[k]['nombre']+'</p>'+



                                  '</div>'+



                                  '<div class="col-md-3">'+



  '<span class="counter-label_span"><label id="txtPrecio">'+formatPrice(getPrecioValor(adicionalesNoIncluidos[k]))+'</label></span>'+



                                  '</div>'+



                                  '<div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >'+



  '<a onclick="CalculaAdicionales('+adicionalesNoIncluidos[k]['idServicioSalidasAdicionales']+',0,0)" ><i class="fa fa-minus-circle fa-2x"></i></a>'+



                                   '</div>'+



                                   '<div class="col-md-4">'+



'<input type="number" class="form-control" id="cantAdicionales'+adicionalesNoIncluidos[k]['idServicioSalidasAdicionales']+'" value="0" min="0" disabled>'+



                                   '</div>'+



                                   '<div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >'+



' <a onclick="CalculaAdicionales('+adicionalesNoIncluidos[k]['idServicioSalidasAdicionales']+',1,0)"><i class="fa fa-plus-circle fa-2x"></i></a>'+



                                   '</div>'+



                            '<div class="col-md-3">'+



      ' <small class="counter-label_span_precio"><label id="lblTotalAdicionales'+adicionalesNoIncluidos[k]['idServicioSalidasAdicionales']+'"></label></small>'+



                                 '  </div>'+



                              '</div>';



   $('#divAdicionalesNoIncluidos').append(lineaAdicionalesNoIncluidos);
   
   $('#lblTotalAdicionales'+adicionalesNoIncluidos[k]['idServicioSalidasAdicionales']).text(formatPrice(0));







     var lineaAdicionalesNoIncluidos='              <div class="container py-3">  <div class="row">'+



                                  '<div class="col-md-12">'+



                                      '<p class="counter-label mb-2 text-left">'+adicionalesNoIncluidos[k]['nombre']+'</p>'+



                                  '</div>'+



                                  '<div class="col-md-3 col-3">'+



  '<span class="counter-label_span"><label id="txtPrecioCelular">'+formatPrice(getPrecioValor(adicionalesNoIncluidos[k]))+'</label></span>'+



                                  '</div>'+



                                  '<div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >'+



  '<a onclick="CalculaAdicionales('+adicionalesNoIncluidos[k]['idServicioSalidasAdicionales']+',0,0)" ><i class="fa fa-minus-circle fa-2x"></i></a>'+



                                   '</div>'+



                                   '<div class="col-md-4 col-3">'+



'<input type="number" class="form-control" id="cantAdicionalesCelular'+adicionalesNoIncluidos[k]['idServicioSalidasAdicionales']+'" value="0" min="0" disabled>'+



                                   '</div>'+



                                   '<div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >'+



' <a onclick="CalculaAdicionales('+adicionalesNoIncluidos[k]['idServicioSalidasAdicionales']+',1,0)"><i class="fa fa-plus-circle fa-2x"></i></a>'+



                                   '</div>'+



                            '<div class="col-md-3 col-3">'+



      ' <small class="counter-label_span_precio"><label id="lblTotalAdicionalesCelular'+adicionalesNoIncluidos[k]['idServicioSalidasAdicionales']+'"></label></small>'+



                                 '  </div>'+



                              '</div></div>';



   $('#divAdicionalesNoIncluidosCelular').append(lineaAdicionalesNoIncluidos);
   
   $('#lblTotalAdicionalesCelular'+adicionalesNoIncluidos[k]['idServicioSalidasAdicionales']).text(formatPrice(0));











var descripcion="";





//console.log(tarifas[0]["adicionalesNoIncluidos"][k]);



  $('#ulNoIncluidos').append('<li>'+adicionalesNoIncluidos[k]['nombre']+" "+adicionalesNoIncluidos[k]['descripcion']+' '+adicionalesNoIncluidos[k]['valor']+'</li>');



   $('#ulNoIncluidosMovil').append('<li>'+adicionalesNoIncluidos[k]['nombre']+" "+adicionalesNoIncluidos[k]['descripcion']+' '+adicionalesNoIncluidos[k]['valor']+'</li>');











 $('#seleccionar_adicionales_a').show();

}

    // Mejoras de UX: Resaltar bot�n seleccionado y manejar disponibilidad
    setTimeout(function() {
      if (typeof resaltarSalidaSeleccionada === 'function') {
        resaltarSalidaSeleccionada($idSalida);
      }
      if (typeof manejarDisponibilidad === 'function') {
        manejarDisponibilidad();
      }
      
      // Actualizar precios totales (mostrar $0 al cambiar de salida)
      actualizaPrecios();
      
      // Si no hay disponibilidad, buscar otra salida autom�ticamente
      if (typeof buscarSalidaConDisponibilidad === 'function' && disponibilidad <= 0) {
        setTimeout(function() {
          buscarSalidaConDisponibilidad();
        }, 500);
      }
    }, 300);

  });
}

/*FNC CALCULA PERSONAS**************************************************************************************/







function CalculaPersonas(idServicioSalidasTarifas, operacion){
  console.log('CalculaPersonas llamada:', idServicioSalidasTarifas, operacion);
  
  // Obtener disponibilidad específica de esta tarifa
  var tarifaDisponibilidad = disponibilidadPorTarifa[idServicioSalidasTarifas] || 0;
  
  // No permitir superar disponibilidad. Si ya está al máximo, sólo permitir restar.
  if (cantidadPersonas >= tarifaDisponibilidad && operacion == 1) {
    Swal.fire({
      title: 'Sin disponibilidad',
      text: 'No hay más cupos disponibles para esta tarifa',
      icon: 'warning',
      confirmButtonText: 'Entendido'
    });
    return;
  }
  
  // Prevenir agregar si la tarifa está agotada
  if (tarifaDisponibilidad <= 0) {
    Swal.fire({
      title: 'Tarifa agotada',
      text: 'Esta tarifa no tiene disponibilidad',
      icon: 'warning',
      confirmButtonText: 'Entendido'
    });
    return;
  }

  // Usar clases en lugar de IDs para evitar conflicto PC/móvil
  cantidad = parseInt($('.cantPers.tarifa-'+idServicioSalidasTarifas).val()) || 0;

console.log('Cantidad inicial:', cantidad, 'CantidadPersonas:', cantidadPersonas);

  cantidadPersonas=cantidadPersonas-cantidad;

  $.post("admin/ctrl/ctrlHorarios", {idServicioSalidasTarifas: idServicioSalidasTarifas, cantidad: cantidad}, function(data, status){
console.log('Respuesta del servidor:', data);
tarifas=JSON.parse(data);
var valorAdicional = getPrecioValor(tarifas[0]);
var valorTarifa = getPrecioValor(tarifas[0]);
console.log('Valor tarifa:', valorTarifa, 'Tarifa completa:', tarifas[0]);







if (cantidad>=0) { 





  if (operacion==0 && cantidad>0 ) {

console.log('Operación DECREMENTAR - cantidad antes:', cantidad);

  precioTotal-=valorTarifa;



  cantidad-=1;

console.log('Operación DECREMENTAR - cantidad después:', cantidad);





}







 if  (operacion==1 && cantidad>=0){

console.log('Operación INCREMENTAR - cantidad antes:', cantidad);

    precioTotal-=valorTarifa;



   cantidad+=1;

console.log('Operación INCREMENTAR - cantidad después:', cantidad);







}



   cantidadPersonas=cantidadPersonas+cantidad;

console.log('Actualizando input con cantidad:', cantidad);

$('.cantPers.tarifa-'+idServicioSalidasTarifas).val(cantidad);

console.log('Input actualizado:', $('.cantPers.tarifa-'+idServicioSalidasTarifas).val());

  $.post("admin/ctrl/ctrlHorarios", {idServicioSalidasTarifas: idServicioSalidasTarifas, cantidad: cantidad}, function(data, status){
console.log('Segunda llamada AJAX - Cantidad enviada:', cantidad);
tarifas=JSON.parse(data);
var valorTarifaActual = getPrecioValor(tarifas[0]);

console.log('Valor tarifa actual calculado:', valorTarifaActual);

$('.lblTotal.tarifa-'+idServicioSalidasTarifas).text(formatPrice(valorTarifaActual));

console.log('Label actualizado:', $('.lblTotal.tarifa-'+idServicioSalidasTarifas).text());

precioTotal+=valorTarifaActual;







 var exito=0;



 if(reserva.length>0){



  for (var i = 0; i < reserva.length; i++) {



   



  if (reserva[i]["idServicioSalidasTarifas"]==idServicioSalidasTarifas) {



    reserva[i]["cantidad"]=cantidad;



    if (cantidad<1) {



      reserva.splice(i, 1);



    }



    exito++;



  



  }



  



}







/* Esto hace que si quitan personas, quita los adicionales de la persona que quitaron*/



for (var i = 0; i < reservaAdicionales.length; i++) {



  if (reservaAdicionales[i]["cantidad"]=cantidadPersonas) {



reservaAdicionales[i]["cantidad"]=cantidadPersonas;

    CalculaAdicionales(reservaAdicionales[i]["idServicioSalidasAdicionales"], 0 ,cantidadPersonas);



  }



}



/* FIN Esto hace que si quitan personas, quita los adicionales de la persona que quitaron*/



if (exito<1) {



reserva.push({idServicioSalidasTarifas: idServicioSalidasTarifas, cantidad: cantidad, idServicioSeleccionado: idServicioSeleccionado});



}



 }



 else{



    reserva.push({idServicioSalidasTarifas: idServicioSalidasTarifas, cantidad: cantidad, idServicioSeleccionado: idServicioSeleccionado});







 }















actualizaPrecios();



  });











}







  });















}







/*FIN    FNC CALCULA PERSONAS**************************************************************************************/



/********FNC CALCULA ADICIONALES*******************************************************************************************************************************************/



function CalculaAdicionales(idServicioSalidasAdicionales, operacion, cantPers){ // si la operacion es 2 aplica la cantidad del ultimo parametro







 cantidadPc=($('#cantAdicionales'+idServicioSalidasAdicionales).val());



cantidadCelular=($('#cantAdicionalesCelular'+idServicioSalidasAdicionales).val());



if (cantidadPc>0) {



  cantidad= parseInt(cantidadPc);



}



else{



   cantidad= parseInt(cantidadCelular);



}



    $.post("admin/ctrl/ctrlHorarios", {idServicioSalidasAdicionales: idServicioSalidasAdicionales, cantidad: cantidad}, function(data, status){
tarifas=JSON.parse(data);
var valorAdicional = getPrecioValor(tarifas[0]);

if (operacion==0) {







  cantidad=cantidad-1;



}



else if (operacion==2){

 cantidad=cantPers;

}

else{

   cantidad=cantidad+1;

}

if (cantidad>=0 && cantidad<=cantidadPersonas) { 

    precioTotal-=valorAdicional;

$('#cantAdicionales'+idServicioSalidasAdicionales).val(cantidad);



$('#cantAdicionalesCelular'+idServicioSalidasAdicionales).val(cantidad);



  $.post("admin/ctrl/ctrlHorarios", {idServicioSalidasAdicionales: idServicioSalidasAdicionales, cantidad: cantidad}, function(data, status){






tarifas=JSON.parse(data);

var valorAdicionalActual = getPrecioValor(tarifas[0]);

$('#lblTotalAdicionales'+idServicioSalidasAdicionales).text(formatPrice(valorAdicionalActual));



$('#lblTotalAdicionalesCelular'+idServicioSalidasAdicionales).text(formatPrice(valorAdicionalActual));



precioTotal+=valorAdicionalActual; 







 var exito=0;



 if(reservaAdicionales.length>0){



  for (var i = 0; i < reservaAdicionales.length; i++) {



   



  if (reservaAdicionales[i]["idServicioSalidasAdicionales"]==idServicioSalidasAdicionales) {



    reservaAdicionales[i]["cantidad"]=cantidad;



    if (cantidad<1) {



      reservaAdicionales.splice(i, 1);



    }



    exito++;



  



  }



  



}



if (exito<1) {



reservaAdicionales.push({idServicioSalidasAdicionales: idServicioSalidasAdicionales, cantidad: cantidad, idServicioSalidasAdicionales, idServicioSalidasAdicionales});



}



 }



 else{



    reservaAdicionales.push({idServicioSalidasAdicionales: idServicioSalidasAdicionales, cantidad: cantidad, idServicioSalidasAdicionales, idServicioSalidasAdicionales});







 }











actualizaPrecios();



  });



    



}



else{ //if (cantidad>=0 && cantidad<=cantidadPersonas) {



Swal.fire('Reservate',"Debe agregar personas para poder contratar mas servicios adicionales",'success');







}







  });



















}




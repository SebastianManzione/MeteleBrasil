var reserva = new Array();
var reservaAdicionales = new Array();
var idServicioSeleccionado=0;
var cantidadPersonas=0;
var precioTotal=0;
var disponibilidad=0;

function actualizaPrecios(){

if (precioTotal>=0) {

$('#precioTotal0').text(symMoneda+precioTotal.toFixed(2));
$('#precio-nav').text(symMoneda+precioTotal.toFixed(2));
$('#precioTotalFooterNavCelular').text(symMoneda+precioTotal);

var precioTotalSinDescuento=parseFloat(precioTotal*1.1356987).toFixed(2);

$('#precioTotalSinDescuento').text(symMoneda+precioTotalSinDescuento);

                  }
}

function enviar(){
  if (cantidadPersonas>0) {

  $.post("admin/ctrl/ctrlHorarios", {reserva : reserva ,reservaAdicionales: reservaAdicionales}, function(data, status){
window.location="carrito";
//console.log(data);
  });
}
else{
  Swal.fire('Reservate',"No se puede reservar sin personas",'success');

}
  
}
function limpiarTarifaYAdicionales(){

 $('#divAdicionalesNoIncluidos').empty();
  $('#divAdicionalesNoIncluidosMovil').empty();
  $('#seleccionar_adicionales_a').hide();
  $('#divhora').empty();
 $('#divhora-movil').empty();
  $('#seleccionar_personas').empty();
 $('#seleccionar_personasMovil').empty();
   $('#ulIncluidos').empty();
      $('#ulNoIncluidos').empty();
  reserva=[];
  reservaAdicionales=[];
   cantidadPersonas=0;
 precioTotal=0;
}

var salidas;
function traeHorarios($fecha, $idServicio){
   precioTotal=0;cantidadPersonas=0;
  idServicioSeleccionado=$idServicio;

 
  $.post("admin/ctrl/ctrlHorarios", {fecha: $fecha,idServicio: $idServicio}, function(data, status){

   salidas = JSON.parse(data); 

limpiarTarifaYAdicionales();
for (var i = 0; i < salidas.length; i++) {   
$('#divhora').append(
        '<button type="button" class="btn btn-light btn-block mt-2" onClick="traeTarifas('+salidas[i]["idServicioSalidas"]+')">'+salidas[i]["horaSalida"]+'</button>');
$('#divhora-movil').append(
        '<button type="button" class="btn btn-light btn-block mt-2" onClick="traeTarifas('+salidas[i]["idServicioSalidas"]+')">'+salidas[i]["horaSalida"]+'</button>');
  if(idVendedor>0){
    var lineaVendedor=  '<div class="col-md-12">'+
                                     ' <p class="counter-label mb-2 text-left">Disponibilidad: '+salidas[i]["disponibilidad"]+'</p>'+
                                  '</div>';
$('#divhora').append(lineaVendedor);
$('#divhora-movil').append(lineaVendedor);
  }




}

traeTarifas(salidas[0]["idServicioSalidas"]);
if (salidas[0]["duracionMaxima"]>24) {
 salidas[0]["duracionMaxima"]= (salidas[0]["duracionMaxima"]/24)+" dias";
}
else{
   salidas[0]["duracionMaxima"]= (salidas[0]["duracionMaxima"])+" horas";
}
if (salidas[0]["duracionMinima"]>24) {
 salidas[0]["duracionMinima"]= (salidas[0]["duracionMinima"]/24)+" dias";
}else{
   salidas[0]["duracionMinima"]= (salidas[0]["duracionMinima"])+" horas";
}
$('#txtDuracion').text(salidas[0]["duracionMinima"]+" - "+salidas[0]["duracionMaxima"]);
$('#txtDuracionMovil').text(salidas[0]["duracionMinima"]+" - "+salidas[0]["duracionMaxima"]);

  });


}

function traeTarifas($idSalida){

   precioTotal=0;  cantidadPersonas=0
	$('#seleccionar_personas').empty();
 $('#seleccionar_personasMovil').empty();
  

  $.post("admin/ctrl/ctrlHorarios", {idSalida: $idSalida}, function(data, status){
     $('#divAdicionalesNoIncluidos').empty();

  $('#seleccionar_adicionales_a').hide();

reserva=[];
reservaAdicionales=[];
console.log(data);
   var tarifas = JSON.parse(data);
   
disponibilidad=tarifas[0]["disponibilidad"];
  $('#ulIncluidos').html(''); 
   $('#ulNoIncluidos').html('');
     $('#ulIncluidosMovil').html(''); 
   $('#ulNoIncluidosMovil').html('');
               $('#rowCirculosPrecios').html('');
                 $('#rowCirculosPreciosMovil').html('');

   for (var i = 0; i < tarifas.length; i++) {
tipoTarifa=tarifas[i]['tipoTarifaNombre'];
var lineaPrecios='   <div class="col-lg-2 col-5" style="margin-left: 20px">'+
  '<p class="text-center ">'+tarifas[i]['nombre']+' ('+tarifas[i]['edadFrom']+' a '+tarifas[i]['edadTo']+' Años) '+tipoTarifa+'</p>'+
               '<div class="circulo-b">'+
                 '<p class="text-center text-primary">'+tarifas[i]['valor']+'</p>'+
               '</div>'+
             '</div>';


var linea='	<div class="container py-3">'+
                            '  <div class="row">'+
                                  '<div class="col-md-12">'+
                                     ' <p class="counter-label mb-2 text-left">'+tarifas[i]['nombre']+' ('+tarifas[i]['edadFrom']+' a '+tarifas[i]['edadTo']+' Años) '+tipoTarifa+'</p>'+
                                  '</div>'+
                                  '<div class="col-md-3">'+
   '<span class="counter-label_span"><label id="txtPrecioMovil['+tarifas[i]['idServicioSalidasTarifas']+']">'+tarifas[i]['valor']+'</label></span>'+
                                  '</div>'+
                                  '<div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;">'+
                                 '<a onclick="CalculaPersonas('+tarifas[i]['idServicioSalidasTarifas']+',0)"> '+
                                '  <i class="fa fa-minus-circle fa-2x"></i>'+ 
                                  ' </a></div>'+
                                   '<div class="col-md-4">'+
'<input type="number" class="form-control" value="0" id="cantPersAdc'+tarifas[i]['idServicioSalidasTarifas']+'">'+
                                   '</div>'+

   '<div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;">'+
'  <a onclick="CalculaPersonas('+tarifas[i]['idServicioSalidasTarifas']+',1)">'+
' <i class="fa fa-plus-circle fa-2x"></i>'+
'</a>'+
                                 '  </div>'+
     '  <div class="col-md-3">'+
       '<small class="counter-label_span_precio"><label id="lblTotal'+tarifas[i]['idServicioSalidasTarifas']+'"></label></small>'+
                                   '</div>'+
                              '</div>'+
                          '</div>';


var lineaCelular=' <div class="container py-3">'+
                            '  <div class="row">'+
                                  '<div class="col-md-12">'+
                                     ' <p class="counter-label mb-2 text-left">'+tarifas[i]['nombre']+' ('+tarifas[i]['edadFrom']+' a '+tarifas[i]['edadTo']+' Años) '+tipoTarifa+'</p>'+
                                  '</div>'+
                                  '<div class="col-md-3 col-3">'+
   '<span class="counter-label_span"><label id="txtPrecioMovil['+tarifas[i]['idServicioSalidasTarifas']+']">'+tarifas[i]['valor']+'</label></span>'+
                                  '</div>'+
                                 '<a onclick="CalculaPersonas('+tarifas[i]['idServicioSalidasTarifas']+',0)"> '+
                                 '<div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;">'+

'  <i class="fa fa-minus-circle fa-2x"></i>'+ 
                                  ' </div></a>'+
                                   '<div class="col-md-4 col-3">'+
'<input type="number" class="form-control" value="0" id="cantPersAdcMovil'+tarifas[i]['idServicioSalidasTarifas']+'">'+
                                   '</div>'+

   '<div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;">'+
'  <a onclick="CalculaPersonas('+tarifas[i]['idServicioSalidasTarifas']+',1)">'+

' <i class="fa fa-plus-circle fa-2x"></i>'+
'</a>'+
                                 '  </div>'+
     '  <div class="col-md-3 col-3">'+
       '<small class="counter-label_span_precio"><label id="lblTotalMovil'+tarifas[i]['idServicioSalidasTarifas']+'"></label></small>'+
                                   '</div>'+
                              '</div>'+
                          '</div>';


 $('#divCancelaciones').html("<p></p>");
$('#pIdiomas').text(tarifas[0]["idiomas"]);
$('#pIdiomasMovil').text(tarifas[0]["idiomas"]);
$('#pIdiomasNav').text(tarifas[0]["idiomas"]);
$('#seleccionar_personas').append(linea);
$('#seleccionar_personasMovil').append(lineaCelular);


 $('#rowCirculosPrecios').append(lineaPrecios);
 $('#rowCirculosPreciosMovil').append(lineaPrecios);
 $('#seleccionar_personasMovil').show();
 $('#seleccionar_personas').show();

}  
CalculaPersonas(tarifas[0]['idServicioSalidasTarifas'], 1);



  adicionalesIncluidos=tarifas[0]["adicionalesIncluidos"];

for (var j = 0; j < adicionalesIncluidos.length; j++) {
  
  $('#ulIncluidos').append('<li>'+tarifas[0]["adicionalesIncluidos"][j]["nombre"]+'</li>');
   $('#ulIncluidosMovil').append('<li>'+tarifas[0]["adicionalesIncluidos"][j]["nombre"]+'</li>');
}
    adicionalesNoIncluidos=tarifas[0]["adicionalesNoIncluidos"];
                          
  if (adicionalesNoIncluidos.length<1) { //esto es para ocultar el acordeon de adicionales si no tenemos ningun servicio adicional
    $('#Seleccionar_adicionales_b').hide(); 
    $('#Seleccionar_adicionales_b_celular').hide();
}
else{
  $('#Seleccionar_adicionales_b').show(); 
    $('#Seleccionar_adicionales_b_celular').show();
}          
for (var k = 0; k < adicionalesNoIncluidos.length; k++) { //llenamos el acordeon de adicionales con los adicionales

  var lineaAdicionalesNoIncluidos='             <div class="row">'+
                                  '<div class="col-md-12">'+
                                      '<p class="counter-label mb-2 text-left">'+tarifas[0]["adicionalesNoIncluidos"][k]['nombre']+'</p>'+
                                  '</div>'+
                                  '<div class="col-md-3">'+
   '<span class="counter-label_span"><label id="txtPrecio">'+tarifas[0]["adicionalesNoIncluidos"][k]['valor']+'</label></span>'+
                                  '</div>'+
                                  '<div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >'+
  '<a onclick="CalculaAdicionales('+tarifas[0]['adicionalesNoIncluidos'][k]['idServicioSalidasAdicionales']+',0,0)" ><i class="fa fa-minus-circle fa-2x"></i></a>'+
                                   '</div>'+
                                   '<div class="col-md-4">'+
'<input type="number" class="form-control" id="cantAdicionales'+tarifas[0]['adicionalesNoIncluidos'][k]['idServicioSalidasAdicionales']+'" value="0" min="0">'+
                                   '</div>'+
                                   '<div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >'+
' <a onclick="CalculaAdicionales('+tarifas[0]['adicionalesNoIncluidos'][k]['idServicioSalidasAdicionales']+',1,0)"><i class="fa fa-plus-circle fa-2x"></i></a>'+
                                   '</div>'+
                            '<div class="col-md-3">'+
      ' <small class="counter-label_span_precio"><label id="lblTotalAdicionales'+tarifas[0]['adicionalesNoIncluidos'][k]['idServicioSalidasAdicionales']+'"></label></small>'+
                                 '  </div>'+
                              '</div>';
   $('#divAdicionalesNoIncluidos').append(lineaAdicionalesNoIncluidos);

     var lineaAdicionalesNoIncluidos='              <div class="container py-3">  <div class="row">'+
                                  '<div class="col-md-12">'+
                                      '<p class="counter-label mb-2 text-left">'+tarifas[0]["adicionalesNoIncluidos"][k]['nombre']+'</p>'+
                                  '</div>'+
                                  '<div class="col-md-3 col-3">'+
   '<span class="counter-label_span"><label id="txtPrecioCelular">'+tarifas[0]["adicionalesNoIncluidos"][k]['valor']+'</label></span>'+
                                  '</div>'+
                                  '<div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >'+
  '<a onclick="CalculaAdicionales('+tarifas[0]['adicionalesNoIncluidos'][k]['idServicioSalidasAdicionales']+',0,0)" ><i class="fa fa-minus-circle fa-2x"></i></a>'+
                                   '</div>'+
                                   '<div class="col-md-4 col-3">'+
'<input type="number" class="form-control" id="cantAdicionalesCelular'+tarifas[0]['adicionalesNoIncluidos'][k]['idServicioSalidasAdicionales']+'" value="0" min="0">'+
                                   '</div>'+
                                   '<div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >'+
' <a onclick="CalculaAdicionales('+tarifas[0]['adicionalesNoIncluidos'][k]['idServicioSalidasAdicionales']+',1,0)"><i class="fa fa-plus-circle fa-2x"></i></a>'+
                                   '</div>'+
                            '<div class="col-md-3 col-3">'+
      ' <small class="counter-label_span_precio"><label id="lblTotalAdicionalesCelular'+tarifas[0]['adicionalesNoIncluidos'][k]['idServicioSalidasAdicionales']+'"></label></small>'+
                                 '  </div>'+
                              '</div></div>';
   $('#divAdicionalesNoIncluidosCelular').append(lineaAdicionalesNoIncluidos);


var descripcion="";
if (tarifas[0]["adicionalesNoIncluidos"][k]['descripcion'].length>0) {
  descripcion="("+tarifas[0]["adicionalesNoIncluidos"][k]['descripcion']+")";
}
//console.log(tarifas[0]["adicionalesNoIncluidos"][k]);
  $('#ulNoIncluidos').append('<li>'+tarifas[0]["adicionalesIncluidos"][k]["nombre"]+' '+tarifas[0]['adicionalesNoIncluidos'][k]['valor']+'</li>');
   $('#ulNoIncluidosMovil').append('<li>'+tarifas[0]["adicionalesIncluidos"][k]["nombre"]+' '+tarifas[0]['adicionalesNoIncluidos'][k]['valor']+'</li>');


 $('#seleccionar_adicionales_a').show();
}

  });
}
/*FNC CALCULA PERSONAS**************************************************************************************/

function CalculaPersonas(idServicioSalidasTarifas, operacion){

if (cantidadPersonas>= disponibilidad) {
operacion=0;
}

cantidadPc=($('#cantPersAdc'+idServicioSalidasTarifas).val());
cantidadCelular=($('#cantPersAdcMovil'+idServicioSalidasTarifas).val());
if (cantidadPc>0) {
  cantidad= parseInt(cantidadPc);
}
else{
   cantidad= parseInt(cantidadCelular);
}
 
 cantidadPersonas=cantidadPersonas-cantidad;
  $.post("admin/ctrl/ctrlHorarios", {idServicioSalidasTarifas: idServicioSalidasTarifas, cantidad: cantidad}, function(data, status){

tarifas=JSON.parse(data);

if (cantidad>=0) { 
 


  if (operacion==0 && cantidad>0) {
  precioTotal-=tarifas[0]['valor'];
  cantidad-=1;

}

 if  (operacion==1 && cantidad>=0){
    precioTotal-=tarifas[0]['valor'];
   cantidad+=1;

}
   cantidadPersonas=cantidadPersonas+cantidad;
$('#cantPersAdc'+idServicioSalidasTarifas).val(cantidad);
$('#cantPersAdcMovil'+idServicioSalidasTarifas).val(cantidad);
  $.post("admin/ctrl/ctrlHorarios", {idServicioSalidasTarifas: idServicioSalidasTarifas, cantidad: cantidad}, function(data, status){

tarifas=JSON.parse(data);

$('#lblTotal'+idServicioSalidasTarifas).text(tarifas[0]['valorSym']);
$('#lblTotalMovil'+idServicioSalidasTarifas).text(tarifas[0]['valorSym']);
precioTotal+=tarifas[0]['valor'];

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
  if (reservaAdicionales[i]["cantidad"]>cantidadPersonas) {
reservaAdicionales[i]["cantidad"]=cantidadPersonas;

    CalculaAdicionales(reservaAdicionales[i]["idServicioSalidasAdicionales"], 2,cantidadPersonas);
  }
}
/* FIN Esto hace que si quitan personas, quita los adicionales de la persona que quitaron*/
if (exito<1) {
reserva.push({idServicioSalidasTarifas: idServicioSalidasTarifas, cantidad: cantidad, idServicioSeleccionado, idServicioSeleccionado});
}
 }
 else{
    reserva.push({idServicioSalidasTarifas: idServicioSalidasTarifas, cantidad: cantidad, idServicioSeleccionado, idServicioSeleccionado});

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


if (operacion==0) {

  cantidad=cantidad-1;
}
else if (operacion==2){
 cantidad=cantPers;
}
else{
   cantidad=cantidad+1;
}
if (cantidad>=0 && cantidad<=cantidadPersonas) { // 
    precioTotal-=tarifas[0]['valor'];
$('#cantAdicionales'+idServicioSalidasAdicionales).val(cantidad);
$('#cantAdicionalesCelular'+idServicioSalidasAdicionales).val(cantidad);
  $.post("admin/ctrl/ctrlHorarios", {idServicioSalidasAdicionales: idServicioSalidasAdicionales, cantidad: cantidad}, function(data, status){

tarifas=JSON.parse(data);

$('#lblTotalAdicionales'+idServicioSalidasAdicionales).text(tarifas[0]['valorSym']);
$('#lblTotalAdicionalesCelular'+idServicioSalidasAdicionales).text(tarifas[0]['valorSym']);
precioTotal+=tarifas[0]['valor']; 

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
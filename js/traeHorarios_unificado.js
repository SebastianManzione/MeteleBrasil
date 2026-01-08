// -*- coding: utf-8 -*-
// Nota: Las siguientes variables se definen en servicio_unificado.php ANTES de cargar este archivo:
// - idServicioSeleccionado
// - symMoneda
// - eventArray
// - reserva
// - reservaAdicionales

// Asegurar que existan si no están definidas (fallback)
if (typeof idServicioSeleccionado === 'undefined') {
  var idServicioSeleccionado = 0;
}
if (typeof symMoneda === 'undefined') {
  var symMoneda = '';
}
if (typeof reserva === 'undefined') {
  var reserva = [];
}
if (typeof reservaAdicionales === 'undefined') {
  var reservaAdicionales = [];
}
if (typeof eventArray === 'undefined') {
  var eventArray = [];
}

var cantidadPersonas = 0;
var precioTotal = 0;
var disponibilidad = 0;
var salidas = [];
var cancelaciones = [];

// Localización de calendario y meses
var idiomaSistema = (typeof idiomaSistema !== 'undefined' && idiomaSistema) ? idiomaSistema : 'ES';
var localeMap = { ES: 'es', EN: 'en', PT: 'pt-br', IT: 'it' };
try { if (typeof moment !== 'undefined' && moment && moment.locale) { moment.locale(localeMap[idiomaSistema] || 'es'); } } catch(e) { /* fallback más abajo */ }
var weekOffsetMap = { ES: 1, PT: 1, IT: 1, EN: 0 };
var weekOffset = (typeof weekOffsetMap[idiomaSistema] !== 'undefined') ? weekOffsetMap[idiomaSistema] : 0;
// Iniciales de días (más compactas para el header)
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
var diasSemanaHeader = diasSemanaCortosMap[idiomaSistema] || diasSemanaCortosMap['ES'];

// Formatea precios con separador de miles y dos decimales
function toNumeric(amount) {
  if (typeof amount === 'number') return amount;
  if (amount === null || typeof amount === 'undefined') return 0;
  var s = String(amount).trim();
  s = s.replace(/\./g, '').replace(/,/g, '.');
  var n = parseFloat(s);
  return isNaN(n) ? 0 : n;
}

// Obtiene valor numérico robusto de tarifas/adicionales
function getPrecioValor(item) {
  if (!item) return 0;
  
  var v = null;
  if (item.hasOwnProperty('valor')) {
    if (typeof item['valor'] === 'string' && item['valor'].match(/[^0-9,.-]/)) {
      var stripped = item['valor'].replace(/[^0-9,.-]/g, '');
      v = toNumeric(stripped);
    } else {
      v = toNumeric(item['valor']);
    }
  }
  
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
  var decimals = (symbol.toUpperCase().indexOf('AR') !== -1) ? 0 : 2;
  // Redondeo controlado para evitar deriva (ej: 90.009999 -> 90.00)
  if (decimals === 2) {
    numeric = Math.round(numeric * 100) / 100;
  }
  
  return symbol + ' ' + numeric.toLocaleString('es-ES', {
    minimumFractionDigits: decimals,
    maximumFractionDigits: decimals
  });
}

function actualizaPrecios(){
  // Recalcular total desde los arrays de reserva
  var totalTarifas = 0;
  var totalAdicionales = 0;
  
  console.log('=== ACTUALIZANDO PRECIOS ===');
  console.log('reserva:', reserva);
  console.log('reservaAdicionales:', reservaAdicionales);
  
  // Sumar tarifas - leer desde los labels actualizados
  for (var i = 0; i < reserva.length; i++) {
    var idTarifa = reserva[i]["idServicioSalidasTarifas"];
    var cantidad = reserva[i]["cantidad"] || 0;
    if (cantidad > 0) {
      var textoLabel = $('.lblTotal.tarifa-' + idTarifa).first().text();
      console.log('Tarifa ' + idTarifa + ' texto:', textoLabel);
      var valor = parseFloat(textoLabel.replace(/[^\d.,]/g, '').replace(/\./g, '').replace(',', '.')) || 0;
      // Redondear inmediatamente después del parseo
      valor = Math.round(valor * 100) / 100;
      console.log('Tarifa ' + idTarifa + ' valor parseado y redondeado:', valor);
      totalTarifas += valor;
    }
  }
  
  // Sumar adicionales - usar valores guardados directamente
  for (var j = 0; j < reservaAdicionales.length; j++) {
    var valorTotal = reservaAdicionales[j]["valorTotal"] || 0;
    console.log('Adicional ' + j + ' valorTotal:', valorTotal);
    totalAdicionales += valorTotal;
  }
  
  console.log('Total tarifas:', totalTarifas);
  console.log('Total adicionales:', totalAdicionales);
  var total = totalTarifas + totalAdicionales;
  // Evitar desbordes por flotantes (ej: 90.009999 -> 90.01)
  var totalRounded = Math.round(total * 100) / 100;
  console.log('Total final (raw):', total, 'rounded:', totalRounded);
  precioTotal = totalRounded;
  
  $('#precioTotal0').text(formatPrice(totalRounded));
  $('#precio-nav').text(formatPrice(totalRounded));
  $('#precioTotalMovil').text(formatPrice(totalRounded)); // Barra fija móvil
  if ($('#precioTotalFooterNavCelular').length) {
    $('#precioTotalFooterNavCelular').text(formatPrice(totalRounded));
  }
  
  // Actualizar precio en barra flotante móvil
  if (typeof window.actualizarPrecioMovil === 'function') {
    window.actualizarPrecioMovil(formatPrice(totalRounded));
  }
  
  var precioTotalSinDescuento = totalRounded * 1.1356987;
  var precioTotalSinDescuentoRounded = Math.round(precioTotalSinDescuento * 100) / 100;
  $('#precioTotalSinDescuento').text(formatPrice(precioTotalSinDescuentoRounded));
}

function limpiarTarifaYAdicionales(){
  $('#divAdicionalesNoIncluidos').empty();
  $('#Seleccionar_adicionales_b').hide();
  $('#divhoraBody').empty();
  $('#seleccionar_personas').empty();
  $('#ulIncluidos').empty();
  $('#ulNoIncluidos').empty();
  reserva = [];
  reservaAdicionales = [];
  cantidadPersonas = 0;
  precioTotal = 0;
  cancelaciones = [];
}

function enviar(){
  if (cantidadPersonas > 0) {
    // Obtener el código del cupón si existe (definido en el HTML inline script)
    var cuponActual = (typeof codCupon !== 'undefined' && codCupon) ? codCupon : null;
    
    $.post("admin/ctrl/ctrlHorarios", {
      reserva: reserva,
      reservaAdicionales: reservaAdicionales,
      codCupon: cuponActual
    }, function(data, status){
      window.location="carrito";
    });
  } else {
    Swal.fire({
      title: 'Atención',
      text: 'Seleccione al menos una persona',
      icon: 'warning',
      confirmButtonText: 'Entendido'
    });
  }
}

// ==================== CALENDARIO ====================

function traeHorarios($fecha, $idServicio){
  limpiarTarifaYAdicionales();
  
  $.post("admin/ctrl/ctrlHorarios", {
    fecha: $fecha,
    idServicio: $idServicio
  }, function(data, status){
    try {
      salidas = JSON.parse(data);
    } catch (e) {
      salidas = [];
    }
    
    var salidasDisponibles = [];
    for (var i = 0; i < salidas.length; i++) {
      if (salidas[i]["disponibilidad"] && parseInt(salidas[i]["disponibilidad"]) > 0) {
        salidasDisponibles.push(salidas[i]);
      }
    }
    
    if (salidasDisponibles.length === 0) {
      $('#divhora').html('<div class="alert alert-warning m-3">No hay salidas disponibles para esta fecha</div>');
      return;
    }
    
    var htmlHorarios = '';
    for (var i = 0; i < salidasDisponibles.length; i++) {
      var s = salidasDisponibles[i];
      var nombre = s["nombre"] || '';
      var horaSalida = s["horaSalida"] || '';
      var sinHorario = parseInt(s["sinHorario"] || 0);
      var sinHorarioTexto = s["sinHorarioTexto"] || '';
      var idSalida = s["idServicioSalidas"];
      
      var textoMostrar = '';
      if (sinHorario === 1) {
        // Sin horario fijo: mostrar solo sinHorarioTexto
        textoMostrar = sinHorarioTexto || nombre;
      } else {
        // Con horario fijo: mostrar horaSalida + sinHorarioTexto (ej: "10:00 Barra Da Lagoa")
        if (horaSalida && horaSalida !== '00:00:00') {
          textoMostrar = horaSalida.substring(0,5);
        }
        if (sinHorarioTexto) {
          textoMostrar += (textoMostrar ? ' ' : '') + sinHorarioTexto;
        }
        // Si no hay ninguno, usar el nombre como fallback
        if (!textoMostrar) {
          textoMostrar = nombre;
        }
      }
      
      // IMPORTANTE: El PRIMER botón debe estar seleccionado con btn-primary
      var claseBoton = (i === 0) ? 'btn-primary' : 'btn-outline-primary';
      
      htmlHorarios += '<button id="btnSalida' + idSalida + '" class="btn ' + claseBoton + ' btn-hora btn-block m-2" data-idsalida="' + idSalida + '" onclick="traeTarifas(' + idSalida + ')" style="font-weight: 600;">' + textoMostrar;
      
      // Mostrar disponibilidad en horarios si es admin
      if (isAdmin && salidas[i]["disponibilidad"]) {
        var availabilityText = parseInt(salidas[i]["disponibilidad"]) > 0 ? 'Disp: ' + salidas[i]["disponibilidad"] : 'Agotado';
        htmlHorarios += ' <small class="fw-bold text-dark" >(' + availabilityText + ')</small>';
      }
      
      htmlHorarios += '</button>';
      if (i < salidasDisponibles.length - 1) {
        htmlHorarios += '<hr class="my-1">';
      }
    }
    
    $('#divhoraBody').html(htmlHorarios);
    $('#divhora-movil').html(htmlHorarios); // Acordeón móvil horarios
    
    // Abrir acordeón de horarios (desktop)
    $('#divhora').addClass('show').css('display', 'block');
    $('a[data-target="#divhora"]').attr('aria-expanded', 'true').removeClass('collapsed');
    
    // Agregar event handlers para cambiar estilos cuando el usuario clickea
    $('.btn-hora').off('click').on('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      var idSalida = $(this).attr('id').replace('btnSalida', '');
      
      // Resetear todos los botones a estado no seleccionado
      $('.btn-hora').removeClass('btn-primary').addClass('btn-outline-primary');
      
      // Pintar el clickeado
      $(this).removeClass('btn-outline-primary').addClass('btn-primary');
      
      // Cargar tarifas
      traeTarifas(idSalida);
      return false;
    });
    
    // Auto-seleccionar y cargar tarifas del primer botón
    if (salidasDisponibles.length > 0) {
      setTimeout(function() {
        traeTarifas(salidasDisponibles[0]["idServicioSalidas"]);
      }, 100);
    }
  });
}

// ==================== TARIFAS ====================

function traeTarifas($idSalida){
    // La clase ya fue cambiada por el evento click, solo cargar tarifas
    $('#seleccionar_personas').empty();
  $('#divAdicionalesNoIncluidos').empty();
  $('#Seleccionar_adicionales_b').hide();
  
  reserva = [];
  reservaAdicionales = [];
  cantidadPersonas = 0;
  precioTotal = 0;
  
  $.post("admin/ctrl/ctrlHorarios", {
    idSalida: $idSalida
  }, function(data, status){
    var tarifas = [];
    try {
      tarifas = JSON.parse(data);
    } catch (e) {
    }
    
    if (!tarifas || tarifas.length === 0) {
      $('#seleccionar_personas').html('<div class="alert alert-warning">No hay tarifas disponibles</div>');
      return;
    }
    
    disponibilidad = parseInt(tarifas[0]["disponibilidad"]) || 0;
    
    var htmlTarifas = '';
    for (var i = 0; i < tarifas.length; i++) {
      var tarifa = tarifas[i];
      var tipoTarifa = tarifa.tipoTarifaNombre ? ' - ' + tarifa.tipoTarifaNombre : '';
      var badgeAgotado = (disponibilidad === 0) ? '<span class="badge badge-danger">Agotado</span>' : '';
      
      htmlTarifas += '<div class="py-3 border-bottom">';
      htmlTarifas += '  <p class="mb-2"><strong>' + tarifa.nombre + '</strong> (' + tarifa.edadFrom + ' a ' + tarifa.edadTo + ' años) ' + tipoTarifa + ' ' + badgeAgotado;
      htmlTarifas += '</p>';
        htmlTarifas += '  <div class="d-flex align-items-center justify-content-between flex-nowrap" style="gap: 5px;">';
      htmlTarifas += '    <span class="font-weight-bold text-primary">' + formatPrice(getPrecioValor(tarifa)) + '</span>';
      htmlTarifas += '    <div class="d-flex align-items-center flex-nowrap" style="gap: 5px;">';
      htmlTarifas += '      <a href="javascript:void(0);" class="btn-contador-menos" onclick="CalculaPersonas(' + tarifa.idServicioSalidasTarifas + ',0); return false;" style="cursor:pointer; flex-shrink: 0;">';
      htmlTarifas += '        <i class="fa fa-minus-circle fa-2x text-dark"></i>';
      htmlTarifas += '      </a>';
      htmlTarifas += '      <input type="number" class="form-control text-center cantPers tarifa-' + tarifa.idServicioSalidasTarifas + '" value="0" data-id="' + tarifa.idServicioSalidasTarifas + '" disabled style="width: 60px; flex-shrink: 0; color: #000; background-color: #fff; border: 1px solid #ccc; padding: 0.25rem 0.5rem;">';
      htmlTarifas += '      <a href="javascript:void(0);" class="btn-contador-mas" onclick="CalculaPersonas(' + tarifa.idServicioSalidasTarifas + ',1); return false;" style="cursor:pointer; flex-shrink: 0;">';
      htmlTarifas += '        <i class="fa fa-plus-circle fa-2x text-dark"></i>';
      htmlTarifas += '      </a>';
      htmlTarifas += '    </div>';
      htmlTarifas += '    <strong class="text-primary lblTotal tarifa-' + tarifa.idServicioSalidasTarifas + '" style="text-align: right;">' + symMoneda + ' 0</strong>';
      htmlTarifas += '  </div>';
      htmlTarifas += '</div>';
    }
    
    $('#seleccionar_personas').html(htmlTarifas);
    $('#seleccionar_personasMovil').html(htmlTarifas); // Acordeón móvil personas
    
    // Forzar apertura visual del acordeón de personas (desktop)
    $('#seleccionar_personas').addClass('show').css('display', 'block');
    $('a[data-target="#seleccionar_personas"]').attr('aria-expanded', 'true').removeClass('collapsed');
    
    // 🎯 Rellenar rowCirculosPrecios con los precios de las tarifas
    if ($('#rowCirculosPrecios').length) {
      var htmlPrecios = '<div class="col-lg-3 col-12 my-auto"><p class="popular text-center mb-0"><i class="fa fa-star"></i> ' + (typeof langLabels !== 'undefined' ? langLabels.mas_popular : 'MÁS POPULAR') + '</p></div>';
      
      for (var i = 0; i < tarifas.length; i++) {
        var tarifa = tarifas[i];
        var precioPorPersona = getPrecioValor(tarifa);
        var rangEdad = tarifa.edadFrom + ' a ' + tarifa.edadTo + ' años';
        
        htmlPrecios += '<div class="col-lg-3 col-md-6 col-12 mb-3">';
        htmlPrecios += '  <div class="card p-3 text-center border-0 bg-light" style="border-radius: 8px; min-height: 120px; display: flex; flex-direction: column; justify-content: center;">';
        htmlPrecios += '    <p class="mb-2 font-weight-bold" style="font-size: 13px; color: #666;">' + tarifa.nombre + '</p>';
        htmlPrecios += '    <p class="mb-2" style="font-size: 11px; color: #999;">' + rangEdad + '</p>';
        htmlPrecios += '    <p class="mb-0" style="font-size: 20px; color: #0066cc; font-weight: bold;">' + formatPrice(precioPorPersona) + '</p>';
        htmlPrecios += '  </div>';
        htmlPrecios += '</div>';
      }
      
      $('#rowCirculosPrecios').html(htmlPrecios);
      $('#rowCirculosPreciosMovil').html(htmlPrecios); // Acordeón móvil precio
    }
    
    // Manejar estilo de botones de punto de embarque después de llenar divLugares
    $(document).on('click', '.btn-lugar', function() {
      var $this = $(this);
      
      // Remover clase activa de todos los botones de lugar
      $('.btn-lugar').removeClass('btn-primary').addClass('btn-outline-primary');
      
      // Agregar clase activa al botón clickeado
      $this.removeClass('btn-outline-primary').addClass('btn-primary');
    });
    
    // Actualizar información adicional - idiomas en todos los lugares
    if (tarifas[0]["idiomas"]) {
      $('#pIdiomas').text(tarifas[0]["idiomas"]);
      $('#pIdiomasNav').text(tarifas[0]["idiomas"]);
      $('#pIdiomasNavCelular').text(tarifas[0]["idiomas"]);
      $('#pIdiomasMovil').text(tarifas[0]["idiomas"]); // Acordeón móvil detalles
    }
    
    // Actualizar duración si existe
    if (salidas && salidas[0] && salidas[0]["duracionMinima"]) {
      var duracionMin = parseFloat(salidas[0]["duracionMinima"]);
      var duracionMax = parseFloat(salidas[0]["duracionMaxima"]);
      var categoriaServicio = salidas[0]["idCategoria_servicio"];
      var duracionTexto = "";
      
      // Formatear según categoría y duración (igual que en PHP)
      if (categoriaServicio == 4) { // Paquete
        duracionTexto = duracionMin + " Dias  - " + duracionMax + " Noches ";
      } else {
        var duracionMinTexto = "";
        var duracionMaxTexto = "";
        
        // Formatear duración mínima
        if (duracionMin > 24) {
          duracionMinTexto = Math.ceil(duracionMin / 24) + " Dias ";
        } else if (duracionMin < 1) {
          duracionMinTexto = Math.round(duracionMin * 60) + " Minutos ";
        } else {
          duracionMinTexto = Math.round(duracionMin) + " HS ";
        }
        
        // Formatear duración máxima
        if (duracionMax > 24) {
          duracionMaxTexto = Math.ceil(duracionMax / 24) + " Dias ";
        } else if (duracionMax < 1) {
          duracionMaxTexto = Math.round(duracionMax * 60) + " Minutos ";
        } else {
          duracionMaxTexto = Math.round(duracionMax) + " HS ";
        }
        
        duracionTexto = duracionMinTexto + " - " + duracionMaxTexto;
      }
      
      $('#txtDuracion').text(duracionTexto);
      $('#txtDuracionMovil').text(duracionTexto); // Header móvil
      $('#txtDuracionMovilDetalle').text(duracionTexto); // Acordeón móvil detalles
    }
    
    // Procesar cancelaciones
    if (tarifas[0]["cancelaciones"]) {
      var htmlCancelaciones = '';
      var cancelaciones = tarifas[0]["cancelaciones"];
      
      // Si es un objeto (respuesta antigua [0]), convertir a array
      if (!Array.isArray(cancelaciones)) {
        cancelaciones = [cancelaciones];
      }
      
      if (cancelaciones.length > 0 && cancelaciones[0]) {
        htmlCancelaciones = '<ul class="mb-0">';
        for (var j = 0; j < cancelaciones.length; j++) {
          if (cancelaciones[j] && cancelaciones[j]["texto"]) {
            htmlCancelaciones += '<li>' + cancelaciones[j]["texto"] + '</li>';
          }
        }
        htmlCancelaciones += '</ul>';
      } else {
        htmlCancelaciones = '<p class="mx-4">Consultar política de cancelación al momento de reservar.</p>';
      }
      $('#divCancelaciones').html(htmlCancelaciones);
      $('#divCancelacionesMovil').html(htmlCancelaciones); // Acordeón móvil
      
      var tieneGratuita = false;
      for (var j = 0; j < cancelaciones.length; j++) {
        if (cancelaciones[j] && cancelaciones[j]["idCancelacion"] == 1) {
          tieneGratuita = true;
          break;
        }
      }
      $('#textoCancelacionGratuita').toggle(tieneGratuita);
    } else {
      var msgCancelacion = '<p class="mx-4">Consultar política de cancelación al momento de reservar.</p>';
      $('#divCancelaciones').html(msgCancelacion);
      $('#divCancelacionesMovil').html(msgCancelacion); // Acordeón móvil
    }
    
    // Procesar incluidos
    if (tarifas[0]["adicionalesIncluidos"]) {
      var htmlIncluidos = '';
      for (var j = 0; j < tarifas[0]["adicionalesIncluidos"].length; j++) {
        htmlIncluidos += '<li>' + tarifas[0]["adicionalesIncluidos"][j]["nombre"] + '</li>';
      }
      $('#ulIncluidos').html(htmlIncluidos);
      $('#ulIncluidosMovil').html(htmlIncluidos); // Acordeón móvil
    }
    
    // Procesar no incluidos
    if (tarifas[0]["adicionalesNoIncluidos"] && tarifas[0]["adicionalesNoIncluidos"].length > 0) {
      var htmlNoIncluidos = '';
      var htmlAdicionalesDiv = '';
      
      for (var k = 0; k < tarifas[0]["adicionalesNoIncluidos"].length; k++) {
        var adicional = tarifas[0]["adicionalesNoIncluidos"][k];
        htmlNoIncluidos += '<li>' + adicional["nombre"] + ' ' + adicional["descripcion"] + ' ' + formatPrice(getPrecioValor(adicional)) + '</li>';
        
        htmlAdicionalesDiv += '<div class="py-3 border-bottom">';
        htmlAdicionalesDiv += '  <p class="mb-2"><strong>' + adicional["nombre"] + '</strong>';
        if (adicional["descripcion"]) {
          htmlAdicionalesDiv += ' <small class="text-muted">' + adicional["descripcion"] + '</small>';
        }
        htmlAdicionalesDiv += '</p>';
        htmlAdicionalesDiv += '<div class="d-flex align-items-center justify-content-between flex-nowrap" style="gap: 5px;">';
        htmlAdicionalesDiv += '    <span class="font-weight-bold text-primary">' + formatPrice(getPrecioValor(adicional)) + '</span>';
        htmlAdicionalesDiv += '    <div class="d-flex align-items-center flex-nowrap" style="gap: 5px;">';
        htmlAdicionalesDiv += '      <a href="javascript:void(0);" class="btn-contador-menos-adic" onclick="CalculaAdicionales(' + adicional.idServicioSalidasAdicionales + ',0); return false;" style="cursor:pointer; flex-shrink: 0;">';
        htmlAdicionalesDiv += '        <i class="fa fa-minus-circle fa-2x text-dark"></i>';
        htmlAdicionalesDiv += '      </a>';
        htmlAdicionalesDiv += '      <input type="number" class="form-control text-center cantAdicionales adicional-' + adicional.idServicioSalidasAdicionales + '" id="cantAdicionales' + adicional.idServicioSalidasAdicionales + '" value="0" data-id="' + adicional.idServicioSalidasAdicionales + '" disabled style="width: 60px; flex-shrink: 0; color: #000; background-color: #fff; border: 1px solid #ccc; padding: 0.25rem 0.5rem;">';
        htmlAdicionalesDiv += '      <a href="javascript:void(0);" class="btn-contador-mas-adic" onclick="CalculaAdicionales(' + adicional.idServicioSalidasAdicionales + ',1); return false;" style="cursor:pointer; flex-shrink: 0;">';
        htmlAdicionalesDiv += '        <i class="fa fa-plus-circle fa-2x text-dark"></i>';
        htmlAdicionalesDiv += '      </a>';
        htmlAdicionalesDiv += '    </div>';
        htmlAdicionalesDiv += '    <strong class="text-primary lblTotalAdicional adicional-' + adicional.idServicioSalidasAdicionales + '" id="lblTotalAdicionales' + adicional.idServicioSalidasAdicionales + '">' + symMoneda + ' 0</strong>';
        htmlAdicionalesDiv += '  </div>';
        htmlAdicionalesDiv += '</div>';
      }
      
      $('#ulNoIncluidos').html(htmlNoIncluidos);
      $('#ulNoIncluidosMovil').html(htmlNoIncluidos); // Acordeón móvil
      $('#divAdicionalesNoIncluidos').html(htmlAdicionalesDiv);
      $('#divAdicionalesNoIncluidosMovil').html(htmlAdicionalesDiv); // Acordeón móvil
      $('#Seleccionar_adicionales_b').show();
    } else {
      $('#Seleccionar_adicionales_b').hide();
      $('#divNoIncluidosCuerpo').hide(); // Ocultar sección desktop
      $('#divNoIncluidosCuerpoMovil').hide(); // Ocultar sección móvil
    }
    
    // Incrementar primero
    CalculaPersonas(tarifas[0]['idServicioSalidasTarifas'], 1);
    
    actualizaPrecios();
  });
}

// ==================== CALCULAR PERSONAS ====================

function CalculaPersonas(idServicioSalidasTarifas, operacion){
  // Efecto visual: cambiar color del botón al clickear
  var selector = operacion == 0 ? '.btn-contador-menos' : '.btn-contador-mas';
  var $boton = $(event.target).closest('a');
  
  // Si no encontramos el botón por event.target, buscamos todos y le aplicamos el efecto
  if ($boton.length === 0) {
    $boton = $(selector);
  }
  
  // Cambiar a celeste (primario) durante el click
  var $icono = $boton.find('i');
  var claseOriginal = 'text-dark';
  $icono.removeClass(claseOriginal).addClass('text-primary');
  
  // Volver a negro después de 300ms
  setTimeout(function(){
    $icono.removeClass('text-primary').addClass(claseOriginal);
  }, 300);
  
  if (cantidadPersonas >= disponibilidad && operacion == 1) {
    Swal.fire({
      title: 'Sin disponibilidad',
      text: 'No hay más cupos disponibles para esta salida',
      icon: 'warning',
      confirmButtonText: 'Entendido'
    });
    return;
  }
  
  var cantidad = parseInt($('.cantPers.tarifa-' + idServicioSalidasTarifas).val()) || 0;
  cantidadPersonas = cantidadPersonas - cantidad;
  
  $.post("admin/ctrl/ctrlHorarios", {
    idServicioSalidasTarifas: idServicioSalidasTarifas,
    cantidad: cantidad
  }, function(data, status){
    var tarifas = JSON.parse(data);
    var valorTarifa = getPrecioValor(tarifas[0]);
    
    if (operacion == 0 && cantidad > 0) {
      precioTotal -= valorTarifa;
      cantidad -= 1;
    } else if (operacion == 1 && cantidad >= 0) {
      precioTotal -= valorTarifa;
      cantidad += 1;
    }
    
    cantidadPersonas = cantidadPersonas + cantidad;
    $('.cantPers.tarifa-' + idServicioSalidasTarifas).val(cantidad);
    
    $.post("admin/ctrl/ctrlHorarios", {
      idServicioSalidasTarifas: idServicioSalidasTarifas,
      cantidad: cantidad
    }, function(data, status){
      var tarifas = JSON.parse(data);
      console.log("DEBUG: Tarifa recibida del servidor:", tarifas[0]);
      var valorTarifaActual = getPrecioValor(tarifas[0]);
      console.log("DEBUG: valorTarifaActual ANTES de redondeo:", valorTarifaActual);
      
      // Redondear para evitar deriva de flotantes
      valorTarifaActual = Math.round(valorTarifaActual * 100) / 100;
      console.log("DEBUG: valorTarifaActual DESPUÉS de redondeo:", valorTarifaActual);
      
      $('.lblTotal.tarifa-' + idServicioSalidasTarifas).text(formatPrice(valorTarifaActual));
      
      precioTotal += valorTarifaActual;
      
      var exito = 0;
      if (reserva.length > 0) {
        for (var i = 0; i < reserva.length; i++) {
          if (reserva[i]["idServicioSalidasTarifas"] == idServicioSalidasTarifas) {
            reserva[i]["cantidad"] = cantidad;
            if (cantidad < 1) {
              reserva.splice(i, 1);
            }
            exito++;
          }
        }
      }
      
      if (exito < 1 && cantidad > 0) {
        reserva.push({
          idServicioSalidasTarifas: idServicioSalidasTarifas,
          cantidad: cantidad,
          idServicioSeleccionado: idServicioSeleccionado
        });
      }
      
      actualizaPrecios();
    });
  });
}

// ==================== CALCULAR ADICIONALES ====================

function CalculaAdicionales(idServicioSalidasAdicionales, operacion) {
  // Efecto visual: cambiar color del botón al clickear
  var selector = operacion == 0 ? '.btn-contador-menos-adic' : '.btn-contador-mas-adic';
  var $boton = $(event.target).closest('a');
  
  // Si no encontramos el botón por event.target, buscamos todos y le aplicamos el efecto
  if ($boton.length === 0) {
    $boton = $(selector);
  }
  
  // Cambiar a celeste (primario) durante el click
  var $icono = $boton.find('i');
  var claseOriginal = 'text-dark';
  $icono.removeClass(claseOriginal).addClass('text-primary');
  
  // Volver a negro después de 300ms
  setTimeout(function(){
    $icono.removeClass('text-primary').addClass(claseOriginal);
  }, 300);
  
  if (cantidadPersonas < 1) {
    Swal.fire({
      title: 'Atención',
      text: 'Debe agregar personas para poder contratar servicios adicionales',
      icon: 'warning',
      confirmButtonText: 'Entendido'
    });
    return;
  }
  
  var cantidad = parseInt($('.cantAdicionales.adicional-' + idServicioSalidasAdicionales).val()) || 0;
  
  if (operacion == 0) {
    if (cantidad > 0) cantidad--;
  } else if (operacion == 1) {
    if (cantidad < cantidadPersonas) {
      cantidad++;
    } else {
      Swal.fire({
        title: 'Límite alcanzado',
        text: 'No puedes agregar más servicios adicionales que la cantidad de pasajeros (' + cantidadPersonas + ')',
        icon: 'info',
        confirmButtonText: 'Entendido'
      });
      return;
    }
  }
  
  $('.cantAdicionales.adicional-' + idServicioSalidasAdicionales).val(cantidad);
  
  $.post("admin/ctrl/ctrlHorarios", {
    idServicioSalidasAdicionales: idServicioSalidasAdicionales,
    cantidad: cantidad
  }, function(data, status){
    console.log('Respuesta servidor adicionales:', data);
    var response;
    try {
      response = JSON.parse(data);
    } catch(e) {
      console.error('Error parseando JSON adicionales:', e);
      console.error('Data recibida:', data);
      return;
    }
    var valor = getPrecioValor(response[0]);
    var total = valor * cantidad;
    
    // Actualizar el total usando ambos selectores (ID y clase) para compatibilidad
    $('#lblTotalAdicionales' + idServicioSalidasAdicionales).text(formatPrice(total));
    $('.lblTotalAdicional.adicional-' + idServicioSalidasAdicionales).text(formatPrice(total));
    
    var exito = 0;
    if (reservaAdicionales.length > 0) {
      for (var i = 0; i < reservaAdicionales.length; i++) {
        if (reservaAdicionales[i]["idServicioSalidasAdicionales"] == idServicioSalidasAdicionales) {
          reservaAdicionales[i]["cantidad"] = cantidad;
          reservaAdicionales[i]["valorUnitario"] = valor;
          reservaAdicionales[i]["valorTotal"] = total;
          if (cantidad < 1) {
            reservaAdicionales.splice(i, 1);
          }
          exito++;
        }
      }
    }
    
    if (exito < 1 && cantidad > 0) {
      reservaAdicionales.push({
        idServicioSalidasAdicionales: idServicioSalidasAdicionales,
        cantidad: cantidad,
        valorUnitario: valor,
        valorTotal: total
      });
    }
    
    actualizaPrecios();
  });
}

// ==================== INICIALIZACIÓN ====================

$(document).ready(function(){
  // Inicializar calendario CLNDR unificado - UNA SOLA VEZ
  if ($('#calendar').length && !$('#calendar').data('clndr-initialized')) {
    if (eventArray.length === 0) {
    }
    
    $('#calendar').clndr({
      events: eventArray,
      startWithMonth: eventArray.length > 0 ? eventArray[0]["date"] : new Date(),
      daysOfTheWeek: diasSemanaHeader,
      showAdjacentMonths: false,
      weekOffset: weekOffset,
      clickEvents: {
        click: function (target) {
          if ($(target.element).hasClass('event')) {
            var fechaSeleccionada = target.date._i;
            
            // Colorear día seleccionado
            $('#calendar .day.event').css({
              'background': '#FFF',
              'color': 'black',
              'border-radius': '0px'
            });
            $(target.element).css({
              'background': '#029ce2',
              'color': '#FFF',
              'border-radius': '50%'
            });
            
            // Cargar horarios
            traeHorarios(fechaSeleccionada, idServicioSeleccionado);
          }
        }
      },
      doneRendering: function() {
        // Traducir encabezado del mes (fallback en caso de que moment.locale no esté disponible con locales)
        try {
          var currentMonthMoment = this.month; // instancia de CLNDR
          var idxMes = currentMonthMoment.month();
          var anio = currentMonthMoment.year();
          var nombreMes = (mesesMap[idiomaSistema] || mesesMap['ES'])[idxMes];
          $('#calendar .month').text(nombreMes + ' ' + anio);
        } catch(e) { }
        // Colorear primer día con eventos
        setTimeout(function() {
          var primerDia = $('#calendar .day.event').first();
          if (primerDia.length) {
            primerDia.css({
              'background': '#029ce2',
              'color': '#FFF',
              'border-radius': '50%'

            });
            // Auto-seleccionar primer día
            var fechaPrimerDia = primerDia.data('date') || eventArray[0]["date"];
            traeHorarios(fechaPrimerDia, idServicioSeleccionado);
          }
        }, 500);
      }
    });
    
    // Marcar como inicializado
    $('#calendar').data('clndr-initialized', true);
  }
  
  // Inicializar calendario MÓVIL - UNA SOLA VEZ
  if ($('#mini-clndr-movil').length && !$('#mini-clndr-movil').data('clndr-initialized')) {
    if (eventArray.length === 0) {
      console.warn('No hay eventos disponibles para el calendario móvil');
    }
    
    $('#mini-clndr-movil').clndr({
      events: eventArray,
      startWithMonth: eventArray.length > 0 ? eventArray[0]["date"] : new Date(),
      daysOfTheWeek: diasSemanaHeader,
      showAdjacentMonths: false,
      weekOffset: weekOffset,
      clickEvents: {
        click: function (target) {
          if ($(target.element).hasClass('event')) {
            var fechaSeleccionada = target.date._i;
            
            // Colorear día seleccionado
            $('#mini-clndr-movil .day.event').css({
              'background': '#FFF',
              'color': 'black',
              'border-radius': '0px'
            });
            $(target.element).css({
              'background': '#029ce2',
              'color': '#FFF',
              'border-radius': '50%'
            });
            
            // Cargar horarios
            traeHorarios(fechaSeleccionada, idServicioSeleccionado);
          }
        }
      },
      doneRendering: function() {
        // Traducir encabezado del mes
        try {
          var currentMonthMoment = this.month;
          var idxMes = currentMonthMoment.month();
          var anio = currentMonthMoment.year();
          var nombreMes = (mesesMap[idiomaSistema] || mesesMap['ES'])[idxMes];
          $('#mini-clndr-movil .month').text(nombreMes + ' ' + anio);
        } catch(e) { }
        // Colorear primer día con eventos
        setTimeout(function() {
          var primerDia = $('#mini-clndr-movil .day.event').first();
          if (primerDia.length) {
            primerDia.css({
              'background': '#029ce2',
              'color': '#FFF',
              'border-radius': '50%'
            });
          }
        }, 500);
      }
    });
    
    // Marcar como inicializado
    $('#mini-clndr-movil').data('clndr-initialized', true);
  }
  
  // Inicializar precios
  actualizaPrecios();
});

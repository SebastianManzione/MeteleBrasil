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
    
    var precioTotalSinDescuento = total * 1.1356987;
    $('#precioTotalSinDescuento').text(formatPrice(precioTotalSinDescuento));
  }
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
  console.log('🔵 traeHorarios INICIADA');
  console.log('  - fecha:', $fecha);
  console.log('  - idServicio:', $idServicio);
  
  limpiarTarifaYAdicionales();
  
  $.post("admin/ctrl/ctrlHorarios", {
    fecha: $fecha,
    idServicio: $idServicio
  }, function(data, status){
    console.log('📡 Respuesta AJAX:', data);
    
    try {
      salidas = JSON.parse(data);
    } catch (e) {
      console.error('❌ ERROR al parsear JSON:', e);
      console.error('Contenido de data:', data);
      salidas = [];
    }
    
    console.log('salidas después parse:', salidas);
    
    var salidasDisponibles = [];
    for (var i = 0; i < salidas.length; i++) {
      if (salidas[i]["disponibilidad"] && parseInt(salidas[i]["disponibilidad"]) > 0) {
        salidasDisponibles.push(salidas[i]);
      }
    }
    
    console.log('salidasDisponibles:', salidasDisponibles.length);
    
    if (salidasDisponibles.length === 0) {
      console.warn('⚠️ No hay salidas disponibles');
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
      
      htmlHorarios += '<button id="btnSalida' + idSalida + '" class="btn btn-outline-primary btn-hora btn-block m-2" data-idsalida="' + idSalida + '" onclick="traeTarifas(' + idSalida + ')">' + textoMostrar + '</button>';
      if (i < salidasDisponibles.length - 1) {
        htmlHorarios += '<hr class="my-1">';
      }
    }
    
    console.log('✅ HTML generado, poblando #divhoraBody');
    console.log('htmlHorarios length:', htmlHorarios.length);
    $('#divhoraBody').html(htmlHorarios);
    console.log('post-insert innerHTML length:', $('#divhoraBody').html().length);
    
    // Forzar apertura visual del acordeón de horarios
    console.log('Forzando apertura de #divhora');
    $('#divhora').addClass('show').css('display', 'block');
    $('a[data-target="#divhora"]').attr('aria-expanded', 'true').removeClass('collapsed');
    
    // Auto-seleccionar primera salida
    if (salidasDisponibles.length > 0) {
      console.log('Auto-seleccionando primera salida:', salidasDisponibles[0]["idServicioSalidas"]);
      setTimeout(function() {
        traeTarifas(salidasDisponibles[0]["idServicioSalidas"]);
      }, 300);
    }
  });
}

// ==================== TARIFAS ====================

function traeTarifas($idSalida){
  console.log('🟢 traeTarifas INICIADA con idSalida:', $idSalida);
    // Marcar botón seleccionado
  $('.btn-hora').removeClass('btn-primary').addClass('btn-outline-primary');
  $('#btnSalida' + $idSalida).removeClass('btn-outline-primary').addClass('btn-primary');
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
    console.log('📡 Respuesta traeTarifas AJAX:', data);
    
    var tarifas = [];
    try {
      tarifas = JSON.parse(data);
    } catch (e) {
      console.error('❌ ERROR al parsear tarifas:', e);
      console.error('Contenido data:', data);
    }
    
    console.log('tarifas después parse:', tarifas);
    
    if (!tarifas || tarifas.length === 0) {
      console.warn('⚠️ No hay tarifas disponibles');
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
      
      // Mostrar disponibilidad solo si es admin
      if (isAdmin) {
        htmlTarifas += ' <small class="text-muted">| Disp: ' + disponibilidad + '</small>';
      }
      
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
      htmlTarifas += '    <strong class="text-primary lblTotal tarifa-' + tarifa.idServicioSalidasTarifas + '" style="text-align: right;">AR$ 0</strong>';
      htmlTarifas += '  </div>';
      htmlTarifas += '</div>';
    }
    
    console.log('✅ Poblando #seleccionar_personas con:', htmlTarifas.length, 'caracteres');
    $('#seleccionar_personas').html(htmlTarifas);
    
    // Forzar apertura visual del acordeón de personas
    console.log('Forzando apertura de #seleccionar_personas');
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
      console.log('✅ Precios populares cargados en rowCirculosPrecios');
    }
    
    // Actualizar información adicional - idiomas en todos los lugares
    if (tarifas[0]["idiomas"]) {
      $('#pIdiomas').text(tarifas[0]["idiomas"]);
      $('#pIdiomasNav').text(tarifas[0]["idiomas"]);
      $('#pIdiomasNavCelular').text(tarifas[0]["idiomas"]);
    }
    
    // Procesar cancelaciones
    if (tarifas[0]["cancelaciones"]) {
      var htmlCancelaciones = '';
      for (var j = 0; j < tarifas[0]["cancelaciones"].length; j++) {
        htmlCancelaciones += '<p>' + tarifas[0]["cancelaciones"][j]["texto"] + '</p>';
      }
      $('#divCancelaciones').html(htmlCancelaciones);
      
      var tieneGratuita = false;
      for (var j = 0; j < tarifas[0]["cancelaciones"].length; j++) {
        if (tarifas[0]["cancelaciones"][j]["idCancelacion"] == 1) {
          tieneGratuita = true;
          break;
        }
      }
      $('#textoCancelacionGratuita').toggle(tieneGratuita);
    }
    
    // Procesar incluidos
    if (tarifas[0]["adicionalesIncluidos"]) {
      var htmlIncluidos = '';
      for (var j = 0; j < tarifas[0]["adicionalesIncluidos"].length; j++) {
        htmlIncluidos += '<li>' + tarifas[0]["adicionalesIncluidos"][j]["nombre"] + '</li>';
      }
      $('#ulIncluidos').html(htmlIncluidos);
    }
    
    // Procesar no incluidos
    if (tarifas[0]["adicionalesNoIncluidos"] && tarifas[0]["adicionalesNoIncluidos"].length > 0) {
      var htmlNoIncluidos = '';
      var htmlAdicionalesDiv = '';
      
      for (var k = 0; k < tarifas[0]["adicionalesNoIncluidos"].length; k++) {
        var adicional = tarifas[0]["adicionalesNoIncluidos"][k];
        htmlNoIncluidos += '<li>' + adicional["nombre"] + ' ' + adicional["descripcion"] + ' ' + adicional["valor"] + '</li>';
        
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
        htmlAdicionalesDiv += '    <strong class="text-primary lblTotalAdicional adicional-' + adicional.idServicioSalidasAdicionales + '" id="lblTotalAdicionales' + adicional.idServicioSalidasAdicionales + '">AR$ 0</strong>';
        htmlAdicionalesDiv += '  </div>';
        htmlAdicionalesDiv += '</div>';
      }
      
      $('#ulNoIncluidos').html(htmlNoIncluidos);
      $('#divAdicionalesNoIncluidos').html(htmlAdicionalesDiv);
      $('#Seleccionar_adicionales_b').show();
    } else {
      $('#Seleccionar_adicionales_b').hide();
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
      var valorTarifaActual = getPrecioValor(tarifas[0]);
      
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
    var response = JSON.parse(data);
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
        cantidad: cantidad
      });
    }
    
    actualizaPrecios();
  });
}

// ==================== INICIALIZACIÓN ====================

$(document).ready(function(){
  // Logs iniciales de depuración
  console.log('=== INICIANDO TRAEHORARIOS_UNIFICADO ===');
  console.log('idServicioSeleccionado:', idServicioSeleccionado);
  console.log('eventArray:', eventArray);
  console.log('eventArray length:', eventArray.length);
  console.log('symMoneda:', symMoneda);
  console.log('HTML calendar exists:', $('#calendar').length);
  console.log('HTML collapseCalendario exists:', $('#collapseCalendario').length);
  console.log('HTML divhora exists:', $('#divhora').length);
  console.log('HTML seleccionar_personas exists:', $('#seleccionar_personas').length);
  
  // Inicializar calendario CLNDR unificado - UNA SOLA VEZ
  if ($('#calendar').length && !$('#calendar').data('clndr-initialized')) {
    console.log('Inicializando CLNDR calendario...');
    
    if (eventArray.length === 0) {
      console.warn('⚠️ ADVERTENCIA: eventArray está vacío!');
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
            console.log('Día clickeado:', fechaSeleccionada);
            
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
            console.log('Llamando traeHorarios con:', fechaSeleccionada, idServicioSeleccionado);
            traeHorarios(fechaSeleccionada, idServicioSeleccionado);
          }
        }
      },
      doneRendering: function() {
        console.log('CLNDR rendering completo');
        // Traducir encabezado del mes (fallback en caso de que moment.locale no esté disponible con locales)
        try {
          var currentMonthMoment = this.month; // instancia de CLNDR
          var idxMes = currentMonthMoment.month();
          var anio = currentMonthMoment.year();
          var nombreMes = (mesesMap[idiomaSistema] || mesesMap['ES'])[idxMes];
          $('#calendar .month').text(nombreMes + ' ' + anio);
        } catch(e) { console.warn('No se pudo traducir el nombre del mes:', e); }
        // Colorear primer día con eventos
        setTimeout(function() {
          var primerDia = $('#calendar .day.event').first();
          console.log('Primer día encontrado:', primerDia.length);
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
  
  // Inicializar precios
  actualizaPrecios();
});

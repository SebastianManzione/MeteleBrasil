// Mejoras de UX para disponibilidad y selección de salidas

// Variable global para almacenar salidas disponibles
var salida_actual = 0;
var intentoActual = 0;
var salidastotales = [];

// Función para marcar tarifas agotadas en el selector de personas
function marcarTarifasAgotadas() {
  // Buscar párrafos con counter-label que contengan el nombre de la tarifa
  $('#seleccionar_personas').find('.counter-label').each(function() {
    var $this = $(this);
    var text = $this.text();
    
    // Si no contiene AGOTADO y disponibilidad es 0, agregar badge
    if (!text.includes('AGOTADO') && disponibilidad <= 0) {
      $this.append(' <span class="badge badge-danger">AGOTADO</span>');
    }
  });
  
  $('#seleccionar_personasMovil').find('.counter-label').each(function() {
    var $this = $(this);
    var text = $this.text();
    
    if (!text.includes('AGOTADO') && disponibilidad <= 0) {
      $this.append(' <span class="badge badge-danger">AGOTADO</span>');
    }
  });
}

// Función para agregar IDs a botones de salida (si no los tienen)
function agregarIDsBotones(salidas) {
  if (!salidas || salidas.length === 0) return;
  
  for (var i = 0; i < salidas.length; i++) {
    var idSalida = salidas[i]['idServicioSalidas'];
    
    // Buscar el botón basado en el texto y asignarle un ID
    $('#divhora').find('button').each(function(index) {
      if ($(this).attr('onclick') && $(this).attr('onclick').includes('traeTarifas(' + idSalida + ')')) {
        $(this).attr('id', 'btnSalida' + idSalida);
      }
    });
    
    $('#divhora-movil').find('button').each(function(index) {
      if ($(this).attr('onclick') && $(this).attr('onclick').includes('traeTarifas(' + idSalida + ')')) {
        $(this).attr('id', 'btnSalidaMov' + idSalida);
      }
    });
  }
}

// Función para resaltar el botón seleccionado y manejar disponibilidad
function resaltarSalidaSeleccionada(idServicioSalidas) {
  console.log('=== resaltarSalidaSeleccionada INICIO ===');
  console.log('ID recibido:', idServicioSalidas);
  
  if (!idServicioSalidas) {
    console.log('ERROR: No ID provided');
    return;
  }
  
  // Limpiar TODOS los botones
  $('#divhora button, #divhora-movil button').each(function() {
    this.style.removeProperty('background-color');
    this.style.removeProperty('border-color');
    this.style.removeProperty('color');
    this.style.removeProperty('box-shadow');
    this.style.removeProperty('font-weight');
  });
  
  console.log('Botones limpiados');
  
  // Aplicar estilos al botón desktop
  var btnDesktop = document.getElementById('btnSalida' + idServicioSalidas);
  if (btnDesktop) {
    console.log('Pintando botón desktop');
    btnDesktop.style.setProperty('background-color', '#029ce2', 'important');
    btnDesktop.style.setProperty('border-color', '#029ce2', 'important');
    btnDesktop.style.setProperty('color', '#fff', 'important');
    btnDesktop.style.setProperty('box-shadow', '0 0 8px rgba(2, 156, 226, 0.8)', 'important');
    btnDesktop.style.setProperty('font-weight', 'bold', 'important');
    console.log('Desktop pintado - color:', btnDesktop.style.backgroundColor);
  } else {
    console.log('Botón desktop NO encontrado: btnSalida' + idServicioSalidas);
  }
  
  // Aplicar estilos al botón móvil
  var btnMovil = document.getElementById('btnSalidaMov' + idServicioSalidas);
  if (btnMovil) {
    console.log('Pintando botón móvil');
    btnMovil.style.setProperty('background-color', '#029ce2', 'important');
    btnMovil.style.setProperty('border-color', '#029ce2', 'important');
    btnMovil.style.setProperty('color', '#fff', 'important');
    btnMovil.style.setProperty('box-shadow', '0 0 8px rgba(2, 156, 226, 0.8)', 'important');
    btnMovil.style.setProperty('font-weight', 'bold', 'important');
    console.log('Móvil pintado');
  } else {
    console.log('Botón móvil NO encontrado: btnSalidaMov' + idServicioSalidas);
  }
  
  console.log('=== resaltarSalidaSeleccionada FIN ===');
}

// Función para deshabilitar inputs si no hay disponibilidad
function manejarDisponibilidad() {
  // Si no hay disponibilidad, deshabilitar los inputs de personas
  if (disponibilidad <= 0) {
    $('[id^="cantPersAdc"]').prop('disabled', true).css('background-color', '#f5f5f5');
    $('[id^="cantPersAdcMovil"]').prop('disabled', true).css('background-color', '#f5f5f5');
    // También deshabilitar los botones +/-
    $('#divhora').find('a[onclick*="CalculaPersonas"]').css({'opacity': '0.5', 'pointer-events': 'none', 'cursor': 'not-allowed'});
    $('#divhora-movil').find('a[onclick*="CalculaPersonas"]').css({'opacity': '0.5', 'pointer-events': 'none', 'cursor': 'not-allowed'});
    
    // Agregar etiqueta AGOTADO en círculos de precio
    agregarEtiquetaAgotado();
    
    // Agregar etiqueta AGOTADO en selector de personas
    marcarTarifasAgotadas();
  } else {
    $('[id^="cantPersAdc"]').prop('disabled', false).css('background-color', '');
    $('[id^="cantPersAdcMovil"]').prop('disabled', false).css('background-color', '');
    $('#divhora').find('a[onclick*="CalculaPersonas"]').css({'opacity': '1', 'pointer-events': 'auto', 'cursor': 'pointer'});
    $('#divhora-movil').find('a[onclick*="CalculaPersonas"]').css({'opacity': '1', 'pointer-events': 'auto', 'cursor': 'pointer'});
    
    // Remover etiqueta AGOTADO
    removerEtiquetaAgotado();
    
    // Remover etiquetas AGOTADO del selector de personas
    $('#seleccionar_personas').find('.badge-danger').remove();
    $('#seleccionar_personasMovil').find('.badge-danger').remove();
  }
}

// Función para agregar etiqueta AGOTADO
function agregarEtiquetaAgotado() {
  var existeEtiqueta = $('#rowCirculosPrecios').find('.badge-agotado').length > 0;
  if (!existeEtiqueta) {
    $('#rowCirculosPrecios').find('.circulo-b').each(function() {
      if (!$(this).find('.badge-agotado').length) {
        $(this).append('<span class="badge badge-danger badge-agotado" style="position: absolute; top: 5px; right: 5px;">AGOTADO</span>');
      }
    });
    $('#rowCirculosPreciosMovil').find('.circulo-b').each(function() {
      if (!$(this).find('.badge-agotado').length) {
        $(this).append('<span class="badge badge-danger badge-agotado" style="position: absolute; top: 5px; right: 5px;">AGOTADO</span>');
      }
    });
  }
}

// Función para remover etiqueta AGOTADO
function removerEtiquetaAgotado() {
  $('#rowCirculosPrecios').find('.badge-agotado').remove();
  $('#rowCirculosPreciosMovil').find('.badge-agotado').remove();
}

// Función para buscar la primera salida con disponibilidad
function buscarPrimeraSalidaDisponible(salidas) {
  if (!salidas || salidas.length === 0) return null;
  
  // Intentar encontrar una salida con disponibilidad > 0
  for (var i = 0; i < salidas.length; i++) {
    if (salidas[i]['disponibilidad'] && salidas[i]['disponibilidad'] > 0) {
      return salidas[i]['idServicioSalidas'];
    }
  }
  
  // Si ninguna tiene disponibilidad, retornar la primera
  return salidas[0]['idServicioSalidas'];
}

// Función para inicializar el resaltado al cargar la página
function inicializarResaltadoInicial() {
  // Esperar a que se carguen los botones
  setTimeout(function() {
    var primerBoton = $('#divhora').find('button').first();
    if (primerBoton.length > 0 && primerBoton.attr('onclick')) {
      // Extraer idServicioSalidas del onclick
      var onclick = primerBoton.attr('onclick');
      var match = onclick.match(/traeTarifas\((\d+)\)/);
      if (match && match[1]) {
        resaltarSalidaSeleccionada(match[1]);
      }
    }
  }, 200);
}

// Función para buscar automáticamente otra salida si la actual no tiene disponibilidad
function buscarSalidaConDisponibilidad() {
  // Si no hay disponibilidad y aún hay salidas por intentar
  if (disponibilidad <= 0 && typeof salidastotales !== 'undefined' && salidastotales.length > 0) {
    intentoActual++;
    
    if (intentoActual < salidastotales.length) {
      // Intentar con la siguiente salida
      var proximaSalida = salidastotales[intentoActual];
      if (proximaSalida && proximaSalida['idServicioSalidas']) {
        traeTarifas(proximaSalida['idServicioSalidas']);
      }
    }
  }
}



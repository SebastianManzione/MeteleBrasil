// Fix para coloreo de días en calendario
// Modifica el comportamiento del evento click después de que CLNDR esté inicializado

(function() {
  // Esperar a que se cargue CLNDR completamente
  var esperarCLNDR = function() {
    // Después de 2 segundos, CLNDR debería estar inicializado
    setTimeout(function() {
      // Encontrar todos los elementos .day.event
      var aplicarFix = function() {
        var diasConEventos = document.querySelectorAll('.day.event');
        
        diasConEventos.forEach(function(dia) {
          if (!dia.dataset.fixAplicado) {
            dia.dataset.fixAplicado = 'true';
            
            // Guardar el manejador onclick original si existe
            var onclickOriginal = dia.onclick;
            
            // Reemplazar el manejador onclick
            dia.onclick = function(event) {
              // Ejecutar primero el manejador original (que llama a traeHorarios)
              if (onclickOriginal) {
                onclickOriginal.call(this, event);
              }
              
              // Luego ejecutar nuestro coloreado
              setTimeout(function() {
                var clases = dia.getAttribute('class');
                var calendario = dia.closest('.cal1') || dia.closest('.cal2');
                
                if (calendario) {
                  // Limpiar todos
                  var todosLosDias = calendario.querySelectorAll('.day.event');
                  todosLosDias.forEach(function(d) {
                    d.style.background = '#FFF';
                    d.style.color = 'black';
                    d.style.borderRadius = '0px';
                  });
                  
                  // Colorear seleccionado
                  dia.style.background = '#029ce2';
                  dia.style.color = '#FFF';
                  dia.style.borderRadius = '50%';
                }
              }, 100);
              
              return false;
            };
          }
        });
      };
      
      // Aplicar el fix inicialmente
      aplicarFix();
      
      // Colorear el primer día con eventos automáticamente
      setTimeout(function() {
        var primerDiaCal1 = document.querySelector('.cal1 .day.event');
        var primerDiaCal2 = document.querySelector('.cal2 .day.event');
        
        if (primerDiaCal1) {
          primerDiaCal1.style.background = '#029ce2';
          primerDiaCal1.style.color = '#FFF';
          primerDiaCal1.style.borderRadius = '50%';
        }
        
        if (primerDiaCal2) {
          primerDiaCal2.style.background = '#029ce2';
          primerDiaCal2.style.color = '#FFF';
          primerDiaCal2.style.borderRadius = '50%';
        }
      }, 500);
      
      // Y seguir aplicándolo cada 500ms para elementos nuevos
      var intervalo = setInterval(aplicarFix, 500);
      
      // Detener después de 120 segundos
      setTimeout(function() {
        clearInterval(intervalo);
      }, 120000);
    }, 2000);
  };
  
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', esperarCLNDR);
  } else {
    esperarCLNDR();
  }
})();

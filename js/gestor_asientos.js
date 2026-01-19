(function(){
  var seleccionados = new Set();
  function toggleSeat(el, countEl){
    var estado = el.getAttribute('data-estado');
    var id = el.getAttribute('data-seat-id');
    if (estado === 'ocupado') return;
    if (el.classList.contains('seleccionado')) {
      el.classList.remove('seleccionado');
      seleccionados.delete(id);
    } else {
      el.classList.add('seleccionado');
      seleccionados.add(id);
    }
    if (countEl) { countEl.textContent = String(seleccionados.size); }
  }
  function init(containerSelector, countSelector){
    var container = document.querySelector(containerSelector);
    var countEl = document.querySelector(countSelector);
    if (!container) return;
    var seats = container.querySelectorAll('.seat');
    seats.forEach(function(el){
      el.addEventListener('click', function(){ toggleSeat(el, countEl); });
    });
    if (countEl) { countEl.textContent = String(seleccionados.size); }
  }
  function getSeleccionados(){
    return Array.from(seleccionados);
  }
  window.AsientosGestor = { init: init, getSeleccionados: getSeleccionados };
})();

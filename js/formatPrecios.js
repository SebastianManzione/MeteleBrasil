// Formateo de precios para el index
function toNumeric(amount) {
  if (typeof amount === 'number') return amount;
  if (amount === null || typeof amount === 'undefined') return 0;
  var s = String(amount).trim();
  // Quitar separadores de miles (.) y normalizar coma decimal a punto
  s = s.replace(/\./g, '').replace(/,/g, '.');
  var n = parseFloat(s);
  return isNaN(n) ? 0 : n;
}

function formatPrice(text) {
  if (!text || text === 'ESGOTADO' || text === 'ESGOTADO!' || text === 'AGOTADO') {
    return text;
  }
  
  // Extraer símbolo de moneda y valor
  var match = text.match(/^([^0-9]+)\s*(.+)$/);
  if (!match) return text;
  
  var symbol = match[1].trim();
  var valueStr = match[2].trim();
  
  // Convertir a número
  var numeric = toNumeric(valueStr);
  if (numeric === 0) return text;
  
  // Si es AR$ (pesos argentinos), no mostrar decimales
  var decimals = (symbol.toUpperCase().indexOf('AR') !== -1) ? 0 : 2;
  
  return symbol + ' ' + numeric.toLocaleString('es-ES', {
    minimumFractionDigits: decimals,
    maximumFractionDigits: decimals
  });
}

// Reformatear todos los precios en el index cuando carga la página
document.addEventListener('DOMContentLoaded', function() {
  var preciosCards = document.querySelectorAll('.precio-card');
  preciosCards.forEach(function(elemento) {
    var textoOriginal = elemento.textContent.trim();
    var textoFormateado = formatPrice(textoOriginal);
    elemento.textContent = textoFormateado;
  });
});

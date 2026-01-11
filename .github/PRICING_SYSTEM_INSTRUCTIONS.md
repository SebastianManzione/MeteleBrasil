# Sistema de Precios Psicológicos para Monedas Devaluadas

**AGREGAR ESTA SECCIÓN AL FINAL DE copilot-instructions.md**

---

## Sistema de Precios Psicológicos para Monedas Devaluadas (Implementado - Enero 2026)

### Propósito
Mostrar precios inflados al cliente (percepción de mayor valor) mientras se carga el precio real en la BD y se calculan comisiones sobre el precio real (justicia financiera).

### Monedas Afectadas
- **ARS (Peso Argentino):** Código 270 - Tasa fija 1 USD = 1550 ARS **NO MODIFICAR**
- **CLP (Peso Chileno):** Código 271 - Devaluada
- **PYG (Guaraní):** Código 225 - Devaluada

**Monedas sin redondeo:** USD, EUR, BRL

### Fórmula de Inflación
```php
// admin/classes/tarifas.php líneas 217-237
$valorOriginal = $retorno[$i]['valor'];  // Precio antes de inflación
$precioUnitario = $valorOriginal / $cantidad;
$precioUnitarioInflado = ceil($precioUnitario / 1000) * 1000;  // Redondear UP
$valorInflado = $precioUnitarioInflado * $cantidad;
$redondeoDiferencia = $valorInflado - $valorOriginal;  // "Descuento" psicológico

$retorno[$i]['valor'] = $valorInflado;           // Lo que ve el cliente
$retorno[$i]['valorOriginal'] = $valorOriginal;  // Lo que se guarda realmente
```

**Máximo aumento:** ±1000 por redondeo (no ±2000)

### Flujo de Datos Crítico

#### 1. Cálculo (calculaTarifa)
BD precio → convertir moneda → inflar → retornar ambos (valor + valorOriginal)

#### 2. Guardado (guardaReservas.php + reserva.php:altaReservaTarifas)
```
Guarda EN BD:
  - valor = 1.749.000 (inflado - mostrar)
  - valorOriginal = 1.748.400 (real - usar para cálculos)
```

#### 3. Mostrar Cliente (carrito.php, datosPersonales.php, consultaReserva.php)
Muestra: valor (inflado). En modal: muestra descuento = redondeoDiferencia

#### 4. Financiero (financieroPrestador.php, ajax_get_pasajeros_salida.php)
SIEMPRE usa: `valorOriginal > 0 ? valorOriginal : valor`
Comisión = valorOriginal * porcentaje (NUNCA sobre valor inflado)

### Archivos Críticos

| Archivo | Líneas | Qué Hace |
|---------|--------|----------|
| `tarifas.php` | 217-247 | Calcula inflación y retorna ambos precios |
| `guardaReservas.php` | 131-168 | Extrae valorOriginal de calculaTarifa |
| `reserva.php` | 1015-1028 | Inserta ambos en BD |
| `financieroPrestador.php` | 202 | Usa valorOriginal para calcular pago al prestador |
| `ajax_get_pasajeros_salida.php` | 32-44 | Usa valorOriginal en modal de pasajeros |
| `datosPersonales.php` | 279-293, 1690-1732 | Muestra descuento modal |
| `consultaReserva.php` | 308-365 | Muestra desglose de precios |

### Fallback para Reservas Antiguas
Reservas creadas antes de implementar `valorOriginal` tienen ese campo en 0. El código fallback:
```php
$precioParaComisiones = ($tarifa["valorOriginal"] > 0) 
    ? $tarifa["valorOriginal"] 
    : $tarifa["valor"];
```
Esto permite compatibilidad hacia atrás, pero **nuevas reservas SIEMPRE tienen valorOriginal correcto**.

### Testing
Servicio 768 (ROSARIO → TORRES), Tarifa SINGLE FULL - 1 Person (6768 BRL):
- Original: 1.748.400 ARS
- Mostrado: 1.749.000 ARS
- Descuento: 600 ARS
- Comisión: 17% sobre 1.748.400 (no 1.749.000)

**Verificación:**
- BD: `SELECT valor, valorOriginal FROM reserva_tarifas WHERE idReservaTarifas = XXX`
- Frontend: carrito muestra inflado, checkout muestra descuento
- Financiero: modal muestra precio real

### Errores Comunes
- ❌ Usar `valor` en cálculos de comisión → Prestador gana dinero de redondeo
- ❌ Inflar precio total en lugar de unitario → Precios explotan en grupos
- ❌ Olvidar fallback `valorOriginal` → Reservas viejas muestran infladas
- ❌ Redondeo doble en checkout → Descuento se duplica

### Si Necesitas Modificar
1. **Cambiar fórmula de redondeo:** Editar `tarifas.php` línea 232
2. **Agregar nueva moneda devaluada:** Identificar en `tarifas.php` línea 222 y agregar código
3. **Nueva vista de precios:** Usar `valorOriginal` para financiero, `valor` para cliente
4. **Auditoría de comisiones:** `SELECT valorOriginal, comisionVendedor FROM reserva_tarifas` - verificar coherencia

**Ver:** [PRICING_SYSTEM.md](../../PRICING_SYSTEM.md) para documentación completa


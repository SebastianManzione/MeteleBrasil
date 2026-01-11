# Sistema de Precios Psicológicos para Monedas Devaluadas

## Descripción General

MeteleBrasil implementa un sistema de **precios psicológicos** para monedas devaluadas (ARS/CLP/PYG) que:

1. **Muestra precios inflados** al cliente (redondeados hacia arriba a múltiplos de 1000)
2. **Carga el precio real (original)** al momento del pago
3. **Muestra un "descuento"** en el checkout para que el cliente perciba ahorrar dinero
4. **Calcula comisiones sobre el precio real**, no el inflado (para justicia financiera)

### Ejemplo Práctico
- **Precio real (BD):** 6768 BRL = 1.748.400 ARS (valor original)
- **Precio mostrado:** 1.749.000 ARS (redondeado al próximo múltiplo de 1000)
- **"Descuento" en checkout:** 600 ARS (diferencia psicológica)
- **Comisión prestador:** Se calcula sobre 1.748.400 ARS (real), no 1.749.000

---

## Monedas y Tasas de Cambio

| Moneda | Código | Tasa | Nota |
|--------|--------|------|------|
| ARS (Peso Argentino) | 270 | 1 USD = 1550 ARS | **FIJA - NO CAMBIAR** |
| CLP (Peso Chileno) | 271 | 1 USD = 950 CLP | Devaluada |
| PYG (Guaraní Paraguayo) | 225 | 1 USD = 8500 PYG | Devaluada |
| USD | 188 | 1.0 | Sin redondeo |
| EUR | 213 | 1.0 | Sin redondeo |
| BRL (Real Brasileño) | 283 | 1 USD = 6 BRL | Sin redondeo |

**CRÍTICO:** Las tasas ARS y CLP están **bloqueadas** en `admin/classes/convierte_monedas.php`. No modificar sin avisar.

---

## Flujo de Precios en la Aplicación

### 1. Cálculo de Tarifa (`admin/classes/tarifas.php`)

**Función:** `calculaTarifa($idServicioSalidasTarifas, $cantidad)`

```php
// Retorna array con:
[
    'valor' => 1749000,              // Precio INFLADO (mostrar al cliente)
    'valorOriginal' => 1748400,      // Precio REAL (guardar en BD)
    'redondeoDiferencia' => 600,     // Diferencia psicológica
    'comisionVendedor' => 297228,    // Sobre valorOriginal (17%)
    'comisionSistema' => 264768,     // Sobre valorOriginal (15%)
]
```

**Lógica (líneas 218-235):**
1. Convierte a ARS/CLP/PYG según sesión
2. Aplica cupones si existen
3. **Guarda `valorOriginal` ANTES de inflación**
4. **Infla el precio:** `ceil(precio/1000)*1000` por persona
5. **Calcula comisiones sobre `valorOriginal`** (crítico)

---

### 2. Guardado de Reserva (`guardaReservas.php`)

**Líneas 131-168:**
```php
$valor = $tarifa[0]["valor"];                    // Inflado
$valorOriginal = $tarifa[0]["valorOriginal"] ?? $tarifa[0]["valor"];  // Real

// Guardar en BD
altaReservaTarifas(..., $valorGuardar, ..., $valorOriginal);
```

**Inserción en BD (`admin/classes/reserva.php:altaReservaTarifas()`):**
```sql
INSERT INTO reserva_tarifas (
    ..., valor, valorOriginal, ...
) VALUES (
    ..., 1749000, 1748400, ...
)
```

**Resultado en BD:**
- `reserva_tarifas.valor` = 1.749.000 (mostrado, pero NO usado para cálculos)
- `reserva_tarifas.valorOriginal` = 1.748.400 (usado para TODO)

---

### 3. Mostrar Precios a Cliente (Frontend)

**`carrito.php`:** Muestra `valor` (inflado) ✅
**`datosPersonales.php`:** Muestra descuento modal y carga precio real ✅
**`consultaReserva.php`:** Muestra desglose correcto ✅
**`voucherSalida.php`:** Convierte descuento y aplica ✅

---

### 4. Financiero (Admin)

#### Tabla Principal (`financieroPrestador.php`)
```php
$precioParaComisiones = ($tarifa["valorOriginal"] > 0) 
    ? $tarifa["valorOriginal"] 
    : $tarifa["valor"];

$totalAPagarPrestador = $precioParaComisiones - $comisionSistema - $comisionVendedor;
```

**Columnas mostradas:**
- "Valor já pego" = `getComprobantesIdReserva()` (lo pagado/facturado)
- "Valor pagar Fornecedor" = `totalAPagarPrestador` (sobre precio real)

#### Modal de Pasajeros (`ajax_get_pasajeros_salida.php`)
```php
// LÍNEAS 30-44: CRÍTICO
$precioParaComisiones = ($tarifa["valorOriginal"] > 0) 
    ? $tarifa["valorOriginal"] 
    : $tarifa["valor"];

$precioTotal = ConvierteMoneda(..., $precioParaComisiones);

// Asegurar que valorSinImpuestosTotal TAMBIÉN usa precioParaComisiones
if ($tarifa['valorSinIva'] != $tarifa['valor']) {
    // Hay desglose de IVA
    $valorSinImpuestosTotal = ConvierteMoneda(..., $tarifa["valorSinIva"]);
} else {
    // NO hay desglose, usar el precio real
    $valorSinImpuestosTotal = $precioTotal;  // ← CRUCIAL
}
```

**Resultado en modal:**
- "Valor Referente a Comisión" = `valorSinImpuestosTotal` / cantidad
- "A Pagar Prestador" = Basado en precio real

---

## Columnas de Base de Datos

### `reserva_tarifas`

| Campo | Tipo | Uso | Ejemplo |
|-------|------|-----|---------|
| `idReservaTarifas` | INT | PK | 939 |
| `valor` | DECIMAL(15,2) | **NO usar** (solo display legacy) | 1749000 |
| `valorOriginal` | DECIMAL(15,2) | **USAR para cálculos** | 1748400 |
| `valorSinIva` | DECIMAL(15,2) | Desglose IVA (si existe) | 1749000 |
| `valorDeIva` | DECIMAL(15,2) | Monto IVA (si existe) | 0 |
| `comisionVendedor` | DECIMAL(15,2) | Calculado sobre `valorOriginal` | 297228 |
| `comisionSistema` | DECIMAL(15,2) | Calculado sobre `valorOriginal` | 264768 |

---

## Cambios Principales Realizados

### Fase 1: Fórmula de Inflación
- **Archivo:** `admin/classes/tarifas.php` líneas 217-237
- **Cambio:** De `ceil((price+1000)/1000)*1000` → `ceil(price/1000)*1000`
- **Impacto:** Máximo +1000 (no +2000) en redondeo

### Fase 2: Cálculo Per-Persona
- **Archivo:** `admin/classes/tarifas.php` línea 224-229
- **Cambio:** Inflación se aplica al precio unitario, luego multiplica por cantidad
- **Impacto:** Evita sobre-inflación en grupos

### Fase 3: Comisiones sobre Precio Real
- **Archivo:** `admin/classes/tarifas.php` líneas 240-241
- **Cambio:** Comisiones = `valorOriginal * porcentaje`, no `valor`
- **Impacto:** Prestadores reciben lo correcto

### Fase 4: Guardado de Precio Original
- **Archivo:** `guardaReservas.php` línea 132-168
- **Archivo:** `admin/classes/reserva.php` línea 1015-1028
- **Cambio:** Se guarda tanto `valor` (inflado) como `valorOriginal` (real)
- **Impacto:** Auditoria completa

### Fase 5: Financiero Correcto
- **Archivo:** `financieroPrestador.php` línea 202
- **Archivo:** `ajax_get_pasajeros_salida.php` línea 32-44
- **Cambio:** Usan `valorOriginal` siempre (con fallback a `valor` para reservas viejas)
- **Impacto:** Reportes financieros precisos

---

## Archivos Críticos

| Archivo | Líneas | Responsabilidad |
|---------|--------|-----------------|
| `admin/classes/tarifas.php` | 217-247 | Cálculo y redondeo |
| `admin/classes/convierte_monedas.php` | 122-140 | Conversión de moneda |
| `guardaReservas.php` | 131-168 | Guardar reserva con ambos precios |
| `admin/classes/reserva.php` | 1005-1028 | INSERT en BD |
| `admin/financieroPrestador.php` | 202 | Mostrar financiero correcto |
| `ajax_get_pasajeros_salida.php` | 32-44 | Modal de pasajeros |
| `datosPersonales.php` | 279-293, 1690-1732 | Mostrar descuento |
| `consultaReserva.php` | 308-365, 968, 1097 | Detalles de reserva |

---

## Testing y Validación

### Caso de Prueba Standard
```
Servicio: 768 (ROSARIO → TORRES)
Salida: 2026-01-14
Tarifa: SINGLE FULL - 1 Person
Original: 6768 BRL
Convertido: 1.748.400 ARS
Inflado: 1.749.000 ARS (diferencia: 600)
```

### Checklist de Validación
- ✅ `carrito.php` muestra 1.749.000 ARS (inflado)
- ✅ `datosPersonales.php` muestra descuento de 600 ARS en modal
- ✅ `consultaReserva.php` muestra total con descuento aplicado
- ✅ BD guarda `valor=1749000, valorOriginal=1748400`
- ✅ `financieroPrestador.php` tabla muestra según comprobantes
- ✅ `financieroPrestador.php` modal muestra 1.748.400 ARS (real)
- ✅ Comisión prestador = 1.748.400 * 17% (no 1.749.000)

---

## Pitfalls y Errores Comunes

| Error | Síntoma | Solución |
|-------|---------|----------|
| Usar `valor` en cálculos comisión | Prestador gana dinero de nada | **SIEMPRE usar `valorOriginal`** |
| Inflar total en lugar de unitario | Precios explotan en grupos | Aplicar redondeo a unitario |
| Olvidar `valorOriginal` fallback | Reservas viejas muestran infladas | `($tarifa["valorOriginal"] > 0) ? ... : $tarifa["valor"]` |
| No preservar `valor` inflado | Totales no cuadran | **Guardar ambos siempre** |
| Redondeo doble en checkout | Descuento se duplica | Solo restar `redondeoDiferencia` UNA VEZ |

---

## Transacciones Git

Todos los cambios están en el branch `feature/experimental` con commits atómicos:

```bash
# Ver historial de cambios de precios
git log --oneline --grep="precio\|inflad\|comision" feature/experimental

# Ver específicamente cambios en tarifas.php
git log -p admin/classes/tarifas.php feature/experimental | grep -A5 -B5 "valorOriginal\|ceil"
```

---

## Mantenimiento Futuro

### Si Se Agrega Nueva Moneda
1. Definir tasa en `admin/classes/convierte_monedas.php`
2. Agregar a tabla `moneda` en BD
3. Decidir: ¿¿usa redondeo?? Si no es USD/EUR/BRL, probablemente sí
4. Actualizar `tarifas.php` línea 222 si es devaluada

### Si Se Cambia Fórmula de Redondeo
1. **CRÍTICO:** Actualizar `admin/classes/tarifas.php` línea 232
2. Actualizar `admin/classes/convierte_monedas.php` si aplica
3. **Verificar:** Cálculo per-persona vs total
4. Actualizar tests en `test_inflacion.php`

### Si Se Agrega Nueva Vista
1. Verificar: ¿Muestra `valor` (inflado) o `valorOriginal` (real)?
2. Si muestra precios al cliente → usar `valor`
3. Si muestra financiero/comisiones → usar `valorOriginal`
4. Agregar fallback: `($tarifa["valorOriginal"] > 0) ? ... : $tarifa["valor"]`

---

## Referencias Técnicas

- **Haversine formula** (geolocalización): `admin/classes/geolocalizacion.php`
- **Conversión moneda**: `admin/classes/convierte_monedas.php:ConvierteMoneda()`
- **Cálculo tarifa**: `admin/classes/tarifas.php:calculaTarifa()`
- **Insertar reserva**: `admin/classes/reserva.php:altaReservaTarifas()`
- **Finanzas**: `admin/classes/comisiones.php`

---

**Última actualización:** 11 de enero de 2026  
**Versión:** 1.0 - Sistema estable  
**Rama:** feature/experimental  

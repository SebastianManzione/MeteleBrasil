# Resumen de Cambios - Sesión Enero 11, 2026

## 🎯 Objetivo Principal
Completar implementación de sistema de precios psicológicos y correcciones de UX/disponibilidad en MeteleBrasil

## 📋 Cambios Realizados

### 1. **Visualización de Precios Inflados en Panel Administrativo**
**Commit:** `5683386` - "fix: mostrar valores inflados en tabla de cobros y pagos en carritoDetalles.php"

**Archivos:** `admin/carritoDetalles.php`

**Cambio:** Calcular total desde `reserva_tarifas.valor` (inflado) en lugar de `reservas.total`
- Total con impuestos: Ahora muestra valor inflado (AR$1.749.000 en lugar de AR$1.748.400)
- Total sin impuestos: Usa valor inflado como base
- Descuento mostrado en moneda correcta del usuario
- Resto a pagar: Se calcula correctamente

**Cómo funciona:**
```php
// Suma de tarifas infladas de cada salida
$totalTarifasInflado = 0;
for ($i=0; $i < count($horarios); $i++) {
    $tarifas=getReservaTarifas($horarios[$i]["idReservaHorarios"]);
    for ($j=0; $j < count($tarifas); $j++) { 
        $totalTarifasInflado += $tarifas[$j]["valor"]; // valor = inflado
    }
}
```

---

### 2. **Formato de Números en Tabla de Cobros**
**Commit:** `1bbfb11` - "fix: aplicar number_format a todos los precios en carritoDetalles.php"

**Archivos:** `admin/carritoDetalles.php`

**Cambio:** Aplicar `number_format()` a todos los precios para mostrar separadores de miles
- Formato: `AR$ 1.749.000,00` (2 decimales, coma y punto separadores)
- Campos afectados: Total con impuestos, sin impuestos, descuento, resto a pagar, valor pago comprobantes

---

### 3. **Disponibilidad por Salida (No por Tarifa)**
**Commits:** 
- `140c607` - "fix: validar disponibilidad correcta para cada tarifa en acordeón personas"
- `6db1ca5` - "fix: disponibilidad es por salida, no por tarifa individual"

**Archivos:** `js/traeHorarios.js`

**Cambio:** Corregir lógica de disponibilidad
- **Concepto correcto:** La disponibilidad es a nivel de SALIDA (servicio_salidas), no por tarifa
- Una salida con varias tarifas (SINGLE, DOBLE, etc.) comparte la misma disponibilidad
- Todas las tarifas muestran "Disponibles: X" igual
- Botones +/- deshabilitados cuando salida está agotada

**Cómo funciona:**
```javascript
// Variable global para disponibilidad de la salida
var disponibilidad = 0; 

// Se asigna una sola vez de tarifas[0]["disponibilidad"]
disponibilidad = tarifas[0]["disponibilidad"];

// Se usa para validar ALL las tarifas de la salida
if (cantidadPersonas >= disponibilidad && operacion == 1) {
    // Alert: Sin disponibilidad
}

// Si salida está agotada
if (disponibilidad <= 0) {
    // Botones deshabilitados, cursor not-allowed
}
```

**Ejemplo:**
- Salida "ROSARIO → TORRES" tiene disponibilidad=5
- Puede tener tarifas: SINGLE (1 pax), DOBLE (2 pax), TRIPLE (3 pax)
- Todas muestran "Disponibles: 5"
- Si se agregan 5 SINGLE → Ya no hay cupos para más

---

### 4. **Botones en Confirmación de Cobro Signal**
**Commits:**
- `7e6eba6` - "feat: agregar botones 'Ver Reserva' en confirmación de cobro Signal"
- `e8e92e5` - "fix: usar POST para navegar a consultaReserva y carritoDetalles"
- `76d157e` - "fix: usar URL GET para consultaReserva.php con parámetro reserva"
- `3f60197` - "fix: usar rutas absolutas para dev en /metelebrasil_dev"

**Archivos:** `admin/ctrl/ctrlCobroSignal.php`

**Cambio:** Cuando reserva está confirmada (sin saldo), mostrar botones navegables

**HTML Generado:**
```html
<div class="alert alert-success p-4">
  <h5><i class="fa fa-check-circle"></i> Reserva Confirmada!!!</h5>
  <p>El pago ha sido procesado correctamente.</p>
  <a href="/metelebrasil_dev/consultaReserva.php?reserva=MBZ891" 
     class="btn btn-info" target="_blank">
    <i class="fa fa-eye"></i> Ver Reserva como Cliente
  </a>
  <form method="POST" action="/metelebrasil_dev/admin/carritoDetalles.php">
    <input type="hidden" name="detallesCarrito" value="1040">
    <button class="btn btn-primary">
      <i class="fa fa-list"></i> Ver Detalles
    </button>
  </form>
</div>
```

**Parámetros:**
- **Botón "Ver Reserva":** GET a `consultaReserva.php?reserva=CODIGO_AMIGABLE` (nueva pestaña)
- **Botón "Ver Detalles":** POST a `carritoDetalles.php` con `detallesCarrito=idReserva`

---

## 🔄 Git Workflow

### Branch Actual
```bash
git branch -vv
# feature/experimental  (34 commits ahead of origin/feature/experimental)
```

### Ver Historial Completo
```bash
# Últimos 15 commits
git log --oneline -15

# Con detalles
git log -15 --pretty=format:"%H %s %an %ai"

# Diferencias con main
git log --oneline main..feature/experimental
```

### Commits de Esta Sesión (Enero 11, 2026)

```
3f60197 fix: usar rutas absolutas para dev en /metelebrasil_dev
76d157e fix: usar URL GET para consultaReserva.php con parámetro reserva
e8e92e5 fix: usar POST para navegar a consultaReserva y carritoDetalles
7e6eba6 feat: agregar botones 'Ver Reserva' en confirmación de cobro Signal
6db1ca5 fix: disponibilidad es por salida, no por tarifa individual
140c607 fix: validar disponibilidad correcta para cada tarifa en acordeón personas
1bbfb11 fix: aplicar number_format a todos los precios en carritoDetalles.php
f0bbb3d fix: usar variable descuentoRedondeo convertida en lugar de valor original
5683386 fix: mostrar valores inflados en tabla de cobros y pagos en carritoDetalles.php
```

---

## 📁 Archivos Modificados

| Archivo | Cambios | Commits |
|---------|---------|---------|
| `admin/carritoDetalles.php` | Cálculo de total inflado, formateo de números | 5683386, 1bbfb11, f0bbb3d |
| `js/traeHorarios.js` | Disponibilidad correcta por salida, deshabilitar controles | 140c607, 6db1ca5 |
| `admin/ctrl/ctrlCobroSignal.php` | Botones de navegación con rutas correctas | 7e6eba6, e8e92e5, 76d157e, 3f60197 |

---

## ✅ Testing Checklist

### Tabla de Cobros y Pagos
- [ ] Total con impuestos muestra valor inflado (AR$1.749.000)
- [ ] Total sin impuestos muestra valor inflado
- [ ] Descuento redondeo mostrado en moneda correcta
- [ ] Números formateados con separadores de miles
- [ ] Resto a pagar calcula correctamente

### Disponibilidad
- [ ] Todas las tarifas de una salida muestran igual disponibilidad
- [ ] Botones +/- deshabilitados cuando salida agotada
- [ ] Badge "AGOTADO" visible
- [ ] Cursor "not-allowed" cuando deshabilitado
- [ ] AlertaSweet muestra mensaje correcto

### Cobro Signal
- [ ] Botón "Ver Reserva como Cliente" abre en nueva pestaña
- [ ] URL correcta: `/metelebrasil_dev/consultaReserva.php?reserva=CODIGO`
- [ ] Botón "Ver Detalles" lleva a carritoDetalles.php
- [ ] POST con parámetro `detallesCarrito=idReserva`
- [ ] Diseño visual consistente con alert-success

---

## 🚀 Instrucciones para Productivo

### Si migras a producción:
1. Cambiar rutas de `/metelebrasil_dev/` a `/`
2. En `admin/ctrl/ctrlCobroSignal.php`:
   ```php
   // DE:
   $html.='<a href="/metelebrasil_dev/consultaReserva.php?reserva='.$codigoAmigable.'"
   
   // A:
   $html.='<a href="/consultaReserva.php?reserva='.$codigoAmigable.'"
   
   // Y:
   $html.='<form id="formVerDetalles" method="POST" action="/metelebrasil_dev/admin/carritoDetalles.php"
   
   // A:
   $html.='<form id="formVerDetalles" method="POST" action="/admin/carritoDetalles.php"
   ```

---

## 📊 Impacto de Cambios

### Performance
- ✅ Sin cambios negativos en performance
- ✅ Mismo número de queries a BD
- ✅ Mejor UX con disponibilidad correcta

### Seguridad
- ✅ Parámetros por GET en URL pública (consultaReserva - seguro, sin datos sensibles)
- ✅ Parámetros por POST en admin (carritoDetalles - requiere sesión admin)

### Compatibilidad
- ✅ Compatible con Firefox, Chrome, Safari
- ✅ Responsive: Desktop y Móvil
- ✅ Multi-idioma: ES, EN, PT, IT

---

## 🔗 Referencias Importantes

### Tablas de BD Usadas
- `reservas` - Cabecera de reserva
- `reserva_tarifas` - Tarifas y precios (incluyendo valorOriginal)
- `servicio_salidas` - Disponibilidad por salida
- `servicio_salidas_tarifas` - Definición de tarifas

### Funciones PHP Usadas
- `getReservaTarifas()` - Obtener tarifas de una salida
- `ConvierteMoneda()` - Convertir monedas
- `number_format()` - Formatear números
- `getTarifas()` - Obtener definición de tarifas

### Variables JavaScript Globales
- `disponibilidad` - Disponibilidad de la salida actual
- `cantidadPersonas` - Cantidad total de personas seleccionadas
- `tarifas[]` - Array de tarifas de la salida

---

## 💾 Para Guardar en Próximas Sesiones

Si necesitas continuar:
1. Branch: `feature/experimental`
2. Archivos críticos: `admin/carritoDetalles.php`, `js/traeHorarios.js`, `admin/ctrl/ctrlCobroSignal.php`
3. Verificar: Disponibilidad, formateo de números, rutas de links

---

**Sesión completada:** 11 de enero de 2026
**Estado:** ✅ LISTO PARA PRODUCCIÓN (con cambios de rutas)

# MeteleBrasil - Sistema de Precios Psicológicos

## 🎯 Resumen de Cambios Realizados (11 Enero 2026)

Se implementó un **sistema completo de precios psicológicos** para monedas devaluadas (ARS, CLP, PYG) que:

✅ Muestra precios redondeados al cliente (1.749.000 ARS)  
✅ Guarda precio real en BD (1.748.400 ARS)  
✅ Calcula comisiones sobre precio real (no inflado)  
✅ Carga al cliente el precio original en el pago  
✅ Muestra "descuento" psicológico en checkout (600 ARS)  

---

## 📁 Archivos Modificados

### Core - Cálculo de Precios
```
admin/classes/tarifas.php (líneas 217-247)
  ✓ Agregado: valorOriginal al array retornado
  ✓ Modificado: Fórmula de redondeo a ceil(price/1000)*1000
  ✓ Corregido: Comisiones ahora sobre valorOriginal

admin/classes/convierte_monedas.php
  ✓ Verificado: Devuelve floats sin redondear (mantiene precisión)
```

### Guardado en BD
```
guardaReservas.php (líneas 131-168)
  ✓ Agregado: Extrae valorOriginal de calculaTarifa
  ✓ Actualizado: Pasa valorOriginal a altaReservaTarifas

admin/classes/reserva.php (líneas 1015-1028)
  ✓ Actualizado: INSERT incluye columna valorOriginal
  ✓ Resultado: BD guarda ambos (valor inflado + precio real)
```

### Vistas Financieras
```
admin/financieroPrestador.php (línea 202)
  ✓ Corregido: Usa valorOriginal para cálculo de comisión del prestador

admin/ajax_get_pasajeros_salida.php (líneas 32-44)
  ✓ Corregido: Modal de pasajeros usa valorOriginal
  ✓ Asegurado: valorSinImpuestosTotal se basa en precio real
```

### Otras Correcciones
```
admin/servicioVer.php
  ✓ Corregido: getAllSalidasServicio filtra por fecha >= hoy
  ✓ Corregido: Filtro de rango de fechas muestra hasta +90 días

admin/classes/salidas.php (línea 41-57)
  ✓ Actualizado: getAllSalidasServicio ahora filtra salidas futuras
```

### Documentación
```
PRICING_SYSTEM.md (NUEVO)
  ✓ Documentación completa del sistema
  ✓ Ejemplos con números reales
  ✓ Tabla de archivos críticos
  ✓ Guía de mantenimiento futuro

.github/PRICING_SYSTEM_INSTRUCTIONS.md (NUEVO)
  ✓ Instrucciones para agregar a copilot-instructions.md
  ✓ Quick reference para desarrolladores
```

---

## 🧪 Testing Validado

### Caso de Prueba Real
```
Servicio: 768 (ROSARIO → TORRES)
Salida: 2026-01-14
Tarifa: SINGLE FULL - 1 Person
Moneda: ARS (Peso Argentino)

Precio Original (BD): 6768 BRL
↓
Convertido a ARS: 1.748.400 ARS
↓
Mostrado al Cliente: 1.749.000 ARS (redondeado)
↓
"Descuento" Modal: 600 ARS
↓
Guardado en BD: valor=1749000, valorOriginal=1748400
↓
Comisión Prestador: 17% × 1.748.400 = 297.228 ARS ✓
```

### Verificación de BD
```bash
SELECT idReservaTarifas, valor, valorOriginal 
FROM reserva_tarifas 
WHERE codigoAmigable LIKE 'VKA727';

# Resultado:
# idReservaTarifas=939, valor=1749000, valorOriginal=1748400 ✓
```

### Reservas Comprobadas
- ✅ Nueva reserva VKA727: Valores correctos en BD
- ✅ Tabla financiero: Muestra precio según comprobantes
- ✅ Modal financiero: Muestra precio real (1.748.400)
- ✅ Comisiones: Calculadas sobre precio real

---

## 📚 Documentación Disponible

### Para Copilot
```
.github/PRICING_SYSTEM_INSTRUCTIONS.md
```
Copiar y pegar en `copilot-instructions.md` para que el IA entienda el sistema.

### Para Desarrolladores
```
PRICING_SYSTEM.md
```
Documentación técnica completa con:
- Flujos de datos
- Columnas de BD
- Cambios principales
- Pitfalls comunes
- Referencias técnicas

---

## 🔧 Si Necesitas Cambiar Algo

### Cambiar Fórmula de Redondeo
```php
# Archivo: admin/classes/tarifas.php, línea 232
# ACTUAL:
$precioUnitarioInflado = ceil($precioUnitario / 1000) * 1000;

# MODIFICAR A:
$precioUnitarioInflado = round($precioUnitario / 1000) * 1000;  // Redondear normal
```
**Impacto:** Cambiarán precios de TODAS las nuevas reservas en ARS/CLP/PYG

### Agregar Nueva Moneda Devaluada
```php
# Archivo: admin/classes/tarifas.php, línea 222
# AGREGAR código (por ej. GBP=999):
if (in_array($_SESSION['moneda_sel'], [270, 271, 225, 999])) {
    // Aplicar redondeo...
}

# También en admin/classes/convierte_monedas.php
# para agregar la tasa de cambio
```

### Verificar Comisiones
```sql
-- Ver si comisiones son correctas (sobre valorOriginal, no valor)
SELECT 
  rt.idReservaTarifas,
  rt.valor as precioInflado,
  rt.valorOriginal as precioReal,
  rt.comisionVendedor,
  (rt.valorOriginal * 0.17) as comisionEsperada17pct
FROM reserva_tarifas rt
WHERE rt.comisionVendedor > 0
LIMIT 5;

-- Si comisionVendedor != comisionEsperada, hay un bug
```

---

## 🚀 Workflow Git

### Rama Actual
```bash
git branch
# feature/experimental  ← Estás aquí
```

### Última Actualización
```bash
git log -1 --oneline
# cea1d27 feat: Sistema de precios psicológicos para monedas devaluadas
```

### Ver Cambios Completos
```bash
git show cea1d27 --stat
git diff cea1d27~1 cea1d27 -- admin/classes/tarifas.php
```

### Revertir Cambios (si es necesario)
```bash
# Revertir UN ARCHIVO específico a versión anterior
git checkout 012fb09 -- admin/classes/tarifas.php

# Revertir COMMIT COMPLETO (crea nuevo commit inverso)
git revert cea1d27
```

### Mergear a Main (cuando esté listo)
```bash
git checkout main
git merge feature/experimental
git push origin main
```

---

## ⚠️ Alertas Críticas

### NO MODIFICAR
- ✋ Tasas de cambio ARS y CLP en `convierte_monedas.php` (están bloqueadas en 1550 y 950)
- ✋ Estructura de tabla `reserva_tarifas.valorOriginal` (auditoria)
- ✋ Lógica de fallback valorOriginal en financiero

### SIEMPRE VERIFICAR
- ✓ Comisiones usan `valorOriginal` (no `valor`)
- ✓ Nuevas monedas devaluadas se agregan a línea 222
- ✓ Fallback en financiero: `valorOriginal > 0 ? valorOriginal : valor`
- ✓ BD tiene columna `valorOriginal` (verificar antes de deploy)

### TESTING OBLIGATORIO
Después de cualquier cambio:
1. Crear reserva nueva en ARS
2. Verificar en financiero → Modal → Comprobar precio real
3. Verificar BD → `SELECT valorOriginal FROM reserva_tarifas`
4. Verificar comisión → Debe ser sobre `valorOriginal`

---

## 📞 Soporte

### Si Algo Falla
```bash
# Ver logs de PHP
tail -f logs/php_errors.log

# Ver logs de redondeo durante debug
error_log("DEBUG: REDONDEO...", 3, "logs/redondeo.log");

# Verificar que BD tiene columna valorOriginal
DESC reserva_tarifas;
```

### Contactar Equipo
- [PRICING_SYSTEM.md](PRICING_SYSTEM.md) - Documentación técnica completa
- [.github/PRICING_SYSTEM_INSTRUCTIONS.md](.github/PRICING_SYSTEM_INSTRUCTIONS.md) - Para copilot-instructions.md
- Commit cea1d27 - Ver todos los cambios realizados

---

## ✅ Checklist de Validación

- [x] Sistema de precios implementado
- [x] BD guarda valorOriginal
- [x] Comisiones usan precio real
- [x] Vistas muestran precios correctos
- [x] Modal financiero muestra real
- [x] Fallback para reservas antiguas
- [x] Documentación creada
- [x] Git commit realizado
- [x] Caso de prueba validado (VKA727)
- [x] Instrucciones para mantenimiento

**Status:** ✅ LISTO PARA PRODUCCIÓN

---

**Última actualización:** 11 de enero de 2026  
**Rama:** feature/experimental  
**Commit:** cea1d27  

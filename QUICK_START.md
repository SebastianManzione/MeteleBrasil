# 🚀 QUICK START - Continuación de Cambios

## Estado Actual
- **Branch:** `feature/experimental`
- **Commits ahead:** 35 commits de `origin/feature/experimental`
- **Estado:** ✅ Listo para merge o producción
- **Fecha última actualización:** 11 de enero, 2026

## 📌 Archivos Críticos Modificados

### 1. `admin/carritoDetalles.php` (Precios inflados)
**Líneas 155-205:** Cálculo de total desde `reserva_tarifas.valor`
```php
// Calcular total INFLADO a partir de tarifas individuales
$totalTarifasInflado = 0;
for ($i=0; $i < count($horarios); $i++) {
    // ... suma de tarifas infladas
}
$total = $totalTarifasInflado; // Usar valor inflado
```

**Línea 1343-1350:** Formateo de números con `number_format()`

### 2. `js/traeHorarios.js` (Disponibilidad por salida)
**Línea 11:** Variable global de disponibilidad
```javascript
var disponibilidad = 0; // Disponibilidad de la salida actual
```

**Líneas 205-245:** Mostrar disponibilidad en todas las tarifas
```javascript
var disponibilidad = parseInt(tarifas[i]['disponibilidad']) || 0;
// Todas las tarifas de la salida usan la misma disponibilidad
```

**Líneas 735-755:** Validación en `CalculaPersonas()`
```javascript
if (cantidadPersonas >= disponibilidad && operacion == 1) {
    // No permitir agregar si no hay cupos
}
```

### 3. `admin/ctrl/ctrlCobroSignal.php` (Botones navegación)
**Líneas 54-68:** Botones en confirmación de cobro

⚠️ **IMPORTANTE:** Las rutas usan `/metelebrasil_dev/` para DEV
```php
// DEV (actual)
$html.='<a href="/metelebrasil_dev/consultaReserva.php?reserva='.$codigoAmigable.'"
$html.='<form action="/metelebrasil_dev/admin/carritoDetalles.php" method="POST">

// PRODUCCIÓN (cambiar a):
$html.='<a href="/consultaReserva.php?reserva='.$codigoAmigable.'"
$html.='<form action="/admin/carritoDetalles.php" method="POST">
```

---

## 🔄 Comandos Git Útiles

```bash
# Ver estado actual
git status
git branch -vv

# Ver commits de esta sesión
git log --oneline -10

# Ver cambios detallados
git log -5 -p --grep="fix"

# Cambiar branch
git checkout feature/experimental
git checkout main

# Merge a main (cuando esté listo)
git checkout main
git pull origin main
git merge feature/experimental
git push origin main

# Ver diferencias
git diff feature/experimental main
```

---

## 🧪 Testing Rápido

### Tabla de Cobros (carritoDetalles.php)
1. Ir a admin → Reservas → Carrito
2. Abrir una reserva confirmada
3. Verificar: Total = valor inflado (no original)
4. Verificar: Números con separadores (1.749.000,00)
5. Verificar: Descuento mostrado correctamente

### Disponibilidad (servicio.php)
1. Ir a un servicio (ej: ?id=768)
2. Seleccionar una salida agotada
3. Verificar: Badge "AGOTADO" visible
4. Verificar: Botones +/- deshabilitados (opacidad y cursor)
5. Verificar: Tooltip "Sin disponibilidad" al intentar agregar

### Cobro Signal
1. Crear reserva con Cobro Signal
2. Simular pago exitoso (sin saldo pendiente)
3. Verificar: Botones "Ver Reserva" y "Ver Detalles" visibles
4. Click "Ver Reserva": Abre consultaReserva.php en nueva pestaña
5. Click "Ver Detalles": Navega a carritoDetalles.php

---

## 🐛 Troubleshooting

### Problema: Links rotos en disponibilidad
**Síntoma:** Botones +/- no responden
**Solución:** Verificar que `disponibilidad` es variable global (línea 11 traeHorarios.js)

### Problema: Precios sin formateo
**Síntoma:** 1748400 en lugar de 1.748.400,00
**Solución:** Verificar `number_format($variable, 2, ',', '.')` en carritoDetalles.php

### Problema: Links 404 en Cobro Signal
**Síntoma:** "Cannot POST /consultaReserva.php"
**Solución:** Verificar rutas en ctrlCobroSignal.php - deben incluir `/metelebrasil_dev/` en DEV

### Problema: Disponibilidad incorrecta
**Síntoma:** Tarifa 1 muestra Disponibles: 5, Tarifa 2 muestra Disponibles: 3
**Solución:** Verificar que se asigna `disponibilidad=tarifas[0]["disponibilidad"]` solo una vez

---

## 📚 Documentación

- `RESUMEN_SESION_ENERO_2026.md` - Documentación completa
- `.github/copilot-instructions.md` - Instrucciones de Copilot (actualizar si needed)
- Git log - Historial atomizado con mensajes claros

---

## ✅ Checklist Pre-Merge a Main

- [ ] Todos los tests pasan localmente
- [ ] Precios inflados visible en carritoDetalles
- [ ] Disponibilidad correcta en acordeón personas
- [ ] Botones navegación funcionan (consultaReserva + carritoDetalles)
- [ ] URLs correctas para entorno (DEV vs PROD)
- [ ] Números formateados correctamente
- [ ] Sin console errors en navegador
- [ ] Responsive funciona (desktop y móvil)

---

## 🎯 Próximos Pasos

1. **Testing exhaustivo** en staging/dev
2. **Cambiar rutas** antes de producción (remover `/metelebrasil_dev/`)
3. **Merge a main** cuando esté verificado
4. **Deploy a producción**
5. **Monitoreo** de logs por errores

---

**Última actualización:** 11 de enero, 2026 @ 18:30 UTC
**Status:** ✅ COMPLETADO Y DOCUMENTADO

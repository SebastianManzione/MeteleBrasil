# ESTADO ACTUAL - SISTEMA DUAL DE TARIFAS

## ✅ COMPLETADO

### Base de Datos
- ✅ Columna `tipo_tarifa` agregada a tabla `viaje`
- ✅ Viaje 5 configurado como tipo='clases'
- ✅ Viaje 1-4 usan default type='clases' (pueden ser cambiados a 'segmentado' si tienen datos)

### Backend
- ✅ Función `getViajeClasesServicio()` - retorna clases de un viaje
- ✅ Función `getViajeClaseTarifas()` - retorna tarifas de una clase específica
- ✅ Función `getTarifasViaje()` - retorna tarifas de segmento origen-destino
- ✅ Controller case `getClasesTarifas` - AJAX endpoint para método CLASES
- ✅ Controller case `getTarifasViaje` - AJAX endpoint para método SEGMENTADO
- ✅ Conversión de moneda en ambas respuestas

### Frontend HTML/PHP
- ✅ Detección de `tipo_tarifa` en línea 15
- ✅ Acordeón de clase (visible solo si tipo='clases')
- ✅ Acordeones de origen/destino (visibles solo si tipo='segmentado')
- ✅ Acordeones de pasajeros (dinámico, se llena después de cargar tarifas)
- ✅ Versión desktop + mobile (misma lógica condicional)

### Frontend JavaScript
- ✅ Variable global `tipoTarifa = '<?= $tipoTarifa ?>'`
- ✅ Función `cargarTarifas()` con dual routing:
  - Si tipo='clases': obtiene idViajeClase, llama getClasesTarifas
  - Si tipo='segmentado': obtiene origen/destino, llama getTarifasViaje
- ✅ Función `generarSelectoresPasajeros()` - genera dinámicamente
- ✅ Función `cambiarCantidad()` - incrementa/decrementa cantidades
- ✅ Función `calcularPrecioTotal()` - suma precio total

### Validaciones y Errores
- ✅ Validación de parámetros en controller (idViaje, idViajeClase, etc.)
- ✅ Manejo de casos sin tarifas disponibles
- ✅ Mensajes de error descriptivos en JSON
- ✅ Logging en consola JavaScript para debugging

---

## 📊 ESTADO POR VIAJE

| ID | Tipo | Datos en viaje_clase_tarifa | Datos en viaje_tarifa | Status |
|----|------|---------------------------|----------------------|--------|
| 5  | clases | ✅ SÍ (Adulto/Niño/Senior) | ❌ NO | ✅ LISTO |
| 1-4 | clases (default) | ⚠️ DESCONOCIDO | ❌ NO | 🔄 REVISAR |

---

## 🧪 TESTING CHECKLIST

### Pre-Test Setup
- [x] Ejecutar setup_tipo_tarifa.php
- [x] Verificar columna tipo_tarifa existe
- [x] Viaje 5 tiene tipo_tarifa='clases'

### Test Manual - Viaje 5 (CLASES)

**Paso 1: Abrir página**
- [ ] Ir a: http://localhost/metelebrasil_dev/servicio_contransporte.php?id=5
- [ ] Página carga sin errores

**Paso 2: Verificar UI**
- [ ] Título muestra "Rosario - Florianópolis" (o ruta viaje 5)
- [ ] Mapa está visible (Google Maps)
- [ ] Paradas están listadas

**Paso 3: Verificar Acordeones**
- [ ] **Acordeón CLASE**: Visible (no origen/destino)
  - [ ] Contiene opciones: Economy, Business, First
- [ ] **Acordeón PASAJEROS**: Visible pero vacío
  - [ ] Dice "Seleccione pasajeros después de elegir clase"
- [ ] **Acordeón INFORMACIÓN**: Visible con datos del viaje

**Paso 4: Seleccionar Clase**
- [ ] Click en acordeón CLASE para expandir
- [ ] Seleccionar "Economy"
- [ ] Acordeón se contrae

**Paso 5: Verificar Carga de Tarifas**
- [ ] Acordeón PASAJEROS se actualiza (se llena)
- [ ] Muestra 4 opciones:
  - [ ] Adulto (ARS XXXX)
  - [ ] Niño (ARS XXXX - menor que Adulto)
  - [ ] Senior (ARS XXXX - menor que Adulto)
  - [ ] Estudiante (ARS XXXX - menor que Adulto)

**Paso 6: Cambiar Cantidades**
- [ ] Click [+] en Adulto → cantidad sube a 1
- [ ] Precio total se actualiza (muestra ARS XXXX)
- [ ] Click [+] en Niño → cantidad sube a 1
- [ ] Precio total se actualiza (suma correcta)
- [ ] Click [+] en Senior → cantidad sube a 1
- [ ] Precio total muestra suma de los 3 tipos

**Paso 7: Verificar Botón Reservar**
- [ ] Botón [Reservar] se habilita cuando hay al menos 1 pasajero
- [ ] Click en [Reservar] (próxima fase)

### Test Manual - Viaje 1 (SEGMENTADO - si aplica)

Si viaje 1 tiene datos en viaje_tarifa:
- [ ] Abrir: http://localhost/metelebrasil_dev/servicio_contransporte.php?id=1
- [ ] Acordeones de ORIGEN + DESTINO visibles (no CLASE)
- [ ] Seleccionar origen + destino
- [ ] Tarifas cargan correctamente
- [ ] Seleccionar cantidades
- [ ] Precio total se calcula

---

## 🔧 SCRIPTS DISPONIBLES

| Script | URL | Propósito |
|--------|-----|-----------|
| setup_tipo_tarifa.php | /setup_tipo_tarifa.php | Agrega columna tipo_tarifa + configura viaje 5 |
| test_viaje_5.php | /test_viaje_5.php | Verifica datos de viaje 5 (clases, tarifas) |
| verificar_tipo_tarifa.php | /verificar_tipo_tarifa.php | Verifica estructura tabla viaje + valores |

---

## 🐛 TROUBLESHOOTING

### Problema: "No se encontraron tarifas para esta clase"

**Causas posibles:**
1. Viaje 5 no tiene datos en `viaje_clase_servicio`
2. Clase seleccionada no tiene datos en `viaje_clase_tarifa`
3. idViajeClase pasado a controller es incorrecto

**Solución:**
```sql
-- Verificar clases de viaje 5
SELECT * FROM viaje_clase_servicio WHERE idViaje=5;

-- Si está vacío, agregar clases:
INSERT INTO viaje_clase_servicio (idViaje, idClaseServicio, asientos_totales, asientos_disponibles, precio_base, idMoneda, comisiona, habilitado)
VALUES (5, 1, 30, 30, 5000, 1, 1, 1);
```

### Problema: Selector de clase no aparece

**Causa:** tipo_tarifa no está siendo detectado correctamente

**Solución:**
1. Abrir F12 → Console
2. Ejecutar: `console.log(tipoTarifa);`
3. Debería mostrar: "clases"
4. Si muestra undefined: revisar línea 15 de servicio_contransporte.php

### Problema: Tarifas no cargan después de seleccionar clase

**Causa:** AJAX no está funcionando

**Solución:**
1. Abrir F12 → Network tab
2. Seleccionar clase
3. Buscar request POST a `ctrlTarifasViaje.php`
4. Ver respuesta: ¿es JSON válido?
5. Si hay error SQL: revisar query en getViajeClaseTarifas()

### Problema: Precios incorrectos

**Causa:** Conversión de moneda fallando

**Solución:**
1. Verificar que `$_SESSION['moneda_sel']` está set
2. Verificar que existen registros en tabla `moneda`
3. Revisar función `ConvierteMoneda()` en admin/classes/moneda.php

---

## 📝 NOTAS DE DESARROLLO

### Para Cambiar Tipo de Tarifa de un Viaje

```php
// En administrador:
UPDATE viaje SET tipo_tarifa='segmentado' WHERE idViaje=X;
```

### Para Agregar Nueva Clase a Viaje 5

```php
// 1. Obtener idClaseServicio disponible
SELECT idClaseServicio FROM clase_servicio_transporte;

// 2. Insertar en viaje_clase_servicio
INSERT INTO viaje_clase_servicio 
(idViaje, idClaseServicio, asientos_totales, asientos_disponibles, precio_base, idMoneda, comisiona, habilitado)
VALUES (5, 2, 30, 30, 8000, 1, 1, 1);

// 3. Obtener idViajeClase recién creado
SELECT LAST_INSERT_ID();

// 4. Insertar tarifas para cada tipo de pasajero
INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda, comisiona)
VALUES 
(XX, 1, 8000, 1, 1),  // Adulto
(XX, 2, 5600, 1, 1),  // Niño (70%)
(XX, 3, 6800, 1, 1),  // Senior (85%)
(XX, 4, 6400, 1, 1);  // Estudiante (80%)
```

### Para Agregar Tarifa Segmentada a Viaje 1

```php
// Si quieres que viaje 1 use método SEGMENTADO:
UPDATE viaje SET tipo_tarifa='segmentado' WHERE idViaje=1;

// Luego agregar datos en viaje_tarifa:
INSERT INTO viaje_tarifa 
(idViaje, idOrigenParada, idDestinoParada, idTipoTarifa, precio, idMoneda, comisiona)
VALUES 
(1, 10, 15, 1, 2500, 1, 1),  // Adulto
(1, 10, 15, 2, 1750, 1, 1),  // Niño
(1, 10, 15, 3, 2125, 1, 1),  // Senior
(1, 10, 15, 4, 2000, 1, 1);  // Estudiante
```

---

## 🚀 PRÓXIMOS PASOS

### 1. Testing Manual (PRIORITARIO)
- Ejecutar checklist anterior
- Verificar que viaje 5 funciona completamente
- Capturar pantallas para documentación

### 2. Integración con Reservas
- Modificar `guardaReservas.php` para detectar tipo de tarifa
- Grabar en tabla `reserva_transporte` apropiadamente
- Mostrar resumen con clase seleccionada (si CLASES) o segmento (si SEGMENTADO)

### 3. Datos para Otros Viajes
- Decidir: ¿Viajes 1-4 usan CLASES o SEGMENTADO?
- Si CLASES: crear datos en viaje_clase_servicio + viaje_clase_tarifa
- Si SEGMENTADO: crear datos en viaje_tarifa

### 4. UI/UX Improvements
- Agregar indicador visual del tipo (badge "CLASES" vs "SEGMENTADO")
- Mejorar estilos de acordeones
- Mostrar "Desde $X" como precio mínimo

### 5. Documentación
- Documentar proceso para que otros devs puedan mantener
- Crear guía de troubleshooting
- Ejemplos SQL para operaciones comunes

---

## 📞 SOPORTE

**Si tienes dudas:**
1. Revisar IMPLEMENTACION_DUAL_TARIFAS_ENERO_2026.md (técnico)
2. Revisar DIAGRAMA_DUAL_TARIFAS.md (visual)
3. Ejecutar test_viaje_5.php (debug)
4. Revisar console.log en F12 (errores JS)

---

**Última actualización:** Enero 2026
**Status**: ✅ LISTO PARA TESTING
**Siguiente sesión:** Ejecutar checklist de testing manual

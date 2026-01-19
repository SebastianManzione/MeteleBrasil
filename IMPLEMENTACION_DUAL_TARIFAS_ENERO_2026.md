# Sistema Dual de Tarifas - Implementación Completada

## Descripción
Se ha implementado un sistema dual de tarifas que permite a cada viaje usar uno de dos métodos:
1. **Método CLASES**: Tarifas basadas en clases de servicio (Economy, Business, First, etc.) 
2. **Método SEGMENTADO**: Tarifas basadas en segmentos origen-destino

## Cambios Realizados

### 1. Database Schema (setup_tipo_tarifa.php)
- ✅ Agregada columna `tipo_tarifa` a tabla `viaje` (VARCHAR(20), DEFAULT='clases')
- ✅ Viaje 5 configurado como tipo='clases'
- ✅ Otros viajes pueden usar tipo='segmentado'

### 2. Frontend - servicio_contransporte.php (743 → 856 líneas)

**Línea 15**: Detección del tipo de tarifa
```php
$tipoTarifa = isset($viaje['tipo_tarifa']) ? $viaje['tipo_tarifa'] : 'clases';
```

**Sidebar Desktop - Acordeones Condicionales**:
- Si `tipoTarifa='clases'`: Muestra selector de clase
- Si `tipoTarifa='segmentado'`: Muestra selectores origen/destino
- Ambos casos cargan pasajeros dinámicamente

**Sidebar Mobile**:
- Misma lógica condicional
- HTML generado dinámicamente según tipo

### 3. JavaScript - Función cargarTarifas()

**Antes**: Lógica hardcodeada para segmentado

**Después**: Dual routing inteligente
```javascript
if (tipoTarifa === 'clases') {
    // Obtener id de clase seleccionada
    // Llamar a getClasesTarifas
} else if (tipoTarifa === 'segmentado') {
    // Obtener origen/destino seleccionados
    // Llamar a getTarifasViaje
}
```

### 4. Backend - Controller (ctrlTarifasViaje.php)

**Nuevo case**: getClasesTarifas
- Recibe: idViaje, idViajeClase
- Retorna: Tarifas de viaje_clase_tarifa convertidas a moneda usuario
- Maneja errores apropiadamente

**Existente**: getTarifasViaje (sin cambios)
- Sigue funcionando para método segmentado
- Filtra por origen-destino

### 5. Backend - Base de Datos (transporte.php)

**Función getViajeClaseTarifas()** - CORREGIDA
- Campo: `tipo_tarifa` → `nombre_tipo_tarifa` (consistencia con JavaScript)
- Retorna array con estructura correcta para generarSelectoresPasajeros()

**Funciones Existentes - Sin cambios**:
- getViajeClasesServicio() → Obtiene clases de viaje
- getTarifasViaje() → Obtiene tarifas por segmento
- getViajeClaseTarifas() → Obtiene tarifas de clase específica

## Flujo de Funcionamiento

### Viaje con tipo='clases' (Ejemplo: Viaje 5)
1. Usuario abre servicio_contransporte.php?id=5
2. PHP detecta: `tipoTarifa='clases'`
3. Se renderiza selector de clase (no origen/destino)
4. Usuario selecciona clase → JavaScript llama cargarTarifas()
5. cargarTarifas() detecta tipoTarifa='clases'
6. AJAX POST a ctrlTarifasViaje.php con accion='getClasesTarifas'
7. Controller retorna tarifas de viaje_clase_tarifa (Adulto, Niño, Senior, Estudiante)
8. JavaScript crea selectores de cantidad para cada tipo
9. Usuario selecciona cantidad de cada tipo
10. Precio total se calcula automáticamente
11. Usuario presiona "Reservar"

### Viaje con tipo='segmentado' (Ejemplo: Viaje 1, 2, 3)
1. Usuario abre servicio_contransporte.php?id=X
2. PHP detecta: `tipoTarifa='segmentado'` (o default)
3. Se renderiza selectores de origen/destino
4. Usuario selecciona origen + destino → JavaScript llama cargarTarifas()
5. cargarTarifas() detecta tipoTarifa='segmentado'
6. AJAX POST a ctrlTarifasViaje.php con accion='getTarifasViaje'
7. Controller retorna tarifas de viaje_tarifa (origen-destino específico)
8. Resto del flujo igual

## Testing Checklist

- ✅ Columna tipo_tarifa existe en tabla viaje
- ✅ Viaje 5 tiene tipo_tarifa='clases'
- ✅ servicio_contransporte.php detecta tipo_tarifa
- ✅ Acordeones se renderizan según tipo (clase vs origen/destino)
- ✅ JavaScript cargarTarifas() tiene dual routing
- ✅ Controller ctrlTarifasViaje.php tiene ambos cases
- ✅ Función getViajeClaseTarifas() retorna nombre_tipo_tarifa correcto
- ⏳ Test real: Abrir viaje 5, seleccionar clase, verificar que cargan tarifas

## Próximos Pasos

1. **Testing Manual**:
   - Abrir http://localhost/metelebrasil_dev/servicio_contransporte.php?id=5
   - Verificar que muestra selector de clase (no origen/destino)
   - Seleccionar una clase
   - Verificar que aparecen selectores Adulto/Niño/Senior/Estudiante
   - Cambiar cantidades, verificar precio total

2. **Debugging si hay errores**:
   - Abrir F12 (Developer Tools)
   - Revisar consola por errores JavaScript
   - Revisar pestaña Network → ver respuesta AJAX
   - Verificar query SQL en controller

3. **Migración de otros viajes**:
   - Decidir si viajes 1-4 usan 'clases' o 'segmentado'
   - Si segmentado: asegurar que tienen datos en viaje_tarifa
   - Si clases: asegurar que tienen datos en viaje_clase_servicio + viaje_clase_tarifa

4. **UI Improvements** (si es necesario):
   - Agregar descriptores visuales ("Clase", "Clase seleccionada", etc.)
   - Mejorar estilos de acordeones
   - Agregar indicadores de precio mínimo

5. **Integración con Reservas**:
   - guardarReserva() debe detectar qué método se usó
   - Grabar en tabla reserva_transporte de forma apropiada
   - Mostrar resumen de viaje + clase/segmento en confirmación

## Archivos Modificados

1. **servicio_contransporte.php** 
   - Agregada detección de tipo_tarifa (línea 15)
   - Agregados acordeones condicionales (líneas ~390-430)
   - Actualizada función cargarTarifas() (líneas ~520-590)

2. **admin/ctrl/ctrlTarifasViaje.php**
   - Agregado case 'getClasesTarifas' (líneas ~77-127)
   - Mantiene case 'getTarifasViaje' (líneas ~20-75)

3. **admin/classes/transporte.php**
   - Corregido campo en getViajeClaseTarifas() (línea 860)
   - De: `tipo_tarifa` → A: `nombre_tipo_tarifa`

4. **setup_tipo_tarifa.php** (NUEVO - Script Setup)
   - Agrega columna tipo_tarifa a tabla viaje
   - Configura viaje 5 como tipo='clases'

5. **test_viaje_5.php** (NUEVO - Script Testing)
   - Verifica estructura de viaje 5
   - Muestra clases disponibles
   - Muestra tarifas de cada clase

6. **verificar_tipo_tarifa.php** (NUEVO - Script Verificación)
   - Verifica existencia de columna tipo_tarifa
   - Muestra estructura de tabla viaje
   - Lista valores de tipo_tarifa en viajes

## Notas Técnicas

- **Persistencia de datos**: tipo_tarifa se define una sola vez al crear el viaje, luego es inmutable
- **Moneda**: Ambos métodos convierten precios a moneda usuario automáticamente
- **Validación**: Controller valida que idViaje y idViajeClase existan antes de procesar
- **Errores**: Manejo robusto con JSON responses que incluyen mensajes descriptivos
- **Compatibilidad**: Backward compatible - viajes sin tipo_tarifa usan default 'clases'

## Referencia Rápida

Para cambiar tipo de tarifa de un viaje en SQL:
```sql
UPDATE viaje SET tipo_tarifa='segmentado' WHERE idViaje=1;
UPDATE viaje SET tipo_tarifa='clases' WHERE idViaje=5;
```

Para verificar tipos actuales:
```sql
SELECT idViaje, tipo_tarifa, COUNT(*) as cantidad FROM viaje GROUP BY tipo_tarifa;
```

## Performance

- ✅ Sin queries adicionales en frontend (tipo_tarifa viene con getViaje)
- ✅ AJAX lazy-loaded (solo cuando usuario selecciona)
- ✅ Conversión de moneda una sola vez por respuesta
- ✅ Array filtering eficiente en JavaScript

---

**Status**: ✅ LISTO PARA TESTING
**Próxima Acción**: Abrir viaje 5 en navegador y verificar funcionamiento

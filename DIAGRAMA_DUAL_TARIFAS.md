# DIAGRAMA: Sistema Dual de Tarifas (CLASES vs SEGMENTADO)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    VIAJE (servicio_contransporte.php)                       │
│                                                                             │
│  SELECT tipo_tarifa FROM viaje WHERE idViaje = 5                           │
│  → tipo_tarifa = 'clases' (ó 'segmentado')                                 │
└─────────────────────────────────────────────────────────────────────────────┘
                                    ↓
                    ┌───────────────┴──────────────┐
                    ↓                              ↓
          ┌──────────────────┐          ┌──────────────────┐
          │  TIPO = CLASES   │          │ TIPO = SEGMENTADO│
          │  (viaje 5)       │          │ (viaje 1-4)      │
          └──────────────────┘          └──────────────────┘
                    ↓                              ↓
        ┌───────────────────────┐     ┌───────────────────────┐
        │ SELECT * FROM         │     │ SELECT * FROM         │
        │ viaje_clase_servicio  │     │ ruta_paradas          │
        │ WHERE idViaje = 5     │     │ WHERE idRuta = X      │
        │                       │     │                       │
        │ RESULTADO:            │     │ RESULTADO:            │
        │ • Clase 1 (Economy)   │     │ • Parada origen 1     │
        │ • Clase 2 (Business)  │     │ • Parada origen 2     │
        │ • Clase 3 (First)     │     │ • Parada destino 1    │
        └───────────────────────┘     └───────────────────────┘
                    ↓                              ↓
      ┌─────────────────────────┐   ┌─────────────────────────┐
      │ ACORDEÓN: Seleccionar   │   │ ACORDEÓN: Seleccionar   │
      │ Clase de Servicio       │   │ Origen                  │
      │ ┌───────────────────┐   │   │ ┌───────────────────┐   │
      │ │ [▼] Clase         │   │   │ │ [▼] Origen        │   │
      │ │  • Economy        │   │   │ │  • Terminal A     │   │
      │ │  • Business       │   │   │ │  • Terminal B     │   │
      │ │  • First          │   │   │ │  • Terminal C     │   │
      │ └───────────────────┘   │   │ └───────────────────┘   │
      └─────────────────────────┘   │                         │
                    ↓                │  + ACORDEÓN: Destino   │
                                     │  ┌───────────────────┐ │
                    │                │  │ [▼] Destino       │ │
                    │                │  │  • Terminal X     │ │
                    │                │  │  • Terminal Y     │ │
                    │                │  │  • Terminal Z     │ │
                    │                │  └───────────────────┘ │
                    │                └─────────────────────────┘
                    │                              ↓
                    │               Usuario selecciona origen+destino
                    │                              ↓
      cargarTarifas() ◄────────────────────────────┴──────────────┐
      onchange event                                              │
            ↓                                                      ↓
      ┌──────────────────────────────────────────────────────────┐│
      │ JavaScript:                                               ││
      │ if (tipoTarifa === 'clases') {                           ││
      │    idViajeClase = $('#selectClase').val()               ││
      │    AJAX POST accion='getClasesTarifas'                  ││
      │ } else if (tipoTarifa === 'segmentado') {               ││
      │    idOrigen = $('#selectOrigen').val()                  ││
      │    idDestino = $('#selectDestino').val()                ││
      │    AJAX POST accion='getTarifasViaje'                   ││
      │ }                                                         ││
      └──────────────────────────────────────────────────────────┘│
            ↓                                                      ↓
      ┌──────────────────────────┐         ┌──────────────────────────┐
      │ AJAX → ctrlTarifasViaje  │         │ AJAX → ctrlTarifasViaje  │
      │                          │         │                          │
      │ POST data:               │         │ POST data:               │
      │ {                        │         │ {                        │
      │  accion: 'getClasesTar'  │         │  accion: 'getTarifasVia' │
      │  idViaje: 5,             │         │  idViaje: 1,             │
      │  idViajeClase: 2         │         │  idOrigen: 10,           │
      │ }                        │         │  idDestino: 15           │
      │                          │         │ }                        │
      └──────────────────────────┘         └──────────────────────────┘
            ↓                                      ↓
      ┌──────────────────────────┐         ┌──────────────────────────┐
      │ Controller CASE:         │         │ Controller CASE:         │
      │ getClasesTarifas         │         │ getTarifasViaje          │
      │                          │         │                          │
      │ $tarifas = getViaje      │         │ $tarifas = getTarifas    │
      │    ClaseTarifas($idVC)   │         │    Viaje($idViaje)       │
      │                          │         │                          │
      │ Filter by origin/dest:   │         │ Filter by origin-dest:   │
      │ $tarifas = array_filter( │         │ $tarifas = array_filter( │
      │   ...                    │         │   ...idOrigen==X &&      │
      │ )                        │         │   idDestino==Y           │
      │                          │         │ )                        │
      │ Convert moneda           │         │ Convert moneda           │
      │ Return JSON              │         │ Return JSON              │
      └──────────────────────────┘         └──────────────────────────┘
            ↓                                      ↓
      ┌──────────────────────────┐         ┌──────────────────────────┐
      │ RESPUESTA JSON:          │         │ RESPUESTA JSON:          │
      │ {                        │         │ {                        │
      │   success: true,         │         │   success: true,         │
      │   tarifas: [             │         │   tarifas: [             │
      │     {                    │         │     {                    │
      │      idTipoTarifa: 1,    │         │      idOrigenParada: 10, │
      │      nombre_tipo_tarif:  │         │      idDestinoParada: 15,│
      │        "Adulto",         │         │      idTipoTarifa: 1,    │
      │      precio: 5000,       │         │      nombre_tipo_tarif:  │
      │      idMoneda: 1,        │         │        "Adulto",         │
      │      precio_convertido:  │         │      precio: 2500,       │
      │        5000,             │         │      precio_convertido:  │
      │      moneda_usuario:     │         │        2500,             │
      │        "ARS"             │         │      moneda_usuario:     │
      │     },                   │         │        "ARS"             │
      │     {...Niño...},        │         │     },                   │
      │     {...Senior...},      │         │     {...Niño...},        │
      │     {...Estudiante...}   │         │     {...Senior...},      │
      │   ]                      │         │     {...Estudiante...}   │
      │ }                        │         │   ]                      │
      │                          │         │ }                        │
      └──────────────────────────┘         └──────────────────────────┘
            ↓                                      ↓
            └──────────────────────┬───────────────┘
                                   ↓
            ┌────────────────────────────────────────────────┐
            │ JavaScript: generarSelectoresPasajeros()       │
            │                                                │
            │ forEach tarifa in tarifasActuales:             │
            │   Crear input:                                 │
            │   [−] Adulto (ARS 5000) [+]                   │
            │   [−] Niño (ARS 3500) [+]                     │
            │   [−] Senior (ARS 4250) [+]                   │
            │   [−] Estudiante (ARS 4000) [+]               │
            │                                                │
            │ Total: ARS 0 (actualizado en tiempo real)      │
            └────────────────────────────────────────────────┘
                                   ↓
            Usuario selecciona cantidades y precio se actualiza
                                   ↓
            ┌────────────────────────────────────────────────┐
            │ Click [Reservar]                               │
            │                                                │
            │ Información guardada:                          │
            │ • idViaje: 5                                   │
            │ • idViajeClase: 2 (si clases)                  │
            │ • ó (idOrigen: 10, idDestino: 15) (segmentado)│
            │ • Cantidades por tipo pasajero                 │
            │ • Total: ARS XXXX                              │
            │                                                │
            │ → Siguiente paso: Pago / Datos personales      │
            └────────────────────────────────────────────────┘
```

## Estado de Implementación

| Componente | Status | Notas |
|-----------|--------|-------|
| Database schema (`tipo_tarifa` column) | ✅ | Agregada, viaje 5 = 'clases' |
| PHP detection (`$tipoTarifa`) | ✅ | Línea 15 servicio_contransporte.php |
| Conditional UI rendering | ✅ | Acordeones dinámicos según tipo |
| JavaScript dual routing | ✅ | cargarTarifas() detecta tipo |
| Controller getClasesTarifas case | ✅ | Agregado a ctrlTarifasViaje.php |
| Backend getViajeClaseTarifas() | ✅ | Corregida columna nombre_tipo_tarifa |
| Testing manual | ⏳ | Próximo: abrir viaje 5 en navegador |
| Reservation integration | ⏳ | Próximo: grabar en tabla reserva_transporte |

## Vista Rápida: Qué Ve el Usuario

### Viaje CLASES (Viaje 5)
```
╔═══════════════════════════════════════╗
║ Rosario - Florianópolis (Clase)       ║
╠═══════════════════════════════════════╣
║                                       ║
║ [▼] Clase (accordion)                 ║
║    • Economy                          ║
║    • Business                         ║
║    • First                            ║
║                                       ║
║ [▼] Pasajeros (accordion)             ║
║    [−] Adulto (ARS 5000) [+]         ║
║    [−] Niño (ARS 3500) [+]           ║
║    [−] Senior (ARS 4250) [+]         ║
║    [−] Estudiante (ARS 4000) [+]     ║
║                                       ║
║ Total: ARS 0                          ║
║ [Reservar] (deshabilitado hasta selec)║
║                                       ║
╚═══════════════════════════════════════╝
```

### Viaje SEGMENTADO (Viaje 1-4)
```
╔═══════════════════════════════════════╗
║ Rosario - Buenos Aires (Segmentado)   ║
╠═══════════════════════════════════════╣
║                                       ║
║ [▼] Origen (accordion)                ║
║    • Rosario (Terminal A)             ║
║    • Rosario (Terminal B)             ║
║                                       ║
║ [▼] Destino (accordion)               ║
║    • Buenos Aires (Retiro)            ║
║    • Buenos Aires (Liniers)           ║
║                                       ║
║ [▼] Pasajeros (accordion)             ║
║    [−] Adulto (ARS 2500) [+]         ║
║    [−] Niño (ARS 1750) [+]           ║
║    [−] Senior (ARS 2125) [+]         ║
║    [−] Estudiante (ARS 2000) [+]     ║
║                                       ║
║ Total: ARS 0                          ║
║ [Reservar] (deshabilitado hasta selec)║
║                                       ║
╚═══════════════════════════════════════╝
```

## Líneas de Código Clave

### 1. PHP Detection (servicio_contransporte.php:15)
```php
$tipoTarifa = isset($viaje['tipo_tarifa']) ? $viaje['tipo_tarifa'] : 'clases';
```

### 2. Conditional Rendering (servicio_contransporte.php:~390)
```php
<?php if ($tipoTarifa === 'clases'): 
    $clasesDisponibles = getViajeClasesServicio($idViaje);
?>
    <!-- Select de clase -->
<?php endif; ?>

<?php if ($tipoTarifa === 'segmentado'): ?>
    <!-- Selects de origen/destino -->
<?php endif; ?>
```

### 3. JavaScript Dual Routing (servicio_contransporte.php:~520)
```javascript
function cargarTarifas() {
    if (tipoTarifa === 'clases') {
        // Lógica CLASES
        var idViajeClase = $('#selectClase').val();
        $.ajax({...accion: 'getClasesTarifas'...});
    } else if (tipoTarifa === 'segmentado') {
        // Lógica SEGMENTADO
        var origen = $('#selectOrigen').val();
        var destino = $('#selectDestino').val();
        $.ajax({...accion: 'getTarifasViaje'...});
    }
}
```

### 4. Controller Cases (ctrlTarifasViaje.php)
```php
case 'getClasesTarifas':
    $tarifas = getViajeClaseTarifas($idViajeClase);
    // Convert & return

case 'getTarifasViaje':
    $tarifas = getTarifasViaje($idViaje);
    // Filter & convert & return
```

## Flujo de Testing Recomendado

1. **Abrir página**: http://localhost/metelebrasil_dev/servicio_contransporte.php?id=5
2. **Verificar en navegador**:
   - ¿Se muestra selector de CLASE (no origen/destino)?
   - ¿El selector tiene opciones: Economy, Business, First?
3. **Seleccionar una clase**: Hacer click en "Economy" u otra
4. **Verificar que carga**:
   - ¿Aparecen selectores de Adulto, Niño, Senior, Estudiante?
   - ¿Muestran precios correctos convertidos a moneda usuario?
5. **Incrementar cantidades**: Click en botones +
6. **Verificar precio total**: ¿Se actualiza correctamente?
7. **Presionar Reservar**: (Próxima fase - integration con formulario)

## Debugging Tips

Si algo no funciona:

1. **F12** → Consola: Ver errores JavaScript
2. **F12** → Network: Ver respuesta AJAX (debería ser JSON válido)
3. **php.ini**: Verificar `display_errors=On` para ver errores PHP
4. **Logs**: `logs/` carpeta para errores de servidor

## Comandos SQL Útiles

```sql
-- Ver tipo de tarifa de todos los viajes
SELECT idViaje, tipo_tarifa FROM viaje;

-- Cambiar tipo de un viaje
UPDATE viaje SET tipo_tarifa='segmentado' WHERE idViaje=1;

-- Ver clases disponibles para viaje 5
SELECT * FROM viaje_clase_servicio WHERE idViaje=5;

-- Ver tarifas de clase 1
SELECT * FROM viaje_clase_tarifa WHERE idViajeClase=1;

-- Ver tarifas segmentadas de viaje 1
SELECT * FROM viaje_tarifa WHERE idViaje=1;
```

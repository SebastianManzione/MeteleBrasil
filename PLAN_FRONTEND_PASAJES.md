# Plan de Implementación: Frontend de Búsqueda y Compra de Pasajes

## 📍 Ubicación en el Flujo del Sistema

```
Cliente visita site
    ↓
index.php → categorias.php → servicios (actividades, tours)
    ↓
¿Quiere comprar PASAJES DE TRANSPORTE? (NUEVO)
    ↓
buscar_pasajes.php (PÁGINA NUEVA)
    ↓
Selecciona: origen + destino + fecha + cantidad+tipo pasajero
    ↓
API: admin/ctrl/ctrlBusquedaPasajes.php
    ↓
resultados_viajes.php (PÁGINA NUEVA)
    ↓
Muestra viajes disponibles con precios
    ↓
Selecciona viaje → viaje_detalle.php (PÁGINA NUEVA)
    ↓
Elige asientos + confirma datos
    ↓
Agrega al carrito → carrito_pasajes.php (PÁGINA NUEVA)
    ↓
Procede a checkout → checkout_pasajes.php (PÁGINA NUEVA)
    ↓
Elige método de pago (PayPal / MercadoPago)
    ↓
Integración con pasarelas existentes
    ↓
BD: reservas, reserva_horarios, reserva_tarifas (existentes)
    ↓
Email de confirmación + voucher
```

## 🎯 Componentes a Crear

### 1. Página de Búsqueda: `buscar_pasajes.php`

**Ubicación:** Raíz del proyecto (como `carrito.php`)

**Estructura HTML:**
```
┌─────────────────────────────────────────┐
│         BUSCAR PASAJES DE TRANSPORTE    │
├─────────────────────────────────────────┤
│                                         │
│  Origen:        [SELECT] (Terminales)   │
│  Destino:       [SELECT] (Terminales)   │
│  Fecha:         [DATE PICKER]           │
│                                         │
│  Pasajeros:                             │
│  ├─ Adultos:    [SPINNER]               │
│  ├─ Niños:      [SPINNER]               │
│  ├─ Seniors:    [SPINNER]               │
│  └─ Estudiantes:[SPINNER]               │
│                                         │
│  Tipo de Transporte: [CHECKBOX GROUP]   │
│  ├─ □ Bus      ├─ □ Avión              │
│  ├─ □ Tren     ├─ □ Barco              │
│                                         │
│  [BUSCAR] [LIMPIAR]                    │
│                                         │
└─────────────────────────────────────────┘
```

**Funcionalidades:**
- Selector de origen con búsqueda (GET: `getTerminalesDestino()`)
- Selector dinámico de destino (después de elegir origen)
- Calendario con fechas disponibles (mín: hoy, máx: +30 días)
- Spinners para cantidad de pasajeros
- Checkboxes para filtrar por tipo de transporte
- Botón [BUSCAR] → POST a `admin/ctrl/ctrlBusquedaPasajes.php`

**Variables de Sesión Requeridas:**
- `$_SESSION['moneda_sel']` - Moneda del usuario (para mostrar precios)
- `$_SESSION['idioma']` - Idioma (para labels)
- `$_SESSION['geoFinal']` - Geolocalización opcional (para sugerir destinos cercanos)

**Traducciones Necesarias:**
- "buscar_pasajes" → "Buscar Pasajes"
- "origen" → "Origen"
- "destino" → "Destino"
- "fecha_viaje" → "Fecha de Viaje"
- "cantidad_pasajeros" → "Cantidad de Pasajeros"
- "adultos" → "Adultos"
- "ninos" → "Niños"
- "seniors" → "Seniors"
- "estudiantes" → "Estudiantes"
- "tipo_transporte" → "Tipo de Transporte"
- "sin_resultados" → "No hay viajes disponibles"

---

### 2. Controller AJAX: `admin/ctrl/ctrlBusquedaPasajes.php`

**Acciones:**
```php
// POST: action=buscar
Parámetros:
- origen: ID terminal origen
- destino: ID terminal destino
- fecha: YYYY-MM-DD
- adultos: int (cantidad)
- ninos: int
- seniors: int
- estudiantes: int
- tipos: array de idTipoTransporte (1=Bus, 2=Avión, etc)

Lógica:
1. Validar parámetros
2. Consulta a BD: obtener viajes con:
   - Ruta origen-destino válida
   - Fecha = parámetro
   - Tipo transporte en filtro
   - Asientos_disponibles >= suma(pasajeros)
3. Por cada viaje:
   - Calcular tarifa: getTarifa(idViaje, idOrigenParada, idDestinoParada)
   - Multiplicar por cantidad de cada tipo pasajero
   - Sumar total
4. Retornar JSON con array de viajes + precios
```

**Respuesta JSON:**
```json
{
  "success": true,
  "viajes": [
    {
      "idViaje": 1,
      "idRuta": 1,
      "ruta_nombre": "Rosario - Buenos Aires",
      "tipo_transporte": "Bus",
      "fecha_salida": "2026-01-15",
      "hora_salida": "08:00",
      "hora_llegada": "10:30",
      "duracion": "2h 30min",
      "asientos_disponibles": 25,
      "asientos_totales": 50,
      "modelo_vehiculo": "Marcopolo Doble Piso G7",
      "empresa": "Empresa XYZ",
      "precio_unitario": 850,
      "precio_total": 3400,
      "moneda": "ARS",
      "tarifa_detalle": {
        "adultos": 1000,
        "ninos": 700,
        "seniors": 850,
        "estudiantes": 800
      }
    },
    ... más viajes
  ]
}
```

**SQL Query Base:**
```sql
SELECT vt.idViaje, vt.fecha_salida, vt.hora_salida, vt.asientos_disponibles,
       r.nombre as ruta_nombre, r.idTipoTransporte,
       t.nombre as tipo_transporte,
       m.nombre as modelo_vehiculo,
       MIN(vtar.precio) as precio_min
FROM viaje_transporte vt
JOIN ruta_transporte r ON vt.idRuta = r.idRuta
JOIN tipo_transporte t ON r.idTipoTransporte = t.idTipoTransporte
JOIN vehiculo_transporte v ON vt.idVehiculo = v.idVehiculo
JOIN modelo_vehiculo_transporte m ON v.idModelo = m.idModelo
LEFT JOIN viaje_tarifa vtar ON vt.idViaje = vtar.idViaje
WHERE r.idRuta IN (
    SELECT DISTINCT rp1.idRuta 
    FROM ruta_paradas rp1 
    JOIN ruta_paradas rp2 ON rp1.idRuta = rp2.idRuta
    WHERE rp1.idTerminal = :origen AND rp1.es_origen = 1
    AND rp2.idTerminal = :destino AND rp2.es_destino = 1
)
AND vt.fecha_salida = :fecha
AND vt.asientos_disponibles >= :cantidad_total
AND vt.estado = 'confirmado'
AND r.habilitado = 1
```

---

### 3. Página de Resultados: `resultados_viajes.php`

**GET Parameters:**
- `origen` - ID origen
- `destino` - ID destino
- `fecha` - YYYY-MM-DD
- `adultos`, `ninos`, `seniors`, `estudiantes` - cantidades
- `tipos` - IDs de tipos (comma-separated o array)

**Estructura HTML:**
```
┌──────────────────────────────────────────────────┐
│      RESULTADOS DE VIAJES - (Origen → Destino)  │
│      Fecha: 15/01/2026 | 3 Adultos + 1 Niño    │
├──────────────────────────────────────────────────┤
│                                                  │
│ FILTROS (Sidebar):                               │
│ ├─ Tipo: □ Bus  □ Avión  □ Tren  □ Barco      │
│ ├─ Hora: [TIME RANGE SLIDER]                    │
│ ├─ Precio: [PRICE RANGE SLIDER]                 │
│ └─ [Aplicar Filtros] [Limpiar]                 │
│                                                  │
├──────────────────────────────────────────────────┤
│ RESULTADOS (Main):                               │
│                                                  │
│ ┌─────────────────────────────────────────────┐ │
│ │ BUS | Marcopolo Doble Piso G7               │ │
│ │ 08:00 → 10:30 (2h 30min)                    │ │
│ │ Rosario Central → Buenos Aires (180 km)     │ │
│ │                                              │ │
│ │ Empresa: Flecha Bus | 25/50 asientos       │ │
│ │ Tarifa: $850 adult | $595 niño              │ │
│ │                                              │ │
│ │ TOTAL: $3,400.00 (3 × $850 + 1 × $595)     │ │
│ │                    [Ver Detalle] [Aceptar]  │ │
│ └─────────────────────────────────────────────┘ │
│                                                  │
│ ┌─────────────────────────────────────────────┐ │
│ │ BUS | Volvo Doble Piso                      │ │
│ │ 11:00 → 13:15 (2h 15min)                    │ │
│ │ ... más resultados ...                       │ │
│ └─────────────────────────────────────────────┘ │
│                                                  │
└──────────────────────────────────────────────────┘
```

**Funcionalidades:**
- Listado de viajes en tarjetas
- Información de empresa, modelo, hora, duración
- Precio por tipo de pasajero + total
- Botones [Ver Detalle] y [Aceptar/Agregar al carrito]
- Filtros de sidebar (tipo, hora, precio)
- Ordenamiento por precio/hora/duración
- Paginación si hay muchos resultados

---

### 4. Detalle de Viaje: `viaje_detalle.php`

**GET Parameters:**
- `idViaje` - Viaje seleccionado
- `origen`, `destino`, `fecha` - Del carrito (para contexto)
- `adultos`, `ninos`, `seniors`, `estudiantes` - Cantidades

**Estructura HTML:**
```
┌────────────────────────────────────────────┐
│        DETALLE DEL VIAJE SELECCIONADO      │
├────────────────────────────────────────────┤
│                                            │
│ [Breadcrumb: Búsqueda > Resultados > Aquí]│
│                                            │
│ ┌─ INFORMACIÓN DEL VIAJE                  │
│ │ Fecha: 15/01/2026 | Hora: 08:00          │
│ │ Origen: Terminal Rosario                 │
│ │ Destino: Terminal Buenos Aires           │
│ │ Duración: 2h 30min                       │
│ │ Distancia: 180 km                        │
│ │                                          │
│ │ Vehículo: Marcopolo Doble Piso G7       │
│ │ Empresa: Flecha Bus                      │
│ │ Asientos: 25 disponibles de 50           │
│ └─                                         │
│                                            │
│ ┌─ MAPA DE ASIENTOS                       │
│ │ [SEAT MAP VISUAL]                        │
│ │ Verde = Disponible | Rojo = Ocupado     │
│ │ Azul = Seleccionado                     │
│ │                                          │
│ │ [Pasajero 1 (Adulto)]    [Asiento ??]  │
│ │ [Pasajero 2 (Adulto)]    [Asiento ??]  │
│ │ [Pasajero 3 (Adulto)]    [Asiento ??]  │
│ │ [Pasajero 4 (Niño)]      [Asiento ??]  │
│ └─                                         │
│                                            │
│ ┌─ TARIFA                                  │
│ │ Adultos (3):    3 × $850 = $2,550       │
│ │ Niños (1):      1 × $595 = $595         │
│ │ ─────────────────────────────            │
│ │ TOTAL:                    $3,145         │
│ │ Moneda: ARS (Pesos)                     │
│ └─                                         │
│                                            │
│ [Volver] [Agregar al Carrito]             │
│                                            │
└────────────────────────────────────────────┘
```

**Funcionalidades:**
- Información completa del viaje
- Mapa visual de asientos (opcional: selección interactiva)
- Breakdown de tarifa por tipo de pasajero
- Detalles de politica de cancelación (si aplica)
- Botón para agregar al carrito

---

### 5. Carrito de Pasajes: `carrito_pasajes.php`

**Sesión:** Almacenar en `$_SESSION['carrito_pasajes']`

```php
$_SESSION['carrito_pasajes'] = [
    'viaje_1' => [
        'idViaje' => 1,
        'fecha' => '2026-01-15',
        'origen' => 'Rosario',
        'destino' => 'Buenos Aires',
        'cantidad_total' => 4,
        'pasajeros' => [
            ['tipo' => 'adulto', 'nombre' => 'Juan García', 'precio' => 850],
            ['tipo' => 'adulto', 'nombre' => 'María López', 'precio' => 850],
            ['tipo' => 'adulto', 'nombre' => 'Carlos Ruiz', 'precio' => 850],
            ['tipo' => 'nino', 'nombre' => 'Lucía García', 'precio' => 595]
        ],
        'subtotal' => 3145,
        'moneda' => 'ARS'
    ],
    ... más viajes si hay
]
```

**Estructura HTML:**
```
┌──────────────────────────────────────────┐
│         CARRITO DE PASAJES                │
├──────────────────────────────────────────┤
│                                          │
│ ┌─ VIAJE 1: Rosario → Buenos Aires      │
│ │ Fecha: 15/01/2026 | Hora: 08:00       │
│ │                                        │
│ │ Pasajeros:                             │
│ │ 1. Juan García (Adulto) ......... ARS$850│
│ │ 2. María López (Adulto) ......... ARS$850│
│ │ 3. Carlos Ruiz (Adulto) ........ ARS$850│
│ │ 4. Lucía García (Niño) ......... ARS$595│
│ │                                        │
│ │ Subtotal: ARS$3,145                   │
│ │ [Editar] [Eliminar]                    │
│ └─                                       │
│                                          │
│ [Agregar otro viaje]                    │
│                                          │
├──────────────────────────────────────────┤
│ RESUMEN:                                 │
│ Subtotal:        ARS$3,145               │
│ Impuestos (21%): ARS$660.45              │
│ TOTAL:           ARS$3,805.45            │
│                                          │
│ Moneda: ARS (Pesos Argentinos)          │
│ [Cambiar Moneda ▼]                      │
│                                          │
│ [Continuar Compra] [Seguir Buscando]    │
│                                          │
└──────────────────────────────────────────┘
```

**Funcionalidades:**
- Listar todos los viajes agregados
- Mostrar pasajeros con detalles
- Calcular subtotal + impuestos
- Selector de moneda
- Botones para editar/eliminar items
- Opción para agregar más viajes
- Proceder a checkout

---

### 6. Checkout: `checkout_pasajes.php`

**Etapas:**
1. Confirmación de datos de pasajeros
2. Selección de método de pago
3. Procesamiento de pago
4. Confirmación y voucher

```
┌────────────────────────────────────────────────────┐
│           FINALIZAR COMPRA DE PASAJES              │
├────────────────────────────────────────────────────┤
│                                                    │
│ PASO 1: DATOS DEL COMPRADOR                       │
│ ├─ Nombre: [INPUT] (requerido)                    │
│ ├─ Email: [INPUT] (requerido)                     │
│ ├─ Teléfono: [INPUT]                              │
│ ├─ Documento: [INPUT] (requerido)                 │
│ └─ País: [SELECT] (requerido)                     │
│                                                    │
│ PASO 2: REVISIÓN DE PASAJEROS                     │
│ ├─ □ 1. Juan García - DNI 35.445.123             │
│ ├─ □ 2. María López - DNI 38.566.234             │
│ ├─ □ 3. Carlos Ruiz - DNI 40.778.345             │
│ └─ □ 4. Lucía García - DNI 42.889.456 (Menor)   │
│                                                    │
│ PASO 3: MÉTODO DE PAGO                            │
│ ├─ (○) PayPal                                     │
│ ├─ (○) MercadoPago                                │
│ ├─ (○) Transferencia Bancaria                     │
│ └─ (○) Tarjeta de Crédito                         │
│                                                    │
│ RESUMEN:                                           │
│ ├─ Subtotal: ARS$3,145                           │
│ ├─ Impuesto: ARS$660.45                          │
│ └─ TOTAL: ARS$3,805.45                           │
│                                                    │
│ [Términos] Acepto los términos y condiciones    │
│ □ Recibir confirmación por email                  │
│                                                    │
│ [Cancelar] [Pagar Ahora]                          │
│                                                    │
└────────────────────────────────────────────────────┘
```

**Funcionalidades:**
- Formulario de datos de comprador
- Validación de documento
- Selección de método de pago
- Integración con PayPal/MercadoPago
- Términos y condiciones
- Confirmación antes de procesar

---

## 💾 Cambios a Base de Datos

### Nuevas Columnas (OPCIONAL)
En tabla `reservas`:
- `tipo_reserva` - 'actividad' | 'pasaje' (para diferenciar tipo)
- `idRutaTransporte` - FK a ruta_transporte (si es pasaje)

En tabla `reserva_pasajeros`:
- `numero_documento` - DNI/Pasaporte
- `tipo_documento` - DNI/Pasaporte/Otro
- `asiento` - Número de asiento (si aplica)
- `tipo_tarifa_pasajero` - 'adulto', 'nino', 'senior', 'estudiante'

### Tablas Existentes a REUTILIZAR
- `reservas` - Cabecera de reserva
- `reserva_horarios` - Viajes seleccionados (idReserva, idViaje)
- `reserva_tarifas` - Precios aplicados
- `reserva_pasajeros` - Detalles de pasajeros
- `comprobante` - Comprobante de pago
- `usuario_comisiones` - Comisiones ganadas

---

## 🔌 Integración con Sistema Existente

### Reutilizar Clases:
- `admin/classes/transporte.php` - getAllViajes, getTarifa, etc
- `admin/classes/moneda.php` - ConvierteMoneda()
- `admin/classes/usuario.php` - Datos del usuario
- `admin/classes/comisiones.php` - Cálculo de comisiones

### Reutilizar Controllers:
- `admin/ctrl/ctrlMoneda.php` - Cambio de moneda
- `admin/pasarelas/PayPal/` - Procesamiento de pago PayPal
- `config/mercadopago.php` - Procesamiento de pago MercadoPago

### Email (crear si no existe):
- `admin/email/email_reserva_pasaje_confirmada.php` - Email de confirmación con voucher

---

## 📊 Estimaciones

| Componente | Líneas de Código | Tiempo Estimado |
|-----------|------------------|-----------------|
| buscar_pasajes.php | 150-200 | 2-3h |
| ctrlBusquedaPasajes.php | 200-250 | 2-3h |
| resultados_viajes.php | 300-400 | 3-4h |
| viaje_detalle.php | 250-300 | 2-3h |
| carrito_pasajes.php | 200-250 | 2-3h |
| checkout_pasajes.php | 300-400 | 3-4h |
| Email templates | 100-150 | 1-2h |
| CSS/Responsive | Variable | 3-4h |
| Testing completo | Variable | 4-5h |

**Total Estimado:** 25-35 horas de desarrollo

---

## ✅ Checklist de Ejecución

- [ ] Crear buscar_pasajes.php
- [ ] Crear ctrlBusquedaPasajes.php
- [ ] Crear resultados_viajes.php
- [ ] Crear viaje_detalle.php
- [ ] Crear carrito_pasajes.php
- [ ] Crear checkout_pasajes.php
- [ ] Crear email_reserva_pasaje_confirmada.php
- [ ] Testing búsqueda
- [ ] Testing agregación al carrito
- [ ] Testing checkout con PayPal
- [ ] Testing checkout con MercadoPago
- [ ] Testing de impuestos por país
- [ ] Testing multi-moneda
- [ ] Testing en mobile
- [ ] Traducción en 4 idiomas (ES, EN, PT, IT)
- [ ] Documentación de APIs

---

**Estado:** 📋 Listos para comenzar implementación
**Próximo Paso:** Crear buscar_pasajes.php como punto de entrada


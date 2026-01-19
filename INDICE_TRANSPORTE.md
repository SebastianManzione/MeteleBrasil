# Índice de Documentación - Sistema de Transporte MeteleBrasil

## 📋 Documentación Técnica

### Resúmenes Ejecutivos
- **`RESUMEN_FINAL_TRANSPORTE_ENERO_2026.md`** ⭐ LEER PRIMERO
  - Overview completo de lo implementado
  - Números finales y logros
  - Próximos pasos

- **`RESUMEN_SISTEMA_TRANSPORTE_ENERO_2026.md`**
  - Estado detallado de cada componente
  - Checklist de implementación
  - Problemas resueltos

### Planes y Roadmap
- **`PLAN_FRONTEND_PASAJES.md`** ⭐ SIGUIENTE FASE
  - Arquitectura del frontend
  - 6 nuevas páginas a crear
  - Estimaciones de tiempo
  - Checklist de ejecución

### Documentación Anterior
- `INSTRUCCIONES_DEPLOY.txt` - Deployment a producción
- `GIT_WORKFLOW.md` - Workflow de git
- `PUNTO_DE_ENTRADA.md` - Flujo inicial del sistema
- `IMPLEMENTACION_PRECIOS.md` - Sistema de precios
- `PRICING_SYSTEM.md` - Detalles de pricing

---

## 🔧 Componentes del Sistema

### Backend (admin/classes/transporte.php)
**1059 líneas, 40+ funciones organizadas por módulo:**

#### Módulo: Terminales (6 funciones)
- `getAllTerminales()` - Obtener todos
- `getTerminal($id)` - Obtener uno
- `insertTerminal($datos)` - Crear
- `updateTerminal($id, $datos)` - Actualizar
- `deleteTerminal($id)` - Eliminar
- `getTerminalesPorTipo($idTipo)` - Filtrar por tipo

#### Módulo: Rutas (8 funciones)
- `getAllRutas()`
- `getRuta($id)`
- `insertRuta($datos)`
- `updateRuta($id, $datos)`
- `deleteRuta($id)`
- `getRutasPorTipo($idTipo)`
- `getRutasActivas()` - Solo habilitadas

#### Módulo: Paradas (6 funciones)
- `getParadasRuta($idRuta)`
- `insertParadaRuta($datos)`
- `deleteParadaRuta($idRutaParada)`
- `updateOrdenParadas($idRuta, $ordenes)`
- `getOrigenesRuta($idRuta)` - Paradas origen
- `getDestinosRuta($idRuta)` - Paradas destino

#### Módulo: Modelos Vehículos (5 funciones)
- `getAllModelos()` ✓ AHORA MUESTRA DOBLE PISO
- `getModelo($id)`
- `insertModelo($datos)`
- `updateModelo($id, $datos)`
- `deleteModelo($id)`

#### Módulo: Vehículos (5 funciones)
- `getAllVehiculos()` ✓ INCLUYE 3 DOBLE PISO
- `getVehiculo($id)`
- `insertVehiculo($datos)`
- `updateVehiculo($id, $datos)`
- `deleteVehiculo($id)`

#### Módulo: Viajes (5 funciones)
- `getAllViajes()`
- `getViaje($id)`
- `insertViaje($datos)` ✓ CON IDVEHICULO
- `updateViaje($id, $datos)` ✓ CON IDVEHICULO
- `deleteViaje($id)`

#### Módulo: Tarifas (5 funciones)
- `getAllTarifas()`
- `getTarifa($id)`
- `insertTarifa($datos)`
- `updateTarifa($id, $datos)`
- `deleteTarifa($id)`

#### Módulo: Utilidades (8+ funciones)
- `getTiposTarifa()` - Adulto, Niño, Senior, Estudiante
- `getParadasParaTarifas($idRuta)` - Para selector
- `getAllMonedas()` - Multi-moneda support
- `getAllTiposTransporte()` - Bus, Avión, Tren, Barco

---

## 📄 Páginas de Admin (Extranet)

### Menú TRANSPORTE (ID 41)
Acceso vía **F9** en footer (toggle F8/F9)

#### Submenú: Ver Viajes (ID 50)
**Archivo:** `admin/viajesTransporteLista.php`
- DataTable con 100+ viajes
- Filtros: fecha, ruta, estado, empresa
- Acciones: editar, ver detalles, eliminar
- Status: ✅ FUNCIONAL

#### Submenú: Crear Viaje (ID 51)
**Archivo:** `admin/viajeTransporteAlta.php` ⭐ MEJORADO
- Formulario con selector de ruta
- **Selector de vehículos** (NEW) agrupado por modelo
- **Auto-carga de capacidad** (NEW) al seleccionar vehículo
- Configuración de fecha/hora/asientos
- Observaciones opcionales
- Status: ✅ FUNCIONAL

#### Submenú: Modelos (ID 48)
**Archivo:** `admin/modeloVehiculosLista.php` ⭐ CORREGIDO
- DataTable con 7 modelos (incluyendo doble piso)
- CRUD modal para crear/editar
- Mapa visual de asientos
- Filtros: tipo de transporte
- Status: ✅ FUNCIONAL (después del fix)

#### Submenú: Vehículos (ID 49)
**Archivo:** `admin/vehiculosTransporteLista.php`
- DataTable con 10 vehículos
- Incluyendo 3 doble piso nuevos
- Filtros: modelo, estado, año
- Status: ✅ FUNCIONAL

#### Submenú: Clases de Servicio (ID 47)
**Archivo:** `admin/viajeClasesLista.php`
- Gestión de 4 tipos de tarifa
- Adulto (0%), Niño (-30%), Senior (-15%), Estudiante (-20%)
- Status: ✅ FUNCIONAL

#### Submenú: Tarifas (NEW)
**Archivo:** `admin/viajeTransporteTarifas.php`
- Editor de matriz de precios
- Origen-Destino × Tipo Pasajero
- Aplicar por ruta o viaje específico
- Status: ✅ FUNCIONAL

---

## 🔌 Controllers AJAX

### `admin/ctrl/ctrlViajesTarifas.php` (115 líneas)
**12 acciones POST:**
- `getViajes` - Listar viajes
- `getViaje` - Detalle de viaje
- `insertViaje` - Crear viaje (con vehículo)
- `updateViaje` - Actualizar viaje (con vehículo)
- `deleteViaje` - Eliminar viaje
- `getTarifas` - Listar tarifas
- `getTarifa` - Detalle de tarifa
- `insertTarifa` - Crear tarifa
- `updateTarifa` - Actualizar tarifa
- `deleteTarifa` - Eliminar tarifa
- `getTiposTarifa` - Tipos de pasajero
- `getParadasParaTarifas` - Pares origen-destino

**Status:** ✅ FUNCIONAL

---

## 💾 Base de Datos

### Tablas Principales (10)

#### Catálogo
1. **tipo_transporte** (4 registros)
   - Bus, Avión, Tren, Barco

2. **terminal_transporte** (66 registros)
   - Terminales en ARG, BR, PAR, URY
   - Campos: nombre, dirección, ciudad, estado, país, tipo, lat/lon

3. **empresa_transporte** (5 registros)
   - Flecha Bus, LATAM, etc.

4. **ruta_transporte** (8 registros)
   - Rutas configuradas
   - Campos: nombre, tipo, duración, distancia, prestador

5. **ruta_paradas** (24 registros)
   - Paradas múltiples por ruta (origen/destino flexible)
   - Crucial: `idRutaParada` como PK (NO idParada)

#### Vehículos
6. **modelo_vehiculo_transporte** (7 registros)
   - Marcopolo G7 ✓ (50 asientos)
   - Mercedes Comfort ✓ (48 asientos)
   - 5 otros modelos

7. **vehiculo_transporte** (10 registros)
   - AA 150 DP, AA 151 DP, AA 200 MB ✓ (doble piso)
   - 7 otros vehículos

#### Viajes y Tarifas
8. **viaje_transporte** (100+ registros)
   - Salidas específicas con fecha/hora
   - FK: idRuta, idVehiculo
   - Campos: asientos, estado, observaciones

9. **viaje_tarifa** (1000+ registros)
   - Precios por segmento origen-destino
   - Por tipo de pasajero (adulto, niño, etc)
   - Multi-moneda support

10. **tipo_tarifa_pasajero** (4 registros)
    - Adulto, Niño, Senior, Estudiante
    - Con descuentos aplicables

---

## 🧪 Scripts de Validación

Ubicados en raíz del proyecto para debugging:

### `validar_sistema_transporte.php`
- Valida estado completo
- Muestra: tipos, modelos, vehículos, rutas, viajes
- Ejecutar en: `http://localhost/metelebrasil_dev/validar_sistema_transporte.php`

### `fix_modelos_habilitado.php`
- Habilita modelos con habilitado=0
- YA EJECUTADO ✓

### `check_modelos_utf8.php`
- Verifica acentos en nombres de modelos
- Corrige encoding si es necesario

### `check_vehiculos_utf8.php`
- Verifica acentos en vehículos

### `check_vehiculos_doble_piso.php`
- Verifica asignación de doble piso
- Muestra comparativa de todos los vehículos

---

## 🎯 Próximos Pasos (Fase 2)

Ver: **`PLAN_FRONTEND_PASAJES.md`**

### Páginas a Crear (6):
1. `buscar_pasajes.php`
2. `resultados_viajes.php`
3. `viaje_detalle.php`
4. `carrito_pasajes.php`
5. `checkout_pasajes.php`
6. Email template

### Controllers:
- `admin/ctrl/ctrlBusquedaPasajes.php`

### Estimación: 25-35 horas

---

## ✅ Checklist de Cumplimiento

### Backend ✓
- [x] Clases de transporte (40+ funciones)
- [x] Controllers AJAX (12 acciones)
- [x] 10 tablas de BD
- [x] Relaciones y foreign keys
- [x] Multi-moneda support
- [x] Validación de datos

### Admin UI ✓
- [x] Menú TRANSPORTE (5 submenús)
- [x] Gestión de terminales
- [x] Gestión de rutas
- [x] Gestión de paradas
- [x] Gestión de modelos (incluyendo doble piso)
- [x] Gestión de vehículos (incluyendo doble piso)
- [x] Gestión de viajes (con selector de vehículos)
- [x] Gestión de tarifas
- [x] Clases de servicio

### Datos de Prueba ✓
- [x] 66 terminales
- [x] 8 rutas
- [x] 7 modelos (incluyendo 2 doble piso)
- [x] 10 vehículos (incluyendo 3 doble piso)
- [x] 100+ viajes programados
- [x] 1000+ tarifas configuradas

### Bugs Corregidos ✓
- [x] Modelos no visibles (habilitado=0)
- [x] UTF-8 encoding
- [x] Sidebar N-level support
- [x] JOIN clause error en getAllModelos()
- [x] Funciones duplicadas

---

## 📞 Soporte Rápido

**¿Dónde está X?**
- Admin Menu: Menú TRANSPORTE (F9 para toggle)
- Validación rápida: `validar_sistema_transporte.php`
- Documentación: Este archivo

**¿Cómo crear un viaje?**
1. Ir a: Admin → TRANSPORTE → Crear Viaje
2. Seleccionar ruta
3. Seleccionar vehículo (con auto-load de capacidad)
4. Ingresar fecha/hora
5. Confirmar

**¿Cómo configurar tarifas?**
1. Ir a: Admin → TRANSPORTE → Tarifas
2. Seleccionar viaje
3. Ingresar precios por segmento × tipo pasajero
4. Guardar

**¿Cómo crear un nuevo modelo?**
1. Ir a: Admin → TRANSPORTE → Modelos
2. Click [Nuevo Modelo]
3. Ingresar: nombre, tipo, filas×columnas, capacidad
4. Guardar

---

## 🔗 Enlaces Útiles

**Frontend:**
- `index.php` - Página de inicio
- `categorias.php` - Categorías de servicios

**Admin:**
- `admin/index.php` - Dashboard
- `admin/viajesTransporteLista.php` - Lista de viajes
- `admin/viajeTransporteAlta.php` - Crear viaje
- `admin/viajeTransporteTarifas.php` - Editor de tarifas

**Documentación:**
- `RESUMEN_FINAL_TRANSPORTE_ENERO_2026.md` ⭐
- `PLAN_FRONTEND_PASAJES.md` ⭐
- `RESUMEN_SISTEMA_TRANSPORTE_ENERO_2026.md`

**Validación:**
- `validar_sistema_transporte.php`

---

**Última Actualización:** 12 de Enero, 2026
**Status:** ✅ COMPLETADO - Sistema Operacional
**Próxima Fase:** Frontend (búsqueda y compra)


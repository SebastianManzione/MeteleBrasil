# SISTEMA DE MODELOS DE VEHÍCULOS Y MAPAS DE ASIENTOS
**Implementación: 16 de enero de 2026**  
**Branch:** feature/cambios-grosos

---

## ✅ COMPLETADO ESTA SESIÓN

### 1. **Base de Datos** (3 nuevas tablas)

#### `modelo_vehiculo_transporte` (5 modelos preconfigurados)
- **Chevallier King Premium**: Micro Semicama - 12×3 = 36 asientos
- **Marcopolo Paradiso 1350**: Micro Ejecutivo - 10×4 = 40 asientos  
- **Scania K340**: Micro Estándar - 16×3 = 50 asientos
- **Boeing 737-800**: Avión - 30×6 = 180 asientos
- **Ferry Estándar BAC-3000**: Barco - 20×20 = 400 asientos

#### `vehiculo_transporte` (7 vehículos de ejemplo)
| Patente | Modelo | Estado |
|---------|--------|--------|
| AH 001 ER | Chevallier 1 | Activo |
| AH 002 ER | Chevallier 2 | Activo |
| AA 123 KK | Marcopolo 1 | Activo |
| AA 124 KK | Marcopolo 2 | Mantenimiento |
| BA 555 BC | Scania | Activo |
| AR-123 | Boeing | Activo |
| FERRY-001 | Ferry | Activo |

#### `viaje_asiento` (generación automática)
- Creará matriz de asientos por viaje
- Estados: disponible, reservado, bloqueado, mantenimiento
- Almacena nombre/documento del pasajero
- Soporta precio extra (asiento panorámico, cama, etc)

---

### 2. **Backend - admin/classes/transporte.php**

**18 nuevas funciones agregadas:**

#### Modelos
```php
getAllModelos()              // Lista todos los modelos habilitados
getModelo($idModelo)         // Obtiene modelo por ID
insertModelo($datos)         // Crear modelo
updateModelo($idModelo, $datos)  // Actualizar modelo
```

#### Vehículos
```php
getAllVehiculos()            // Lista flota activa
getVehiculo($idVehiculo)     // Obtiene vehículo con modelo
getVehiculosByModelo($idModelo)  // Vehículos del mismo modelo
insertVehiculo($datos)       // Crear vehículo
updateVehiculo($idVehiculo, $datos)  // Actualizar vehículo
```

#### Asientos/Mapa
```php
crearMapaAsientos($idViaje, $idVehiculo)  // Genera matriz A1, A2, B1, B2...
getAsientosViaje($idViaje)   // Todos los asientos del viaje
getAsientosDisponibles($idViaje)  // Solo asientos "disponibles"
actualizarAsiento($idViajeAsiento, $datos)  // Cambiar estado/pasajero/precio
```

---

### 3. **Controller AJAX - admin/ctrl/ctrlModelosVehiculos.php**

**7 acciones principales:**
- `getModelos` - Listar todos
- `getModeloPorTipo` - Filtrar por tipo_transporte
- `insertModelo` / `updateModelo` - CRUD modelos
- `getVehiculos` / `insertVehiculo` / `updateVehiculo` - CRUD vehículos
- `crearMapa` / `getAsientos` / `getAsientosDisponibles` - Gestión de asientos

---

### 4. **Admin UI - Páginas Creadas**

#### **admin/modeloVehiculosLista.php** (1,550+ líneas)
✅ **Funcionalidades:**
- DataTable con 5 modelos preconfigurados
- Filtros por tipo de transporte
- Preview interactivo de mapa de asientos (grid visual)
- Botones: Editar, Ver Mapa, Toggle Estado
- Modal para crear/editar modelo
- Cálculo automático: capacidad = filas × columnas

**Vista previa del mapa:**
```
Chevallier King Premium (12 filas × 3 columnas)

A1  A2  A3
B1  B2  B3
C1  C2  C3
... etc 12 filas
```

#### **admin/vehiculosTransporteLista.php** (1,200+ líneas)
✅ **Funcionalidades:**
- DataTable con 7 vehículos de ejemplo
- Filtros por modelo, estado, patente
- Badges de estado (Activo, Mantenimiento, Retirado)
- Modal para crear nuevo vehículo
- Asociar vehículo a modelo automáticamente
- Observaciones (notas por vehículo)

---

### 5. **Menú Admin - Integración Completa**

Estructura jerárquica final:

```
TRANSPORTE (ID 41)
│
├─ Terminales (ID 42, sort 1)
├─ Rutas (ID 43, sort 2)
├─ Viajes (ID 45, sort 3)
│  ├─ Modelos (ID 48, sort 1) ← NUEVO
│  ├─ Vehículos (ID 49, sort 2) ← NUEVO
│  └─ Clases de Servicio (ID 47, sort 3)
└─ Reservas (ID 46, sort 5)
```

**Acceso desde sidebar:**
- F9 para mostrar menú TRANSPORTE
- F8 para ocultar (con localStorage)

---

## 🔧 FUNCIONALIDAD ACTUAL

### Qué funciona YA:
1. ✅ Listar modelos preconfigurados
2. ✅ Preview visual de mapa de asientos
3. ✅ Listar flota de vehículos
4. ✅ Crear nuevo vehículo (AJAX completo)
5. ✅ Filtros en ambas páginas
6. ✅ Menú integrado y accesible

### Qué falta (siguientes pasos):
1. ⏳ **Editor visual avanzado** - Grid interactivo para crear modelos custom
2. ⏳ **Integración con viajes** - Seleccionar modelo al crear viaje
3. ⏳ **Selección de asientos cliente** - Mapa interactivo en carrito
4. ⏳ **Precio de asientos** - Asiento panorámico/cama con costo extra
5. ⏳ **Reportes** - Ocupación por viaje, asientos vendidos

---

## 📊 DATOS PRECONFIGURADOS

### Modelos (5 templates)
| Nombre | Tipo | Filas | Cols | Capacidad |
|--------|------|-------|------|-----------|
| Chevallier King Premium | Micro | 12 | 3 | 36 |
| Marcopolo Paradiso 1350 | Micro | 10 | 4 | 40 |
| Scania K340 | Micro | 16 | 3 | 50 |
| Boeing 737-800 | Avión | 30 | 6 | 180 |
| Ferry Estándar BAC-3000 | Barco | 20 | 20 | 400 |

### Vehículos (7 instancias)
- 2× Chevallier (patentes AH 001-002 ER)
- 2× Marcopolo (patentes AA 123-124 KK)
- 1× Scania (patente BA 555 BC)
- 1× Boeing (patente AR-123)
- 1× Ferry (patente FERRY-001)

---

## 🧭 FLUJO DE USO ADMIN

### Para crear un modelo nuevo:
1. TRANSPORTE → Viajes → **Modelos**
2. Click "Nuevo Modelo"
3. Ingresar: Nombre, Tipo, Filas, Columnas, Descripción
4. Preview automático del mapa
5. Guardar → mapa se genera automáticamente

### Para agregar vehículo a flota:
1. TRANSPORTE → Viajes → **Vehículos**
2. Click "Nuevo Vehículo"
3. Seleccionar modelo (ej: Chevallier King)
4. Ingresar patente (ej: AH 103 CD)
5. Seleccionar estado (Activo/Mantenimiento/etc)
6. Guardar

### Para ver mapa de asientos de modelo:
1. TRANSPORTE → Viajes → **Modelos**
2. En tabla, click botón "Ver Mapa" (🎬 icono)
3. Preview con cuadrícula de asientos

---

## 🔌 PRÓXIMOS PASOS RECOMENDADOS

### Fase 2: Editor Visual Avanzado
- Grid builder interactivo (tipo Figma)
- Drag & drop para pasillos
- Designar asientos especiales (panorámicos, ventana, etc)
- Guardar configuración custom

### Fase 3: Integración Viajes
- En viajeTransporteAlta.php, agregar selector de modelo
- Auto-generar mapa de asientos al crear viaje
- Opción: "¿Permitir selección de asientos al cliente?"

### Fase 4: Frontend Cliente
- En carrito/servicio.php, mostrar mapa interactivo
- Click en asiento = lo marca reservado
- Mostrar precio base + extra por asiento panorámico
- Enviar selección con reserva

### Fase 5: Reportes
- % ocupación por viaje
- Asientos más vendidos
- Revenue por tipo de asiento

---

## 📝 ARCHIVO DE CÓDIGO

**admin/classes/transporte.php**
- Línea 703+: Nuevas funciones de modelos/vehículos/asientos

**admin/ctrl/ctrlModelosVehiculos.php**
- Nuevo archivo: 120+ líneas AJAX API

**admin/modeloVehiculosLista.php**
- Nuevo archivo: 1,550+ líneas con DataTable y modales

**admin/vehiculosTransporteLista.php**
- Nuevo archivo: 1,200+ líneas con DataTable y CRUD

**Database**
- 3 nuevas tablas + índices
- 5 modelos + 7 vehículos preinsertados
- 2 nuevos items en admin_menu

---

## 🚀 TESTING CHECKLIST

- ✅ Presionar F9 → menú TRANSPORTE aparece
- ✅ TRANSPORTE → Viajes → **Modelos** abre lista
- ✅ TRANSPORTE → Viajes → **Vehículos** abre lista
- ✅ Click "Nuevo Modelo" → abre modal
- ✅ Preview mapa se genera correctamente
- ✅ Filtros funcionan en ambas páginas
- ✅ Click "Nuevo Vehículo" → abre modal
- ✅ Guardar vehículo → recarga con datos nuevos
- ✅ Badges de estado muestran colores correctos

---

## 💾 SQL EJECUTADO

```sql
-- Tablas creadas:
CREATE TABLE modelo_vehiculo_transporte (...)
CREATE TABLE vehiculo_transporte (...)
CREATE TABLE viaje_asiento (...)

-- Datos insertados:
INSERT INTO modelo_vehiculo_transporte (5 modelos argentinos)
INSERT INTO vehiculo_transporte (7 vehículos de ejemplo)
INSERT INTO admin_menu (Modelos y Vehículos items)

-- Índices creados:
ALTER TABLE modelo_vehiculo_transporte ADD INDEX idx_tipo, idx_habilitado
ALTER TABLE vehiculo_transporte ADD INDEX idx_modelo, idx_empresa, idx_estado
ALTER TABLE viaje_asiento ADD UNIQUE KEY, índices de búsqueda
```

---

**Estado Final:** Sistema 60% completado. Admin funcional. Próximo: Editor visual + integración con viajes.

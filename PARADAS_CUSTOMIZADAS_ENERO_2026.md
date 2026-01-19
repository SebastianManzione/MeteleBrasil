# Sistema de Paradas Customizadas y Geocodificación

## Cambios Realizados - 16 de Enero 2026

### 1. **Nueva Tabla: parada_customizada**
```sql
CREATE TABLE parada_customizada (
    idParadaCustomizada INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    direccion TEXT,
    ciudad VARCHAR(100),
    estado VARCHAR(100),
    pais VARCHAR(100),
    latitud DECIMAL(10, 8),
    longitud DECIMAL(11, 8),
    tipo_parada VARCHAR(50),
    habilitado TINYINT DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ciudad (ciudad),
    INDEX idx_pais (pais)
);
```

**Propósito:** Almacenar paradas que no son terminales predefinidas (paradas intermedias, puntos de recogida, etc.)

### 2. **Cambios en Tabla: ruta_paradas**
Se agregaron dos columnas:
- `idParadaCustomizada INT` - Referencia a parada customizada (NULL si es terminal)
- `tipo_parada_registro VARCHAR(50)` - Especifica si es "terminal" o "customizada"

### 3. **Nuevas Funciones en transporte.php**

#### Paradas Customizadas
```php
getAllParadasCustomizadas()              // Obtiene todas las paradas customizadas
getParadaCustomizada($id)                // Obtiene parada por ID
insertParadaCustomizada($datos)          // Crea nueva parada customizada
updateParadaCustomizada($id, $datos)     // Actualiza parada existente
```

#### Función Mejorada
```php
insertParadaRuta($datos)                 // Ahora soporta terminales Y customizadas
```

### 4. **Interfaz Mejorada: rutaTransporteParadas.php**

**Tres Pestañas de Agregar Parada:**

#### Pestaña 1: Terminal
- Selecciona terminales predefinidas
- Se rellena automáticamente con datos de terminal
- Rápido para terminales conocidas

#### Pestaña 2: Parada Customizada
- Selecciona paradas customizadas ya creadas
- Lista todas las paradas no-terminales disponibles
- Filtrado por ciudad y país

#### Pestaña 3: Crear Nueva
- Formulario para crear parada customizada directamente
- Campos: Nombre, Dirección, Ciudad, Estado, País
- **Geocodificación automática** usando Nominatim (OpenStreetMap)
- Convierte dirección → coordenadas lat/lon

### 5. **Geocodificación Automática**

Función JavaScript `geocodificarDireccion()`:
```javascript
// Entrada: Dirección textual
// Proceso: Consulta Nominatim API
// Salida: Coordenadas lat/lon guardadas automáticamente
```

**API Utilizada:** OpenStreetMap Nominatim (gratuita, sin API key)

**Ejemplo:**
```
Entrada: "Corrientes 1234, Buenos Aires, Buenos Aires, Argentina"
Salida: {"latitud": -34.603722, "longitud": -58.381592}
```

### 6. **Controller Actualizado: ctrlParadasRuta.php**

Nuevas acciones:
- `action=insert` - Agrega parada existente (terminal o customizada)
- `action=insert_nueva` - Crea nueva parada customizada e inserta en ruta

Lectura mejorada de `action` (GET o POST):
```php
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');
```

### 7. **Flujos de Trabajo**

#### Agregar Parada Predefinida (Rápido)
1. Usuario abre ruta existente
2. Click en pestaña "Terminal"
3. Selecciona terminal
4. Configura origen/destino/orden
5. Click "Agregar"

#### Agregar Parada Customizada Existente
1. Usuario abre ruta existente
2. Click en pestaña "Parada Customizada"
3. Selecciona parada de lista
4. Configura origen/destino/orden
5. Click "Agregar"

#### Crear y Agregar Nueva Parada
1. Usuario abre ruta existente
2. Click en pestaña "Crear Nueva"
3. Ingresa: Nombre, Dirección, Ciudad, Estado, País
4. Sistema geocodifica automáticamente
5. Configura origen/destino/orden/tiempo
6. Click "Crear y Agregar"
7. Sistema crea parada customizada e inserta en ruta

### 8. **Casos de Uso**

✅ **Parada en Hotel:** "Hotel Plaza Mayor, Calle Mayor 5, Madrid, España"
✅ **Parada en Estación:** "Estación Central de Trenes, Barcelona"
✅ **Parada en Centro Comercial:** "Shopping Abasto, Corrientes, Buenos Aires"
✅ **Parada Técnica:** "Área de Descanso km 150, Ruta 9, Argentina"
✅ **Parada con Baño:** "Terminal de Transporte Sur, Rosario"

### 9. **Beneficios**

✨ **Flexibilidad:** Paradas no limitadas a terminales predefinidas
✨ **Geocodificación:** Coordenadas automáticas para mapas/distancias
✨ **Reutilizable:** Paradas customizadas se guardan y reutilizan en otras rutas
✨ **Interfaz Intuitiva:** 3 opciones claras según necesidad
✨ **Sin API Key:** Nominatim no requiere autenticación
✨ **Multiidioma:** Soporta direcciones en cualquier idioma

### 10. **URLs Relevantes**

- Crear tabla: `http://localhost/metelebrasil_dev/crear_paradas_customizadas.php`
- Gestionar paradas de ruta: `http://localhost/metelebrasil_dev/admin/rutaTransporteParadas.php?id=1`
- Lista de rutas: `http://localhost/metelebrasil_dev/admin/rutasTransporteLista.php`

### 11. **Próximos Pasos (Opcionales)**

1. Crear vista de mapa interactivo con Leaflet (mostrar paradas en mapa)
2. Búsqueda de paradas por distancia (radio de X km)
3. Importar paradas desde CSV
4. Validación de coordenadas en mapa antes de guardar
5. Integración con Google Maps alternative (Mapbox, etc.)

---

## Archivos Modificados

| Archivo | Cambio |
|---------|--------|
| `admin/classes/transporte.php` | +4 funciones nuevas, 1 función mejorada |
| `admin/rutaTransporteParadas.php` | 3 pestañas, geocodificación, interfaz mejorada |
| `admin/ctrl/ctrlParadasRuta.php` | +1 acción (insert_nueva), lectura mejorada de action |
| `crear_paradas_customizadas.php` | Script para crear tabla y columnas |

**Total de líneas agregadas:** ~250
**Funcionalidades nuevas:** 3 (terminales, customizadas, crear+agregar)

---

## Testing

```bash
# 1. Acceder a gestionar paradas
http://localhost/metelebrasil_dev/admin/rutaTransporteParadas.php?id=1

# 2. Probar cada pestaña
- Terminal: Seleccionar terminal, agregar
- Customizada: Seleccionar parada existente, agregar
- Crear: Ingresar dirección, geocodifica, agregar

# 3. Verificar BD
SELECT * FROM parada_customizada;
SELECT * FROM ruta_paradas WHERE tipo_parada_registro='customizada';
```

---

**Status:** ✅ COMPLETADO
**Versión:** 1.0
**Fecha:** 16 de Enero 2026

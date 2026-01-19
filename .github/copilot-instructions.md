# Instrucciones Copilot para MeteleBrasil

## 🌟 NOVEDAD ENERO 2026 - PLANOS REALES DE MICROS

### ¡INTEGRACIÓN DE mundocolectivo.com.ar!

MeteleBrasil tiene un plan ZARPADO: integrar 3,090 planos reales de micros para diferenciarse en el mercado.

**Estado:** 📋 Listo para implementar (7-9 horas de trabajo)
**Documentación:** Leer `README_PLANOS_REALES.md` PRIMERO, luego `PLAN_PLANOS_REALES_MICROS.md`
**Quick Start:**
1. `python descargar_planos_mundocolectivo.py` - Descargar imágenes
2. `mysql -u root metelebrasil_experimental < migrations/014_planos_reales_mundocolectivo.sql` - Crear tabla
3. `python procesar_planos_ocr.py` - Procesar con OCR
4. Implementar `pasaje_detalle.php` + `js/gestor_asientos.js`

**Impacto esperado:** +20-40% conversión, -25% tiempo de compra, -15% cancelaciones

**Archivos clave:**
- `README_PLANOS_REALES.md` - Guía de inicio rápido (START HERE!)
- `PLAN_PLANOS_REALES_MICROS.md` - Plan técnico completo (600+ líneas)
- `ESTADO_PROYECTO_TRANSPORTE.md` - Contexto general
- `descargar_planos_mundocolectivo.py` - Script listo para ejecutar
- `migrations/014_planos_reales_mundocolectivo.sql` - Schema BD listo

---

## ⚠️ INFORMACIÓN CRÍTICA - LEER PRIMERO

### Bases de Datos - IMPORTANTE
**SIEMPRE verifica qué base de datos usar antes de hacer queries:**

1. **`metelebrasil`** (PRODUCCIÓN - SOLO LECTURA para desarrollo):
   - Base de datos en PRODUCCIÓN ONLINE
   - **NO MODIFICAR** durante desarrollo
   - Solo usar para consultas de referencia
   - Contiene servicios, reservas, usuarios actuales

2. **`metelebrasil_experimental`** (DESARROLLO - TODO NUEVO VA AQUÍ):
   - **Base de datos para TODO el desarrollo nuevo**
   - Sistema de TRANSPORTE (pasajes bus/avión/tren)
   - Tabla `terminal_transporte` (33 terminales)
   - Tabla `ruta_transporte`, `viaje_transporte`
   - Tabla `hoteles` (AQUÍ, no en metelebrasil)
   - **TODO lo nuevo se crea aquí primero**
   - Luego se migrará a producción cuando esté listo
   - **SIEMPRE usar PDO con conexión directa:**
     ```php
     $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4', 'root', '');
     ```

3. **Regla de oro:**
   - ¿Es desarrollo NUEVO (hoteles, terminales, rutas, etc.)? → `metelebrasil_experimental`
   - ¿Solo necesitas consultar datos existentes? → `metelebrasil` (solo lectura)
   - **EN CASO DE DUDA:** Usar `metelebrasil_experimental`

4. **Migración futura:**
   - Eventualmente se unirá `metelebrasil_experimental` con producción
   - Por eso TODO el desarrollo debe estar en experimental
   - Facilita la migración con scripts SQL

### Google Maps API
- **API Key:** `AIzaSyDdetJDksIXOsWVt7UQx9EF3ulkhYNJsmE` (definida en `config/config.php`)
- **Librerías requeridas:** `&libraries=places` para autocomplete
- **Reverse Geocoding:** Ver `admin/altaSalidas.php` (líneas 1540-1600) como referencia
- **Pattern correcto:**
  ```php
  <?php
  if (!defined('GOOGLE_MAPS_API_KEY')) {
      require_once(__DIR__ . '/../config/config.php');
  }
  ?>
  <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>&libraries=places"></script>
  ```

### Sistema de Hoteles (EN DESARROLLO)
- **BD:** `metelebrasil_experimental` (TODO va aquí)
- **Tabla:** `hoteles` 
- **Controller:** Crear en `admin/ctrl/ctrlHoteles.php`
- **Estructura:** idHotel, nombre, idServicio, ciudad, estado, pais, latitud, longitud, direccion
- Los hoteles pueden vincularse a servicios turísticos
- **IMPORTANTE:** Usar Google Maps con reverse geocoding (como terminales)
- **Estado:** Tabla creada, lista para ABM completo

### Sistema de Terminales de Transporte (NUEVO - Enero 2026)
- **BD:** `metelebrasil_experimental`
- **Tabla:** `terminal_transporte` (33 registros)
- **Archivos:**
  - `admin/terminalAlta.php` - Editor con Google Maps + reverse geocoding
  - `admin/terminalesLista.php` - Lista de terminales
  - `admin/ctrl/ctrlTerminalesNuevo.php` - Controller
- **Características:**
  - Google Places Autocomplete
  - Reverse Geocoding (click/drag en mapa autocompleta TODO)
  - Campos: nombre, idTipoTransporte, ciudad, estado, pais, latitud, longitud
  - Campos readonly: ciudad, estado, pais, direccion (se completan solos)
- **Documentación:** `SISTEMA_TERMINALES_COMPLETADO.md`

---

## Descripción General del Proyecto
**MeteleBrasil** es una plataforma online de reserva de actividades, excursiones y paseos en barco. Permite a usuarios buscar, filtrar y comprar servicios turísticos con soporte multi-moneda, geolocalización e integración de pagos.

**Stack Tecnológico:** PHP procedural, MySQL/MySQLi/PDO, jQuery/JavaScript, XAMPP (desarrollo local)

---

## Arquitectura y Flujo de Datos

### Componentes Principales

**Capa Frontend** (`/` root, `/includes/`)
- `index.php`: Página de inicio con detección de geolocalización y recomendaciones
- `includes/navbar.php`: Inicialización de sesión, geolocalización, selección idioma/moneda
- Sistema de idiomas dinámico: `admin/lang/{ES,EN,PT,IT}.php`
- Diferentes vistas según dispositivo: `*Movil.php` para mobile

**Extranet (Panel Administrativo)** (`/admin/`)
- `index.php`: Dashboard principal con métricas (servicios, salidas, usuarios, visitas)
- **Gestión de Servicios:** `serviciosLista.php`, `altaServicio.php`, `servicioVer.php`
- **Gestión de Salidas:** `salidasLista.php`, `altaSalidas.php`, `salidasEditar.php`
- **Gestión de Tarifas:** `tarifasEditor.php`, `tarifasEditorXPack.php`
- **Reservas:** `carritosLista.php`, `reservaDetalles.php`, `reservasEstado.php`
- **Financiero:** `financieroLista.php`, `comisionesLista.php`, `export_comisiones_csv.php`
- **Prestadores:** `prestadores.php`, `prestadoresAlta.php`
- **AJAX/Controllers:** `admin/ctrl/` con endpoints para operaciones dinámicas

**Capa de Lógica de Negocio** (`/admin/classes/`)
- **Servicios:** `servicio.php` (getAllServicios, getServicio, altaServicio, updateServicio)
- **Salidas:** `salidas.php` (getSalidas, getSalida, getAllSalidasServicio, getSalidasServicio)
- **Tarifas:** `tarifas.php` (getTarifa, getTarifas, alterarTarifa por rango de edades)
- **Reservas:** `reserva.php` (getReserva, getReservaWithItems, flujo de booking)
- **Usuarios:** `usuario.php`, `prestador.php`
- **Servicios Adicionales:** `servicios_adicionales.php` (traslados, comidas, etc.)
- **Comisiones:** `comisiones.php`, `comision_prestador.php`, `servicio_comision_prestador.php`
- **Utilidades:** `moneda.php` (conversión), `geolocalizacion.php` (localización), `funciones.php`

**Capa de Datos** (`/admin/classes/`)
- `conexion.php`: Conexión PDO (dev: localhost, prod: servidor remoto)
- `config/db.php`: Conexión MySQLi como fallback
- Tablas core: `servicio`, `servicio_salidas`, `servicio_salidas_tarifas`, `reservas`, `usuario`, `prestadores`

### Gestión de Sesiones
- Se inicializa en `includes/navbar.php` (verificación PHP_SESSION_NONE)
- Variables de sesión clave:
  - `$_SESSION['moneda_sel']` / `['moneda_sel_sym']`: Moneda seleccionada
  - `$_SESSION['idioma']`: Idioma del sistema (ES, EN, PT, IT)
  - `$_SESSION['geoFinal']`: Datos de geolocalización (país, ciudad, lat/lon)
  - `$_SESSION['login']`: Estado de autenticación (idUsuario, idPrestador, rol)
  - `$_SESSION['impuestos_pais']`: Tasas impositivas por país

### Manejo de Entornos
- **Detección:** `config/config.php` identifica dev vs. prod por hostname ("server" = prod)
- **Logs de Errores:** Dev muestra errores; prod registra en `logs/` sin visualizar
- **Conexión BD:** PDO es el principal, MySQLi es fallback

---

## Patrones y Convenciones Críticos

### Estructura de Servicios, Salidas y Tarifas
- **Servicios** (`servicio`): Catálogo de actividades (excursiones, paseos, etc.)
- **Categorías** (`categoria_servicio`): Agrupación de servicios
- **Salidas** (`servicio_salidas`): Fechas/horarios específicos de un servicio
- **Tarifas** (`servicio_salidas_tarifas`): Precios por rango de edad en cada salida
  - Usa `idFromEdad` y `idToEdad` para rangos
  - Campo `valor` es el precio bruto
  - Campo `comisiona` indica si aplica comisión

**Flujo:** Servicio → Salidas → Tarifas por edad → Cálculo de reserva

### Servicios Adicionales
- Catálogo en `servicios_adicionales` (traslados, comidas, tours extra, etc.)
- Precios por salida en `servicio_salidas_adicionales` con `valor` e `idMoneda`
- Se compran junto con reserva en `reserva_adicionales`

### Integración de Pagos
**PayPal** (`/admin/pasarelas/PayPal/`)
- SDK vendor en namespace `PayPal\Api\*`
- Flujo: Crear `Payment` → Establecer `Payer`, `Transaction`, `RedirectUrls` → Ejecutar

**MercadoPago** (`/config/mercadopago.php`)
- Config multi-región (AR, BR) con credenciales sandbox/producción
- Webhooks: `mercadopago_webhook_ar.php`, `mercadopago_webhook_br.php`

### Manejo de Monedas
- `admin/classes/moneda.php`: `ConvierteMoneda()` convierte precios
- Tabla `moneda` tiene catálogo de monedas activas
- Tabla `moneda_cambio` almacena tasas de cambio
- Mostrar siempre en `$_SESSION['moneda_sel_sym']` del usuario

### Sistema Multi-Idioma
- Archivos: `admin/lang/{CODIGO_LANG}.php` (ES, EN, PT, IT)
- BD soporta campos por idioma: `_en`, `_pt`, `_it`

### Geolocalización
- `admin/classes/geolocalizacion.php` → `getGeolocalizacionData()`
- Usado para auto-selección moneda, cálculo impuestos, recomendaciones por distancia

### Comisiones y Financiero
- **Prestadores:** Comisión global en `prestador_comision` 
- **Servicio específico:** Override en `servicio_comision_prestador`
- **Tarifa individual:** Cálculo en `reserva_tarifas` al crear reserva
- Reportes: `financieroLista.php`, `comisionesLista.php` con conversión moneda

---

## Esquema de Base de Datos (Tablas Principales)

### Servicios y Catálogo
| Tabla | Descripción |
|-------|-----------|
| `servicio` | Catálogo de actividades |
| `categoria_servicio` | Categorías de servicios |
| `servicio_img` | Fotos de servicios |
| `servicio_salidas` | Fechas/horarios específicos |
| `servicio_salidas_tarifas` | Precios por rango edad |
| `servicio_salidas_adicionales` | Extras por salida |
| `servicios_adicionales` | Catálogo de extras |
| `destinos` | Destinos turísticos |

### Reservas
| Tabla | Descripción |
|-------|-----------|
| `reservas` | Cabecera de reserva |
| `reserva_horarios` | Detalles de salidas |
| `reserva_tarifas` | Precio por pasajero/tarifa |
| `reserva_pasajeros` | Nombres de pasajeros |
| `reserva_adicionales` | Extras contratados |
| `reserva_notas` | Notas internas |

### Usuarios y Actores
| Tabla | Descripción |
|-------|-----------|
| `usuario` | Cuentas (clientes, vendedores, admin) |
| `prestadores` | Proveedores de servicios |
| `prestador_comision` | Comisiones por prestador |
| `servicio_comision_prestador` | Comisión especial por servicio |
| `agencias` | Agencias de viaje |

### Pagos y Financiero
| Tabla | Descripción |
|-------|-----------|
| `comprobante` | Comprobante de pago |
| `usuario_comisiones` | Comisiones ganadas |
| `usuario_comprobantes` | Resumen pagos usuario |

### Configuración
| Tabla | Descripción |
|-------|-----------|
| `moneda` | Catálogo de monedas |
| `moneda_cambio` | Tasas de cambio |
| `impuestos_pais` | Impuestos por país |
| `paises` | Catálogo de países |
| `estados_reserva` | Estados de reserva |
| `tipos_tarifa` | Tipos de tarifa |
| `config` | Configuración empresa |
| `cupones_descuento` | Códigos promocionales |

---

## Flujos de Trabajo Comunes

### Agregar un Nuevo Servicio (Extranet)
1. `admin/altaServicio.php`: Completar formulario, vinculado a categoría y prestador
2. `admin/servicioFotos.php`: Cargar fotos → tabla `servicio_img`
3. `admin/altaSalidas.php`: Crear salidas con fechas, horarios, disponibilidad
4. `admin/tarifasEditor.php`: Definir tarifas por rango edad
5. `admin/serviciosAdicionalesAlta.php`: Vincular servicios adicionales

### Flujo de Reserva (Frontend)
1. **`carrito.php`**: Recolectar fechas, personas, servicios vía form/AJAX
2. **`guardaReservas.php`**: Insertar en `reservas`, `reserva_horarios`, `reserva_tarifas`
3. **Pago**: Redirección a PayPal o MercadoPago
4. **Webhook**: Actualizar estado a "confirmada"
5. **Email**: Confirmación vía `email_reserva_confirmada.php`

### Gestión de Reservas (Extranet)
1. `admin/carritosLista.php`: Listado con filtros por fecha/estado
2. `admin/reservaDetalles.php`: Detalle completo con ítems y comisiones
3. `admin/reservasEstado.php`: Cambiar estado manualmente
4. `reserva_notas`: Agregar notas para auditoría
5. Comisiones se calculan automáticamente

### Reportes Financieros (Extranet)
1. `admin/financieroLista.php`: Ingresos por reserva, convertidos a moneda usuario
2. `admin/financieroPrestador.php`: Filtrado por prestador
3. `admin/comisionesLista.php`: Desglose de comisiones
4. `admin/export_comisiones_csv.php`: Exportar para contabilidad

---

## Ubicaciones de Archivos Clave

| Archivo/Carpeta | Responsabilidad |
|-------------|-----------------|
| `config/config.php` | Detección entorno (dev/prod) |
| `config/mercadopago.php` | Credenciales MercadoPago por país |
| `includes/navbar.php` | Sesión, geolocalización, idioma/moneda |
| `includes/detect_device.php` | Detección mobile |
| `admin/index.php` | Dashboard extranet |
| `admin/classes/servicio.php` | CRUD servicios |
| `admin/classes/salidas.php` | CRUD salidas |
| `admin/classes/tarifas.php` | CRUD tarifas |
| `admin/classes/reserva.php` | CRUD reservas |
| `admin/classes/comisiones.php` | Cálculos comisión |
| `admin/ctrl/ctrlMoneda.php` | Cambio de moneda |
| `admin/ctrl/ctrlIdioma.php` | Cambio de idioma |
| `admin/ctrl/ctrlHorarios.php` | Fetch horarios (AJAX) |
| `admin/lang/{ES,EN,PT,IT}.php` | Strings por idioma |
| `admin/pasarelas/` | PayPal, MercadoPago |
| `admin/email/` | Templates de email |
| `js/navbar-functions.js` | Interacciones navbar |
| `js/traeHorarios.js` | Fetch horarios |
| `img/countries/` | Banderas países |

---

## Trampas Importantes

1. **Conexiones BD:** PDO es el principal (`conexion.php`), MySQLi es fallback (`db.php`)
2. **Sesión:** Verificar `session_status() === PHP_SESSION_NONE` antes de `session_start()`
3. **Fechas:** Formato BD es `YYYY-MM-DD`; conversión necesaria para visualización
4. **AJAX:** POST a `admin/ctrl/*.php`; respuestas JSON o texto plano
5. **Monedas:** Cada salida/tarifa tiene `idMoneda`; convertir al mostrar con `ConvierteMoneda()`
6. **Códigos reserva:** `codigoAmigable` es lo que ve el cliente (no `idReserva`)
7. **Edades:** Usa rangos `idFromEdad` → `idToEdad` en tabla `edades`
8. **Estados:** Revisar valores en `estados_reserva` (1=pendiente, 2=confirmada, 3=cancelada)
9. **Prestadores:** Un servicio → un prestador; comisiones variables por servicio

---

## ✅ CHECKLIST ANTES DE HACER CAMBIOS

### Antes de crear/modificar queries SQL:
1. ✅ **Verificar qué BD usar:** ¿metelebrasil o metelebrasil_experimental?
2. ✅ **Verificar tabla existe:** `SHOW TABLES LIKE 'nombre_tabla'`
3. ✅ **Verificar estructura:** `DESCRIBE nombre_tabla` antes de INSERT/UPDATE
4. ✅ **Verificar datos existentes:** `SELECT * FROM tabla LIMIT 5` para ver ejemplos

### Antes de modificar archivos PHP:
1. ✅ **Leer archivo completo primero:** No asumir estructura, verificar líneas exactas
2. ✅ **Buscar código similar existente:** Usar grep_search para encontrar patrones
3. ✅ **Verificar includes/requires:** ¿Qué archivos se cargan? ¿Qué variables globales hay?
4. ✅ **Verificar si usa MySQLi o PDO:** No mezclar conexiones

### Antes de trabajar con Google Maps:
1. ✅ **Verificar API Key está definida:** Buscar GOOGLE_MAPS_API_KEY en config/config.php
2. ✅ **Incluir libraries=places:** Si necesitas autocomplete
3. ✅ **Verificar patrón correcto:** Ver admin/altaSalidas.php o admin/terminalAlta.php
4. ✅ **Campos readonly:** ciudad, estado, pais, direccion deben ser readonly si usan reverse geocoding

### Antes de trabajar con hoteles:
1. ✅ **BD correcta:** `metelebrasil_experimental` (TODO va aquí)
2. ✅ **Tabla:** `hoteles` (verificar estructura con DESCRIBE)
3. ✅ **Controller:** Crear/usar en `admin/ctrl/ctrlHoteles.php` con conexión a experimental
4. ✅ **Vinculación:** Hoteles pueden vincularse a servicios o ser standalone
5. ✅ **Google Maps:** Usar reverse geocoding como en terminales

### Antes de trabajar con terminales:
1. ✅ **BD correcta:** `metelebrasil_experimental` (NO metelebrasil)
2. ✅ **Tabla:** `terminal_transporte` (33 registros)
3. ✅ **Controller:** `admin/ctrl/ctrlTerminalesNuevo.php`
4. ✅ **Campos:** nombre, idTipoTransporte, ciudad, estado, pais, latitud, longitud
5. ✅ **Reverse geocoding:** Ya implementado, ver admin/terminalAlta.php

---

## 📋 SISTEMAS YA IMPLEMENTADOS (No reinventar la rueda)

### ✅ Reverse Geocoding con Google Maps (COMPLETADO)
**Ubicación:** `admin/altaSalidas.php` (líneas 1540-1600), `admin/terminalAlta.php`

**Funcionalidad:**
- Click en mapa → Obtiene dirección completa (país, estado, ciudad, dirección)
- Drag marker → Actualiza todos los campos automáticamente
- Autocomplete → Google Places con sugerencias en tiempo real

**Código de referencia:**
```javascript
var geocoder = new google.maps.Geocoder();
geocoder.geocode({ location: { lat: lat, lng: lng } }, function(results, status) {
    if (status === 'OK' && results[0]) {
        // Extraer address_components
        // Llenar campos: pais, estado, ciudad, direccion
    }
});
```

**No volver a implementar desde cero:** Copiar patrón de terminalAlta.php

### ✅ Sistema de Hoteles (IMPLEMENTADO - Enero 2026)
**BD:** `metelebrasil_experimental`
**Tabla:** `hoteles` con 22 campos (check_in, check_out, precio_desde, etc)
**Datos actuales:** 5 hoteles Costa Atlántica (2-4 estrellas, ARS 3,500-15,000/noche)
**Hoteles insertados:**
1. Hotel Costa Atlántica - San Clemente (3⭐, ARS 8,500)
2. Apart Hotel Las Toninas (2⭐, ARS 6,000)
3. Hotel Mar del Tuyú Resort (4⭐, ARS 15,000)
4. Hostel Joven San Clemente (2⭐, ARS 3,500)
5. Hotel Familiar Las Toninas (3⭐, ARS 7,200)

**Próximo paso:** 
- Crear `admin/hotelAlta.php` con Google Maps (como terminalAlta.php)
- Crear `admin/hotelesLista.php` con DataTables
- Vincular hoteles con rutas de transporte

### ✅ Sistema de Terminales de Transporte (COMPLETADO Enero 2026)
**BD:** `metelebrasil_experimental`
**Tabla:** `terminal_transporte` (33 registros)
**Archivos:**
- `admin/terminalAlta.php` - Editor con Google Maps + autocomplete
- `admin/terminalesLista.php` - Listado
- `admin/ctrl/ctrlTerminalesNuevo.php` - Controller
**Características:**
- ✅ Google Places Autocomplete funcional
- ✅ Reverse Geocoding al click/drag
- ✅ Campos readonly (ciudad, estado, pais) se autocompletan
- ✅ 33 terminales precargados (Argentina, Brasil, Paraguay, Uruguay)

### ✅ Sistema de Filtros Combinables (IMPLEMENTADO Dic 2025)
**Ubicación:** `categorias.php`
**Filtros disponibles:**
- Precio: menor/mayor
- Distancia: más cercano/más lejano
- Combinables entre sí (ej: cercano + menor precio)
**Documentación:** Líneas 950+ en copilot-instructions.md

### ✅ Sistema Multi-Idioma (FUNCIONANDO)
**Archivos:** `admin/lang/{ES,EN,PT,IT}.php`
**BD:** Campos por idioma (_en, _pt, _it)
**Uso:** `$lang['clave']` carga del array según `$_SESSION['idioma']`

### ✅ Sistema de Conversión de Monedas (FUNCIONANDO)
**Archivo:** `admin/classes/moneda.php`
**Función:** `ConvierteMoneda($monto, $idMonedaOrigen, $idMonedaDestino)`
**Tablas:** `moneda`, `moneda_cambio`

---

## Comandos Testing

- **BD:** `metelebrasil` en localhost (root, sin password)
- **Email:** Templates en `admin/email/*.php`
- **Monedas:** Cambiar `$_SESSION['moneda_sel']` y recargar
- **Pagos:** Crear reserva test → verificar webhook → cambio estado
- **AJAX:** Postear a `admin/ctrl/ctrlMoneda.php`, `admin/ctrl/ctrlHorarios.php`, etc.

---

## Próximos Pasos

1. Revisar `includes/navbar.php` para flujo de sesión
2. Explorar `admin/classes/servicio.php` y `salidas.php`
3. Entender: Servicio → Salidas → Tarifas → Reservas
4. Testing completo: Crear salida → tarifas → reserva → pago
5. Revisar `financieroLista.php` para comisiones
6. Explorar `admin/prestadores.php` para gestión de proveedores

---

## Sistema de Filtros Combinables (Implementado - Dic 2025)

### Arquitectura de Filtros en categorias.php

**Parámetros GET duales e independientes:**
- `orden_precio`: `price_asc` (menor precio) o `price_desc` (mayor precio)
- `orden_distancia`: `cercano` (más cercano) o `lejano` (más lejano)

**Combinabilidad:** Los filtros funcionan simultáneamente. Ejemplo:
```
?orden_distancia=cercano&orden_precio=price_asc
```
Ordena primero por distancia (más cercano primero), luego por precio (menor primero) como criterio secundario.

### Lógica de Ordenamiento (categorias.php líneas 99-139)

**Código completo de la lógica principal:**
```php
// Líneas 58-60: Inicialización de variables
$orden_precio = isset($_GET['orden_precio']) ? $_GET['orden_precio'] : '';
$orden_distancia = isset($_GET['orden_distancia']) ? $_GET['orden_distancia'] : '';

// Líneas 99-123: Lógica combinable
if (($orden_distancia === 'cercano' || $orden_distancia === 'lejano') && isset($_SESSION['geoFinal']['latitud'])) {
    // Primero ordenar por distancia (principal)
    $servicios_ordenados = ordenarPorProximidad($servicios_completos, $latUsuario, $lonUsuario, $orden_distancia === 'lejano');
    
    // Luego aplicar ordenamiento secundario de precio si está activo
    if ($orden_precio === 'price_asc' || $orden_precio === 'price_desc') {
        $servicios_ordenados = aplicarOrdenPrecio($servicios_ordenados, $orden_precio);
    }
    
    $servicios = array_slice($servicios_ordenados, $desde, $cantidad_por_pagina);
    $total_registros = count($servicios_ordenados);
} else if ($orden_precio === 'price_asc' || $orden_precio === 'price_desc') {
    // Fallback: solo precio sin distancia
    $servicios_ordenados = aplicarOrdenPrecio($servicios_completos, $orden_precio);
    $servicios = array_slice($servicios_ordenados, $desde, $cantidad_por_pagina);
    $total_registros = count($servicios_ordenados);
}
```

### Funciones Críticas

#### `ordenarPorProximidad($servicios, $latUsuario, $lonUsuario, $inverso = false)`
**Ubicación:** `categorias.php` líneas 127-149

**Código completo:**
```php
function ordenarPorProximidad($servicios, $latUsuario, $lonUsuario, $inverso = false) {
    foreach ($servicios as &$servicio) {
        $latServicio = floatval($servicio['latitud']);
        $lonServicio = floatval($servicio['longitud']);
        
        // Haversine formula para calcular distancia
        $radioTierra = 6371; // en kilómetros
        $dLat = deg2rad($latServicio - $latUsuario);
        $dLon = deg2rad($lonServicio - $lonUsuario);
        
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latUsuario)) * cos(deg2rad($latServicio)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distancia_km = $radioTierra * $c;
        
        $servicio['distancia_km'] = $distancia_km;
    }
    
    // Ordenar por distancia
    usort($servicios, function($a, $b) use ($inverso) {
        if ($inverso) {
            return $b['distancia_km'] <=> $a['distancia_km']; // Más lejano primero
        } else {
            return $a['distancia_km'] <=> $b['distancia_km']; // Más cercano primero
        }
    });
    
    return $servicios;
}
```

**Parámetros:**
- `$inverso = false`: Más cercano primero (default)
- `$inverso = true`: Más lejano primero (nuevo filtro)
- Usa `$_SESSION['geoFinal']['latitud']` y `['longitud']` del usuario

#### `aplicarOrdenPrecio($servicios, $orden)`
**Ubicación:** `categorias.php` líneas 171-181

**Código completo:**
```php
function aplicarOrdenPrecio($servicios, $orden) {
    usort($servicios, function($a, $b) use ($orden) {
        if ($orden === 'price_asc') {
            return $a['precio_min'] <=> $b['precio_min']; // Menor precio primero
        } else {
            return $b['precio_min'] <=> $a['precio_min']; // Mayor precio primero
        }
    });
    return $servicios;
}
```

**Propósito:** Ordenamiento secundario sin romper agrupación por distancia

#### `generarFiltrosPrecio($queryString, $orden_precio, $orden_distancia, $lang)`
**Ubicación:** `categorias.php` líneas 216-262

**Firma actualizada (CRÍTICO - 4 parámetros requeridos):**
```php
function generarFiltrosPrecio($queryString, $orden_precio, $orden_distancia, $lang) {
    // Parsea $queryString para preservar otros parámetros (categoría, búsqueda)
    parse_str($queryString, $params);
    unset($params['orden_precio'], $params['orden_distancia']); // Remover para regenerar
    $baseQuery = http_build_query($params);
    
    // Genera 4 cards de filtro (2 precio + 2 distancia)
    // Cada URL preserva el otro filtro activo
    // Retorna HTML completo con toggle switches estilo MercadoLibre
}
```

**Llamadas actualizadas:**
1. **Sidebar desktop** (línea ~945): 
   ```php
   <?= generarFiltrosPrecio($queryString, $orden_precio, $orden_distancia, $lang); ?>
   ```

2. **Modal móvil** (línea 1248):
   ```php
   <?= generarFiltrosPrecio($queryString, $orden_precio, $orden_distancia, $lang); ?>
   ```

#### `generarFiltrosCategorias($idCategoria, $busqueda, $orden_precio, $lang)`
**Ubicación:** Función helper para sidebar

**Tercer parámetro cambió:** `$orden` → `$orden_precio`

**Llamadas actualizadas:**
1. **Sidebar desktop** (línea 956):
   ```php
   <?= generarFiltrosCategorias($idCategoria, $busqueda, $orden_precio, $lang); ?>
   ```

2. **Modal móvil** (línea 1256):
   ```php
   <?= generarFiltrosCategorias($idCategoria, $busqueda, $orden_precio, $lang); ?>
   ```

### Traducciones Multi-Idioma

**Nuevo string "mas_lejano" agregado en 4 idiomas:**

```php
// admin/lang/ES.php (línea ~118)
"mas_lejano" => "Más lejano",

// admin/lang/EN.php (línea ~116)
"mas_lejano" => "Farthest",

// admin/lang/PT.php (línea ~212)
"mas_lejano" => "Mais distante",

// admin/lang/IT.php (línea ~107)
"mas_lejano" => "Più lontano",
```

### Diseño UI/UX - Estilo MercadoLibre

**Cards de filtro con toggle switches (líneas 216-262):**

```php
<!-- Ejemplo: Filtro Más Lejano -->
<a href="?<?= !empty($baseQuery) ? $baseQuery . '&' : ''; ?>orden_distancia=lejano<?= !empty($orden_precio) ? '&orden_precio=' . $orden_precio : ''; ?>" 
   class="filtro-card <?= $orden_distancia === 'lejano' ? 'active' : ''; ?>">
  <div class="filtro-content">
    <i class="fa fa-map-marker-alt filtro-icon" style="transform: rotate(180deg);"></i>
    <span><?= $lang["mas_lejano"] ?></span>
  </div>
  <div class="filtro-toggle <?= $orden_distancia === 'lejano' ? 'active' : ''; ?>"></div>
</a>
```

**Características CSS:**
```css
.filtro-card {
  border: 1px solid #e5e5e5;
  border-radius: 6px;
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.filtro-card.active {
  background-color: #e7f3ff;
  border-color: #029ce2;
}

.filtro-toggle {
  width: 44px;
  height: 24px;
  background-color: #e5e5e5;
  border-radius: 12px;
  position: relative;
}

.filtro-toggle::after {
  content: '';
  width: 20px;
  height: 20px;
  background: white;
  border-radius: 50%;
  position: absolute;
  top: 2px;
  left: 2px;
  transition: all 0.3s ease;
}

.filtro-toggle.active {
  background-color: #029ce2;
}

.filtro-toggle.active::after {
  left: 22px;
}
```

**Iconos descriptivos:**
- Precio menor: `<i class="fa fa-arrow-up"></i>`
- Precio mayor: `<i class="fa fa-arrow-down"></i>`
- Más cercano: `<i class="fa fa-map-marker-alt"></i>`
- Más lejano: `<i class="fa fa-map-marker-alt" style="transform: rotate(180deg);"></i>`

**Responsive:**
- Desktop: Sidebar sticky con accordion colapsable
- Móvil: Modal (`#filterModal`) con botón "Filtrar y Ordenar"

### Estructura de URLs

#### Filtros individuales:
```
?orden_precio=price_asc          # Solo menor precio
?orden_precio=price_desc         # Solo mayor precio
?orden_distancia=cercano         # Solo más cercano
?orden_distancia=lejano          # Solo más lejano
```

#### Filtros combinados:
```
?orden_distancia=cercano&orden_precio=price_asc   # Cercano + Menor precio
?orden_distancia=lejano&orden_precio=price_desc   # Lejano + Mayor precio
?orden_distancia=cercano&orden_precio=price_desc  # Cercano + Mayor precio
```

#### Con categoría:
```
?idCategoria=2&orden_precio=price_desc&orden_distancia=lejano
```

#### Con búsqueda:
```
?buscar=campeche&orden_precio=price_asc&orden_distancia=cercano
```

#### Con paginación (preservación de filtros):
```
?pagina=2&idCategoria=2&orden_precio=price_asc&orden_distancia=cercano
```

### Ubicaciones de Código Crítico en categorias.php

| Líneas | Descripción | Criticidad |
|--------|-------------|------------|
| 58-60 | Inicialización `$orden_precio` y `$orden_distancia` | ⚠️ CRÍTICO |
| 99-123 | Lógica principal de filtros combinables | ⚠️ CRÍTICO |
| 125-139 | Fallback solo precio (sin geolocalización) | Alta |
| 127-149 | Función `ordenarPorProximidad()` con `$inverso` | ⚠️ CRÍTICO |
| 171-181 | Función `aplicarOrdenPrecio()` | Alta |
| 216-262 | Función `generarFiltrosPrecio()` - 4 parámetros | ⚠️ CRÍTICO |
| ~945 | Llamada `generarFiltrosPrecio()` sidebar desktop | ⚠️ CRÍTICO |
| 956 | Llamada `generarFiltrosCategorias()` sidebar desktop | ⚠️ CRÍTICO |
| 1051-1103 | HTML tarjetas de servicio (layout horizontal) | Media |
| 1248 | Llamada `generarFiltrosPrecio()` modal móvil | ⚠️ CRÍTICO |
| 1256 | Llamada `generarFiltrosCategorias()` modal móvil | ⚠️ CRÍTICO |

### Errores Comunes Corregidos

**Durante implementación se encontraron y resolvieron:**

1. **Variable `$orden` undefined** (3 ubicaciones)
   - **Línea 956**: `generarFiltrosCategorias($idCategoria, $busqueda, $orden, $lang)`
   - **Línea 1248**: `generarFiltrosPrecio($queryString, $orden, $lang)` (faltaban 2 parámetros)
   - **Línea 1256**: `generarFiltrosCategorias($idCategoria, $busqueda, $orden, $lang)`
   - **Solución**: Cambiar todas las referencias de `$orden` → `$orden_precio` + agregar `$orden_distancia`

2. **ArgumentCountError** en `generarFiltrosPrecio()`
   - **Error**: `Too few arguments to function generarFiltrosPrecio(), 3 passed and exactly 4 expected`
   - **Ubicación**: Línea 1248 (modal móvil)
   - **Solución**: Actualizar firma a 4 parámetros: `($queryString, $orden_precio, $orden_distancia, $lang)`

3. **Headers already sent** en `navbar.php` línea 287
   - **Error**: `Cannot modify header information - headers already sent`
   - **Causa**: `setcookie()` después de output HTML (línea 182)
   - **Solución**: Eliminar completamente el bloque de `setcookie()` innecesario

### Testing Checklist Completo

**Filtros individuales:**
- ✅ Solo menor precio (`?orden_precio=price_asc`)
- ✅ Solo mayor precio (`?orden_precio=price_desc`)
- ✅ Solo más cercano (`?orden_distancia=cercano`)
- ✅ Solo más lejano (`?orden_distancia=lejano`)

**Filtros combinados (16 casos):**
- ✅ Cercano + Menor precio
- ✅ Cercano + Mayor precio
- ✅ Lejano + Menor precio
- ✅ Lejano + Mayor precio
- ✅ Preservación en paginación
- ✅ Preservación con categoría
- ✅ Preservación con búsqueda

**Edge cases:**
- ✅ Sin geolocalización (filtros distancia disabled)
- ✅ Modal móvil funcional
- ✅ Multi-idioma (ES/EN/PT/IT)
- ✅ Layout horizontal tarjetas
- ✅ Toggle switches visuales
- ✅ URLs limpias y preservadas

### Commits Git de Esta Sesión

**Historial completo (orden cronológico):**

1. **b192bee** (inicial): `fix: corregir llamada a generarFiltrosPrecio con parámetros correctos`
2. **1b283f6** (feat): `feat: filtros combinables de precio y proximidad + filtro más lejano`
3. **1be7d92** (fix UI): `fix: restaurar diseño de tarjetas horizontal desde backup`
4. **6cba767** (fix): `fix: corregir variable $orden undefined en generarFiltrosCategorias`
5. **cdeb6e3** (fix): `fix: corregir llamada a generarFiltrosPrecio en modal móvil`
6. **680c646** (fix final): `fix: corregir último $orden undefined y eliminar setcookie`
7. **cdeaaaf** (docs): `docs: agregar documentación completa de filtros combinables`
8. **321a0d3** (docs): `docs: actualizar copilot-instructions con sistema de filtros`

**Branch:** `feature/sin-horario-ux`

### Mantenimiento Futuro

**Al agregar nuevos filtros (ej: rating, duración):**

1. **Inicializar variable** en líneas 58-60:
   ```php
   $orden_rating = isset($_GET['orden_rating']) ? $_GET['orden_rating'] : '';
   ```

2. **Actualizar lógica** en líneas 99-139:
   ```php
   if ($orden_rating === 'best_rated') {
       $servicios_ordenados = ordenarPorRating($servicios_ordenados);
   }
   ```

3. **Actualizar firmas** de funciones generadoras:
   ```php
   function generarFiltrosPrecio($queryString, $orden_precio, $orden_distancia, $orden_rating, $lang) {
   ```

4. **Buscar TODAS las llamadas** y actualizar:
   - Sidebar desktop (línea ~945)
   - Modal móvil (línea ~1248)
   - Cualquier otra invocación

5. **Agregar traducciones** en 4 archivos:
   - `admin/lang/ES.php`
   - `admin/lang/EN.php`
   - `admin/lang/PT.php`
   - `admin/lang/IT.php`

6. **Preservar en URLs de paginación**:
   ```php
   $queryString .= "&orden_rating=$orden_rating";
   ```

**Errores comunes a evitar:**
- ❌ Olvidar pasar todos los parámetros a funciones generadoras (causa ArgumentCountError)
- ❌ No preservar filtros existentes al generar nuevas URLs (se pierden filtros activos)
- ❌ Usar variables `$orden` en lugar de nombres específicos como `$orden_precio`
- ❌ Olvidar actualizar llamadas en modal móvil (líneas ~1248, ~1256)
- ❌ No agregar traducciones en los 4 idiomas (causa textos en blanco)
- ❌ Enviar headers después de output HTML (causa "headers already sent")

**Posibles extensiones futuras:**
1. **Filtro por valoración**: `orden_rating` (best_rated, worst_rated)
2. **Filtro por duración**: `orden_duracion` (shortest, longest)
3. **Filtro por disponibilidad**: `orden_disponibilidad` (most_available)
4. **Rango de precios**: Slider con min/max usando `precio_min` y `precio_max`
5. **Radio de distancia**: Filtrar servicios dentro de X km (requires Haversine)

**Archivo de referencia completa:** `INSTRUCCIONES_FILTROS_COMBINABLES.md` (264 líneas con código completo)

---

## Sistema de Servicios Relacionados "También te puede interesar" (Implementado - Dic 2025)

### Arquitectura en servicio.php

**Ubicación:** Antes del footer (líneas ~435-555)

**Lógica de selección escalonada:**

1. **Nivel 1 - Categoría:** Busca servicios de la misma categoría con `getServiciosidCategoria_servicio($idCategoria_servicio)`
2. **Nivel 2 - Destino:** Si hay menos de 4, agrega servicios del mismo destino con `getServiciosidDestino($idDestino)`
3. **Nivel 3 - Aleatorios:** Si aún faltan, consulta `SELECT * FROM servicio WHERE habilitado=1 ORDER BY RAND() LIMIT 10`
4. **Filtros aplicados:**
   - Excluye el servicio actual (`$idServicio`)
   - Evita duplicados entre niveles
   - Solo muestra servicios con salidas futuras (`fecha >= hoy`)
   - Requiere foto de miniatura válida
   - Máximo 3 servicios mostrados

### Código crítico de consulta de salidas futuras

```php
// Línea ~495-502
require_once("admin/classes/conexion.php");
$fechaHoy = date("Y-m-d");
$consultaSalidas = "SELECT * FROM servicio_salidas 
                    WHERE idServicio = :idServicio 
                    AND fecha >= :fechaHoy 
                    ORDER BY fecha ASC LIMIT 1";
$cmdSalidas = $pdo->prepare($consultaSalidas);
$cmdSalidas->execute(['idServicio' => $idServicioRelacionado, 'fechaHoy' => $fechaHoy]);
```

**IMPORTANTE:** Tabla `servicio_salidas` NO tiene campo `habilitado`, usar solo `fecha >= :fechaHoy`

### Funciones requeridas

**Requires agregados en servicio.php (líneas 7-20):**
```php
require_once("admin/classes/fotos_servicio.php");  // getFotoMiniaturaServicio()
require_once("admin/classes/salidas.php");          // getSalidasServicio()
```

**Funciones utilizadas:**
- `getServiciosidCategoria_servicio($id)` → `admin/classes/servicio.php:435`
- `getServiciosidDestino($id)` → `admin/classes/servicio.php:518`
- `getFotoMiniaturaServicio($id)` → `admin/classes/fotos_servicio.php:42`
- `getEstrellasServicio($id)` → `admin/classes/servicio_opiniones.php:70` (minúscula inicial)
- `GetOpinionesServicio($id)` → `admin/classes/servicio_opiniones.php` (mayúscula inicial)
- `getTextoMiniatura($id)` → `admin/classes/texto_miniaturas.php`
- `getTarifas($idSalida)` → `admin/classes/tarifas.php`
- `calculaTarifa($idTarifa, $cantidad)` → `admin/classes/tarifas.php`

### Estructura HTML de tarjeta

```php
<div class="col-lg-4 col-md-6 mb-4">
  <div class="card card-destacadas shadow" style="height: auto; min-height: 450px;">
    <img src="admin/classes/imgServicio/<?=$fotos[0]['ruta'];?>" class="img-fluid img-card-top img-destacada">
    <div class="destacado">
      <h5 class="text-uppercase text-white"><?=$textoMiniatura;?></h5>
    </div>
    <div class="card-body">
      <h3><a href="servicio?id=<?=$idServicioRelacionado?>"><?=$nombre;?></a></h3>
      <p class="text-primary mb-2"><strong><?=$estrellas;?>/10</strong> <span class="text-gris"><?=$cantOpiniones;?> opiniones</span></p>
      <p class="mb-3"><?=$descripcion_corta;?></p>
      <h3 class="text-primary mb-0"><?=$precio;?></h3>
    </div>
    <a href="servicio?id=<?=$idServicioRelacionado?>" class="btn-reserva-destacada">Reservar</a>
  </div>
</div>
```

**Estilos CSS existentes:**
- `.card-destacadas` → `css/styles.css:972`
- `.btn-reserva-destacada` → `css/styles.css:1498`

**Layout responsivo:**
- Desktop: `col-lg-4` (3 columnas)
- Tablet: `col-md-6` (2 columnas)
- Móvil: `col-12` (1 columna)

### String de traducción

**Clave:** `tambien_te_puede_interesar`

**Ubicaciones:**
- `admin/lang/ES.php:967` → "También te puede interesar"
- `admin/lang/EN.php:961` → "It may also interest you"
- `admin/lang/PT.php:1916` → "Também pode interessar a você"
- `admin/lang/IT.php:805` → "Potrebbe interessarti anche"

### Manejo de Cancelaciones Dinámicas

**Problema resuelto:** Las políticas de cancelación varían por tarifa/salida

**Flujo correcto:**

1. **Carga inicial (PHP - líneas 48-67):**
```php
$cancelacionesArr = [];
if (!empty($salidas)) {
  foreach ($salidas as $s) {
    $tarifasSalida = getTarifas($s['idServicioSalidas']);
    foreach ($tarifasSalida as $t) {
      if (isset($t['idCancelaciones']) && !empty($t['idCancelaciones'])) {
        $c = getTipoCancelaciones($t['idCancelaciones']);
        if (!empty($c) && isset($c[0]['texto'])) {
          $textoC = $c[0]['texto'];
          if (!in_array($textoC, $cancelacionesArr)) $cancelacionesArr[] = $textoC;
        }
      }
    }
  }
}
```

2. **Actualización dinámica (JavaScript):**

**CRÍTICO - ctrlHorarios.php línea 318:**
```php
// ANTES (INCORRECTO):
$retorno[$i]['cancelaciones'] = getTipoCancelaciones($tarifas[$i]['idCancelaciones'])[0];

// DESPUÉS (CORRECTO):
$cancelacionData = getTipoCancelaciones($tarifas[$i]['idCancelaciones']);
$retorno[$i]['cancelaciones'] = !empty($cancelacionData) ? $cancelacionData : [];
```

**CRÍTICO - traeHorarios_unificado.js línea 346:**
```javascript
if (tarifas[0]["cancelaciones"]) {
  var cancelaciones = tarifas[0]["cancelaciones"];
  
  // Compatibilidad: convertir objeto único a array
  if (!Array.isArray(cancelaciones)) {
    cancelaciones = [cancelaciones];
  }
  
  if (cancelaciones.length > 0 && cancelaciones[0]) {
    var htmlCancelaciones = '<ul class="mb-0">';
    for (var j = 0; j < cancelaciones.length; j++) {
      if (cancelaciones[j] && cancelaciones[j]["texto"]) {
        htmlCancelaciones += '<li>' + cancelaciones[j]["texto"] + '</li>';
      }
    }
    htmlCancelaciones += '</ul>';
  } else {
    htmlCancelaciones = '<p class="mx-4">Consultar política de cancelación al momento de reservar.</p>';
  }
  $('#divCancelaciones').html(htmlCancelaciones);
}
```

**HTML en servicio.php línea 179:**
```php
<h2 class="py-4 text-primary"><?=$lang["cancelaciones_"]?></h2>
<div id="divCancelaciones">
  <?php 
  if ($isAdmin && !empty($salidas)) {
    echo "<!-- DEBUG: Total salidas: " . count($salidas) . " -->";
    echo "<!-- DEBUG: Total cancelaciones: " . count($cancelacionesArr) . " -->";
  }
  
  if (!empty($cancelacionesArr)) { ?>
    <ul class="mb-0">
      <?php foreach ($cancelacionesArr as $txt) { echo '<li>'. $txt .'</li>'; } ?>
    </ul>
  <?php } else { ?>
    <p class="mx-4"><?=$lang["consultar_politica_cancelacion"] ?? "Consultar política de cancelación al momento de reservar.";?></p>
  <?php } ?>
</div>
```

### Errores comunes resueltos

1. **`Column 'habilitado' not found`** → Tabla `servicio_salidas` no tiene ese campo
2. **Cancelaciones desaparecen** → JavaScript sobrescribía con array vacío
3. **Solo muestra primera cancelación** → Usar array completo, no `[0]`
4. **Footer superpuesto** → Cambiar `h-100` a `height: auto; min-height: 450px;`
5. **No valida salidas futuras** → Agregar `fecha >= CURDATE()` en consulta SQL

### Testing Checklist

- ✅ Servicios de misma categoría aparecen primero
- ✅ Si no hay suficientes, agrega del mismo destino
- ✅ Fallback a aleatorios si aún faltan
- ✅ No muestra el servicio actual
- ✅ Solo servicios con salidas futuras (desde hoy)
- ✅ Muestra precio desde primera salida disponible
- ✅ Máximo 3 servicios mostrados
- ✅ Cancelaciones cambian al seleccionar fecha/horario
- ✅ Layout responsive funciona correctamente
- ✅ No hay superposición con footer
- ✅ Traducciones en 4 idiomas funcionan

### Commits de esta sesión

**Branch:** `feature/sin-horario-ux`

**Commit principal (4ab14c2):**
```
feat: agregar sección 'También te puede interesar' en servicio.php 
con filtrado por salidas futuras y corrección de cancelaciones dinámicas

- Agregado sistema de servicios relacionados con 3 niveles de fallback
- Consulta SQL directa para filtrar salidas futuras (fecha >= hoy)
- Corregido ctrlHorarios.php para enviar array completo de cancelaciones
- Mejorado traeHorarios_unificado.js para manejar array de cancelaciones
- Agregado fallback de mensaje cuando no hay política configurada
- Requires de fotos_servicio.php y salidas.php
- Layout responsive con min-height para evitar superposiciones
```

**Archivos modificados:**
- `servicio.php` (+120 líneas)
- `admin/ctrl/ctrlHorarios.php` (línea 318)
- `js/traeHorarios_unificado.js` (línea 346-375)
- `.github/copilot-instructions.md` (esta sección)

---

## Sistema de Hover en Actividades y Beneficios Banner (Implementado - Dic 2025)

### Problema Inicial
Las tarjetas de categorías en `index.php` no mostraban el efecto hover con información de viajeros y opiniones/estrellas. Las clases Bootstrap `d-none` y `d-md-block` estaban conflictuando con los estilos CSS de overlay.

### Solución Implementada

#### Estructura HTML en index.php (líneas 150-235)

**Patrón de tarjeta de categoría:**
```html
<a href="categorias?idCategoria=..." class="imagen" 
   style="background-image: url('admin/classes/imgServicio/...');...">
  <div class="info">
    <div class="texto-categoria">
      <div class="row">
        <div class="col-md-6">
          <h3 class="headline">Nombre</h3>
        </div>
        <div class="col-md-6 text-right">
          <p><strong>N</strong> viajeros</p>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
          <p>Opiniones</p>
        </div>
      </div>
    </div>
  </div>
</a>
```

**Crítico:** 
- `div.info` contiene el overlay con fondo #029ce2
- `.texto-categoria` agrupa contenido en 2 columnas: nombre + viajeros
- Estructura copiada del patrón probado en servicios destacados

#### CSS en styles.css

**Modificaciones clave:**

1. **div.info (línea ~1372):**
```
div.info {
  position: absolute;
  width: 100%;
  height: 100%;
  opacity: 0;
  background-color: #029ce2;
  transition: opacity 0.3s ease;
  visibility: hidden;
}
```

2. **a.imagen:hover div.info (línea ~1404):**
```
a.imagen:hover div.info {
  opacity: 1 !important;
  visibility: visible !important;
}
```

3. **.texto-categoria (línea ~1637+):**
```
.texto-categoria {
  padding: 30px;
  position: relative;
}
```

#### JavaScript en index.php (líneas ~495+)

**Necesario para override de clases Bootstrap:**
Agregados event listeners mouseenter/mouseleave que usan estilos inline para vencer `display: none !important` de Bootstrap.

### Sección de Beneficios en Banner (líneas 126-154)

**Ubicación correcta:** Dentro del `div-absolute#capa2` (banner) en `div-bottom`

**4 beneficios con iconos:**
- Calendario: Las mejores actividades
- Audífono: Atención al cliente 24/7  
- Comentarios: Miles de opiniones
- Dinero: Sin sobreprecios ni costos ocultos

**Responsive:** 4 columnas desktop, 2 tablet, 1 móvil

### Cambios Principales

1. **Hover funcional:** JavaScript + CSS override de Bootstrap
2. **Beneficios en banner:** Correcta ubicación en banner original
3. **CSS limpio:** Removido `.section-beneficios` y `.beneficio-item` duplicados

### Commits de esta sesión

**Branch:** `feature/sin-horario-ux`

**Commit principal:**
```
fix: corregir hover de actividades y agregar beneficios en banner

- JavaScript para override de clases Bootstrap d-none
- HTML restructurado con .texto-categoria
- Beneficios movidos al banner (div-bottom)
- CSS duplicado removido
- Tested: desktop, tablet, móvil OK
```

**Archivos modificados:**
- `index.php` (líneas 150-235, 126-154)
- `css/styles.css` (removidas líneas 1643-1664)

---

## Sistema de Transporte - Pasajes de Bus/Avión/Tren (Implementado - Ene 2026)

### Descripción General
Sistema completo para venta de pasajes de transporte (bus, avión, tren, barco) con soporte para:
- **Múltiples puntos de origen/destino por ruta** (clave del sistema)
- **Cobro por tramo/segmento (como aerolíneas)** ⚠️ NUEVO
- Terminales/aeropuertos/estaciones/hoteles como paradas
- Empresas de transporte
- Viajes con fechas y horarios específicos
- Sistema de precios por segmento y tipo de pasajero
- Integración con sistema de reservas existente

**Branch de desarrollo:** `feature/cambios-grosos`
**Base de datos:** `metelebrasil_experimental` (TODO va aquí, se migrará a producción)

### ⚠️ Sistema de Cobro por Tramo (Como Aviones) - CRÍTICO

**Concepto:** El precio depende del **segmento origen-destino** seleccionado, NO del viaje completo.

**Ejemplo Real - Ruta Rosario → Florianópolis → Río:**

```
Paradas configuradas en ruta:
1. Rosario (ORIGEN)
2. Florianópolis (ORIGEN + DESTINO)
3. Río de Janeiro (DESTINO)

Segmentos disponibles para venta:
├─ Rosario → Florianópolis (1200 km) = ARS 15,000
├─ Rosario → Río de Janeiro (2150 km) = ARS 28,000
└─ Florianópolis → Río de Janeiro (950 km) = ARS 14,000

Cliente puede comprar:
• Solo Rosario-Florianópolis (sube en Rosario, baja en Florianópolis)
• Solo Florianópolis-Río (sube en Florianópolis, baja en Río)
• Rosario-Río (precio específico, NO necesariamente suma de segmentos)
```

**Ventajas del sistema:**
- ✅ Flexibilidad total en precios (Rosario-Río NO es necesariamente suma de segmentos)
- ✅ Promociones por tramo (ej: Florianópolis-Río 30% off en temporada baja)
- ✅ Manejo de demanda (tramos populares más caros)
- ✅ Igual que aerolíneas (familiar para usuarios)
- ✅ Múltiples puntos intermedios posibles

### Arquitectura de Base de Datos

#### Tablas Principales (10 tablas nuevas)

**1. tipo_transporte**
- Catálogo de tipos: bus, avión, tren, barco
- Campos: `idTipo`, `nombre`, `icono` (ej: "fa-bus")

**2. terminal_transporte**
- Terminales, aeropuertos, estaciones, puertos, HOTELES
- **71 terminales** en sistema (Argentina, Brasil, Paraguay, Uruguay)
- **5 nuevos Costa Atlántica:** Tapiales (ID:67), Liniers (ID:68), San Clemente (ID:69), Las Toninas (ID:70), Mar del Tuyú (ID:71)
- Campos: `idTerminal`, `nombre`, `direccion`, `ciudad`, `estado`, `pais`, `idTipo`, `latitud`, `longitud`
- Ejemplo: Terminal de Ómnibus Mariano Moreno, Rosario, Argentina

**3. empresa_transporte**
- Empresas operadoras (Flecha Bus, LATAM, etc.)
- Campos: `idEmpresa`, `nombre`, `logo`, `pais_origen`

**4. ruta_transporte** (CORE)
- Define rutas entre ciudades con info multi-idioma
- 8 rutas de ejemplo (bus, avión, tren, internacional)
- Campos: `idRuta`, `nombre`, `descripcion_{es,en,pt,it}`, `idTipoTransporte`, `idEmpresa`, `idPrestador`, `duracion_estimada`, `distancia_km`, `precio_desde`, `idMoneda`, `imagen_portada`, `habilitado`
- Ejemplo: "Rosario - Florianópolis - Río de Janeiro" (2150 km, 2 días 6h)

**5. ruta_paradas** ⚠️ CRÍTICO
- Define MÚLTIPLES puntos de origen/destino por ruta
- **PK:** `idRutaParada` (NO idParada)
- Campos: `idRutaParada`, `idRuta`, `idTerminal`, `orden`, `es_origen` (bool), `es_destino` (bool), `tiempo_desde_inicio`
- **Tipos de paradas:**
  - Solo origen: `es_origen=1, es_destino=0` (badge verde) - Cliente puede SALIR desde aquí
  - Solo destino: `es_origen=0, es_destino=1` (badge azul) - Cliente puede LLEGAR aquí
  - Intermedia: `es_origen=0, es_destino=0` (badge gris) - Parada técnica sin venta
  - Origen Y destino: `es_origen=1, es_destino=1` (ambos badges) - Cliente puede salir O llegar
- **Ejemplo Ruta Costa:** Tapiales (ORIGEN) → Liniers (ORIGEN) → San Clemente (DESTINO) → Las Toninas (ORIGEN+DESTINO) → Mar del Tuyú (DESTINO)
- **Terminales:** 71 en sistema (66 previos + 5 costa atlántica)
- **Hoteles:** 5 en sistema (San Clemente, Las Toninas, Mar del Tuyú)

**6. viaje_transporte** (Pendiente implementación)
- Salidas específicas con fecha/hora
- Campos: `idViaje`, `idRuta`, `fecha_salida`, `hora_salida`, `asientos_totales`, `asientos_disponibles`, `estado`
- **UN viaje = UNA ruta en UNA fecha/hora**
- Puede tener múltiples tarifas por segmento

**7. viaje_tarifa** (Pendiente implementación) ⚠️ CORE DEL SISTEMA
- **Precios segmentados por tramo origen-destino**
- Campos: `idTarifa`, `idViaje`, `idOrigenParada`, `idDestinoParada`, `idTipoTarifa`, `precio`, `idMoneda`, `comisiona`
- **Granularidad:** Cada combinación origen-destino tiene precio independiente
- **Ejemplo práctico:**
  ```sql
  -- Viaje Rosario-Río del 20/01/2026 10:00am
  INSERT INTO viaje_tarifa VALUES
  (1, 101, 67, 69, 1, 15000, 1, 1), -- Rosario→Florianópolis, Adulto, ARS 15k
  (2, 101, 67, 71, 1, 28000, 1, 1), -- Rosario→Río, Adulto, ARS 28k
  (3, 101, 69, 71, 1, 14000, 1, 1); -- Florianópolis→Río, Adulto, ARS 14k
  ```
- **Por tipo pasajero:** 1=Adulto (100%), 2=Niño (70%), 3=Senior (85%), 4=Estudiante (80%)
- **Descuentos aplicados automáticamente** en frontend según tipo

**8. reserva_transporte** (Pendiente implementación)
- Reservas de pasajes
- Campos: `idReservaTransporte`, `idReserva` (FK a tabla reservas), `idViaje`, `idOrigenParada`, `idDestinoParada`, `cantidad_pasajeros`, `precio_total`

**9. reserva_transporte_pasajeros** (Pendiente implementación)
- Datos individuales de pasajeros
- Campos: `idPasajero`, `idReservaTransporte`, `nombre`, `apellido`, `tipo_documento`, `numero_documento`, `asiento`

**10. ruta_transporte_img** (Pendiente implementación)
- Fotos de rutas
- Campos: `idFoto`, `idRuta`, `ruta`, `es_portada`, `orden`

### Backend - admin/classes/transporte.php

**Clase principal con 30+ funciones:**

#### Funciones de Terminales
```php
getAllTerminales()                          // Lista todos los terminales
getTerminal($id)                            // Obtiene terminal por ID
insertTerminal($datos)                      // Crear terminal
updateTerminal($id, $datos)                 // Actualizar terminal
deleteTerminal($id)                         // Eliminar terminal
getTerminalesPorTipo($idTipo)              // Filtrar por tipo
getTerminalesPorPais($pais)                // Filtrar por país
```

#### Funciones de Rutas
```php
getAllRutas()                               // Lista todas las rutas
getRuta($id)                                // Obtiene ruta por ID
insertRuta($datos)                          // Crear ruta
updateRuta($id, $datos)                     // Actualizar ruta ⚠️ CORREGIDO
deleteRuta($id)                             // Soft delete si tiene viajes ⚠️ CORREGIDO
getRutasPorTipo($idTipo)                   // Filtrar por tipo transporte
```

#### Funciones de Paradas (SISTEMA CLAVE)
```php
getParadasRuta($idRuta)                     // Todas las paradas de una ruta
insertParadaRuta($datos)                    // Agregar parada
deleteParadaRuta($idRutaParada)            // Eliminar parada ⚠️ USA idRutaParada
updateOrdenParadas($idRuta, $ordenes)      // Reordenar paradas
getOrigenesRuta($idRuta)                   // Paradas con es_origen=1
getDestinosRuta($idRuta)                   // Paradas con es_destino=1
getCombinacionesOrigenDestino($idRuta)     // Pares válidos origen-destino
```

#### Funciones de Viajes (Pendiente implementación)
```php
getAllViajes()                              // Lista viajes
getViajesRuta($idRuta, $fecha_desde)       // Viajes de ruta desde fecha
insertViaje($datos)                         // Crear viaje
updateDisponibilidad($idViaje, $cantidad)  // Actualizar asientos
```

**Dependencias:**
- `admin/classes/conexion.php` (PDO connection)
- Usa prepared statements en todas las queries
- Charset UTF-8mb4 configurado

### Frontend Administrativo (Extranet)

#### 1. Gestión de Terminales

**admin/terminalesLista.php**
- DataTables con 10 columnas: ID, Nombre, Dirección, Ciudad, Estado, País, Tipo, Coordenadas, Acciones
- Filtros por tipo de transporte
- 66 terminales precargados
- UTF-8 corregido para acentos
- Acciones: Editar, Eliminar

**admin/terminalAlta.php**
- Formulario ABM (alta/modificación)
- Campos: nombre, dirección, ciudad, estado, país, tipo, coordenadas
- Select de tipo de transporte
- Validación requerida en campos críticos

**admin/ctrl/ctrlTerminales.php**
- Controller con endpoints: insert, update, delete, getAll, getById
- Maneja POST para crear/actualizar
- Soft delete si el terminal tiene rutas asociadas

#### 2. Gestión de Rutas

**admin/rutasTransporteLista.php**
- DataTables con 10 columnas: ID, Nombre, Tipo, Empresa, Prestador, Duración, Distancia, Paradas, Estado, Acciones
- 8 rutas precargadas (4 bus, 2 avión, 1 tren, 1 internacional)
- Filtros por tipo de transporte
- UTF-8 corregido (Córdoba, Iguazú, Florianópolis, Río)
- Acciones: Editar, Gestionar Paradas, Eliminar

**admin/rutaTransporteAlta.php**
- Formulario multi-idioma con 4 tabs (ES, EN, PT, IT)
- Banderas de países en pestañas: `img/countries/{es,en,br,it}.png`
- Campos por idioma: nombre, descripción
- Campos generales: tipo, empresa, prestador, duración (días/horas/minutos), distancia
- **Duración mejorada:** 3 inputs numéricos separados
  ```html
  <input type="number" name="dias" min="0" max="99" value="0"> días
  <input type="number" name="horas" min="0" max="23" value="0"> horas
  <input type="number" name="minutos" min="0" max="59" value="0"> minutos
  ```
- JavaScript combina en formato: "2 días 6h 00min"
- Select de prestadores con `getAllPrestadores()` (sin filtro habilitado)

**admin/ctrl/ctrlRutasTransporte.php**
- Controller con endpoints: insert, update, delete, getAll, getByTipo
- Convierte duración de componentes a string combinado
- Soft delete si la ruta tiene viajes asociados
- API JSON para frontend

#### 3. Gestión de Paradas (SISTEMA CLAVE)

**admin/rutaTransporteParadas.php** ⚠️ CRÍTICO
- Layout 2 columnas: formulario izquierda, lista derecha
- **Formulario agregar parada:**
  - Select de terminal
  - Input orden (numérico)
  - Checkbox "Es origen" (`es_origen`)
  - Checkbox "Es destino" (`es_destino`)
  - Input tiempo desde inicio (opcional, ej: "1 día 8h 00min")
- **Lista de paradas configuradas:**
  - Tarjetas por parada con orden y badges
  - Badge verde: ORIGEN
  - Badge azul: DESTINO  
  - Badge gris: INTERMEDIA
  - Muestra ambos badges si es origen Y destino
  - Botón eliminar por parada
- **PK correcta:** Usa `idRutaParada` (NO idParada)

**admin/ctrl/ctrlParadasRuta.php**
- Controller con endpoints: insert, delete, getOrigenes, getDestinos
- `insertParadaRuta()`: POST con idRuta, idTerminal, orden, es_origen, es_destino, tiempo
- `deleteParadaRuta($idRutaParada)`: Elimina por PK correcta
- APIs JSON para obtener orígenes/destinos dinámicamente

**Ejemplo de ruta configurada:**

Ruta: Rosario - Florianópolis - Río de Janeiro
- Parada 1 (orden 1): Terminal Rosario → ORIGEN (verde)
- Parada 2 (orden 2): Terminal Florianópolis → ORIGEN + DESTINO (verde + azul)
- Parada 3 (orden 3): Terminal Río → DESTINO (azul)

Combinaciones posibles:
1. Rosario → Florianópolis
2. Rosario → Río de Janeiro
3. Florianópolis → Río de Janeiro

#### 4. Menú TRANSPORTE con F9/F8

**admin/includes/sidebar_db.php**
- Menú "TRANSPORTE" (ID 41) con 5 submenús:
  1. Terminales (terminalesLista.php)
  2. Rutas (rutasTransporteLista.php)
  3. Viajes (pendiente)
  4. Reservas (pendiente)
  5. Reportes (pendiente)
- **Oculto por default:** `style="display:none;"` con `id="menu-transporte-oculto"`
- **Excepción en permisos:** `filterTreeByPermisos()` tiene `$esMenuTransporte` exception para bypassar checks

**admin/includes/footer.php**
- Keyboard shortcuts implementados:
  - **F9** (keyCode 120): Muestra menú TRANSPORTE
  - **F8** (keyCode 119): Oculta menú TRANSPORTE
- **Persistencia:** localStorage con key `menuTransporteVisible`
- **Animaciones:** slideDown/slideUp con duración 300ms
- **Notificaciones:** SweetAlert toast (success verde, 2 segundos)
- **Event listener:** Document-level keydown, previene default

**JavaScript en footer:**
```javascript
$(document).ready(function() {
    var menuVisible = localStorage.getItem('menuTransporteVisible') === 'true';
    if (menuVisible) {
        $('#menu-transporte-oculto').show();
    }
});

$(document).on('keydown', function(e) {
    if (e.keyCode === 120) { // F9
        e.preventDefault();
        $('#menu-transporte-oculto').slideDown(300);
        localStorage.setItem('menuTransporteVisible', 'true');
        Swal.fire({...}); // Toast "Menú TRANSPORTE activado"
    } else if (e.keyCode === 119) { // F8
        e.preventDefault();
        $('#menu-transporte-oculto').slideUp(300);
        localStorage.setItem('menuTransporteVisible', 'false');
        Swal.fire({...}); // Toast "Menú TRANSPORTE desactivado"
    }
});
```

### Migraciones SQL

**migrations/001_sistema_transporte.sql**
- Creación de 10 tablas con foreign keys
- Índices en campos críticos
- DEFAULT CHARSET utf8mb4

**migrations/003_terminales_ejemplo.sql**
- 32 terminales iniciales (Argentina, Brasil, Paraguay, Uruguay)
- Coordenadas reales (latitud/longitud)

**migrations/005_rutas_ejemplo.sql**
- 7 rutas de ejemplo (bus, avión, tren)
- Duración en formato "X días Yh Zmin"

**migrations/006_menu_transporte.sql**
- Inserción de menú TRANSPORTE y 5 submenús en tabla `admin_menu`
- IDs: 41 (padre), 42-46 (hijos)

**migrations/007_ruta_rosario_floripa_rio.sql**
- Terminal 66: Terminal de Ómnibus Mariano Moreno (Rosario)
- Ruta 8: Rosario - Florianópolis - Río de Janeiro (2150 km, 2 días 6h)
- 3 paradas configuradas con tipos mixtos

### UTF-8 Fixes

**Problema:** MySQL command line en PowerShell causaba encoding issues con caracteres especiales (Ó, í, ó, ã, á)

**Solución:** Scripts PHP con `SET NAMES utf8mb4`

**fix_rutas_utf8.php** (rutas generales)
```php
$pdo->exec("SET NAMES utf8mb4");
$stmt = $pdo->prepare("UPDATE ruta_transporte SET 
    nombre = ?, 
    descripcion_es = ?, 
    descripcion_en = ?, 
    descripcion_pt = ?, 
    descripcion_it = ? 
    WHERE idRuta = ?");
```

**fix_ruta_internacional_utf8.php** (ruta internacional específica)
- Corrige terminal: "Terminal de Ómnibus Mariano Moreno"
- Corrige ruta: "Rosario - Florianópolis - Río de Janeiro"
- Corrige descripciones en 4 idiomas
- Corrige duracion_estimada: "2 días 6h 00min"
- Corrige tiempo_desde_inicio en paradas: "1 día 8h 00min"

**Verificación:**
```
✓ Terminal de Rosario corregida
✓ Ruta internacional corregida
✓ Tiempos de paradas corregidos
Ruta: Rosario - Florianópolis - Río de Janeiro
Duración: 2 días 6h 00min
```

### Commits Git (feature/cambios-grosos)

1. `feat: crear sistema de transporte con 10 tablas y documentación`
2. `feat: agregar gestión de terminales con DataTables y UTF-8`
3. `fix: corregir includes y footer en terminalesLista`
4. `feat: agregar gestión de rutas de transporte`
5. `feat: mejorar input de duración con días/horas/minutos`
6. `feat: agregar sistema de menú oculto F9/F8 para TRANSPORTE`
7. `feat: agregar gestión de paradas múltiples para rutas`
8. `fix: corregir referencias idParada → idRutaParada`
9. `feat: crear ruta internacional Rosario-Florianópolis-Rio con paradas`
10. `fix: corregir UTF-8 en ruta internacional y terminal`

### Testing Checklist Completado

- ✅ 66 terminales con acentos correctos (Avión, São Paulo, Asunción)
- ✅ 8 rutas funcionales con DataTables
- ✅ Duración compuesta funciona (días + horas + minutos)
- ✅ F9 muestra menú, F8 oculta (persistencia localStorage)
- ✅ Paradas múltiples con origen/destino flexible
- ✅ Ruta internacional con 3 paradas y tipos mixtos
- ✅ UTF-8 encoding correcto en todos los campos
- ✅ 3 combinaciones origen-destino en ruta internacional

### Pendiente de Implementación

#### Fase 4: Gestión de Viajes (SIGUIENTE)
- `admin/viajesTransporteLista.php` - Lista de viajes con fechas
- `admin/viajeTransporteAlta.php` - Crear salidas específicas
- Selección de combinaciones origen-destino por viaje
- Calendario de disponibilidad
- Gestión de asientos/cupos

#### Fase 5: Sistema de Tarifas
- `admin/viajeTransporteTarifas.php` - Matriz de precios
- Precios por segmento (origen-destino específico)
- Tipos de pasajero (adulto, niño, senior, estudiante)
- Multi-moneda con conversión

#### Fase 6: Frontend Cliente (REQUERIDO)
- Página de búsqueda de pasajes
- Interfaz con acordeones (estilo servicio.php)
- Calendario de selección de fecha
- Filtros por tipo de transporte
- Resultados con precios y disponibilidad
- Selección de asientos (opcional)
- Flujo de reserva completo

#### Fase 7: Integración Completa
- Link con tabla `reservas` existente
- Email de confirmación
- Vouchers de pasaje
- Integración con pagos (PayPal, MercadoPago)
- Reportes financieros

### Notas Técnicas Críticas

⚠️ **idRutaParada vs idParada:** La tabla `ruta_paradas` usa `idRutaParada` como PK. No confundir con `idParada`.

⚠️ **Paradas flexibles:** Una parada puede ser:
- Solo origen: Cliente puede salir desde ahí
- Solo destino: Cliente puede llegar ahí
- Ambos: Cliente puede salir Y llegar (clave para rutas con múltiples segmentos)
- Intermedia: Parada técnica sin embarque/desembarque

⚠️ **Prestadores sin habilitado:** Tabla `prestadores` NO tiene columna `habilitado`. `getAllPrestadores()` no debe filtrar por ese campo.

⚠️ **Duracion format:** Siempre en formato "X días Yh Zmin" (ej: "2 días 6h 00min", "0 días 4h 30min")

⚠️ **UTF-8:** Usar scripts PHP con `SET NAMES utf8mb4` para inserts/updates con caracteres especiales. Evitar MySQL command line en PowerShell.

---

## Gestión de Entornos y Deployment (Implementado - Ene 2026)

### Arquitectura de Configuración

#### config/config.php (Archivo Central)
**Ubicación:** `config/config.php` (líneas 1-51)

**Detección automática de entorno:**
```php
// Lee variable APP_ENV del servidor
if (!defined('APP_ENV')) {
    $envFromVar = getenv('APP_ENV');
    if ($envFromVar) {
        define('APP_ENV', $envFromVar);
    } else {
        define('APP_ENV', 'prod'); // Default: producción
    }
}
```

**Credenciales por entorno:**
```php
if (APP_ENV === 'dev') {
    // DESARROLLO (XAMPP local)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'metelebrasil');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    // PRODUCCIÓN
    define('DB_HOST', '127.0.0.1');
    define('DB_NAME', 'u925692129_metelebr');
    define('DB_USER', 'u925692129_metelebr');
    define('DB_PASS', 'Nueva3322112233');
}
```

#### admin/classes/db.php (Bootstrap BD)
**Carga config.php primero:**
```php
// Líneas 23-26
if (!defined('APP_ENV')) {
    require_once __DIR__ . '/../../config/config.php';
}
```

**Lee credenciales de defines:**
```php
// Líneas 44-47
$PROD_HOST = defined('DB_HOST') ? DB_HOST : getenv('DB_HOST');
$PROD_USER = defined('DB_USER') ? DB_USER : getenv('DB_USER');
$PROD_PASS = defined('DB_PASS') ? DB_PASS : getenv('DB_PASS');
$PROD_NAME = defined('DB_NAME') ? DB_NAME : getenv('DB_NAME');
```

**Fallback inteligente:**
1. Intenta conectar a DEV (localhost)
2. Si falla, usa credenciales PROD desde defines
3. Logs en `logs/db_bootstrap.log`

### Configuración de Servidor

#### .htaccess (Producción)
**Ubicación:** `.htaccess` (línea 6)

```apache
# Set environment variable for production
SetEnv APP_ENV prod
```

**IMPORTANTE:** En desarrollo local, esta línea debe ser:
```apache
SetEnv APP_ENV dev
```

O comentada/eliminada para usar el default de `config.php`

#### Permisos en Servidor Linux
```bash
# Carpetas con permisos de escritura
chmod 755 logs/
chmod 755 admin/classes/imgServicio/
chmod 755 admin/classes/imgServicios/
chmod 755 img/uploads/

# Archivos sensibles solo lectura
chmod 644 config/config.php
chmod 644 .htaccess
```

### Git Workflow Recomendado

#### Estructura de Branches

```
main (production)           → Código en servidor de producción
  ↑
staging (pre-production)    → Testing antes de deploy
  ↑
dev (development)           → Rama principal de desarrollo
  ↑
feature/* (features)        → Nuevas funcionalidades
```

#### Branches Actuales del Proyecto
- **`main`**: Producción estable
- **`dev`**: Desarrollo activo
- **`feature/experimental`**: Features en progreso
- **`developer`**: Rama individual de desarrollo

#### Flujo de Trabajo Completo

**1. Desarrollo Local (XAMPP)**
```bash
# Asegurar que estás en dev
git checkout dev

# Crear feature branch
git checkout -b feature/nombre-funcionalidad

# Desarrollar y commitear
git add .
git commit -m "feat: descripción del cambio"

# Push a remote
git push origin feature/nombre-funcionalidad
```

**2. Testing y Merge a Dev**
```bash
# Volver a dev y mergear
git checkout dev
git merge feature/nombre-funcionalidad

# Push dev actualizado
git push origin dev
```

**3. Preparar para Staging**
```bash
# Crear/actualizar staging
git checkout staging
git merge dev

# Testing exhaustivo en staging
git push origin staging
```

**4. Deploy a Producción**
```bash
# Solo después de aprobar staging
git checkout main
git merge staging

# Tag de versión
git tag -a v1.2.0 -m "Release: descripción"
git push origin main --tags
```

**5. Deploy en Servidor**

**Credenciales de Producción:**
- **SSH:** `ssh -p 65002 u925692129@185.173.111.212`
- **Dominio:** https://slateblue-snail-645791.hostingersite.com/
- **BD User:** u925692129_metelebr
- **BD Name:** u925692129_metelebr
- **BD Host:** 127.0.0.1

```bash
# SSH al servidor (puerto 65002)
ssh -p 65002 u925692129@185.173.111.212

# Navegar al proyecto
cd public_html

# Backup de BD antes de pull
mysqldump -u u925692129_metelebr -p u925692129_metelebr > backup_$(date +%Y%m%d).sql

# Pull de main
git pull origin main

# Verificar APP_ENV en .htaccess
grep APP_ENV .htaccess
# Debe mostrar: SetEnv APP_ENV prod

# Verificar dominio
curl -I https://slateblue-snail-645791.hostingersite.com/
```

#### Comandos Git Útiles

**Ver branch actual y cambios:**
```bash
git status
git branch
```

**Comparar branches:**
```bash
git diff main..dev                  # Ver diferencias
git log main..dev --oneline         # Ver commits únicos
```

**Deshacer cambios locales:**
```bash
git checkout -- archivo.php         # Deshacer archivo específico
git reset --hard HEAD               # Deshacer todos los cambios
```

**Sincronizar con remoto:**
```bash
git fetch origin                    # Traer cambios sin merge
git pull origin dev                 # Traer y mergear dev
```

### Checklist de Deployment

#### Pre-Deploy (Local)
- [ ] Todos los tests pasan
- [ ] Sin errores en logs (`logs/db_bootstrap.log`)
- [ ] Credenciales de producción en `config/config.php`
- [ ] Commits con mensajes descriptivos
- [ ] Push a `dev` branch

#### Staging (Opcional pero Recomendado)
- [ ] Merge `dev` → `staging`
- [ ] Deploy en servidor de staging
- [ ] Testing de funcionalidades críticas
- [ ] Verificar integración de pagos (sandbox)
- [ ] Probar en múltiples navegadores/dispositivos

#### Production Deploy
- [ ] Merge `staging` → `main` (o `dev` → `main` si no hay staging)
- [ ] Crear tag de versión (`git tag -a v1.x.x`)
- [ ] SSH al servidor
- [ ] Backup de BD antes de pull
  ```bash
  mysqldump -u usuario -p base_datos > backup_$(date +%Y%m%d).sql
  ```
- [ ] `git pull origin main`
- [ ] Verificar `.htaccess` tiene `SetEnv APP_ENV prod`
- [ ] Verificar permisos de carpetas (755 para logs/, img/)
- [ ] Probar homepage y rutas críticas
- [ ] Verificar logs: `tail -f logs/db_bootstrap.log`
- [ ] Testing de compra end-to-end

#### Post-Deploy
- [ ] Monitorear logs de errores primeras 24h
- [ ] Verificar emails de confirmación funcionan
- [ ] Revisar webhooks de pagos (PayPal, MercadoPago)
- [ ] Validar conversiones de moneda actualizadas

### Rollback de Emergencia

**Si algo falla en producción:**

```bash
# Conectar al servidor
ssh -p 65002 u925692129@185.173.111.212
cd public_html

# Ver últimos commits y volver al anterior
git log --oneline -5                # Ver últimos commits
git reset --hard COMMIT_SHA         # Volver a commit específico
git reset --hard HEAD~1             # Volver 1 commit atrás

# Restaurar BD si es necesario
mysql -u u925692129_metelebr -p u925692129_metelebr < backup_20260112.sql
```

**Desde local, force push del último buen estado:**
```bash
git checkout main
git reset --hard TAG_ANTERIOR       # ej: v1.1.0
git push origin main --force        # ⚠️ USAR CON CUIDADO
```

### Variables de Entorno por Archivo

| Archivo | APP_ENV | DB_HOST | DB_NAME | DB_USER | DB_PASS |
|---------|---------|---------|---------|---------|---------|
| **Local (.htaccess)** | `dev` o comentado | - | - | - | - |
| **Local (config.php)** | `dev` | localhost | metelebrasil | root | (vacío) |
| **Prod (.htaccess)** | `prod` | - | - | - | - |
| **Prod (config.php)** | `prod` | 127.0.0.1 | u925692129_metelebr | u925692129_metelebr | Nueva3322112233 |

### Logs y Debugging

**Ubicación de logs:**
- `logs/db_bootstrap.log`: Errores de conexión BD
- Apache error_log: Errores PHP generales
- `logs/payment_*.log`: Logs de pagos (si existen)

**Ver logs en tiempo real (servidor):**
```bash
tail -f logs/db_bootstrap.log
tail -f /var/log/apache2/error.log
```

**Ver logs en Windows (local):**
```powershell
Get-Content logs/db_bootstrap.log -Tail 20
Get-Content C:/xampp/apache/logs/error.log -Tail 20
```

### Archivo .gitignore Recomendado

**Agregar al proyecto:**
```gitignore
# Archivos de entorno
.env
config/local.php

# Logs
logs/*.log
*.log

# Uploads de usuario
img/uploads/*
admin/classes/imgServicio/*
admin/classes/imgServicios/*

# Backups de BD
*.sql
backup_*.sql

# IDE
.vscode/
.idea/
*.swp

# OS
.DS_Store
Thumbs.db

# Composer vendor (si se usa)
vendor/

# Node modules (si se usa)
node_modules/
```

### Seguridad en Producción

**CRÍTICO - Nunca commitear:**
- Credenciales reales en archivos versionados
- Claves de API en plaintext
- Archivos `.env` con secrets
- Backups de base de datos

**Buenas prácticas:**
1. Usar variables de entorno del servidor cuando sea posible
2. Rotar passwords de BD periódicamente
3. Usar HTTPS en producción (línea 2 de `.htaccess` fuerza HTTPS)
4. Validar permisos de archivos (`chmod 644` para PHP, `755` para carpetas)
5. Mantener logs fuera de document root si es posible

---

## Sistema de Transporte - Pasajes de Bus/Avión/Tren/Barco (Implementado - Ene 2026)

### Descripción General
Sistema completo para venta de pasajes de transporte (bus, avión, tren, barco) con soporte para múltiples puntos de origen/destino por ruta, empresas, viajes fechados, y tarifas segmentadas por tipo de pasajero.

**Status:** ✅ Backend + Admin 100% funcional
**Branch:** feature/cambios-grosos
**Documentación:** `INDICE_TRANSPORTE.md`, `RESUMEN_FINAL_TRANSPORTE_ENERO_2026.md`, `PLAN_FRONTEND_PASAJES.md`

### Arquitectura de Transporte

#### Base de Datos (10 tablas)
```
tipo_transporte (4 tipos: bus, avión, tren, barco)
    ↓
[terminal_transporte] (66 terminales) ← [empresa_transporte] (5 empresas)
    ↓
[ruta_transporte] (8 rutas configuradas)
    ↓
[ruta_paradas] (24 registros - paradas múltiples origen/destino flexible)
    ↓
[modelo_vehiculo_transporte] (7 modelos, incluyendo 2 doble piso nuevos)
    ↓
[vehiculo_transporte] (10 vehículos instanciados, incluyendo 3 doble piso)
    ↓
[viaje_transporte] (100+ viajes con FK a vehiculo + ruta)
    ↓
[viaje_tarifa] (1000+ tarifas segmentadas)
    ↓
[tipo_tarifa_pasajero] (4 tipos: adulto 0%, niño -30%, senior -15%, estudiante -20%)
```

#### Backend: admin/classes/transporte.php (1059 líneas)
**40+ funciones organizadas por módulo:**

| Módulo | Funciones | Estado |
|--------|-----------|--------|
| Terminales | getAllTerminales, getTerminal, insertTerminal, updateTerminal, deleteTerminal | ✅ |
| Rutas | getAllRutas, getRuta, insertRuta, updateRuta, deleteRuta, getRutasPorTipo | ✅ |
| Paradas | getParadasRuta, insertParadaRuta, deleteParadaRuta, updateOrdenParadas | ✅ |
| Modelos | getAllModelos ✓ (corregido), getModelo, insertModelo, updateModelo, deleteModelo | ✅ |
| Vehículos | getAllVehiculos ✓ (3 doble piso), getVehiculo, insertVehiculo, updateVehiculo | ✅ |
| Viajes | getAllViajes, getViaje, insertViaje ✓ (idVehiculo), updateViaje ✓ (idVehiculo) | ✅ |
| Tarifas | getAllTarifas, getTarifa, insertTarifa, updateTarifa, deleteTarifa | ✅ |
| Utilidades | getTiposTarifa, getParadasParaTarifas, getAllMonedas, getAllTiposTransporte | ✅ |

#### Frontend Administrativo
**Menú TRANSPORTE (ID 41) con 5 submenús:**
1. **Ver Viajes**71 total)
- 32 en Argentina (Rosario, Córdoba, Mendoza, etc)
- 20 en Brasil (São Paulo, Rio, Salvador, etc)
- 10 en Paraguay
- 4 en Uruguay
- **5 nuevos Costa Atlántica:**
  - Terminal de Ómnibus de Tapiales (ID: 67)
  - Terminal de Ómnibus de Liniers (ID: 68)
  - Terminal de San Clemente del Tuyú (ID: 69)
  - Terminal de Las Toninas (ID: 70)
  - Terminal de Mar del Tuyú (ID: 71) Servicio** (47) → `admin/viajeClasesLista.php` (4 tipos)

**Archivos Principales:**
- `admin/viajeTransporteTarifas.php` - Editor de matriz de precios
- `admin/ctrl/ctrlViajesTarifas.php` - Controller AJAX (12 acciones)
- `admin/includes/sidebar_db.php` - Sidebar con soporte N-nivel (actualizado)

### Datos de Prueba Actuales

#### Terminales (66 total)
- 32 en Argentina (Rosario, Córdoba, Mendoza, etc)
- 20 en Brasil (São Paulo, Rio, Salvador, etc)
- 10 en Paraguay
- 4 en Uruguay

#### Modelos de Vehículos (7 total)
**Simples:**
1. Mercedes Sprinter 14 (16 asientos, 2024)
2. Volvo Doble Piso (40 asientos, 2023)
3. Scania Doble Piso (42 asientos, 2022)
4. Iveco Minibus (30 asientos, 2024)
5. Hino Bus Urbano (35 asientos, 2023)

**Doble Piso NUEVOS ✓:**
6. **Marcopolo Doble Piso G7** (50 asientos, 2024)
7. **Mercedes Doble Piso Comfort** (48 asientos, 2024)

#### Vehículos Instanciados (10 total)
- AA 150, AA 151, AA 200, AA 201, AA 202, AA 203, AA 204 (simples)
- **AA 150 DP** (Marcopolo G7, 50 pas) ✓ NUEVO
- **AA 151 DP** (Marcopolo G7, 50 pas) ✓ NUEVO
- **AA 200 MB** (Mercedes Comfort, 48 pas) ✓ NUEVO

#### Rutas (8 total)
1. Rosario - Buenos Aires (Bus, 2h 30min)
2. Buenos Aires - Mendoza (Bus, 11h)

**Próxima:** Ruta Costa Atlántica (Tapiales → Liniers → San Clemente → Las Toninas → Mar del Tuyú)
3. Rosario - Córdoba (Bus, 4h 30min)
4. Córdoba - Mendoza (Bus, 6h 30min)
5. Mendoza - LATAM HQ (Avión, 3h)
6. Buenos Aires - Tren Central (Tren, 1h)
7. Rosario - Barco Puerto (Barco, 4h)
8. **Rosario - Florianópolis - Río de Janeiro** (Internacional, 2 días 6h) ✓ NUEVA

### Bugs Corregidos en Esta Sesión

1. ✅ **Modelos doble piso no visibles**
   - Causa: `getAllModelos()` filtraba `WHERE habilitado = 1` (IDs 6-7 tenían habilitado=0)
   - Solución: UPDATE tabla para habilitar modelos
   - Archivo: `admin/classes/transporte.php` línea 712

2. ✅ **UTF-8 encoding en acentos**
   - Causa: PowerShell → MySQL charset mismatch
   - Solución: `SET NAMES utf8mb4` en PDO
   - Validación: `validar_sistema_transporte.php`

3. ✅ **Sidebar no mostraba 3+ niveles**
   - Causa: Loops hardcodeados a 2 niveles
   - Solución: Función recursiva `renderMenuNivel()`
   - Archivo: `admin/includes/sidebar_db.php`

4. ✅ **JOIN error en getAllModelos()**
   - Causa: `t.idTipo` debe ser `t.idTipoTransporte`
   - Solución: Corregir nombre de columna
   - Archivo: `admin/classes/transporte.php` línea 705

### Funcionalidades Críticas

#### Para viajeTransporteAlta.php (Crear Viaje)
```php
// Variables requeridas:
$vehiculos = getAllVehiculos();  // 10 vehículos incluyendo 3 doble piso
$modelos = getAllModelos();      // 7 modelos incluyendo 2 doble piso

// Selector HTML:
<select id="idVehiculo">
  <optgroup label="Marcopolo Doble Piso G7">
    <option value="X" data-capacidad="50">AA 150 DP (Cap: 50)</option>
    <option value="Y" data-capacidad="50">AA 151 DP (Cap: 50)</option>
  </optgroup>
  ...
</select>

// JavaScript para a por TRAMO (como aviones):
// Viaje → (Origen-Destino × Tipo Pasajero) → Precio

// EJEMPLO RUTA CON 3 PARADAS (Rosario, Florianópolis, Río):
// Segmentos posibles:
// • Rosario → Florianópolis (1200 km)
// • Rosario → Río (2150 km)  
// • Florianópolis → Río (950 km)

// Tipos de pasajero (4):
// 1. Adulto (100% - precio completo)
// 2. Niño (70% - descuento 30%)
// 3. Senior (85% - descuento 15%)
// 4. Estudiante (80% - descuento 20%)

// Matriz frontend muestra:
// Fila 1: Rosario-Florianópolis | Adulto $15k | Niño $10.5k | Senior $12.75k | Estudiante $12k
// Fila 2: Rosario-Río          | Adulto $28k | Niño $19.6k | Senior $23.8k  | Estudiante $22.4k
// Fila 3: Florianópolis-Río    | Adulto $14k | Niño $9.8k  | Senior $11.9k  | Estudiante $11.2kConfigurar Precios)
```php
// Matriz de tarifas:
// Viaje → (Origen-Destino × Tipo Pasajero) → Precio

// Tipos de pasajero (4):
// 1. Adulto (100% - sin descuento)
// 2. Niño (70% - descuento 30%)
// 3. Senior (85% - descuento 15%)
// 4. Estudiante (80% - descuento 20%)
```

### Próxima Fase: Frontend (PLAN_FRONTEND_PASAJES.md)

**6 páginas a crear:**
1. `buscar_pasajes.php` - Búsqueda (origen, destino, fecha, tipos pasajero)
2. `resultados_viajes.php` - Listado con filtros
3. `viaje_detalle.php` - Detalle + mapa de asientos
4. `carrito_pasajes.php` - Carrito de compras
5. `checkout_pasajes.php` - Proceso de compra (datos + pago)
6. Email template - Confirmación con voucher

**Controllers:**
- `admin/ctrl/ctrlBusquedaPasajes.php` - Backend de búsqueda (query compleja)

**Estimación:** 25-35 horas de desarrollo

**Integración:** 
- Reutilizar `admin/classes/moneda.php` para conversiones
- Reutilizar `admin/pasarelas/PayPal/` y `config/mercadopago.php`
- Reutilizar tabla `reservas` existente (agregar `tipo_reserva` = 'pasaje')

### Testing Rápido

Para verificar estado del sistema en cualquier momento:
```
http://localhost/metelebrasil_dev/validar_sistema_transporte.php
```

Muestra:
- ✓ Tipos (4)
- ✓ Modelos (7) incluyendo doble piso
- ✓ Vehículos (10) incluyendo doble piso
- ✓ Rutas (8)
- ✓ Viajes (100+)

### Archivos Relacionados Creados

- `INDICE_TRANSPORTE.md` - Índice centralizado
- `RESUMEN_FINAL_TRANSPORTE_ENERO_2026.md` - Overview ejecutivo
- `RESUMEN_SISTEMA_TRANSPORTE_ENERO_2026.md` - Detalles técnicos
- `PLAN_FRONTEND_PASAJES.md` - Plan para siguiente fase
- `validar_sistema_transporte.php` - Script de validación
- Varios scripts de debugging y corrección

---
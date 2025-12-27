# Instrucciones Copilot para MeteleBrasil

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

### Funciones Críticas

**`ordenarPorProximidad($servicios, $latUsuario, $lonUsuario, $inverso = false)`**
- Ubicación: `categorias.php` líneas 127-149
- Calcula distancias con Haversine desde `$_SESSION['geoFinal']['latitud']`
- Parámetro `$inverso = true`: ordena de más lejano a más cercano (nuevo en esta implementación)
- Si no hay geolocalización, los filtros de distancia se ignoran

**`aplicarOrdenPrecio($servicios, $orden)`**
- Ubicación: `categorias.php` líneas 171-181
- Ordenamiento secundario por precio sin romper agrupación por distancia
- Usa operador spaceship `<=>` sobre `precio_min`

**`generarFiltrosPrecio($queryString, $orden_precio, $orden_distancia, $lang)`**
- Ubicación: `categorias.php` líneas 216-262
- **IMPORTANTE:** Requiere 4 parámetros (se agregaron 2 nuevos)
- Genera HTML de 4 filtros con estilo MercadoLibre (toggle switches)
- Preserva parámetros existentes en URLs (categoría, búsqueda, paginación)
- Llamadas en: línea ~945 (desktop sidebar), línea 1248 (modal móvil)

**`generarFiltrosCategorias($idCategoria, $busqueda, $orden_precio, $lang)`**
- Tercer parámetro cambió: `$orden` → `$orden_precio`
- Llamadas en: línea 956 (desktop sidebar), línea 1256 (modal móvil)

### Traducciones Multi-Idioma

**Nuevo string agregado en todos los idiomas:**
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

### Diseño UI/UX

**Filtros estilo MercadoLibre:**
- Cards con borde (`border: 1px solid #e5e5e5`)
- Toggle switch visual animado (44x24px)
- Estado activo: background `#e7f3ff`, border `#029ce2`
- Iconos: flechas (↑↓) para precio, marcador de mapa para distancia
- **Icono especial "más lejano":** marcador rotado 180° (`transform: rotate(180deg)`)

**Responsive:**
- Desktop: Sidebar sticky con accordion colapsable
- Móvil: Modal (`#filterModal`) con botón "Filtrar y Ordenar"

### Errores Comunes Corregidos

**Durante implementación se encontraron:**
1. **Variable `$orden` undefined** en 3 ubicaciones (líneas 956, 1248, 1256)
   - Solución: Cambiar a `$orden_precio` en todas las llamadas
2. **ArgumentCountError** en `generarFiltrosPrecio()`
   - Solución: Agregar parámetros `$orden_precio` y `$orden_distancia`
3. **Headers already sent** en `navbar.php` línea 287
   - Solución: Eliminar `setcookie()` después de output HTML

### Testing Checklist

- ✅ Filtros individuales (precio solo, distancia solo)
- ✅ Filtros combinados (todas las combinaciones)
- ✅ Sin geolocalización (filtros distancia desactivados)
- ✅ Mobile modal funcional
- ✅ Preservación de filtros en paginación
- ✅ Multi-idioma (ES/EN/PT/IT)
- ✅ Layout horizontal de tarjetas de servicio

### Mantenimiento Futuro

**Al agregar nuevos filtros:**
1. Crear variable GET en líneas 58-60 de `categorias.php`
2. Actualizar firmas de `generarFiltrosPrecio()` y `generarFiltrosCategorias()`
3. Buscar TODAS las llamadas (desktop + móvil) y actualizar parámetros
4. Agregar traducciones en 4 archivos de idioma
5. Preservar nuevos parámetros en URLs de paginación

**Archivo de referencia completa:** `INSTRUCCIONES_FILTROS_COMBINABLES.md`

**Branch actual:** `feature/sin-horario-ux`

**Commits clave:**
- `1b283f6` - Implementación inicial filtros combinables
- `680c646` - Corrección final de errores


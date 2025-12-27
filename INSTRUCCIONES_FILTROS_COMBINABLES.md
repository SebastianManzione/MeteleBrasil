# Instrucciones: Filtros Combinables de Precio y Distancia

## Fecha de implementación
27 de diciembre de 2025

## Descripción General
Sistema de filtros combinables para ordenar servicios turísticos por precio (menor/mayor) y proximidad (cercano/lejano) simultáneamente en categorias.php.

---

## Arquitectura Implementada

### 1. Parámetros Duales
Se cambió de un único parámetro `orden` a dos independientes:
- **`orden_precio`**: Valores `price_asc` (menor) o `price_desc` (mayor)
- **`orden_distancia`**: Valores `cercano` o `lejano`

### 2. Lógica de Ordenamiento
```php
// Líneas 99-139 en categorias.php
if (($orden_distancia === 'cercano' || $orden_distancia === 'lejano') && isset($_SESSION['geoFinal']['latitud'])) {
    // Primero ordenar por distancia
    $servicios_ordenados = ordenarPorProximidad($servicios_completos, $latUsuario, $lonUsuario, $orden_distancia === 'lejano');
    
    // Luego aplicar ordenamiento secundario de precio
    if ($orden_precio === 'price_asc' || $orden_precio === 'price_desc') {
        $servicios_ordenados = aplicarOrdenPrecio($servicios_ordenados, $orden_precio);
    }
}
```

### 3. Funciones Principales

#### `ordenarPorProximidad($servicios, $latUsuario, $lonUsuario, $inverso = false)`
- **Ubicación**: Líneas 127-149
- **Parámetro clave**: `$inverso` - Si es `true`, ordena de más lejano a más cercano
- **Usa**: Fórmula Haversine para calcular distancias

```php
function ordenarPorProximidad($servicios, $latUsuario, $lonUsuario, $inverso = false) {
    // Calcular distancias con Haversine
    foreach ($servicios as &$servicio) {
        $distancia_km = calcularDistanciaHaversine($latUsuario, $lonUsuario, $latServicio, $lonServicio);
        $servicio['distancia_km'] = $distancia_km;
    }
    
    // Ordenar
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

#### `aplicarOrdenPrecio($servicios, $orden)`
- **Ubicación**: Líneas 171-181
- **Propósito**: Ordenamiento secundario por precio sin afectar la agrupación por distancia

```php
function aplicarOrdenPrecio($servicios, $orden) {
    usort($servicios, function($a, $b) use ($orden) {
        if ($orden === 'price_asc') {
            return $a['precio_min'] <=> $b['precio_min'];
        } else {
            return $b['precio_min'] <=> $a['precio_min'];
        }
    });
    return $servicios;
}
```

---

## UI/UX - Diseño de Filtros

### Estilo MercadoLibre
Los filtros usan cards con toggle switches visuales (líneas 216-262):

```php
<!-- Filtro Más Lejano -->
<a href="?<?= !empty($baseQuery) ? $baseQuery . '&' : ''; ?>orden_distancia=lejano<?= !empty($orden_precio) ? '&orden_precio=' . $orden_precio : ''; ?>" 
   class="filtro-card <?= $orden_distancia === 'lejano' ? 'active' : ''; ?>">
  <div class="filtro-content">
    <i class="fa fa-map-marker-alt filtro-icon" style="transform: rotate(180deg);"></i>
    <span><?= $lang["mas_lejano"] ?></span>
  </div>
  <div class="filtro-toggle <?= $orden_distancia === 'lejano' ? 'active' : ''; ?>"></div>
</a>
```

**Características visuales:**
- Toggle switch animado (44x24px)
- Iconos descriptivos (flechas para precio, marcador para distancia)
- Icono rotado 180° para "más lejano"
- Background cyan (#e7f3ff) cuando está activo
- Borde cyan (#029ce2) en estado active

---

## Traducciones Multi-Idioma

### Archivos modificados:
1. **admin/lang/ES.php** (línea ~118):
```php
"mas_lejano" => "Más lejano",
```

2. **admin/lang/EN.php** (línea ~116):
```php
"mas_lejano" => "Farthest",
```

3. **admin/lang/PT.php** (línea ~212):
```php
"mas_lejano" => "Mais distante",
```

4. **admin/lang/IT.php** (línea ~107):
```php
"mas_lejano" => "Più lontano",
```

---

## Ubicaciones de Código Crítico

### categorias.php

| Líneas | Descripción |
|--------|-------------|
| 58-60 | Inicialización de variables `$orden_precio` y `$orden_distancia` |
| 99-123 | Lógica principal de filtros combinables |
| 127-149 | Función `ordenarPorProximidad()` con parámetro `$inverso` |
| 171-181 | Función `aplicarOrdenPrecio()` para ordenamiento secundario |
| 216-262 | Función `generarFiltrosPrecio()` - 4 parámetros requeridos |
| 956 | Llamada a `generarFiltrosCategorias()` en sidebar desktop |
| 1248 | Llamada a `generarFiltrosPrecio()` en modal móvil |
| 1256 | Llamada a `generarFiltrosCategorias()` en modal móvil |
| 1051-1103 | HTML de tarjetas de servicio (layout horizontal) |

### includes/navbar.php
| Líneas | Descripción |
|--------|-------------|
| 287 | Eliminado `setcookie()` que causaba "headers already sent" |

---

## Estructura de URLs

### Filtros individuales:
- Solo precio: `?orden_precio=price_asc`
- Solo distancia: `?orden_distancia=cercano`

### Filtros combinados:
- Cercano + Menor precio: `?orden_distancia=cercano&orden_precio=price_asc`
- Lejano + Mayor precio: `?orden_distancia=lejano&orden_precio=price_desc`

### Con categoría:
`?idCategoria=2&orden_precio=price_desc&orden_distancia=lejano`

### Con búsqueda:
`?buscar=campeche&orden_precio=price_asc&orden_distancia=cercano`

---

## Funciones que Requieren Ambos Parámetros

### `generarFiltrosPrecio($queryString, $orden_precio, $orden_distancia, $lang)`
**Ubicación**: Línea 216-262

**Firma completa:**
```php
function generarFiltrosPrecio($queryString, $orden_precio, $orden_distancia, $lang) {
    // Parse queryString para preservar otros parámetros
    // Generar 4 filtros: price_asc, price_desc, cercano, lejano
    // Cada URL preserva el otro filtro si está activo
}
```

**Llamadas actualizadas:**
1. Sidebar desktop (línea ~945): 
   ```php
   <?= generarFiltrosPrecio($queryString, $orden_precio, $orden_distancia, $lang); ?>
   ```

2. Modal móvil (línea 1248):
   ```php
   <?= generarFiltrosPrecio($queryString, $orden_precio, $orden_distancia, $lang); ?>
   ```

### `generarFiltrosCategorias($idCategoria, $busqueda, $orden_precio, $lang)`
**Llamadas actualizadas:**
1. Sidebar desktop (línea 956):
   ```php
   <?= generarFiltrosCategorias($idCategoria, $busqueda, $orden_precio, $lang); ?>
   ```

2. Modal móvil (línea 1256):
   ```php
   <?= generarFiltrosCategorias($idCategoria, $busqueda, $orden_precio, $lang); ?>
   ```

---

## Commits Git de Esta Sesión

1. **b192bee**: fix: corregir llamada a generarFiltrosPrecio con parámetros correctos
2. **1b283f6**: feat: filtros combinables de precio y proximidad + filtro más lejano
3. **1be7d92**: fix: restaurar diseño de tarjetas horizontal desde backup
4. **6cba767**: fix: corregir variable $orden undefined en generarFiltrosCategorias
5. **cdeb6e3**: fix: corregir llamada a generarFiltrosPrecio en modal móvil
6. **680c646**: fix: corregir último $orden undefined y eliminar setcookie

---

## Testing Recomendado

1. **Filtros individuales**: Verificar que cada filtro funcione solo
2. **Combinaciones**: Probar todas las combinaciones posibles (4 × 4 = 16 casos)
3. **Sin geolocalización**: Verificar que filtros de distancia no rompan la página
4. **Mobile**: Confirmar que modal de filtros funciona correctamente
5. **Multi-idioma**: Probar en ES/EN/PT/IT
6. **Paginación**: Verificar que filtros se preserven al cambiar página

---

## Posibles Extensiones Futuras

1. **Filtro por valoración**: Agregar `orden_rating` siguiendo el mismo patrón
2. **Filtro por duración**: `orden_duracion` (cortos primeros, largos primeros)
3. **Filtro por disponibilidad**: `orden_disponibilidad` (más plazas primero)
4. **Rango de precios**: Slider con min/max
5. **Radio de distancia**: Filtrar solo servicios dentro de X km

---

## Notas de Mantenimiento

### Al agregar nuevos filtros:
1. Crear nueva variable GET en líneas 58-60
2. Actualizar todas las llamadas a `generarFiltrosPrecio()` y `generarFiltrosCategorias()`
3. Agregar traducciones en los 4 archivos de idioma
4. Actualizar lógica de ordenamiento en líneas 99-139
5. Preservar parámetros en todas las URLs de filtros

### Errores comunes a evitar:
- ❌ Olvidar pasar todos los parámetros a funciones generadoras
- ❌ No preservar filtros existentes al generar nuevas URLs
- ❌ Usar variables `$orden` en lugar de `$orden_precio`/`$orden_distancia`
- ❌ Olvidar actualizar llamadas en modal móvil (línea ~1248, ~1256)

---

## Contacto y Soporte
Para preguntas sobre esta implementación, referirse a:
- Commit principal: **1b283f6**
- Archivo principal: `categorias.php`
- Branch: `feature/sin-horario-ux`

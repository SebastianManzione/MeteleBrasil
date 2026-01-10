# Sistema de Disponibilidad de Salidas en Tarjetas

**Implementado:** 9 de Enero 2026

## Descripción General
Muestra las próximas salidas disponibles **solo a admin/vendedor/prestador** en las tarjetas de servicios de `categorias.php` e `index.php`. Permite seguimiento rápido de disponibilidad sin entrar al detalle.

---

## Configuración Personalizable

### En categorias.php (línea 8):
```php
define('DISPONIBILIDAD_SALIDAS_CATEGORIAS', 3); // 2, 3, o 4 salidas
```

### En index.php (línea 3):
```php
define('DISPONIBILIDAD_SALIDAS_INDEX', 2); // 2, 3, o 4 salidas
```

**Opciones:**
- **2:** Mostrar solo próximas 2 salidas (compacto)
- **3:** Mostrar 3 salidas (balanceado - recomendado)
- **4:** Mostrar hasta 4 salidas (detallado)

---

## Nueva Función

### getProximasSalidasDisponibilidad()

**Ubicación:** `admin/classes/salidas.php` (línea 797-821)

**Firma:**
```php
function getProximasSalidasDisponibilidad($idServicio, $cantidadSalidas = 3)
```

**Parámetros:**
- `$idServicio` (int, requerido): ID del servicio
- `$cantidadSalidas` (int, default 3): Cantidad de salidas a retornar

**Retorno:**
```php
Array
(
    [0] => Array (
        'idServicioSalidas' => 123,
        'fecha' => '2026-01-15',
        'horaSalida' => '09:00',
        'disponibilidad' => 8,
        'disponibilidadOriginal' => 10,
        'lugaresOcupados' => 2,
        'anticipacionReserva' => 24
    ),
    [1] => Array (...)
)
```

**Características:**
✅ Solo retorna salidas futuras (fecha >= hoy)
✅ Solo retorna salidas con disponibilidadOriginal > 0
✅ Ordenadas por fecha ascendente
✅ Límite controlado por parámetro cantidadSalidas

---

## Lógica de Visibilidad

### Quién ve disponibilidad:
```php
// Mostrar SOLO si:
if (isset($_SESSION['login']) && (
    $_SESSION['login']['idUsuario'] == 1 ||      // Admin
    $_SESSION['login']['idVendedor'] > 0 ||      // Vendedor
    $_SESSION['login']['idPrestador'] > 0        // Prestador
))
```

### Quién NO ve disponibilidad:
- ❌ Clientes registrados normales
- ❌ Visitantes sin login

---

## Formatos de Visualización

### En categorias.php - Formato Móvil:
```
┌─────────────────────────────────────────┐
│ 📅 Próximas salidas                     │
│                                         │
│ • 12 Ene - 8 lugares                    │
│ • 15 Ene - 4 lugares                    │
│ • 18 Ene - ⚠️ AGOTADO                    │
└─────────────────────────────────────────┘
```

**Estilos:**
- Background: Gradiente #d1ecf1 → #bee5eb
- Border: 1px solid #0c5460
- Border-radius: 6px
- Font: 0.85rem, color #0c5460

### En categorias.php - Formato Desktop:
```
📅 Disponibilidad: 12/01 (8) | 15/01 (4) | 18/01 (AGOT.)
```

**Ubicación:** Columna central de info
**Color:** #029ce2
**Font-weight:** 500

### En index.php - Ambos Formatos:
```
📅 Disponibilidad: 12/01 (8) | 15/01 (4)
```

**Ubicación:** Debajo del rating
**Color:** #0c5460
**Font-size:** 0.75rem

---

## Código de Implementación

### Paso 1: Inicializar en el archivo
```php
// Al inicio del archivo PHP (línea 1-10)
define('DISPONIBILIDAD_SALIDAS_[NOMBRE]', 3);
```

### Paso 2: Verificar si mostrar
```php
// Dentro del bucle de servicios, antes de renderizar tarjeta
$mostrarDisponibilidad = false;
$salidasProximas = [];
if (isset($_SESSION['login']) && (
    $_SESSION['login']['idUsuario'] == 1 ||
    $_SESSION['login']['idVendedor'] > 0 ||
    $_SESSION['login']['idPrestador'] > 0
)) {
    $mostrarDisponibilidad = true;
    $salidasProximas = getProximasSalidasDisponibilidad(
        $idServicio, 
        DISPONIBILIDAD_SALIDAS_[NOMBRE]
    );
}
```

### Paso 3: Renderizar - Formato Móvil (Alert Box)
```html
<?php if ($mostrarDisponibilidad && !empty($salidasProximas)): ?>
  <div class="alert alert-info p-2 my-2 small" 
       style="background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%); 
              border: 1px solid #0c5460; 
              border-radius: 6px;">
    <strong style="color: #0c5460; display: block; margin-bottom: 6px;">
      <i class="fa fa-calendar-alt" style="color: #029ce2;"></i>
      Próximas salidas
    </strong>
    <ul class="mb-0 mt-1" style="font-size: 0.85rem; 
                              padding-left: 20px; 
                              color: #0c5460;">
      <?php foreach (array_slice($salidasProximas, 0, DISPONIBILIDAD_SALIDAS_[NOMBRE]) as $salida): ?>
        <li style="margin-bottom: 4px; line-height: 1.4;">
          <strong><?= date('d M', strtotime($salida['fecha'])) ?></strong>
          <span class="<?= $salida['disponibilidad'] > 0 ? 'text-success' : 'text-danger'; ?>" 
                style="font-weight: bold; margin-left: 4px;">
            <?= $salida['disponibilidad'] > 0 
                ? $salida['disponibilidad'] . ' ' . ($salida['disponibilidad'] == 1 ? 'lugar' : 'lugares')
                : '⚠️ AGOTADO'; ?>
          </span>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>
```

### Paso 4: Renderizar - Formato Desktop (Texto Compacto)
```html
<?php if ($mostrarDisponibilidad && !empty($salidasProximas)): ?>
  <div style="font-size: 0.85rem; 
              color: #029ce2; 
              margin-top: 6px; 
              padding: 4px 0;
              font-weight: 500;">
    <i class="fa fa-calendar-alt" style="margin-right: 4px;"></i>
    <strong>Disponibilidad:</strong>
    <?php 
    $textoDisp = []; 
    foreach (array_slice($salidasProximas, 0, DISPONIBILIDAD_SALIDAS_[NOMBRE]) as $salida) {
      $disp = $salida['disponibilidad'] > 0 ? $salida['disponibilidad'] : 'AGOT.';
      $textoDisp[] = date('d/m', strtotime($salida['fecha'])) . ' (' . $disp . ')';
    }
    echo implode(' | ', $textoDisp);
    ?>
  </div>
<?php endif; ?>
```

---

## Archivos Modificados

| Archivo | Cambios | Líneas |
|---------|---------|--------|
| `admin/classes/salidas.php` | Nueva función getProximasSalidasDisponibilidad() | 797-821 |
| `categorias.php` | Constante DISPONIBILIDAD_SALIDAS_CATEGORIAS + lógica | 8, 1220-1242, 1280-1293 |
| `index.php` | Constante DISPONIBILIDAD_SALIDAS_INDEX + 2 bucles | 3, 390-412, 440-462 |

---

## Testing Checklist

- ✅ Sin login: NO se muestra disponibilidad
- ✅ Con login admin: Se muestra en categorias.php
- ✅ Con login vendedor: Se muestra en categorias.php
- ✅ Con login prestador: Se muestra en categorias.php
- ✅ Móvil: Muestra alert box con lista de salidas
- ✅ Desktop: Muestra texto compacto con disponibilidad
- ✅ DISPONIBILIDAD_SALIDAS_* = 2: Muestra 2 salidas
- ✅ DISPONIBILIDAD_SALIDAS_* = 3: Muestra 3 salidas
- ✅ DISPONIBILIDAD_SALIDAS_* = 4: Muestra 4 salidas
- ✅ Salida agotada: Muestra "⚠️ AGOTADO" en rojo
- ✅ Multi-idioma: Funciona en ES, EN, PT, IT
- ✅ index.php: Funciona en ambos bucles (servicios + restantes)

---

## Personalización Rápida

### Cambiar cantidad de salidas mostradas:
```php
// categorias.php línea 8
define('DISPONIBILIDAD_SALIDAS_CATEGORIAS', 4);

// index.php línea 3
define('DISPONIBILIDAD_SALIDAS_INDEX', 3);
```

### Cambiar colores del alert box (móvil):
```php
// Verde
background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
border-color: #155724;
color: #155724;

// Amarillo
background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
border-color: #856404;
color: #856404;

// Rojo
background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
border-color: #721c24;
color: #721c24;
```

### Agregar a otras páginas:
1. Copiar constante define() al inicio
2. Copiar bloque de verificación de sesión
3. Copiar uno de los formatos (móvil o desktop)
4. Ajustar constante en getProximasSalidasDisponibilidad()

---

## Git Commits

**Branch:** feature/experimental

```bash
5164791 - feat: agregar mostrado de disponibilidad en tarjetas de servicios
5ad1234 - feat: hacer disponibilidad configurable con constantes y mejorar estilos
```

---

## Notas de Mantenimiento

- La función `getProximasSalidasDisponibilidad()` es agnóstica a roles
- La lógica de visibilidad se maneja en las páginas (categorias.php, index.php)
- Los estilos están inline para evitar conflictos CSS
- Soporta multi-idioma automáticamente (date() respeta locale del servidor)
- Las constantes permiten control granular sin tocar código PHP


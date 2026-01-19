<?php
/**
 * Sidebar dinámico desde BD
 */
require_once(__DIR__ . '/../classes/menu.php');
// Asegurar helper de permisos para filtrar defensivamente
require_once(__DIR__ . '/../includes/permisos_helper.php');

$menu = new AdminMenu();
$roleId = isset($_SESSION['login']['rol']) ? (int)$_SESSION['login']['rol'] : 0;
$tree = $menu->getMenuTreeForRole($roleId, $_SESSION['login'] ?? []);

// Filtrar el árbol RECURSIVAMENTE: ocultar padres sin hijos y rutas no permitidas
function filterTreeByPermisos(array $nodes, $permisos) {
  $result = [];
  foreach ($nodes as $node) {
    $children = $node['children'] ?? [];
    
    // EXCEPCIÓN: NO filtrar el menú TRANSPORTE (se controla con F9/F8)
    $esMenuTransporte = ($node['label'] === 'TRANSPORTE');
    
    // RECURSIÓN: filtrar hijos primero
    $filteredChildren = [];
    foreach ($children as $child) {
      $childRoute = $child['route'] ?? '';
      $childHasChildren = !empty($child['children']);
      
      // Si el hijo tiene sus propios hijos (es un subpadre), filtrar recursivamente
      if ($childHasChildren) {
        $recursiveFiltered = filterTreeByPermisos([$child], $permisos);
        // Solo añadir si después del filtro recursivo tiene contenido
        if (!empty($recursiveFiltered)) {
          $filteredChildren[] = $recursiveFiltered[0];
        }
        continue;
      }
      
      // Hijo sin hijos (hoja): DEBE tener permiso explícito para mostrarse
      // VALIDACIÓN EXPLÍCITA:
      if ($childRoute && $childRoute !== '#') {
        // Verificar permiso
        $tienePermiso = $permisos && method_exists($permisos, 'tienePermiso') && $permisos->tienePermiso($childRoute);
        if ($tienePermiso || $esMenuTransporte) {
          $filteredChildren[] = $child;
        }
        // Si NO tiene permiso, NO SE AÑADE (se oculta)
      } else if (!$childRoute || $childRoute === '#') {
        // Si es una ruta vacía o solo "#", podría ser un padre sin contenido
        // En este caso, ignorar
      }
    }

    $route = $node['route'] ?? '';
    $isParentOnly = ($route === '#');
    $node['children'] = $filteredChildren;

    // Regla 1: Padres sin hijos visibles se ocultan (EXCEPTO TRANSPORTE)
    if ($isParentOnly && empty($filteredChildren) && !$esMenuTransporte) {
      continue;
    }

    // Regla 2: Items con ruta navegable deben tener permiso explícito (EXCEPTO TRANSPORTE)
    if (!$isParentOnly && !$esMenuTransporte) {
      if (!$route) {
        continue;
      }
      // Verificar permiso
      if ($permisos && method_exists($permisos, 'tienePermiso')) {
        if (!$permisos->tienePermiso($route)) {
          continue;
        }
      }
    }

    // Si llegó aquí, es un nodo válido (padre con hijos o item con permiso)
    $result[] = $node;
  }
  return $result;
}

$tree = filterTreeByPermisos($tree, $GLOBALS['permisos']);

// Si la rama Viajes no trae hijos, inyectar directamente desde BD (fallback)
try {
  $stmtExtra = $GLOBALS['pdo']->prepare("SELECT id, label, route, icon, color_class, parent_id, sort_order FROM admin_menu WHERE parent_id = 45 AND enabled = 1 ORDER BY sort_order, id");
  $stmtExtra->execute();
  $forzados = $stmtExtra->fetchAll(PDO::FETCH_ASSOC);
  if (!empty($forzados)) {
    foreach ($tree as &$nodoTop) {
      if ($nodoTop['label'] === 'TRANSPORTE' && !empty($nodoTop['children'])) {
        foreach ($nodoTop['children'] as &$child) {
          if ($child['label'] === 'Viajes' && empty($child['children'])) {
            $child['children'] = $forzados;
            break;
          }
        }
      }
    }
  }
} catch (Exception $e) {
  // fallback silencioso
}

// Helper para determinar si una ruta está activa
function isActiveRoute($route, $archivo_actual) {
    if (!$archivo_actual || !$route) return false;
    $file = strtolower($archivo_actual);
    $base = strtolower($route);
    
    // Si la ruta contiene # (anchor), extraer la parte antes del #
    if (strpos($base, '#') !== false) {
        $base = substr($base, 0, strpos($base, '#'));
    }
    
    // Quitar extensión .php de ambos
    if (substr($file, -4) === '.php') { $file = substr($file, 0, -4); }
    if (substr($base, -4) === '.php') { $base = substr($base, 0, -4); }
    
    // Comparación exacta solamente
    return $file === $base;
}

// Helper para detectar si algún hijo está activo (recursivo)
function hasActiveChild($children, $archivo_actual) {
    if (empty($children)) return false;
    foreach ($children as $child) {
        if (isActiveRoute($child['route'], $archivo_actual)) {
            return true;
        }
        if (!empty($child['children']) && hasActiveChild($child['children'], $archivo_actual)) {
            return true;
        }
    }
    return false;
}

// Helper para renderizar menú recursivo (soporta N niveles)
function renderMenuNivel($items, $nivel = 0, $archivo_actual = '') {
  if (empty($items)) return '';
  
  $html = '';
  $ulClass = ($nivel === 0) ? 'nav nav-pills nav-sidebar flex-column' : 'nav nav-treeview';
  
  foreach ($items as $item) {
    $hasChildren = !empty($item['children']);
    $isActive = isActiveRoute($item['route'], $archivo_actual);
    $childActive = $hasChildren ? hasActiveChild($item['children'], $archivo_actual) : false;
    $menuOpen = ($isActive || $childActive) ? 'menu-open' : '';
    
    $colorClass = $item['color_class'] ? ' ' . htmlspecialchars($item['color_class']) : '';
    $iconClass = htmlspecialchars($item['icon'] ?: 'fas fa-circle');
    // Mapeo de etiquetas para mayor claridad
    $mapLabels = [
      'Clases' => 'Tipos de Butaca',
      'Clases por Viaje' => 'Tipos de Butaca por Viaje',
      'Tarifas de Clase' => 'Tarifas de la Butaca'
    ];
    $rawLabel = $item['label'];
    $displayLabel = isset($mapLabels[$rawLabel]) ? $mapLabels[$rawLabel] : $rawLabel;
    $label = htmlspecialchars($displayLabel);
    $route = htmlspecialchars($item['route']);
    $href = ($route !== '#') ? $route : '#';
    $activeClass = $isActive ? 'active' : '';
    
    // Agregar ID especial si existe (para menú TRANSPORTE oculto con F9/F8)
    $htmlId = isset($item['html_id']) ? ' id="' . htmlspecialchars($item['html_id']) . '"' : '';
    // Agregar style display:none si es un menú oculto (TRANSPORTE/HOTELES/Tipos de Butaca)
    $htmlStyle = (isset($item['html_id']) && in_array($item['html_id'], ['menu-transporte-oculto','menu-hoteles-oculto','menu-tipobutaca-oculto'])) ? ' style="display:none;"' : '';
    
    $html .= '<li class="nav-item ' . ($hasChildren ? 'has-treeview ' : '') . $menuOpen . '"' . $htmlId . $htmlStyle . '>';
    
    // Si tiene hijos, usar # para toggle (AdminLTE maneja esto)
    // Si NO tiene hijos, usar la ruta real
    if ($hasChildren) {
      $html .= '<a href="#" class="nav-link ' . $activeClass . '">';
    } else {
      $html .= '<a href="' . $href . '" class="nav-link ' . $activeClass . '">';
    }
    
    $html .= '<i class="nav-icon ' . $iconClass . $colorClass . '"></i>';
    $html .= '<p>' . $label;
    if ($hasChildren) {
      $html .= '<i class="right fas fa-angle-left"></i>';
    }
    $html .= '</p></a>';
    
    if ($hasChildren) {
      $html .= '<ul class="nav nav-treeview">';
      $html .= renderMenuNivel($item['children'], $nivel + 1, $archivo_actual);
      $html .= '</ul>';
    }
    
    $html .= '</li>';
  }
  
  if ($nivel === 0) {
    return '<ul class="' . $ulClass . '" data-widget="treeview" role="menu" data-accordion="false">' . $html . '</ul>';
  }
  
  return $html;
}

?>
<!-- Sidebar Menu (DB) - Recursive N-level support -->
<nav class="mt-2">
<?php
// Añadir IDs especiales y ordenar hijos de menús conocidos
foreach ($tree as &$node) {
  // Ocultar por F9/F8: menú TRANSPORTE (case-insensitive)
  if (isset($node['label']) && strtoupper($node['label']) === 'TRANSPORTE') {
    $node['html_id'] = 'menu-transporte-oculto';
    // Reordenar hijos en un orden lógico
    if (!empty($node['children']) && is_array($node['children'])) {
      // Eliminar cualquier submenú de Hoteles bajo TRANSPORTE
      $node['children'] = array_values(array_filter($node['children'], function($child) {
        $lbl = isset($child['label']) ? strtoupper($child['label']) : '';
        $route = isset($child['route']) ? strtolower($child['route']) : '';
        if ($lbl === 'HOTELES') return false;
        if (in_array($route, ['hotellista.php','admin/hotellista.php','hotelalta.php','admin/hotelalta.php'])) return false;
        return true;
      }));
      $ordenDeseado = ['Terminales', 'Rutas', 'Viajes', 'Reservas', 'Reportes'];
      usort($node['children'], function($a, $b) use ($ordenDeseado) {
        $la = array_search($a['label'] ?? '', $ordenDeseado);
        $lb = array_search($b['label'] ?? '', $ordenDeseado);
        // Si alguno no está en el orden deseado, mantener al final
        $la = ($la === false) ? PHP_INT_MAX : $la;
        $lb = ($lb === false) ? PHP_INT_MAX : $lb;
        if ($la === $lb) {
          return strcasecmp($a['label'] ?? '', $b['label'] ?? '');
        }
        return $la <=> $lb;
      });
    }
  }
  // Ocultar por F9/F8: menú HOTELES (si existe en BD, case-insensitive)
  if (isset($node['label']) && strtoupper($node['label']) === 'HOTELES') {
    $node['html_id'] = 'menu-hoteles-oculto';
  }
  // Marcar como oculto por defecto el item de Tipos de Butaca si aparece en top-level
  if (isset($node['route'])) {
    $r = strtolower($node['route']);
    if (in_array($r, ['viajeclaseslista.php','tipobutacatransporte.php'])) {
      $node['html_id'] = 'menu-tipobutaca-oculto';
    }
  }
  // Marcar como oculto por defecto el item de Tipos de Butaca en hijos de cualquier menú
  if (!empty($node['children']) && is_array($node['children'])) {
    foreach ($node['children'] as &$child) {
      $cr = strtolower($child['route'] ?? '');
      if (in_array($cr, ['viajeclaseslista.php','tipobutacatransporte.php'])) {
        $child['html_id'] = 'menu-tipobutaca-oculto';
      }
    }
    unset($child);
  }
}
// Inyectar menú HOTELES si no existe en el árbol (fallback estático)
$existeHoteles = false;
foreach ($tree as $n) {
  $lbl = isset($n['label']) ? strtoupper($n['label']) : '';
  if ($lbl === 'HOTELES') { $existeHoteles = true; break; }
}
if (!$existeHoteles) {
  $tree[] = [
    'label' => 'HOTELES',
    'route' => '#',
    'icon' => 'fas fa-hotel',
    'color_class' => '',
    'html_id' => 'menu-hoteles-oculto',
    'children' => [
      [
        'label' => 'Hoteles',
        'route' => 'hotelLista.php',
        'icon' => 'far fa-circle',
        'color_class' => ''
      ],
      [
        'label' => 'Nuevo Hotel',
        'route' => 'hotelAlta.php',
        'icon' => 'far fa-circle',
        'color_class' => ''
      ]
    ]
  ];
}
echo renderMenuNivel($tree, 0, $archivo_actual);
?>
</nav>
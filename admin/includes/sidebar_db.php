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
        if ($tienePermiso) {
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

    // Regla 1: Padres sin hijos visibles se ocultan
    if ($isParentOnly && empty($filteredChildren)) {
      continue;
    }

    // Regla 2: Items con ruta navegable deben tener permiso explícito
    if (!$isParentOnly) {
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

// Helper para detectar si algún hijo está activo
function hasActiveChild($children, $archivo_actual) {
    if (empty($children)) return false;
    foreach ($children as $child) {
        if (isActiveRoute($child['route'], $archivo_actual)) {
            return true;
        }
    }
    return false;
}
?>
<!-- Sidebar Menu (DB) -->
<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
<?php 
foreach ($tree as $node) {
    $hasChildren = !empty($node['children']);
    $activeTop = isActiveRoute($node['route'], $archivo_actual);
    $childActive = $hasChildren ? hasActiveChild($node['children'], $archivo_actual) : false;
    $menuOpen = ($activeTop || $childActive) ? 'menu-open' : '';
    
    $colorClass = $node['color_class'] ? ' ' . htmlspecialchars($node['color_class']) : '';
    $iconClass = htmlspecialchars($node['icon'] ?: 'fas fa-circle');
    $label = htmlspecialchars($node['label']);
    $route = htmlspecialchars($node['route']);
    $href = ($route !== '#') ? $route : '#';
    $activeClass = $activeTop ? 'active' : '';
?>
    <li class="nav-item <?= $hasChildren ? 'has-treeview' : '' ?> <?= $menuOpen ?>">
      <a href="<?= $href ?>" class="nav-link <?= $activeClass ?>">
        <i class="nav-icon <?= $iconClass . $colorClass ?>"></i>
        <p>
          <?= $label ?>
          <?php if ($hasChildren): ?><i class="right fas fa-angle-left"></i><?php endif; ?>
        </p>
      </a>
<?php 
    if ($hasChildren) {
?>
      <ul class="nav nav-treeview">
<?php 
        foreach ($node['children'] as $child) {
            $cActive = isActiveRoute($child['route'], $archivo_actual);
            $cColor = $child['color_class'] ? ' ' . htmlspecialchars($child['color_class']) : '';
            $cIcon = htmlspecialchars($child['icon'] ?: 'far fa-circle');
            $cLabel = htmlspecialchars($child['label']);
            $cRoute = htmlspecialchars($child['route']);
            $cActiveClass = $cActive ? 'active' : '';
?>
        <li class="nav-item">
          <a href="<?= $cRoute ?>" class="nav-link <?= $cActiveClass ?>" style="padding-left: 2.5rem;">
            <i class="nav-icon <?= $cIcon . $cColor ?>" style="margin-right: 0.5rem;"></i>
            <p><?= $cLabel ?></p>
          </a>
        </li>
<?php 
        }
?>
      </ul>
<?php 
    }
?>
    </li>
<?php 
}
?>
  </ul>
</nav>

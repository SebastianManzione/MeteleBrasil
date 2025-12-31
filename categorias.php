<?php

include('includes/navbar.php');

// Initialize session variables with defaults if not set (AFTER navbar.php)
if (!isset($_SESSION["idioma"])) {
    $_SESSION["idioma"] = "es";
}
if (!isset($_SESSION["idioma_bandera"])) {
    $_SESSION["idioma_bandera"] = "es.png";
}
if (!isset($_SESSION["moneda_sel_sym"])) {
    $_SESSION["moneda_sel_sym"] = "USD";
}
if (!isset($_SESSION["impuestos_pais"])) {
    $_SESSION["impuestos_pais"] = 0;
}
if (!isset($_SESSION["cupon_descuento"])) {
    $_SESSION["cupon_descuento"] = [];
}

// Initialize $lang if not defined by navbar.php
if (!isset($lang)) {
    $lang = [
        "actividades_en" => "actividades en",
        "espanol" => "Español",
        "ingles" => "English",
        "portugues" => "Português"
    ];
}

include('admin/classes/categoria.php');
include('admin/classes/fotos_categoria.php');
include('admin/classes/opiniones_categoria.php');
require_once('admin/classes/servicio.php');
require_once('admin/classes/servicio_opiniones.php');
require_once('admin/classes/salidas.php');
require_once('admin/classes/fotos_servicio.php');
require_once('admin/classes/tarifas.php');
require_once('admin/classes/cancelaciones.php');
require("admin/classes/texto_miniaturas.php");


// --- Lógica de obtención de datos (sin cambios) ---
$busqueda = "";
$cantidad_por_pagina = 6; // 6 servicios por página (2 filas de 3)
$desde = 0;
$pagina = 1;

$params = $_GET; // Guardar todos los parámetros para la paginación

if (isset($_GET['pagina'])) {
  $pagina = max(1, (int)$_GET['pagina']);
  $desde = ($pagina - 1) * $cantidad_por_pagina;
  unset($params['pagina']); // Remover para no duplicar en URL
}

$queryString = http_build_query($params);
$orden_precio = isset($_GET['orden_precio']) ? $_GET['orden_precio'] : '';
$orden_distancia = isset($_GET['orden_distancia']) ? $_GET['orden_distancia'] : '';
$orden_duracion = isset($_GET['orden_duracion']) ? $_GET['orden_duracion'] : '';

if (isset($_GET["idCategoria"]) && $_GET['idCategoria'] > 0) {
  // CASO 1: Categoría específica
  $idCategoria = $_GET["idCategoria"];
  $categorias = getCategoria($idCategoria);
  $servicios = getServiciosidCategoria_servicioPaginado($idCategoria, $desde, $cantidad_por_pagina);
  $cantidad_servicios_categoria = count(getServiciosidCategoria_servicio($idCategoria));
  $nViajeros = $categorias[0]["nViajeros"];
  $id = $categorias[0]["idCategoria_servicio"];
  $nombre_categoria = $categorias[0]["nombre_categoria_servicio"];
  $opiniones_categoria = OpinionesCategoria($id);
  $cantidad_opiniones_categoria = count($opiniones_categoria);
  $fotos = $categorias[0]["img_categoria_servicio"];
} else if (isset($_GET["buscar"]) && !empty($_GET["buscar"])) {
  // CASO 2: Búsqueda con término específico
  $busqueda = $_GET["buscar"];
  $idCategoria = 0;
  $servicios = getServiciosBusquedaPaginada($_GET["buscar"], $desde, $cantidad_por_pagina);
  $cantidad_servicios_categoria = count(getServiciosBusqueda($_GET["buscar"]));
  $categorias = getCategorias();
  $nViajeros = rand(690, 1200);
  $nombre_categoria = "todas_las_categorias";
  $opiniones_categoria = array();
  $cantidad_opiniones_categoria = rand(100, 500);
  $fotos = "sinCategoria.jpg";
} else {
  // CASO 3: Todas las categorías (sin búsqueda o con búsqueda vacía)
  $idCategoria = 0;
  $servicios = getServiciosPaginado($desde, $cantidad_por_pagina);
  $cantidad_servicios_categoria = count(getServicios());
  $categorias = getCategorias();
  $nViajeros = rand(690, 1200);
  $nombre_categoria = "todas_las_categorias";
  $opiniones_categoria = array();
  $cantidad_opiniones_categoria = rand(100, 500);
  $fotos = "sinCategoria.jpg";
}

// ========== APLICAR ORDENAMIENTO (ANTES DE PAGINACIÓN) ==========
// Combinable: proximidad (cercano/lejano) + precio (asc/desc)
if (($orden_distancia === 'cercano' || $orden_distancia === 'lejano') && isset($_SESSION['geoFinal']['latitud']) && isset($_SESSION['geoFinal']['longitud'])) {
    // Obtener TODOS los servicios (sin paginar)
    if (isset($_GET["idCategoria"]) && $_GET['idCategoria'] > 0) {
        $servicios_completos = getServiciosidCategoria_servicio($idCategoria);
    } else if (isset($_GET["buscar"]) && !empty($_GET["buscar"])) {
        $servicios_completos = getServiciosBusqueda($_GET["buscar"]);
    } else {
        $servicios_completos = getServicios();
    }
    
    // Aplicar ordenamiento por distancia
    $latUsuario = (float)$_SESSION['geoFinal']['latitud'];
    $lonUsuario = (float)$_SESSION['geoFinal']['longitud'];
    $servicios_ordenados = ordenarPorProximidad($servicios_completos, $latUsuario, $lonUsuario, $orden_distancia === 'lejano');
    
    // Si también hay orden por precio, aplicar como segundo criterio
    if ($orden_precio === 'price_asc' || $orden_precio === 'price_desc') {
        $servicios_ordenados = aplicarOrdenPrecio($servicios_ordenados, $orden_precio);
    }
    
    // Si también hay orden por duración, aplicar como tercer criterio
    if ($orden_duracion === 'duracion_asc' || $orden_duracion === 'duracion_desc') {
        $servicios_ordenados = aplicarOrdenDuracion($servicios_ordenados, $orden_duracion);
    }
    
    // Ahora aplicar la paginación manualmente
    $servicios = array_slice($servicios_ordenados, $desde, $cantidad_por_pagina);
    $total_registros = count($servicios_ordenados);
} elseif ($orden_precio === 'price_asc' || $orden_precio === 'price_desc') {
    // Solo ordenamiento por precio (sin distancia)
    if (isset($_GET["idCategoria"]) && $_GET['idCategoria'] > 0) {
        $servicios_completos = getServiciosidCategoria_servicio($idCategoria);
    } else if (isset($_GET["buscar"]) && !empty($_GET["buscar"])) {
        $servicios_completos = getServiciosBusqueda($_GET["buscar"]);
    } else {
        $servicios_completos = getServicios();
    }
    
    $servicios_ordenados = aplicarOrdenPrecio($servicios_completos, $orden_precio);
    $servicios = array_slice($servicios_ordenados, $desde, $cantidad_por_pagina);
    $total_registros = count($servicios_ordenados);
} elseif ($orden_duracion === 'duracion_asc' || $orden_duracion === 'duracion_desc') {
    // Solo ordenamiento por duración (sin distancia ni precio)
    if (isset($_GET["idCategoria"]) && $_GET['idCategoria'] > 0) {
        $servicios_completos = getServiciosidCategoria_servicio($idCategoria);
    } else if (isset($_GET["buscar"]) && !empty($_GET["buscar"])) {
        $servicios_completos = getServiciosBusqueda($_GET["buscar"]);
    } else {
        $servicios_completos = getServicios();
    }
    
    $servicios_ordenados = aplicarOrdenDuracion($servicios_completos, $orden_duracion);
    $servicios = array_slice($servicios_ordenados, $desde, $cantidad_por_pagina);
    $total_registros = count($servicios_ordenados);
}

// ========== FUNCIONES AUXILIARES ==========
// Nota: haversineKm() ya está definida en admin/classes/servicio.php

/**
 * Aplica ordenamiento por proximidad a un array de servicios
 * @param array $servicios - Array de servicios
 * @param float $latUsuario - Latitud del usuario
 * @param float $lonUsuario - Longitud del usuario
 * @param bool $inverso - Si es true, ordena de más lejano a más cercano
 * @return array Servicios ordenados por distancia
 */
function ordenarPorProximidad($servicios, $latUsuario, $lonUsuario, $inverso = false) {
    require('admin/classes/conexion.php');
    
    // Para cada servicio, calcular distancia mínima
    foreach ($servicios as $key => $servicio) {
        $idServicio = $servicio['idServicio'];
        $minKm = 99999.0;
        
        // Obtener todas las ubicaciones de este servicio
        $consulta = "
            SELECT DISTINCT u.latitud, u.longitud
            FROM servicio_salidas ss
            JOIN servicio_salidas_tarifas st ON st.idServicioSalidas = ss.idServicioSalidas
            JOIN servicio_tarifas_ubicacion u ON u.idServicioSalidasTarifas = st.idServicioSalidasTarifas
            WHERE ss.idServicio = :idServicio
              AND u.latitud IS NOT NULL
              AND u.longitud IS NOT NULL
        ";
        
        $comando = $pdo->prepare($consulta);
        $comando->execute([':idServicio' => $idServicio]);
        $ubicaciones = $comando->fetchAll(PDO::FETCH_ASSOC);
        
        // Calcular distancia mínima
        foreach ($ubicaciones as $ubi) {
            $distancia = haversineKm($latUsuario, $lonUsuario, $ubi['latitud'], $ubi['longitud']);
            if ($distancia < $minKm) {
                $minKm = $distancia;
            }
        }
        
        $servicios[$key]['distancia_km'] = round($minKm, 2);
    }
    
    // Ordenar por distancia
    usort($servicios, function($a, $b) use ($inverso) {
        if ($inverso) {
            // De mayor a menor (más lejano primero)
            return $b['distancia_km'] <=> $a['distancia_km'];
        } else {
            // De menor a mayor (más cercano primero)
            return $a['distancia_km'] <=> $b['distancia_km'];
        }
    });
    
    return $servicios;
}

/**
 * Aplica ordenamiento por precio a servicios ya ordenados
 * @param array $servicios - Array de servicios
 * @param string $orden - 'price_asc' o 'price_desc'
 * @return array Servicios con ordenamiento adicional por precio
 */
function aplicarOrdenPrecio($servicios, $orden) {
    require_once('admin/classes/salidas.php');
    require_once('admin/classes/tarifas.php');
    
    // Calcular precio mínimo para cada servicio (CON conversión de moneda - OPTIMIZADO)
    foreach ($servicios as $key => &$servicio) {
        $idServicio = $servicio['idServicio'];
        $fecha = date("Y-m-d");
        $salidas = getSalidasFechaLuegoIdServicio($fecha, $idServicio);
        
        $precioMinimo = 999999;
        
        // OPTIMIZACIÓN: Solo tomar la primera salida disponible (igual que en el display)
        if (!empty($salidas)) {
            $tarifas = getTarifas($salidas[0]['idServicioSalidas']);
            if (!empty($tarifas)) {
                // OPTIMIZACIÓN: Solo calcular la primera tarifa
                $tarifaCalculada = @calculaTarifa($tarifas[0]['idServicioSalidasTarifas'] ?? null, 1);
                if (!empty($tarifaCalculada) && isset($tarifaCalculada[0]["valor"])) {
                    $precioMinimo = floatval($tarifaCalculada[0]["valor"]);
                }
            }
        }
        
        // Si no se encontró precio, asignar valor alto para que quede al final
        $servicio['precio_orden'] = ($precioMinimo == 999999) ? PHP_FLOAT_MAX : $precioMinimo;
    }
    
    // Ordenar por precio
    usort($servicios, function($a, $b) use ($orden) {
        $precio_a = isset($a['precio_orden']) ? $a['precio_orden'] : PHP_FLOAT_MAX;
        $precio_b = isset($b['precio_orden']) ? $b['precio_orden'] : PHP_FLOAT_MAX;
        
        if ($orden === 'price_asc') {
            return $precio_a <=> $precio_b;
        } else {
            return $precio_b <=> $precio_a;
        }
    });
    
    return $servicios;
}

/**
 * Aplica ordenamiento por duración
 * @param array $servicios - Array de servicios
 * @param string $orden - 'duracion_asc' o 'duracion_desc'
 * @return array Servicios ordenados por duración
 */
function aplicarOrdenDuracion($servicios, $orden) {
    require_once('admin/classes/salidas.php');
    
    // Calcular duración máxima en horas para cada servicio
    foreach ($servicios as $key => &$servicio) {
        $idServicio = $servicio['idServicio'];
        $fecha = date("Y-m-d");
        $salidas = getSalidasFechaLuegoIdServicio($fecha, $idServicio);
        
        // Tomar duracionMaxima de la primera salida (en horas)
        $duracionHoras = 0;
        if (!empty($salidas) && isset($salidas[0]['duracionMaxima'])) {
            $duracionHoras = floatval($salidas[0]['duracionMaxima']);
        }
        
        $servicio['duracion_orden'] = $duracionHoras;
    }
    
    // Ordenar por duración
    usort($servicios, function($a, $b) use ($orden) {
        $duracion_a = isset($a['duracion_orden']) ? $a['duracion_orden'] : 0;
        $duracion_b = isset($b['duracion_orden']) ? $b['duracion_orden'] : 0;
        
        if ($orden === 'duracion_asc') {
            return $duracion_a <=> $duracion_b; // Más corta primero
        } else {
            return $duracion_b <=> $duracion_a; // Más larga primero
        }
    });
    
    return $servicios;
}

// ========== FUNCIONES PARA GENERAR HTML DE FILTROS (REUTILIZABLE) ==========

/**
 * Genera los botones de filtro por precio, proximidad y duración
 * @param string $queryString - Query string actual (sin orden_precio, orden_distancia ni orden_duracion)
 * @param string $orden_precio - Orden por precio actual (price_asc, price_desc, o vacío)
 * @param string $orden_distancia - Orden por distancia actual (cercano, lejano, o vacío)
 * @param string $orden_duracion - Orden por duración actual (duracion_asc, duracion_desc, o vacío)
 * @param array $lang - Array de traducciones
 * @return string HTML de los botones
 */
function generarFiltrosPrecio($queryString, $orden_precio, $orden_distancia, $orden_duracion, $lang) {
  // Construir URL base preservando orden_distancia si existe
  $baseParams = [];
  parse_str($queryString, $baseParams);
  unset($baseParams['orden_precio'], $baseParams['orden_duracion']); // Remover para reconstruir
  unset($baseParams['orden_distancia']); // Remover para reconstruir
  $baseQuery = http_build_query($baseParams);
  
  ob_start();
  ?>
  <!-- Filtros de Precio -->
  <a href="?<?= !empty($baseQuery) ? $baseQuery . '&' : ''; ?><?= $orden_precio === 'price_asc' ? '' : 'orden_precio=price_asc'; ?><?= !empty($orden_distancia) && $orden_precio !== 'price_asc' ? '&orden_distancia=' . $orden_distancia : ''; ?>" class="filtro-card <?= $orden_precio === 'price_asc' ? 'active' : ''; ?>">
    <div class="filtro-content">
      <i class="fa fa-arrow-up filtro-icon"></i>
      <span><?= isset($lang["menor_precio"]) ? $lang["menor_precio"] : "Menor Precio"; ?></span>
    </div>
    <div class="filtro-toggle <?= $orden_precio === 'price_asc' ? 'active' : ''; ?>"></div>
  </a>
  
  <a href="?<?= !empty($baseQuery) ? $baseQuery . '&' : ''; ?><?= $orden_precio === 'price_desc' ? '' : 'orden_precio=price_desc'; ?><?= !empty($orden_distancia) && $orden_precio !== 'price_desc' ? '&orden_distancia=' . $orden_distancia : ''; ?>" class="filtro-card <?= $orden_precio === 'price_desc' ? 'active' : ''; ?>">
    <div class="filtro-content">
      <i class="fa fa-arrow-down filtro-icon"></i>
      <span><?= isset($lang["mayor_precio"]) ? $lang["mayor_precio"] : "Mayor Precio"; ?></span>
    </div>
    <div class="filtro-toggle <?= $orden_precio === 'price_desc' ? 'active' : ''; ?>"></div>
  </a>
  
  <!-- Filtros de Proximidad -->
  <a href="?<?= !empty($baseQuery) ? $baseQuery . '&' : ''; ?><?= $orden_distancia === 'cercano' ? '' : 'orden_distancia=cercano'; ?><?= !empty($orden_precio) && $orden_distancia !== 'cercano' ? '&orden_precio=' . $orden_precio : ''; ?>" class="filtro-card <?= $orden_distancia === 'cercano' ? 'active' : ''; ?>">
    <div class="filtro-content">
      <i class="fa fa-map-marker-alt filtro-icon"></i>
      <span><?= isset($lang["mas_cercano"]) ? $lang["mas_cercano"] : "Más cercano"; ?></span>
    </div>
    <div class="filtro-toggle <?= $orden_distancia === 'cercano' ? 'active' : ''; ?>"></div>
  </a>
  
  <a href="?<?= !empty($baseQuery) ? $baseQuery . '&' : ''; ?><?= $orden_distancia === 'lejano' ? '' : 'orden_distancia=lejano'; ?><?= !empty($orden_precio) && $orden_distancia !== 'lejano' ? '&orden_precio=' . $orden_precio : ''; ?>" class="filtro-card <?= $orden_distancia === 'lejano' ? 'active' : ''; ?>">
    <div class="filtro-content">
      <i class="fa fa-map-marker-alt filtro-icon" style="transform: rotate(180deg);"></i>
      <span><?= isset($lang["mas_lejano"]) ? $lang["mas_lejano"] : "Más lejano"; ?></span>
    </div>
    <div class="filtro-toggle <?= $orden_distancia === 'lejano' ? 'active' : ''; ?>"></div>
  </a>
  
  <!-- Filtros de Duración -->
  <a href="?<?= !empty($baseQuery) ? $baseQuery . '&' : ''; ?><?= $orden_duracion === 'duracion_asc' ? '' : 'orden_duracion=duracion_asc'; ?><?= !empty($orden_precio) && $orden_duracion !== 'duracion_asc' ? '&orden_precio=' . $orden_precio : ''; ?><?= !empty($orden_distancia) && $orden_duracion !== 'duracion_asc' ? '&orden_distancia=' . $orden_distancia : ''; ?>" class="filtro-card <?= $orden_duracion === 'duracion_asc' ? 'active' : ''; ?>">
    <div class="filtro-content">
      <i class="fa fa-clock filtro-icon"></i>
      <span><?= isset($lang["duracion_corta"]) ? $lang["duracion_corta"] : "Duración más corta"; ?></span>
    </div>
    <div class="filtro-toggle <?= $orden_duracion === 'duracion_asc' ? 'active' : ''; ?>"></div>
  </a>
  
  <a href="?<?= !empty($baseQuery) ? $baseQuery . '&' : ''; ?><?= $orden_duracion === 'duracion_desc' ? '' : 'orden_duracion=duracion_desc'; ?><?= !empty($orden_precio) && $orden_duracion !== 'duracion_desc' ? '&orden_precio=' . $orden_precio : ''; ?><?= !empty($orden_distancia) && $orden_duracion !== 'duracion_desc' ? '&orden_distancia=' . $orden_distancia : ''; ?>" class="filtro-card <?= $orden_duracion === 'duracion_desc' ? 'active' : ''; ?>">
    <div class="filtro-content">
      <i class="fa fa-hourglass-half filtro-icon"></i>
      <span><?= isset($lang["duracion_larga"]) ? $lang["duracion_larga"] : "Duración más larga"; ?></span>
    </div>
    <div class="filtro-toggle <?= $orden_duracion === 'duracion_desc' ? 'active' : ''; ?>"></div>
  </a>
  <?php
  return ob_get_clean();
}

/**
 * Genera los botones de filtro por categoría
 * @param int $idCategoria - ID de categoría actual
 * @param string $busqueda - Término de búsqueda actual
 * @param string $orden - Orden actual
 * @param array $lang - Array de traducciones
 * @return string HTML de los botones
 */
function generarFiltrosCategorias($idCategoria, $busqueda, $orden, $lang) {
  ob_start();
  $todas_las_categorias = getCategorias();
  
  // URL para "Todas las categorías"
  $urlParamsAll = "";
  if (!empty($busqueda)) {
    $urlParamsAll .= "buscar=" . urlencode($busqueda);
  }
  if (!empty($orden)) {
    $urlParamsAll .= (!empty($urlParamsAll) ? "&" : "") . "orden=" . urlencode($orden);
  }
  
  $isActive = ($idCategoria == 0) ? 'active' : '';
  ?>
  <a href="categorias?<?= $urlParamsAll ?>" class="categoria-link <?= $isActive ?>">
    <?= isset($lang["todas_las_categorias"]) ? $lang["todas_las_categorias"] : "Todas las categorías"; ?>
  </a>
  
  <?php
  for ($i = 0; $i < count($todas_las_categorias); $i++) {
    $idCategoria_item = $todas_las_categorias[$i]["idCategoria_servicio"];
    $nombre_categoria_item = $todas_las_categorias[$i]["nombre_categoria_servicio"];
    
    $urlParams = "idCategoria=" . $idCategoria_item;
    if (!empty($busqueda)) {
      $urlParams .= "&buscar=" . urlencode($busqueda);
    }
    if (!empty($orden)) {
      $urlParams .= "&orden=" . urlencode($orden);
    }
    
    $isActive = ($idCategoria == $idCategoria_item) ? 'active' : '';
    ?>
    <a href="categorias?<?= $urlParams ?>" class="categoria-link <?= $isActive ?>">
      <?= $nombre_categoria_item ?>
    </a>
  <?php
  }
  return ob_get_clean();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>Actividades en <?= $lang[$nombre_categoria] ?? 'Destinos' ?></title>
  <style>
    /* ========== HEADER DESKTOP ========== */
    #header-destinos {
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      color: white;
      padding: 6rem 1rem;
      text-align: center;
      position: relative;
    }

    #header-destinos::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: -1;
    }

    .titulo-categoria {
      font-size: 3rem;
      font-weight: 700;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
      margin-bottom: 2rem;
    }

    .stats-container {
      background: rgba(255, 255, 255, 0.1);
      padding: 2rem;
      border-radius: 8px;
      margin-top: 2rem;
    }

    .stat-item {
      text-align: center;
      margin: 0 1rem;
    }

    .stat-number {
      font-size: 2.5rem;
      font-weight: 700;
      color: #fff;
    }

    .stat-label {
      font-size: 0.95rem;
      color: rgba(255, 255, 255, 0.9);
      margin-top: 0.5rem;
    }

    /* ========== CONTROLES BÚSQUEDA ========== */
    .controles-busqueda {
      background-color: #ffffff;
      padding: 1.5rem;
      border-bottom: 1px solid #dee2e6;
      position: sticky;
      top: 0;
      z-index: 1020;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-control-search {
      border-radius: 50px 0 0 50px;
      border: 2px solid #e9ecef;
      padding: 0.8rem 1.2rem;
      font-size: 1rem;
    }

    .btn-search {
      border-radius: 0 50px 50px 0;
      padding: 0.8rem 1.5rem;
      font-size: 1rem;
      border: 2px solid #e9ecef;
    }

    .btn-lg-filter {
      padding: 0.8rem 1rem;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 8px;
    }

    /* ========== TARJETAS DE SERVICIOS (VERTICAL - 3 COLUMNAS) ========== */
    .service-card {
      border: 1px solid #e5e5e5;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      background: white;
      display: flex;
      flex-direction: column;
      text-decoration: none;
      color: inherit;
      height: 100%;
    }

    .service-card:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      transform: translateY(-2px);
    }

    .service-card a {
      text-decoration: none;
      color: inherit;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .card-img-container {
      position: relative;
      overflow: hidden;
      width: 100%;
      height: 200px;
      background-color: #f0f0f0;
      flex-shrink: 0;
    }

    .card-img-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .service-card:hover .card-img-container img {
      transform: scale(1.05);
    }

    .badge-top {
      position: absolute;
      top: 10px;
      left: 10px;
      background-color: #007bff;
      color: white;
      padding: 0.4rem 0.8rem;
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 600;
      z-index: 10;
      text-transform: uppercase;
    }

    .card-body {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      padding: 1.2rem;
      justify-content: space-between;
    }

    .card-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: #333;
      line-height: 1.4;
    }

    .rating-text {
      color: #007bff;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
    }

    .rating-text strong {
      color: #007bff;
      font-weight: 700;
    }

    .description-text {
      color: #495057;
      font-size: 0.9rem;
      margin-bottom: 0.8rem;
      line-height: 1.5;
    }

    .features-list {
      list-style: none;
      padding: 0;
      margin: 0.5rem 0;
      font-size: 0.9rem;
      color: #6c757d;
    }

    .features-list li {
      margin-bottom: 0.3rem;
    }

    .features-list i {
      color: #007bff;
      margin-right: 8px;
    }

    .price-section {
      border-top: 1px solid #f0f0f0;
      padding-top: 0.8rem;
      margin-top: auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .cancellation-text {
      font-weight: 600;
      font-size: 0.85rem;
      color: #28a745;
    }

    .price-text {
      font-size: 1.5rem;
      font-weight: 700;
      color: #029ce2;
    }

    .price-text.agotado {
      color: #029ce2;
      font-size: 1.2rem;
    }

    /* ========== SIDEBAR DESKTOP ========== */
    .sidebar-container {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      top: 120px;
      position: sticky;
      max-height: calc(100vh - 140px);
      overflow-y: auto;
    }

    .sidebar-container::-webkit-scrollbar {
      width: 6px;
    }

    .sidebar-container::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .sidebar-container::-webkit-scrollbar-thumb {
      background: #007bff;
      border-radius: 10px;
    }

    .sidebar-container::-webkit-scrollbar-thumb:hover {
      background: #0056b3;
    }

    .sidebar-title {
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: #333;
    }

    /* ========== PAGINACIÓN ========== */
    .pagination {
      gap: 0.3rem;
    }

    .pagination .page-item .page-link {
      border: none;
      background: white;
      color: #555;
      padding: 0.4rem 0.7rem;
      font-size: 0.9rem;
      border-radius: 6px;
      transition: all 0.2s ease;
      font-weight: 500;
      margin: 0 2px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    .pagination .page-item .page-link:hover:not(.disabled .page-link) {
      background-color: #e7f3ff;
      color: #029ce2;
      transform: translateY(-2px);
      box-shadow: 0 3px 8px rgba(2, 156, 226, 0.2);
    }

    .pagination .page-item.active .page-link {
      background: linear-gradient(135deg, #029ce2 0%, #0277bd 100%);
      color: white;
      box-shadow: 0 3px 10px rgba(2, 156, 226, 0.35);
    }

    .pagination .page-item.disabled .page-link {
      background-color: #f8f9fa;
      color: #adb5bd;
      cursor: not-allowed;
      opacity: 0.6;
      box-shadow: none;
    }

    /* ========== FILTROS ESTILO MERCADOLIBRE ========== */
    
    /* Cards de filtro con toggle switch */
    .filtro-card {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: white;
      border: 1px solid #e5e5e5;
      border-radius: 6px;
      padding: 1rem;
      margin-bottom: 0.5rem;
      text-decoration: none;
      color: #333;
      transition: all 0.2s ease;
    }

    .filtro-card:hover {
      background-color: #f5f5f5;
      text-decoration: none;
      color: #333;
      box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .filtro-card.active {
      border-color: #029ce2;
      background-color: #e7f3ff;
    }

    .filtro-content {
      display: flex;
      align-items: center;
      gap: 0.7rem;
    }

    .filtro-icon {
      font-size: 1rem;
      color: #029ce2;
      opacity: 0.8;
    }

    .filtro-card.active .filtro-icon {
      opacity: 1;
    }

    /* Toggle switch visual */
    .filtro-toggle {
      width: 44px;
      height: 24px;
      background-color: #e5e5e5;
      border-radius: 12px;
      position: relative;
      transition: all 0.3s ease;
      flex-shrink: 0;
    }

    .filtro-toggle::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      background: white;
      border-radius: 50%;
      top: 2px;
      left: 2px;
      transition: all 0.3s ease;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .filtro-toggle.active {
      background-color: #029ce2;
    }

    .filtro-toggle.active::after {
      left: 22px;
    }

    /* Links de categoría estilo MercadoLibre */
    .categoria-link {
      display: block;
      padding: 0.5rem 0;
      color: #555;
      text-decoration: none;
      font-size: 0.9rem;
      transition: color 0.2s ease;
      border-bottom: 1px solid transparent;
    }

    .categoria-link:hover {
      color: #029ce2;
      text-decoration: none;
    }

    .categoria-link.active {
      color: #029ce2;
      font-weight: 500;
    }

    /* ========== NO RESULTADOS ========== */
    .no-results {
      text-align: center;
      padding: 3rem 1rem;
    }

    .no-results h3 {
      color: #333;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .no-results p {
      color: #6c757d;
    }

  </style>
</head>

<body>

  <!-- HEADER DESKTOP -->
  <header id="header-destinos" class="d-none d-md-block" style="background-image: url('admin/img/categoria_servicio/<?= $fotos; ?>');">
    <div class="container">
      <h1 class="titulo-categoria"><?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $nombre_categoria; ?></h1>
      <div class="stats-container">
        <div class="d-flex justify-content-around flex-wrap">
          <div class="stat-item">
            <div class="stat-number"><?= $cantidad_servicios_categoria; ?></div>
            <div class="stat-label"><?= isset($lang["actividades"]) ? $lang["actividades"] : 'actividades'; ?></div>
          </div>
          <div class="stat-item">
            <div class="stat-number"><?= $nViajeros; ?></div>
            <div class="stat-label"><?= isset($lang["viajeros_lo_han_disfrutado"]) ? $lang["viajeros_lo_han_disfrutado"] : 'viajeros'; ?></div>
          </div>
          <div class="stat-item">
            <div class="stat-number"><?= $cantidad_opiniones_categoria; ?></div>
            <div class="stat-label"><?= isset($lang["opiniones_reales"]) ? $lang["opiniones_reales"] : 'opiniones reales'; ?></div>
          </div>
          <div class="stat-item">
            <div class="stat-number">9,2</div>
            <div class="stat-label"><?= isset($lang["asi_nos_puntuan"]) ? $lang["asi_nos_puntuan"] : 'así nos puntúan'; ?></div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- BÚSQUEDA Y FILTROS RESPONSIVOS -->
  <section class="controles-busqueda">
    <div class="container">
      <!-- BÚSQUEDA -->
      <form class="form-buscar mb-3" method="get">
        <div class="input-group">
          <input class="form-control form-control-lg form-control-search" name="buscar" type="text" placeholder="<?= isset($lang["que_hacemos"]) ? $lang["que_hacemos"] : 'Qué hacemos'; ?>" value="<?= htmlspecialchars($busqueda) ?>">
          <div class="input-group-append">
            <button class="btn btn-primary btn-lg btn-search" type="submit"><i class="fa fa-search"></i></button>
          </div>
        </div>
      </form>

      <!-- CONTROLES ORDENAMIENTO DESKTOP -->
      <div class="d-none d-md-flex gap-2">
      </div>

      <!-- BOTÓN FILTRO MÓVIL -->
      <button class="btn btn-outline-primary btn-lg btn-block btn-lg-filter d-md-none" data-toggle="modal" data-target="#filterModal">
        <i class="fa fa-sliders-h"></i> Filtrar y Ordenar
      </button>
    </div>
  </section>

  <!-- CONTENEDOR PRINCIPAL -->
  <main class="container py-4">
    <div class="row">

      <!-- SIDEBAR FILTROS (DESKTOP ONLY) -->
      <div class="col-lg-3 d-none d-lg-block">
        <div class="sidebar-container">
          <h3 class="sidebar-title"><?= isset($lang["filtrar_resultados"]) ? $lang["filtrar_resultados"] : "Filtrar resultados"; ?></h3>

          <!-- FILTRO DE PRECIO -->
          <div class="mb-4">
            <h6 class="mb-3" style="font-size: 14px; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">
              <i class="fa fa-sort" style="color: #029ce2; margin-right: 8px;"></i><?= isset($lang["ordenar"]) ? $lang["ordenar"] : "Ordenar"; ?>
            </h6>
            <div>
              <?= generarFiltrosPrecio($queryString, $orden_precio, $orden_distancia, $orden_duracion, $lang); ?>
            </div>
          </div>

          <!-- FILTRO DE CATEGORÍAS -->
          <div class="mb-4">
            <h6 class="mb-3" style="font-size: 14px; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">
              <i class="fa fa-filter" style="color: #029ce2; margin-right: 8px;"></i><?= isset($lang["categorias"]) ? $lang["categorias"] : "Categorías"; ?>
            </h6>
            <div>
              <?= generarFiltrosCategorias($idCategoria, $busqueda, $orden_precio, $lang); ?>
            </div>
          </div>

          <!-- ÚLTIMAS OPINIONES (si hay) -->
          <?php if (!empty($opiniones_categoria) && count($opiniones_categoria) > 0) : ?>
            <div class="card" style="border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin-top: 2rem;">
              <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; padding: 1rem;">
                <h5 class="mb-0" style="color: #333; font-weight: 700;">
                  <i class="fa fa-comments" style="margin-right: 0.5rem; color: #007bff;"></i>
                  <?= isset($lang["ultimas_opiniones"]) ? $lang["ultimas_opiniones"] : "Últimas opiniones"; ?>
                </h5>
              </div>
              <div class="card-body" style="padding: 0;">
                <?php 
                $opiniones_mostradas = 0;
                for ($b = 0; $b < count($opiniones_categoria) && $opiniones_mostradas < 3; $b++, $opiniones_mostradas++) : 
                ?>
                  <div style="padding: 1rem; border-bottom: 1px solid #f0f0f0;">
                    <p style="color: #007bff; font-weight: 600; margin-bottom: 0.5rem; font-style: italic;">
                      "<?= htmlspecialchars(substr($opiniones_categoria[$b]['opinion'], 0, 150)); ?><?= strlen($opiniones_categoria[$b]['opinion']) > 150 ? '...' : ''; ?>"
                    </p>
                    <p style="margin-bottom: 0; color: #6c757d; font-size: 0.9rem;">
                      <i class="fa fa-star" style="color: #007bff;"></i>
                      <strong><?= $opiniones_categoria[$b]['nombre']; ?></strong>
                    </p>
                  </div>
                <?php endfor; ?>
              </div>
            </div>
          <?php endif; ?>

        </div>
      </div>

      <!-- ÁREA DE SERVICIOS -->
      <div class="col-lg-9">

        <!-- STATS MÓVIL -->
        <div class="d-md-none py-3">
          <div class="row text-center">
            <div class="col-6 mb-2">
              <div class="stat-number" style="font-size: 1.8rem;"><?= $cantidad_servicios_categoria; ?></div>
              <div class="stat-label" style="font-size: 0.85rem;"><?= isset($lang["actividades"]) ? $lang["actividades"] : 'actividades'; ?></div>
            </div>
            <div class="col-6 mb-2">
              <div class="stat-number" style="font-size: 1.8rem;"><?= $nViajeros; ?></div>
              <div class="stat-label" style="font-size: 0.85rem;"><?= isset($lang["viajeros"]) ? $lang["viajeros"] : 'viajeros'; ?></div>
            </div>
          </div>
        </div>

        <!-- SERVICIOS -->
        <?php if (count($servicios) > 0) : ?>
          <!-- GRID DESKTOP (3 columnas) / STACK MÓVIL (1 columna) -->
          <div class="row">
            <?php for ($i = 0; $i < count($servicios); $i++) {
              $idServicio = $servicios[$i]["idServicio"];
              $fecha = date("Y-m-d");
              $salidas = getSalidasFechaLuegoIdServicio($fecha, $idServicio);

              // Usar misma lógica que index.php
              if (count($salidas) > 0) {
                $idServicioSalidas = $salidas[0]['idServicioSalidas'];
                $tarifas = getTarifas($idServicioSalidas);
                if (!empty($tarifas)) {
                  $tarifa = @calculaTarifa($tarifas[0]['idServicioSalidasTarifas'] ?? null, 1);
                  $precioSugerido = (!empty($tarifa) && isset($tarifa[0]["valorSym"])) ? $tarifa[0]["valorSym"] : "ESGOTADO";
                  
                  // Obtener cancelación
                  $cancelacion = "";
                  $cancelaciones = getTipoCancelaciones($tarifas[0]['idCancelaciones']);
                  if (!empty($cancelaciones)) {
                    switch ($cancelaciones[0]["idCancelacion"]) {
                      case 1:
                      case 3:
                      case 7:
                        $cancelacion = isset($lang["cancelamento_gratis"]) ? $lang["cancelamento_gratis"] : "Cancelamento gratis!";
                        break;
                    }
                  }
                } else {
                  $precioSugerido = "ESGOTADO";
                  $cancelacion = "";
                }
              } else {
                $precioSugerido = "ESGOTADO";
                $cancelacion = "";
              }

              $nombre_servicio = $servicios[$i]["nombre_servicio"];
              $descripcion_corta = $servicios[$i]["descripcion_corta"];
              $opiniones_servicio = getOpinionesServicio($idServicio);
              $estrellas_servicio = getEstrellasServicio($idServicio);
              $cantidad_opiniones_servicio = count($opiniones_servicio);
              $duracion_servicio = getDuracionServicio($idServicio);
              $fotos_servicio = getFotoMiniaturaServicio($idServicio);
              $ruta_foto = !empty($fotos_servicio) ? $fotos_servicio[0]["ruta"] : 'placeholder.jpg';
              $textoMiniatura = getTextoMiniatura($servicios[$i]["idTextoMiniaturas"])[0]["texto"] ?? '';

            ?>
              <!-- TARJETA SERVICIO HORIZONTAL -->
              <a href="servicio?id=<?= $idServicio ?>">
                <div class="mb-4">
                  <div class="card card-visitas">
                    <div class="row no-gutters d-md-none" style="position: absolute;z-index: 999;">
                      <div class="col-6">
                        <?php if (!empty($textoMiniatura)) : ?>
                          <div class="badge badge-primary badge-destacado"><?= $textoMiniatura; ?></div>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="card-body padding-body">
                      <div class="row">
                        <div class="col-md-4 col-4">
                          <img src="admin/classes/imgServicio/<?= $ruta_foto; ?>" class="w-100 img-fluid img-card-destinos">
                        </div>
                        <div class="col-md-8 col-8" style="padding-left:0px !important;">
                          <div class="card-block">
                            <h4 class="text-left titulo-card-destinos semibold"><?= $nombre_servicio ?></h4>
                            <?php if (count($opiniones_servicio) > 0) { ?>
                              <h5 class="texto-opinion-desta"><strong><?= $estrellas_servicio; ?>/10</strong> <small class="text-gris"><?= $cantidad_opiniones_servicio; ?> <?= isset($lang["opiniones"]) ? $lang["opiniones"] : "opiniones"; ?></small></h5>
                            <?php } ?>
                            <p class="text-gris d-md-block"><?= $descripcion_corta; ?></p>
                          </div>
                          <?php if(!empty($duracion_servicio)): ?>
                            <ul class="lista-caracteristicas d-md-none">
                              <li><i class="fa fa-hourglass-half"></i> <?= $duracion_servicio["duracionMinima"]; ?> - <?= $duracion_servicio["duracionMaxima"]; ?></li>
                            </ul>
                          <?php endif; ?>
                          <?php if (!empty($cancelacion)) : ?>
                            <h4 class="text-success text-cancelacion float-left d-md-none semibold"><?= $cancelacion ?></h4>
                          <?php endif; ?>
                          <p class="float-right d-md-none semibold <?= ($precioSugerido === 'ESGOTADO') ? 'agotado' : ''; ?>"><?= $precioSugerido; ?></p>
                        </div>
                      </div>

                      <div class="d-md-block mt-2 d-none">
                        <div class="row no-gutters">
                          <div class="col-lg-4 col-12">
                            <?php if(!empty($duracion_servicio)): ?>
                              <ul class="lista-caracteristicas">
                                <li><i class="fa fa-hourglass-half"></i> <?= $duracion_servicio["duracionMinima"]; ?> - <?= $duracion_servicio["duracionMaxima"]; ?></li>
                              </ul>
                            <?php endif; ?>
                          </div>
                          <div class="col-lg-4 col-12">
                            <?php if (!empty($cancelacion)) : ?>
                              <h4 class="text-success text-cancelacion semibold"><?= $cancelacion; ?></h4>
                            <?php endif; ?>
                          </div>
                          <div class="col-lg-4 col-12">
                            <h4 class="float-right semibold <?= ($precioSugerido === 'ESGOTADO') ? 'agotado' : ''; ?>"><?= $precioSugerido; ?></h4>
                          </div>
                        </div>
                      </div>

                    </div>

                    <div class="destacado d-md-block d-none">
                      <?php if (!empty($textoMiniatura)) : ?>
                        <h5 class="text-uppercase text-white"><?= $textoMiniatura; ?></h5>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </a>

            <?php } ?>
          </div>

          <!-- PAGINACIÓN MEJORADA -->
          <?php
          $cantidad_de_paginas = ceil($cantidad_servicios_categoria / $cantidad_por_pagina);
          if ($cantidad_de_paginas > 1) {
            // Calcular rango de páginas a mostrar (máximo 5 botones numéricos)
            $rango_paginas = 5;
            $inicio_rango = max(1, $pagina - floor($rango_paginas / 2));
            $fin_rango = min($cantidad_de_paginas, $inicio_rango + $rango_paginas - 1);
            
            // Ajustar inicio si fin se acerca al final
            if ($fin_rango - $inicio_rango < $rango_paginas - 1) {
              $inicio_rango = max(1, $fin_rango - $rango_paginas + 1);
            }
          ?>
            <nav aria-label="Paginación de resultados" class="mt-5 mb-4">
              <ul class="pagination justify-content-center flex-wrap">
                <!-- BOTÓN ANTERIOR -->
                <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                  <a class="page-link" href="?pagina=<?= $pagina - 1 ?>&<?= $queryString ?>" aria-label="Anterior">
                    <i class="fa fa-chevron-left"></i>
                  </a>
                </li>

                <!-- NÚMEROS DE PÁGINA -->
                <?php if ($inicio_rango > 1) : ?>
                  <li class="page-item">
                    <a class="page-link" href="?pagina=1&<?= $queryString ?>">1</a>
                  </li>
                  <?php if ($inicio_rango > 2) : ?>
                  <li class="page-item disabled">
                    <span class="page-link">...</span>
                  </li>
                  <?php endif; ?>
                <?php endif; ?>

                <?php for ($p = $inicio_rango; $p <= $fin_rango; $p++) : ?>
                  <li class="page-item <?= ($p === $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $p ?>&<?= $queryString ?>">
                      <?= $p ?>
                    </a>
                  </li>
                <?php endfor; ?>

                <?php if ($fin_rango < $cantidad_de_paginas) : ?>
                  <?php if ($fin_rango < $cantidad_de_paginas - 1) : ?>
                  <li class="page-item disabled">
                    <span class="page-link">...</span>
                  </li>
                  <?php endif; ?>
                  <li class="page-item">
                    <a class="page-link" href="?pagina=<?= $cantidad_de_paginas ?>&<?= $queryString ?>"><?= $cantidad_de_paginas ?></a>
                  </li>
                <?php endif; ?>

                <!-- BOTÓN SIGUIENTE -->
                <li class="page-item <?= ($pagina >= $cantidad_de_paginas) ? 'disabled' : '' ?>">
                  <a class="page-link" href="?pagina=<?= $pagina + 1 ?>&<?= $queryString ?>" aria-label="Siguiente">
                    <i class="fa fa-chevron-right"></i>
                  </a>
                </li>
              </ul>
            </nav>
          <?php } ?>

        <?php else : ?>
          <div class="no-results">
            <h3>No se encontraron resultados</h3>
            <p>Intenta cambiar tus filtros o términos de búsqueda.</p>
          </div>
        <?php endif; ?>

        <!-- TARJETA GUÍA (DESKTOP ONLY) -->
        <?php if ($idCategoria > 0) : ?>
          <div class="card card-guia d-none d-lg-block mt-5" style="border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
            <div class="card-body" style="padding: 0;">
              <form action="guias.php" method="get" style="display: flex; height: 250px; text-decoration: none; color: inherit;">
                <input type="hidden" name="idCategoria" value="<?= $idCategoria; ?>">
                
                <!-- IMAGEN IZQUIERDA -->
                <div style="flex: 0 0 40%; background-color: #f0f0f0; overflow: hidden;">
                  <img src="admin/img/categoria_servicio/<?= $fotos; ?>" class="img-fluid w-100" style="height: 100%; object-fit: cover;">
                </div>

                <!-- CONTENIDO DERECHA -->
                <div style="flex: 1; padding: 2rem; display: flex; flex-direction: column; justify-content: center; background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                  <h4 style="color: white; font-weight: 700; margin-bottom: 0.5rem;">
                    <i class="fa fa-map" style="margin-right: 0.5rem;"></i>
                    <?= isset($lang["conoce_nuestra_guia_de"]) ? $lang["conoce_nuestra_guia_de"] : "Conoce nuestra guía de"; ?>
                  </h4>
                  <p style="color: rgba(255,255,255,0.9); font-size: 1.2rem; margin: 0;">
                    <?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $nombre_categoria; ?>
                  </p>
                  <button type="submit" class="btn btn-light mt-3" style="align-self: flex-start;">
                    Ver Guía <i class="fa fa-arrow-right ml-2"></i>
                  </button>
                </div>
              </form>
            </div>
          </div>
        <?php endif; ?>

      </div>

    </div>
  </main>

  <!-- MODAL FILTROS MÓVIL -->
  <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header" style="border-bottom: 2px solid #029ce2;">
          <h5 class="modal-title font-weight-bold" id="filterModalLabel" style="color: #029ce2;">
            <i class="fa fa-filter"></i> <?= isset($lang["filtros"]) ? $lang["filtros"] : "Filtros"; ?>
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Ordenar -->
          <h6 class="mb-3" style="font-size: 14px; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">
            <i class="fa fa-sort" style="color: #029ce2; margin-right: 8px;"></i><?= isset($lang["ordenar"]) ? $lang["ordenar"] : "Ordenar"; ?>
          </h6>
          <div class="mb-4">
            <?= generarFiltrosPrecio($queryString, $orden_precio, $orden_distancia, $orden_duracion, $lang); ?>
          </div>

          <!-- Categorías -->
          <h6 class="mb-3" style="font-size: 14px; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">
            <i class="fa fa-filter" style="color: #029ce2; margin-right: 8px;"></i><?= isset($lang["categorias"]) ? $lang["categorias"] : "Categorías"; ?>
          </h6>
          <div class="mb-3">
            <?= generarFiltrosCategorias($idCategoria, $busqueda, $orden_precio, $lang); ?>
          </div>

          <!-- Limpiar filtros -->
          <div class="mt-4">
            <a href="?" class="btn btn-outline-secondary btn-block" style="border-radius: 8px;">
              <i class="fa fa-times"></i> <?= isset($lang["limpiar_filtros"]) ? $lang["limpiar_filtros"] : "Limpiar Filtros"; ?>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include('footer.php'); ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

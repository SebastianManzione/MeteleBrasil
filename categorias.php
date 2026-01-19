<?php

include('includes/navbar.php');

// ========== CONFIGURACIÓN DE DISPONIBILIDAD ==========
// Personalizar cantidad de salidas a mostrar en tarjetas
define('DISPONIBILIDAD_SALIDAS_CATEGORIAS', 3); // 2, 3, o 4 salidas

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

// PRIORIDAD: Si hay búsqueda activa, IGNORAR filtros de distancia automáticos
$hay_busqueda = isset($_GET["buscar"]) && !empty($_GET["buscar"]);

// Obtener filtros de URL (si el usuario los seleccionó)
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
} else if ($hay_busqueda) {
  // CASO 2: Búsqueda con término específico - PRIORIDAD MÁXIMA
  $busqueda = $_GET["buscar"];
  $idCategoria = 0;
  // No paginar aún, se hará después de aplicar filtros de ordenamiento
  $servicios = getServiciosBusqueda($_GET["buscar"]);
  $cantidad_servicios_categoria = count($servicios);
  $categorias = getCategorias();
  $nViajeros = rand(690, 1200);
  $nombre_categoria = isset($lang["todas_las_categorias"]) ? $lang["todas_las_categorias"] : "Todas Las Categorías";
  $opiniones_categoria = array();
  $cantidad_opiniones_categoria = rand(100, 500);
  $fotos = "sinCategoria.jpg";
} else {
  // CASO 3: Todas las categorías (sin búsqueda)
  $idCategoria = 0;
  $servicios = getServiciosPaginado($desde, $cantidad_por_pagina);
  $cantidad_servicios_categoria = count(getServicios());
  $categorias = getCategorias();
  $nViajeros = rand(690, 1200);
  $nombre_categoria = isset($lang["todas_las_categorias"]) ? $lang["todas_las_categorias"] : "Todas Las Categorías";
  $opiniones_categoria = array();
  $cantidad_opiniones_categoria = rand(100, 500);
  $fotos = "sinCategoria.jpg";
}

// Obtener imágenes del slider desde BD o usar la imagen de la categoría como slide único
$sliderImages = [];
$sliderIntervalo = 5000; // default 5 segundos
$sliderBasePath = 'img/';

// Si hay categoría seleccionada con imagen propia, usarla como único slide
if ($idCategoria > 0 && isset($categorias[0]['img_categoria_servicio']) && !empty($categorias[0]['img_categoria_servicio'])) {
  $sliderImages[] = $categorias[0]['img_categoria_servicio'];
  $sliderBasePath = 'admin/img/categoria_servicio/';
}

try {
  require_once(__DIR__ . '/config/config.php');
  // Solo consultar la tabla si no se cargó imagen de categoría
  if (empty($sliderImages)) {
    $mysqli_slider = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$mysqli_slider->connect_error) {
      $stmt = $mysqli_slider->query("SELECT imagen, intervalo FROM slider WHERE activo = 1 ORDER BY orden ASC");
      if ($stmt) {
        while ($row = $stmt->fetch_assoc()) {
          $sliderImages[] = $row['imagen'];
          // Usar el intervalo de la primera imagen
          if (count($sliderImages) == 1) {
            $sliderIntervalo = intval($row['intervalo'] ?? 5000);
          }
        }
      }
      $mysqli_slider->close();
    }
  }
} catch (Exception $e) {
  // Fallback a imágenes por defecto
}
if (empty($sliderImages)) {
  $sliderImages = ['slider4.jpg', 'slider2.jpg', 'slider3.jpg', 'slider1.jpg'];
  $sliderBasePath = 'img/';
}

$sliderCount = count($sliderImages);
$showSliderControls = $sliderCount > 1;

// ========== APLICAR ORDENAMIENTO (ANTES DE PAGINACIÓN) ==========
// REGLA: En búsquedas, RELEVANCIA es criterio PRINCIPAL, filtros son SECUNDARIOS

// Si hay búsqueda activa
if ($hay_busqueda) {
    // Los servicios ya están cargados desde getServiciosBusqueda() con campo 'relevancia'
    $servicios_completos = $servicios;
    
    // Aplicar filtros SOLO si el usuario los seleccionó explícitamente
    $hay_filtros = ($orden_distancia !== '' || $orden_precio !== '' || $orden_duracion !== '');
    
    if ($hay_filtros) {
        // IMPORTANTE: Los filtros se aplican como DESEMPATE dentro de cada nivel de relevancia
        
        // Calcular distancia para cada servicio (si hay filtro de distancia)
        if (($orden_distancia === 'cercano' || $orden_distancia === 'lejano') && 
            isset($_SESSION['geoFinal']['latitud']) && isset($_SESSION['geoFinal']['longitud'])) {
            $latUsuario = (float)$_SESSION['geoFinal']['latitud'];
            $lonUsuario = (float)$_SESSION['geoFinal']['longitud'];
            
            // Calcular distancia sin reordenar aún
            foreach ($servicios_completos as &$servicio) {
                $servicio['distancia_km'] = calcularDistanciaServicio($servicio, $latUsuario, $lonUsuario);
            }
        }
        
        // Calcular precio mínimo (si hay filtro de precio)
        if ($orden_precio === 'price_asc' || $orden_precio === 'price_desc') {
            foreach ($servicios_completos as &$servicio) {
                $servicio['precio_min'] = obtenerPrecioMinimo($servicio);
            }
        }
        
        // ORDENAMIENTO MULTI-CRITERIO:
        // 1° RELEVANCIA (campo del query SQL)
        // 2° Filtro seleccionado (distancia, precio o duración)
        usort($servicios_completos, function($a, $b) use ($orden_distancia, $orden_precio, $orden_duracion) {
            // PRIMERO: Ordenar por relevancia (1=nombre, 2=descripción corta, 3=descripción larga)
            $relevanciaCompare = ($a['relevancia'] ?? 999) <=> ($b['relevancia'] ?? 999);
            if ($relevanciaCompare !== 0) {
                return $relevanciaCompare; // Si tienen diferente relevancia, usar eso
            }
            
            // SEGUNDO: Si tienen la MISMA relevancia, aplicar filtro como desempate
            
            // Filtro de distancia
            if ($orden_distancia === 'cercano') {
                return ($a['distancia_km'] ?? 999999) <=> ($b['distancia_km'] ?? 999999);
            } elseif ($orden_distancia === 'lejano') {
                return ($b['distancia_km'] ?? 0) <=> ($a['distancia_km'] ?? 0);
            }
            
            // Filtro de precio
            if ($orden_precio === 'price_asc') {
                return ($a['precio_min'] ?? PHP_INT_MAX) <=> ($b['precio_min'] ?? PHP_INT_MAX);
            } elseif ($orden_precio === 'price_desc') {
                return ($b['precio_min'] ?? 0) <=> ($a['precio_min'] ?? 0);
            }
            
            // Filtro de duración
            if ($orden_duracion === 'duracion_asc') {
                return ($a['duracion_horas'] ?? 999) <=> ($b['duracion_horas'] ?? 999);
            } elseif ($orden_duracion === 'duracion_desc') {
                return ($b['duracion_horas'] ?? 0) <=> ($a['duracion_horas'] ?? 0);
            }
            
            // Si no hay filtro activo, mantener orden alfabético
            return strcmp($a['nombre_servicio'] ?? '', $b['nombre_servicio'] ?? '');
        });
    }
    
    // Aplicar paginación sobre resultados ordenados
    $servicios = array_slice($servicios_completos, $desde, $cantidad_por_pagina);
    $total_registros = count($servicios_completos);
    
} elseif (($orden_distancia === 'cercano' || $orden_distancia === 'lejano') && isset($_SESSION['geoFinal']['latitud']) && isset($_SESSION['geoFinal']['longitud'])) {
    // SIN BÚSQUEDA: Aplicar ordenamiento normal por distancia
    if (isset($_GET["idCategoria"]) && $_GET['idCategoria'] > 0) {
        $servicios_completos = getServiciosidCategoria_servicio($idCategoria);
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
    // SIN BÚSQUEDA: Solo ordenamiento por precio (sin distancia)
    if (isset($_GET["idCategoria"]) && $_GET['idCategoria'] > 0) {
        $servicios_completos = getServiciosidCategoria_servicio($idCategoria);
    } else {
        $servicios_completos = getServicios();
    }
    
    $servicios_ordenados = aplicarOrdenPrecio($servicios_completos, $orden_precio);
    $servicios = array_slice($servicios_ordenados, $desde, $cantidad_por_pagina);
    $total_registros = count($servicios_ordenados);
} elseif ($orden_duracion === 'duracion_asc' || $orden_duracion === 'duracion_desc') {
    // SIN BÚSQUEDA: Solo ordenamiento por duración
    if (isset($_GET["idCategoria"]) && $_GET['idCategoria'] > 0) {
        $servicios_completos = getServiciosidCategoria_servicio($idCategoria);
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
 * Calcula la distancia mínima de un servicio al usuario
 * Versión optimizada para búsquedas
 */
function calcularDistanciaServicio($servicio, $latUsuario, $lonUsuario) {
    require('admin/classes/conexion.php');
    
    $idServicio = $servicio['idServicio'];
    $minKm = 99999.0;
    
    // Obtener ubicaciones del servicio
    $consulta = "
        SELECT DISTINCT u.latitud, u.longitud
        FROM servicio_salidas ss
        JOIN servicio_salidas_tarifas st ON st.idServicioSalidas = ss.idServicioSalidas
        JOIN servicio_tarifas_ubicacion u ON u.idServicioSalidasTarifas = st.idServicioSalidasTarifas
        WHERE ss.idServicio = :idServicio
          AND u.latitud IS NOT NULL
          AND u.longitud IS NOT NULL
        LIMIT 5
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
    
    return round($minKm, 2);
}

/**
 * Obtiene el precio mínimo de un servicio
 * Versión optimizada para búsquedas
 */
function obtenerPrecioMinimo($servicio) {
    require_once('admin/classes/salidas.php');
    require_once('admin/classes/tarifas.php');
    require_once('admin/classes/moneda.php');
    
    $idServicio = $servicio['idServicio'];
    $fecha = date("Y-m-d");
    $salidas = getSalidasFechaLuegoIdServicio($fecha, $idServicio);
    
    if (empty($salidas)) {
        return PHP_INT_MAX; // Sin salidas = precio infinito (va al final)
    }
    
    $precioMin = PHP_INT_MAX;
    $monedaUsuario = $_SESSION['moneda_sel'] ?? 1;
    
    foreach ($salidas as $salida) {
        $tarifas = getTarifas($salida['idServicioSalidas']);
        foreach ($tarifas as $tarifa) {
            $precioConvertido = ConvierteMoneda($tarifa['valor'], $tarifa['idMoneda'], $monedaUsuario);
            if ($precioConvertido < $precioMin) {
                $precioMin = $precioConvertido;
            }
        }
    }
    
    return $precioMin;
}

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
  <!-- Filtros de Proximidad (PRIMERO) -->
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
  <a href="categorias?<?= $urlParamsAll ?>" class="categoria-btn <?= $isActive ?>">
    <i class="fa fa-list-ul categoria-icono"></i>
    <span><?= isset($lang["todas_las_categorias"]) ? $lang["todas_las_categorias"] : "Todas las categorías"; ?></span>
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
    
    // Iconos por categoría
    $iconos = array(
      2 => 'fa-ship',        // PASEOS DE BARCO
      4 => 'fa-suitcase',    // PAQUETES TURÍSTICOS
      6 => 'fa-hiking',      // EXCURSIONES
      7 => 'fa-camera',      // TOURS FOTOGRÁFICOS (si existe)
      8 => 'fa-utensils'     // GASTRONOMÍA (si existe)
    );
    
    $icono = $iconos[$idCategoria_item] ?? 'fa-tag';
    ?>
    <a href="categorias?<?= $urlParams ?>" class="categoria-btn <?= $isActive ?>">
      <i class="fa <?= $icono ?> categoria-icono"></i>
      <span><?= $nombre_categoria_item ?></span>
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
      color: #029ce2;
    }

    .stat-label {
      font-size: 0.95rem;
      color: #666;
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

    /* ========== TARJETA SERVICIO MÓVIL (VERTICAL) ========== */
    @media (max-width: 767.98px) {
      .card-mobile-servicio {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        background: #fff;
        position: relative;
      }
      .card-mobile-servicio .thumb-top {
        width: 100%;
        height: 160px;
        object-fit: cover;
        display: block;
      }
      .card-mobile-servicio .mobile-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #029ce2;
        color: #fff;
        border-radius: 18px;
        padding: 6px 10px;
        font-weight: 700;
        font-size: 0.78rem;
      }
      .card-mobile-servicio .title-mobile {
        text-transform: uppercase;
        font-weight: 800;
        color: #1f2d3d;
      }
      .card-mobile-servicio .rating-mobile {
        color: #029ce2;
        font-weight: 700;
        font-size: 0.9rem;
      }
      .card-mobile-servicio .desc-mobile {
        color: #4f5b66;
        font-size: 0.95rem;
      }
      .card-mobile-servicio .price-mobile {
        color: #029ce2; /* mismo azul que desktop */
        font-weight: 700;
        font-size: 1.4rem;
        margin-left: auto;
      }
      .card-mobile-servicio .price-mobile.agotado {
        font-size: 1.2rem; /* como desktop para ESGOTADO */
      }
    }

    /* ========== SIDEBAR DESKTOP ========== */
    .sidebar-container {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      top: 0;
      position: sticky;
      z-index: 5;
      max-height: calc(100vh);
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

    /* Botones de categoría estilo MercadoLibre */
    .categoria-btn {
      display: flex;
      align-items: center;
      gap: 0.8rem;
      background: white;
      border: 1.5px solid #e5e5e5;
      border-radius: 8px;
      padding: 0.9rem 1rem;
      margin-bottom: 0.6rem;
      text-decoration: none;
      color: #333;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .categoria-btn:hover {
      background-color: #f5f5f5;
      border-color: #ccc;
      text-decoration: none;
      color: #333;
    }

    .categoria-btn.active {
      background: linear-gradient(135deg, #029ce2 0%, #0277bd 100%);
      border-color: #029ce2;
      color: white;
      box-shadow: 0 3px 10px rgba(2, 156, 226, 0.25);
    }

    .categoria-icono {
      font-size: 1.1rem;
      min-width: 20px;
      text-align: center;
      opacity: 0.8;
    }

    .categoria-btn.active .categoria-icono {
      opacity: 1;
    }

    .categoria-btn span {
      flex-grow: 1;
      font-weight: 500;
      font-size: 0.95rem;
    }

    /* ========== BOTONES HORIZONTALES CATEGORÍAS (hover fix) ========== */
    /* Fix para que el texto sea visible en hover de botones outline-primary */
    .btn-outline-primary:hover,
    .btn-outline-primary:focus,
    .btn-outline-primary:active {
      color: #ffffff !important;
      background-color: #029ce2 !important;
      border-color: #029ce2 !important;
    }

    .btn-outline-primary:hover i,
    .btn-outline-primary:focus i,
    .btn-outline-primary:active i {
      color: #ffffff !important;
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

    /* ========== HERO SLIDER + OVERLAY ========== */
    .categoria-hero-wrapper {
      position: relative;
      width: 100%;
      overflow: hidden;
    }

    .categoria-hero-wrapper .carousel,
    .categoria-hero-wrapper .carousel-inner,
    .categoria-hero-wrapper .carousel-item {
      height: 600px;
    }

    .categoria-hero-wrapper .img-slider {
      height: 100%;
      width: 100%;
      object-fit: cover;
    }

    .categoria-hero-wrapper .div-absolute {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 3.5rem 1rem 3rem;
  
      color: #fff;
      z-index: 2;
      overflow-y: auto;
    }

    .categoria-hero-wrapper .div-absolute .hero-title {
      margin-top: 3.5rem;
      margin-bottom: 1.2rem;
    }

    .categoria-hero-wrapper .div-absolute .div-bottom {
      margin-top: 5rem;
    }

    .categoria-hero-wrapper .div-absolute .texto-bottom {
      color: #fff;
    }

    @media (max-width: 768px) {
      .categoria-hero-wrapper .carousel,
      .categoria-hero-wrapper .carousel-inner,
      .categoria-hero-wrapper .carousel-item {
        height: 420px;
      }

      .categoria-hero-wrapper .div-absolute {
        padding: 3rem 1rem 3.5rem;
      }
    }

    /* Sticky buscador + categorías */
    .sticky-header-filters {
      position: sticky;
      top: 0;
      z-index: 1030; /* por encima de dropdowns (1000) y bajo navbar fijo (1030) */
      background: transparent;
    }
    .sticky-header-filters .sticky-inner {
      background: #fff;
      padding: 0.75rem 0;
      border-bottom: 1px solid #e8e8e8;
      box-shadow: none;
      /* Extender fondo al mismo ancho que el row de servicios */
      margin-left: -15px;
      margin-right: -15px;
      padding-left: 15px;
      padding-right: 15px;
    }

    @media (max-width: 768px) {
      .sticky-header-filters {
        top: 0;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
      }
    }

  </style>
</head>

<body>

  <div class="categoria-hero-wrapper">
    <!-- SLIDER CON BÚSQUEDA INTEGRADA (si hay categoría se muestra su imagen como único slide) -->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="<?= $sliderIntervalo ?>">
      <?php if ($showSliderControls): ?>
        <ol class="carousel-indicators" >
          <?php foreach ($sliderImages as $index => $img): ?>
            <li data-target="#carouselExampleIndicators" data-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></li>
          <?php endforeach; ?>
        </ol>
      <?php endif; ?>
      <div class="carousel-inner">
        <?php foreach ($sliderImages as $index => $imagen): ?>
          <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <img class="img-fluid img-slider" src="<?= $sliderBasePath . htmlspecialchars($imagen) ?>" alt="Slider <?= $index + 1 ?>">
          </div>
        <?php endforeach; ?>
      </div>
      <?php if ($showSliderControls): ?>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </a>
      <?php endif; ?>
    </div>

    <section class="div-absolute" id="capa2">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center mb-4 hero-title">
            <h1 class="text-white text-uppercase titulo">
        
              <span class="semibold"><?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $lang['descubri_tu_proxima_aventura']; ?></span><br>
           
              <?= $idCategoria == 0 ? $lang["reserva_tu_proxima_experiencia"]:$nombre_categoria?>
            </h1>
          </div>
          
          <!-- Estadísticas en el Banner -->
          <div class="col-lg-12 text-center text-white div-bottom">
            <div class="container">
              <div class="row">
                 <div class="col-lg-3 col-md-3 col-3" style="font-size: 20px; margin-top: 5px;">
                  <i class="text-white fa fa-hiking fa-2x"></i>
                  <p class="texto-bottom mb-0" style="font-size: 36px; font-weight: bold;"><?= $cantidad_servicios_categoria; ?></p>
                  <p class="texto-bottom" style="font-size: 25px; margin-top: 5px;"><?= isset($lang["actividades"]) ? $lang["actividades"] : 'Actividades'; ?></p>
                </div>
                 <div class="col-lg-3 col-md-3 col-3" style="font-size: 20px; margin-top: 5px;">
                  <i class="text-white fa fa-users fa-2x"></i>
                  <p class="texto-bottom mb-0" style="font-size: 36px; font-weight: bold;"><?= $nViajeros; ?></p>
                  <p class="texto-bottom"style="font-size: 25px; margin-top: 5px;"><?= isset($lang["viajeros_lo_han_disfrutado"]) ? $lang["viajeros_lo_han_disfrutado"] : 'Viajeros'; ?></p>
                </div>
                 <div class="col-lg-3 col-md-3 col-3" style="font-size: 20px; margin-top: 5px;">
                  <i class="text-white fa fa-comment-dots fa-2x"></i>
                  <p class="texto-bottom mb-0" style="font-size: 36px; font-weight: bold;"><?= $cantidad_opiniones_categoria; ?></p>
                  <p class="texto-bottom" style="font-size: 25px; margin-top: 5px;"><?= isset($lang["opiniones_reales"]) ? $lang["opiniones_reales"] : 'Opiniones reales'; ?></p>
                </div>
                
                  <div class="col-lg-3 col-md-3 col-3" style="font-size: 20px; margin-top: 5px;">
                  <i class="text-white fa fa-star fa-2x"></i>
                  <p class="texto-bottom mb-0" style="font-size: 36px; font-weight: bold;">9,2</p>
                  <p class="texto-bottom" style="font-size: 25px; margin-top: 5px;"><?= isset($lang["asi_nos_puntuan"]) ? $lang["asi_nos_puntuan"] : 'Así nos puntúan'; ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

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

          <!-- CATEGORÍAS EN SIDEBAR (ocultas en desktop para evitar duplicado; se mantienen en la franja superior) -->
          <!-- bloque ocultado a pedido: categorías ya se muestran en la barra superior sticky -->

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

        <!-- Sticky buscador + categorías dentro de la columna de contenido -->
        <div class="sticky-header-filters mb-4">
          <div class="row">
            <div class="col-12">
              <div class="sticky-inner">
                <!-- BUSCADOR SUPERIOR -->
                <div class="mb-3 mb-md-4">
                  <form class="form-buscar" method="get" action="categorias">
                    <div class="input-group">
                      <input class="form-control form-control-lg form-control-search" name="buscar" type="text" placeholder="<?= isset($lang["que_hacemos"]) ? $lang["que_hacemos"] : '¿Qué hacemos?'; ?>" value="<?= htmlspecialchars($busqueda) ?>">
                      <?php if (isset($_GET['idCategoria'])): ?>
                        <input type="hidden" name="idCategoria" value="<?= $_GET['idCategoria'] ?>">
                      <?php endif; ?>
                      <div class="input-group-append">
                        <button class="btn btn-primary btn-lg btn-search" type="submit"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </form>
                </div>
                
                <!-- BOTÓN FILTRO MÓVIL DENTRO DEL STICKY -->
                <div class="d-lg-none mb-2">
                  <button class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#filterModal" style="border-radius: 20px;">
                    <i class="fa fa-sliders-h"></i> <?= isset($lang["filtrar_y_ordenar"]) ? $lang["filtrar_y_ordenar"] : "Filtrar y Ordenar"; ?>
                  </button>
                </div>

                <!-- CATEGORÍAS HORIZONTALES (solo desktop) -->
                <div class="mb-2 mb-md-0 d-none d-lg-block">
                  <div class="d-flex flex-wrap gap-2" style="gap: 0.5rem;">
              <?php
              $todas_las_categorias = getCategorias();
              
              // Botón "Todas"
              $urlParamsAll = [];
              if (!empty($busqueda)) { $urlParamsAll['buscar'] = $busqueda; }
              if (!empty($orden_precio)) { $urlParamsAll['orden_precio'] = $orden_precio; }
              if (!empty($orden_distancia)) { $urlParamsAll['orden_distancia'] = $orden_distancia; }
              if (!empty($orden_duracion)) { $urlParamsAll['orden_duracion'] = $orden_duracion; }
              $isActiveAll = ($idCategoria == 0) ? 'btn-primary' : 'btn-outline-primary';
              ?>
              <a href="categorias?<?= http_build_query($urlParamsAll) ?>" class="btn <?= $isActiveAll ?> mb-2" style="border-radius: 20px; font-size: 0.9rem; padding: 0.4rem 1rem;">
                <i class="fa fa-list-ul mr-1"></i>
                <?= isset($lang["todas"]) ? $lang["todas"] : "Todas"; ?>
              </a>
              
              <?php
              // Iconos por categoría
              $iconos = array(
                2 => 'fa-ship',
                4 => 'fa-suitcase',
                6 => 'fa-hiking',
                7 => 'fa-camera',
                8 => 'fa-utensils'
              );
              
              foreach ($todas_las_categorias as $cat) {
                $idCategoria_item = $cat["idCategoria_servicio"];
                $nombre_categoria_item = $cat["nombre_categoria_servicio"];
                
                $paramsCat = $urlParamsAll;
                $paramsCat['idCategoria'] = $idCategoria_item;
                
                $isActive = ($idCategoria == $idCategoria_item) ? 'btn-primary' : 'btn-outline-primary';
                $icono = $iconos[$idCategoria_item] ?? 'fa-tag';
              ?>
                <a href="categorias?<?= http_build_query($paramsCat) ?>" class="btn <?= $isActive ?> mb-2" style="border-radius: 20px; font-size: 0.9rem; padding: 0.4rem 1rem;">
                  <i class="fa <?= $icono ?> mr-1"></i>
                  <?= $nombre_categoria_item ?>
                </a>
              <?php } ?>
            </div>
          </div>
        </div>
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
              
              // Obtener disponibilidad de próximas salidas (solo si es admin/vendedor/prestador)
              $mostrarDisponibilidad = false;
              $salidasProximas = [];
              if (isset($_SESSION['login']) && (
                  ($_SESSION['login']['idUsuario'] ?? 0) == 1 ||  // Admin
                  ($_SESSION['login']['idVendedor'] ?? 0) > 0 ||  // Vendedor
                  ($_SESSION['login']['idPrestador'] ?? 0) > 0    // Prestador
              )) {
                  $mostrarDisponibilidad = true;
                  $salidasProximas = getProximasSalidasDisponibilidad($idServicio, DISPONIBILIDAD_SALIDAS_CATEGORIAS);
              }

            ?>
              <!-- TARJETA SERVICIO MÓVIL (VERTICAL) -->
              <a href="servicio?id=<?= $idServicio ?>" class="d-block d-md-none">
                <div class="card-mobile-servicio mb-4">
                  <div class="position-relative">
                    <img src="admin/classes/imgServicio/<?= $ruta_foto; ?>" class="thumb-top" alt="<?= htmlspecialchars($nombre_servicio) ?>">
                    <?php if (!empty($textoMiniatura)) : ?>
                      <div class="mobile-badge"><?= $textoMiniatura; ?></div>
                    <?php endif; ?>
                  </div>
                  <div class="card-body">
                    <h5 class="title-mobile mb-2"><?= $nombre_servicio ?></h5>
                    <?php if (count($opiniones_servicio) > 0) { ?>
                      <div class="rating-mobile mb-2"><?= $estrellas_servicio; ?>/10 <span class="text-muted" style="font-weight:400;">(<?= $cantidad_opiniones_servicio; ?> <?= isset($lang["opiniones"]) ? $lang["opiniones"] : "opiniones"; ?>)</span></div>
                    <?php } ?>
                    <p class="desc-mobile mb-3"><?= $descripcion_corta; ?></p>
                    <?php if(!empty($duracion_servicio)): ?>
                      <div class="text-muted mb-3" style="font-size:0.9rem;">
                        <i class="fa fa-hourglass-half mr-2"></i> <?= $duracion_servicio["duracionMinima"]; ?> - <?= $duracion_servicio["duracionMaxima"]; ?>
                      </div>
                    <?php endif; ?>
                    
                    <!-- DISPONIBILIDAD MÓVIL (solo admin/vendedor/prestador) -->
                    <?php if ($mostrarDisponibilidad && !empty($salidasProximas)): ?>
                      <div class="alert alert-info p-2 mb-3 small disponibilidad-alert" style="border-radius: 6px; background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%); border: 1px solid #0c5460; margin-top: 10px;">
                        <strong style="color: #0c5460; display: block; margin-bottom: 6px;">
                          <i class="fa fa-calendar-alt" style="color: #029ce2; margin-right: 4px;"></i>
                          Próximas salidas
                        </strong>
                        <ul class="mb-0 mt-1" style="font-size: 0.85rem; padding-left: 20px; color: #0c5460;">
                          <?php foreach (array_slice($salidasProximas, 0, DISPONIBILIDAD_SALIDAS_CATEGORIAS) as $salida): ?>
                            <li style="margin-bottom: 4px; line-height: 1.4;">
                              <strong><?= date('d M', strtotime($salida['fecha'])) ?></strong>
                              <span class="<?= $salida['disponibilidad'] > 0 ? 'text-success' : 'text-danger'; ?>" style="font-weight: bold; margin-left: 4px;">
                                <?= $salida['disponibilidad'] > 0 ? $salida['disponibilidad'] . ' ' . ($salida['disponibilidad'] == 1 ? 'lugar' : 'lugares') : '⚠️ AGOTADO'; ?>
                              </span>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                    <?php endif; ?>
                    
                    <hr class="my-2">
                    <div class="d-flex align-items-center">
                      <div class="price-mobile <?= ($precioSugerido === 'ESGOTADO') ? 'agotado' : ''; ?>"><?= $precioSugerido; ?></div>
                    </div>
                    <span class="whatsapp-fab"><i class="fa fa-whatsapp"></i></span>
                  </div>
                </div>
              </a>

              <!-- TARJETA SERVICIO HORIZONTAL (DESKTOP) -->
              <a href="servicio?id=<?= $idServicio ?>" class="d-none d-md-block">
                <div class="mb-4">
                  <div class="card card-visitas">
                    <div class="row no-gutters d-md-none" style="position: absolute; z-index: 10;">
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
                            
                            <!-- DISPONIBILIDAD (solo admin/vendedor/prestador) -->
                            <?php if ($mostrarDisponibilidad && !empty($salidasProximas)): ?>
                              <div class="alert alert-info p-2 my-2 small disponibilidad-alert" style="border-radius: 6px; background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%); border: 1px solid #0c5460; margin-top: 10px;">
                                <strong style="color: #0c5460; display: block; margin-bottom: 6px;">
                                  <i class="fa fa-calendar-alt" style="color: #029ce2; margin-right: 4px;"></i>
                                  Próximas salidas
                                </strong>
                                <ul class="mb-0 mt-1" style="font-size: 0.85rem; padding-left: 20px; color: #0c5460;">
                                  <?php foreach (array_slice($salidasProximas, 0, DISPONIBILIDAD_SALIDAS_CATEGORIAS) as $salida): ?>
                                    <li style="margin-bottom: 4px; line-height: 1.4;">
                                      <strong><?= date('d M', strtotime($salida['fecha'])) ?></strong>
                                      <span class="<?= $salida['disponibilidad'] > 0 ? 'text-success' : 'text-danger'; ?>" style="font-weight: bold; margin-left: 4px;">
                                        <?= $salida['disponibilidad'] > 0 ? $salida['disponibilidad'] . ' ' . ($salida['disponibilidad'] == 1 ? 'lugar' : 'lugares') : '⚠️ AGOTADO'; ?>
                                      </span>
                                    </li>
                                  <?php endforeach; ?>
                                </ul>
                              </div>
                            <?php endif; ?>
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

        <!-- SECCIÓN DE GUÍAS (MÓVIL ONLY - DESPUÉS DE SERVICIOS) -->
        <div class="card card-ultimas-o d-md-none mb-4" style="border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
          <div class="card-body" style="padding: 1.5rem;">
            <h4 style="margin-bottom: 1rem; color: #333; font-weight: 700;">
              <i class="fa fa-map" style="color: #029ce2; margin-right: 0.5rem;"></i>
              <?= isset($lang["conoce_nuestra_guia"]) ? $lang["conoce_nuestra_guia"] : "Conoce nuestra guía de"; ?> <?= $nombre_categoria; ?>
            </h4>
            <a href="guias.php<?= ($idCategoria > 0) ? '?idCategoria=' . $idCategoria : ''; ?>" style="text-decoration: none; color: inherit;">
              <img src="admin/img/categoria_servicio/<?= isset($fotos) ? $fotos : 'sinCategoria.jpg'; ?>" class="img-fluid img-guia mx-auto d-block" style="border-radius: 8px; margin-bottom: 1rem; max-height: 200px; object-fit: cover;">
              <h4 class="text-guia2" style="text-align: center; color: #029ce2; font-weight: 700; margin-bottom: 1rem;">
                <?= $nombre_categoria; ?>
              </h4>
            </a>
            <a href="guias.php<?= ($idCategoria > 0) ? '?idCategoria=' . $idCategoria : ''; ?>" class="btn btn-primary btn-block" style="border-radius: 8px; font-weight: 600;">
              <?= isset($lang["ver_guias"]) ? $lang["ver_guias"] : "Ver Guías"; ?>
            </a>
          </div>
        </div>

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

          <!-- Categorías en modal móvil -->
          <h6 class="mb-3" style="font-size: 14px; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">
            <i class="fa fa-tags" style="color: #029ce2; margin-right: 8px;"></i><?= isset($lang["categorias"]) ? $lang["categorias"] : "Categorías"; ?>
          </h6>
          <div class="d-flex flex-wrap" style="gap: .5rem;">
            <?php
            $todas_las_categorias = getCategorias();
            $urlParamsAll = [];
            if (!empty($busqueda)) { $urlParamsAll['buscar'] = $busqueda; }
            if (!empty($orden_precio)) { $urlParamsAll['orden_precio'] = $orden_precio; }
            if (!empty($orden_distancia)) { $urlParamsAll['orden_distancia'] = $orden_distancia; }
            if (!empty($orden_duracion)) { $urlParamsAll['orden_duracion'] = $orden_duracion; }
            $isActiveAll = ($idCategoria == 0) ? 'btn-primary' : 'btn-outline-primary';
            ?>
            <a href="categorias?<?= http_build_query($urlParamsAll) ?>" class="btn <?= $isActiveAll ?> mb-2" style="border-radius: 20px; font-size: 0.9rem; padding: 0.4rem 1rem;">
              <i class="fa fa-list-ul mr-1"></i>
              <?= isset($lang["todas"]) ? $lang["todas"] : "Todas"; ?>
            </a>
            <?php
            $iconos = [2=>'fa-ship',4=>'fa-suitcase',6=>'fa-hiking',7=>'fa-camera',8=>'fa-utensils'];
            foreach ($todas_las_categorias as $cat) {
              $idCategoria_item = $cat["idCategoria_servicio"];
              $nombre_categoria_item = $cat["nombre_categoria_servicio"];
              $paramsCat = $urlParamsAll;
              $paramsCat['idCategoria'] = $idCategoria_item;
              $isActive = ($idCategoria == $idCategoria_item) ? 'btn-primary' : 'btn-outline-primary';
              $icono = $iconos[$idCategoria_item] ?? 'fa-tag';
            ?>
              <a href="categorias?<?= http_build_query($paramsCat) ?>" class="btn <?= $isActive ?> mb-2" style="border-radius: 20px; font-size: 0.9rem; padding: 0.4rem 1rem;">
                <i class="fa <?= $icono ?> mr-1"></i>
                <?= $nombre_categoria_item ?>
              </a>
            <?php } ?>
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

# 🚌 ✈️ 🚆 SISTEMA DE TRANSPORTE - DOCUMENTACIÓN COMPLETA

**Branch:** `feature/cambios-grosos`  
**Fecha:** 16 de enero de 2026  
**BD Experimental:** `metelebrasil_experimental`

---

## 📋 RESUMEN EJECUTIVO

Sistema para vender **pasajes de transporte** (micro, avión, tren, barco) con:
- ✅ **Múltiples puntos de salida/llegada** por ruta
- ✅ **Cliente elige origen-destino** en frontend
- ✅ **Admin gestiona todo** desde extranet
- ✅ **Integración con sistema actual** de reservas y pagos

---

## 🏗️ ARQUITECTURA

### Modelo de Datos

```
TIPO_TRANSPORTE (micro, avión, tren)
    ↓
TERMINAL_TRANSPORTE (Terminal Retiro, Aeropuerto Ezeiza, etc.)
    ↓
RUTA_TRANSPORTE (Buenos Aires → Mar del Plata)
    ↓
RUTA_PARADAS (origen: Retiro, destino: Mar del Plata)
    ↓
VIAJE_TRANSPORTE (fecha: 2026-01-20, hora: 10:00)
    ↓
VIAJE_TARIFA (precio por tramo + tipo pasajero)
    ↓
RESERVA_TRANSPORTE (booking del cliente)
```

### Comparación con Sistema Actual

| Actual (Actividades) | Nuevo (Transporte) |
|---------------------|-------------------|
| `servicio` | `ruta_transporte` |
| `servicio_salidas` | `viaje_transporte` |
| `servicio_salidas_tarifas` | `viaje_tarifa` |
| `categoria_servicio` | `tipo_transporte` |
| `destinos` | `terminal_transporte` |
| - | `ruta_paradas` (NUEVO) |
| - | `empresa_transporte` (NUEVO) |

---

## 📊 TABLAS CREADAS

### 1. `tipo_transporte`
Tipos de transporte disponibles (micro, avión, tren, barco).

```sql
CREATE TABLE `tipo_transporte` (
  `idTipoTransporte` int(11) PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `icono` varchar(50) DEFAULT NULL, -- fa-bus, fa-plane, fa-train
  `habilitado` tinyint(1) DEFAULT 1
);
```

**Datos iniciales:**
- 1 = Micro/Bus (fa-bus)
- 2 = Avión (fa-plane)
- 3 = Tren (fa-train)
- 4 = Barco/Ferry (fa-ship)

---

### 2. `terminal_transporte`
Puntos de salida/llegada (terminales, aeropuertos, estaciones).

```sql
CREATE TABLE `terminal_transporte` (
  `idTerminal` int(11) PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL, -- Terminal de Retiro, Aeropuerto GRU
  `direccion` varchar(500),
  `latitud` decimal(10,8),
  `longitud` decimal(11,8),
  `idPais` int(11),
  `ciudad` varchar(200),
  `codigo_iata` varchar(10), -- GRU, EZE, GIG (solo aeropuertos)
  `idTipoTransporte` int(11) NOT NULL, -- Qué transporte opera
  `observaciones` text,
  `habilitado` tinyint(1) DEFAULT 1,
  FOREIGN KEY (`idTipoTransporte`) REFERENCES `tipo_transporte`(`idTipoTransporte`)
);
```

**Ejemplo:**
```sql
INSERT INTO terminal_transporte 
(nombre, ciudad, idTipoTransporte, latitud, longitud)
VALUES 
('Terminal de Retiro', 'Buenos Aires', 1, -34.588886, -58.373993),
('Aeropuerto Ezeiza', 'Buenos Aires', 2, -34.822222, -58.535833);
```

---

### 3. `empresa_transporte`
Operadores de transporte (LATAM, GOL, Via Bariloche, etc.).

```sql
CREATE TABLE `empresa_transporte` (
  `idEmpresa` int(11) PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL, -- LATAM, GOL, Via Bariloche
  `idTipoTransporte` int(11) NOT NULL,
  `logo` varchar(255),
  `habilitado` tinyint(1) DEFAULT 1
);
```

---

### 4. `ruta_transporte` (equivalente a `servicio`)
Rutas de transporte entre ciudades.

```sql
CREATE TABLE `ruta_transporte` (
  `idRuta` int(11) PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(500) NOT NULL, -- Buenos Aires - Mar del Plata
  `nombre_en` varchar(500),
  `nombre_pt` varchar(500),
  `nombre_it` varchar(500),
  `descripcion` text,
  `descripcion_en` text,
  `descripcion_pt` text,
  `descripcion_it` text,
  `idTipoTransporte` int(11) NOT NULL,
  `idEmpresa` int(11), -- Qué empresa opera
  `idPrestador` int(11), -- Prestador que vende
  `duracion_estimada` varchar(50), -- 5h30min
  `distancia_km` int(11),
  `foto_principal` varchar(255),
  `habilitado` tinyint(1) DEFAULT 1,
  FOREIGN KEY (`idTipoTransporte`) REFERENCES `tipo_transporte`(`idTipoTransporte`)
);
```

**Soporta multi-idioma** igual que `servicio`.

---

### 5. `ruta_paradas` (NUEVO - Clave del sistema)
Define qué terminales son origen/destino de una ruta.

```sql
CREATE TABLE `ruta_paradas` (
  `idRutaParada` int(11) PRIMARY KEY AUTO_INCREMENT,
  `idRuta` int(11) NOT NULL,
  `idTerminal` int(11) NOT NULL,
  `orden` int(11) NOT NULL, -- 1=primera parada, 2=intermedia, 3=última
  `es_origen` tinyint(1) DEFAULT 0, -- ¿Puede ser punto de partida?
  `es_destino` tinyint(1) DEFAULT 0, -- ¿Puede ser punto de llegada?
  `tiempo_desde_inicio` varchar(50), -- Ej: +2h desde origen
  FOREIGN KEY (`idRuta`) REFERENCES `ruta_transporte`(`idRuta`) ON DELETE CASCADE,
  FOREIGN KEY (`idTerminal`) REFERENCES `terminal_transporte`(`idTerminal`)
);
```

**Ejemplo de ruta con paradas múltiples:**
```sql
-- Ruta: Buenos Aires → Bahía Blanca → Mar del Plata
INSERT INTO ruta_paradas (idRuta, idTerminal, orden, es_origen, es_destino, tiempo_desde_inicio) VALUES
(1, 1, 1, 1, 0, '0h'),      -- Retiro (origen)
(1, 2, 2, 1, 1, '3h'),      -- Bahía Blanca (origen O destino intermedio)
(1, 3, 3, 0, 1, '5h30min'); -- Mar del Plata (destino final)
```

**Cliente puede comprar:**
- Retiro → Mar del Plata (completo)
- Retiro → Bahía Blanca (parcial)
- Bahía Blanca → Mar del Plata (parcial)

---

### 6. `viaje_transporte` (equivalente a `servicio_salidas`)
Viajes específicos con fecha/hora.

```sql
CREATE TABLE `viaje_transporte` (
  `idViaje` int(11) PRIMARY KEY AUTO_INCREMENT,
  `idRuta` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_salida` time NOT NULL,
  `hora_llegada` time,
  `asientos_totales` int(11) DEFAULT 40,
  `asientos_disponibles` int(11) DEFAULT 40,
  `idTerminalOrigen` int(11) NOT NULL, -- Terminal de salida
  `idTerminalDestino` int(11) NOT NULL, -- Terminal de llegada
  `numero_vuelo_bus` varchar(50), -- Número de vuelo/servicio
  `observaciones` text,
  `habilitado` tinyint(1) DEFAULT 1,
  FOREIGN KEY (`idRuta`) REFERENCES `ruta_transporte`(`idRuta`),
  FOREIGN KEY (`idTerminalOrigen`) REFERENCES `terminal_transporte`(`idTerminal`),
  FOREIGN KEY (`idTerminalDestino`) REFERENCES `terminal_transporte`(`idTerminal`)
);
```

**Índices importantes:**
```sql
CREATE INDEX idx_viaje_fecha_origen_destino 
ON viaje_transporte(fecha, idTerminalOrigen, idTerminalDestino);
```

---

### 7. `viaje_tarifa` (equivalente a `servicio_salidas_tarifas`)
Precios por tramo y tipo de pasajero.

```sql
CREATE TABLE `viaje_tarifa` (
  `idViajeTarifa` int(11) PRIMARY KEY AUTO_INCREMENT,
  `idViaje` int(11) NOT NULL,
  `idTerminalOrigen` int(11) NOT NULL, -- Origen del tramo
  `idTerminalDestino` int(11) NOT NULL, -- Destino del tramo
  `idTipoTarifa` int(11) NOT NULL, -- Adulto, Niño, Senior (tabla edades)
  `precio` decimal(10,2) NOT NULL,
  `idMoneda` int(11) NOT NULL,
  `comisiona` tinyint(1) DEFAULT 1,
  FOREIGN KEY (`idViaje`) REFERENCES `viaje_transporte`(`idViaje`) ON DELETE CASCADE,
  FOREIGN KEY (`idMoneda`) REFERENCES `moneda`(`idMoneda`)
);
```

**Ejemplo:**
```sql
-- Viaje Buenos Aires → Mar del Plata (viaje #1)
INSERT INTO viaje_tarifa (idViaje, idTerminalOrigen, idTerminalDestino, idTipoTarifa, precio, idMoneda) VALUES
(1, 1, 3, 1, 5000.00, 1), -- Retiro → Mar del Plata, Adulto, $5000 ARS
(1, 1, 3, 2, 3000.00, 1), -- Retiro → Mar del Plata, Niño, $3000 ARS
(1, 1, 2, 1, 2500.00, 1), -- Retiro → Bahía Blanca, Adulto, $2500 ARS
(1, 2, 3, 1, 2800.00, 1); -- Bahía Blanca → Mar del Plata, Adulto, $2800 ARS
```

---

### 8. `reserva_transporte` (integración con sistema actual)
Reservas de pasajes vinculadas a `reservas`.

```sql
CREATE TABLE `reserva_transporte` (
  `idReservaTransporte` int(11) PRIMARY KEY AUTO_INCREMENT,
  `idReserva` int(11) NOT NULL, -- FK a tabla reservas existente
  `idViaje` int(11) NOT NULL,
  `idTerminalOrigen` int(11) NOT NULL,
  `idTerminalDestino` int(11) NOT NULL,
  `cantidad_pasajeros` int(11) NOT NULL,
  `precio_total` decimal(10,2) NOT NULL,
  `idMoneda` int(11) NOT NULL,
  `estado` varchar(50) DEFAULT 'pendiente',
  `fecha_reserva` timestamp DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`idViaje`) REFERENCES `viaje_transporte`(`idViaje`)
);
```

---

### 9. `reserva_transporte_pasajeros`
Datos de pasajeros del viaje.

```sql
CREATE TABLE `reserva_transporte_pasajeros` (
  `idPasajeroTransporte` int(11) PRIMARY KEY AUTO_INCREMENT,
  `idReservaTransporte` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `documento` varchar(100),
  `idTipoTarifa` int(11) NOT NULL, -- Adulto, Niño, etc.
  `asiento` varchar(10), -- Número de asiento (opcional)
  FOREIGN KEY (`idReservaTransporte`) REFERENCES `reserva_transporte`(`idReservaTransporte`) ON DELETE CASCADE
);
```

---

## 🔧 FUNCIONES PHP PRINCIPALES

**Archivo:** `admin/classes/transporte.php`

### Terminales

```php
getAllTerminales() // Listar todos los terminales
getTerminal($idTerminal) // Obtener uno específico
getTerminalesByTipo($idTipoTransporte) // Filtrar por tipo (bus, avión, etc.)
getTerminalesByCiudad($ciudad) // Buscar por ciudad
insertTerminal($datos) // Alta de terminal
```

### Rutas

```php
getAllRutas() // Listar rutas (con soporte multi-idioma)
getRuta($idRuta) // Detalle de ruta
insertRuta($datos) // Alta de ruta

// Paradas de ruta
getParadasRuta($idRuta) // Todas las paradas
getOrigenesRuta($idRuta) // Solo puntos de origen
getDestinosRuta($idRuta) // Solo puntos de destino
insertParadaRuta($datos) // Agregar parada
```

### Viajes

```php
getViajesRuta($idRuta, $soloFuturos = true) // Viajes de una ruta
getViaje($idViaje) // Detalle de viaje

// Búsqueda avanzada
buscarViajes([
  'idTerminalOrigen' => 1,
  'idTerminalDestino' => 3,
  'fecha' => '2026-01-20',
  'idTipoTransporte' => 1
])

insertViaje($datos) // Alta de viaje
```

### Tarifas

```php
getTarifasViaje($idViaje) // Tarifas de un viaje (todos los tramos)
insertTarifaViaje($datos) // Alta de tarifa
```

---

## 🎨 FLUJO DE USUARIO FRONTEND

### 1. Página de Búsqueda de Transporte

```html
<!-- transporte_buscar.php -->
<form action="transporte_resultados.php" method="GET">
  <!-- Tipo de transporte -->
  <select name="idTipoTransporte">
    <option value="1">🚌 Micro</option>
    <option value="2">✈️ Avión</option>
    <option value="3">🚆 Tren</option>
  </select>
  
  <!-- Origen (dinámico con Ajax) -->
  <select name="idTerminalOrigen" id="selectOrigen">
    <option value="">Seleccionar origen...</option>
  </select>
  
  <!-- Destino (dinámico con Ajax) -->
  <select name="idTerminalDestino" id="selectDestino">
    <option value="">Seleccionar destino...</option>
  </select>
  
  <!-- Fecha -->
  <input type="date" name="fecha" min="<?=date('Y-m-d')?>">
  
  <!-- Pasajeros -->
  <input type="number" name="adultos" value="1" min="1">
  <input type="number" name="ninos" value="0" min="0">
  
  <button type="submit">Buscar viajes</button>
</form>

<script>
// Cargar terminales al cambiar tipo de transporte
$('#selectTipoTransporte').change(function() {
  var idTipo = $(this).val();
  $.get('admin/ctrl/ctrlTerminales.php', {action: 'getByTipo', idTipo: idTipo}, function(data) {
    $('#selectOrigen, #selectDestino').html(data);
  });
});
</script>
```

---

### 2. Página de Resultados

```php
<?php
// transporte_resultados.php
require_once("admin/classes/transporte.php");

$filtros = [
  'idTerminalOrigen' => $_GET['idTerminalOrigen'],
  'idTerminalDestino' => $_GET['idTerminalDestino'],
  'fecha' => $_GET['fecha'],
  'idTipoTransporte' => $_GET['idTipoTransporte']
];

$viajes = buscarViajes($filtros);

foreach ($viajes as $viaje) {
  // Obtener tarifas del tramo seleccionado
  $tarifas = getTarifasViaje($viaje['idViaje']);
  
  // Filtrar solo el tramo origen-destino buscado
  $tarifaTramo = array_filter($tarifas, function($t) use ($filtros) {
    return $t['idTerminalOrigen'] == $filtros['idTerminalOrigen'] 
        && $t['idTerminalDestino'] == $filtros['idTerminalDestino'];
  });
  
  // Mostrar tarjeta de viaje
  ?>
  <div class="card-viaje">
    <h3><?=$viaje['origen_ciudad']?> → <?=$viaje['destino_ciudad']?></h3>
    <p>🏢 <?=$viaje['empresa_nombre']?></p>
    <p>📅 <?=$viaje['fecha']?> | 🕐 <?=$viaje['hora_salida']?></p>
    <p>💺 <?=$viaje['asientos_disponibles']?> asientos disponibles</p>
    
    <?php foreach ($tarifaTramo as $tarifa) { ?>
      <p>Desde <?=$tarifa['precio']?> <?=$tarifa['moneda_simbolo']?></p>
    <?php } ?>
    
    <a href="transporte_comprar.php?idViaje=<?=$viaje['idViaje']?>&origen=<?=$filtros['idTerminalOrigen']?>&destino=<?=$filtros['idTerminalDestino']?>">
      Comprar pasaje
    </a>
  </div>
  <?php
}
?>
```

---

### 3. Página de Compra (integración con carrito actual)

```php
<?php
// transporte_comprar.php (similar a carrito.php actual)
require_once("admin/classes/transporte.php");

$idViaje = $_GET['idViaje'];
$idOrigen = $_GET['origen'];
$idDestino = $_GET['destino'];

$viaje = getViaje($idViaje);
$tarifas = getTarifasViaje($idViaje);

// Filtrar tarifas del tramo
$tarifasTramo = array_filter($tarifas, function($t) use ($idOrigen, $idDestino) {
  return $t['idTerminalOrigen'] == $idOrigen && $t['idTerminalDestino'] == $idDestino;
});
?>

<form action="guardaReservaTransporte.php" method="POST">
  <input type="hidden" name="idViaje" value="<?=$idViaje?>">
  <input type="hidden" name="idTerminalOrigen" value="<?=$idOrigen?>">
  <input type="hidden" name="idTerminalDestino" value="<?=$idDestino?>">
  
  <h2>Datos de pasajeros</h2>
  
  <?php foreach ($tarifasTramo as $tarifa) { ?>
    <h4><?=$tarifa['idTipoTarifa']?> - <?=$tarifa['precio']?> <?=$tarifa['moneda_simbolo']?></h4>
    <input type="number" name="cantidad_<?=$tarifa['idTipoTarifa']?>" value="0" min="0">
  <?php } ?>
  
  <div id="pasajeros-forms">
    <!-- Generar dinámicamente con JS según cantidad -->
  </div>
  
  <button type="submit">Confirmar y pagar</button>
</form>
```

---

## 🖥️ FLUJO ADMIN (EXTRANET)

### 1. Alta de Terminal

**Archivo:** `admin/terminalAlta.php`

```php
<form action="admin/ctrl/ctrlTerminales.php" method="POST">
  <input type="hidden" name="action" value="insert">
  
  <label>Nombre</label>
  <input type="text" name="nombre" required placeholder="Terminal de Retiro">
  
  <label>Tipo de Transporte</label>
  <select name="idTipoTransporte">
    <?php
    require_once("classes/transporte.php");
    $tipos = getAllTiposTransporte();
    foreach ($tipos as $tipo) {
      echo "<option value='{$tipo['idTipoTransporte']}'>{$tipo['nombre']}</option>";
    }
    ?>
  </select>
  
  <label>Ciudad</label>
  <input type="text" name="ciudad" required>
  
  <label>Dirección</label>
  <input type="text" name="direccion">
  
  <label>Latitud</label>
  <input type="text" name="latitud">
  
  <label>Longitud</label>
  <input type="text" name="longitud">
  
  <label>Código IATA (aeropuertos)</label>
  <input type="text" name="codigo_iata" placeholder="GRU, EZE">
  
  <button type="submit">Guardar Terminal</button>
</form>
```

---

### 2. Alta de Ruta

**Archivo:** `admin/rutaTransporteAlta.php`

```php
<form action="admin/ctrl/ctrlRutasTransporte.php" method="POST">
  <input type="hidden" name="action" value="insert">
  
  <label>Nombre de la Ruta</label>
  <input type="text" name="nombre" required placeholder="Buenos Aires - Mar del Plata">
  
  <label>Tipo de Transporte</label>
  <select name="idTipoTransporte">
    <!-- Cargar tipos -->
  </select>
  
  <label>Empresa Operadora</label>
  <select name="idEmpresa">
    <!-- Cargar empresas -->
  </select>
  
  <label>Prestador (quien vende)</label>
  <select name="idPrestador">
    <!-- Cargar prestadores -->
  </select>
  
  <label>Duración estimada</label>
  <input type="text" name="duracion_estimada" placeholder="5h30min">
  
  <label>Distancia (km)</label>
  <input type="number" name="distancia_km">
  
  <h3>Paradas de la ruta</h3>
  <div id="paradas-container">
    <div class="parada-item">
      <select name="paradas[0][idTerminal]">
        <?php
        $terminales = getAllTerminales();
        foreach ($terminales as $t) {
          echo "<option value='{$t['idTerminal']}'>{$t['nombre']} ({$t['ciudad']})</option>";
        }
        ?>
      </select>
      <label><input type="checkbox" name="paradas[0][es_origen]" value="1"> Origen</label>
      <label><input type="checkbox" name="paradas[0][es_destino]" value="1"> Destino</label>
      <input type="text" name="paradas[0][tiempo]" placeholder="Tiempo desde inicio (ej: 2h30min)">
    </div>
  </div>
  
  <button type="button" onclick="agregarParada()">+ Agregar parada</button>
  <button type="submit">Guardar Ruta</button>
</form>

<script>
function agregarParada() {
  // Clonar último .parada-item e incrementar índice
}
</script>
```

---

### 3. Alta de Viaje

**Archivo:** `admin/viajeTransporteAlta.php`

```php
<form action="admin/ctrl/ctrlViajesTransporte.php" method="POST">
  <input type="hidden" name="action" value="insert">
  
  <label>Ruta</label>
  <select name="idRuta" id="selectRuta" required>
    <?php
    $rutas = getAllRutas();
    foreach ($rutas as $ruta) {
      echo "<option value='{$ruta['idRuta']}'>{$ruta['nombre']}</option>";
    }
    ?>
  </select>
  
  <label>Fecha</label>
  <input type="date" name="fecha" required min="<?=date('Y-m-d')?>">
  
  <label>Hora de salida</label>
  <input type="time" name="hora_salida" required>
  
  <label>Hora de llegada</label>
  <input type="time" name="hora_llegada">
  
  <label>Terminal de Origen</label>
  <select name="idTerminalOrigen" id="selectOrigen">
    <!-- Se carga dinámicamente al seleccionar ruta -->
  </select>
  
  <label>Terminal de Destino</label>
  <select name="idTerminalDestino" id="selectDestino">
    <!-- Se carga dinámicamente al seleccionar ruta -->
  </select>
  
  <label>Asientos Totales</label>
  <input type="number" name="asientos_totales" value="40">
  
  <label>Número de vuelo/servicio</label>
  <input type="text" name="numero_vuelo_bus" placeholder="LA1234, Servicio 101">
  
  <button type="submit">Guardar Viaje</button>
</form>

<script>
$('#selectRuta').change(function() {
  var idRuta = $(this).val();
  
  // Cargar orígenes
  $.get('admin/ctrl/ctrlParadasRuta.php', {action: 'getOrigenes', idRuta: idRuta}, function(data) {
    $('#selectOrigen').html(data);
  });
  
  // Cargar destinos
  $.get('admin/ctrl/ctrlParadasRuta.php', {action: 'getDestinos', idRuta: idRuta}, function(data) {
    $('#selectDestino').html(data);
  });
});
</script>
```

---

### 4. Editor de Tarifas de Viaje

**Archivo:** `admin/viajeTransporteTarifas.php?idViaje=X`

```php
<?php
$idViaje = $_GET['idViaje'];
$viaje = getViaje($idViaje);
$paradas = getParadasRuta($viaje['idRuta']);
?>

<h2>Tarifas para viaje: <?=$viaje['ruta_nombre']?> (<?=$viaje['fecha']?>)</h2>

<form action="admin/ctrl/ctrlTarifasTransporte.php" method="POST">
  <input type="hidden" name="action" value="insertMultiple">
  <input type="hidden" name="idViaje" value="<?=$idViaje?>">
  
  <table>
    <thead>
      <tr>
        <th>Tramo</th>
        <th>Tipo Pasajero</th>
        <th>Precio</th>
        <th>Moneda</th>
        <th>¿Comisiona?</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Generar combinaciones de tramos posibles
      $origenes = array_filter($paradas, fn($p) => $p['es_origen']);
      $destinos = array_filter($paradas, fn($p) => $p['es_destino']);
      
      $i = 0;
      foreach ($origenes as $origen) {
        foreach ($destinos as $destino) {
          if ($origen['orden'] >= $destino['orden']) continue; // Origen debe ser antes que destino
          
          // Obtener tipos de tarifa (tabla edades)
          $tiposTarifa = getTiposTarifa(); // Adulto, Niño, Senior
          
          foreach ($tiposTarifa as $tipo) {
            ?>
            <tr>
              <td><?=$origen['terminal_nombre']?> → <?=$destino['terminal_nombre']?></td>
              <td><?=$tipo['nombre']?></td>
              <td>
                <input type="hidden" name="tarifas[<?=$i?>][idTerminalOrigen]" value="<?=$origen['idTerminal']?>">
                <input type="hidden" name="tarifas[<?=$i?>][idTerminalDestino]" value="<?=$destino['idTerminal']?>">
                <input type="hidden" name="tarifas[<?=$i?>][idTipoTarifa]" value="<?=$tipo['idTipoTarifa']?>">
                <input type="number" step="0.01" name="tarifas[<?=$i?>][precio]" required>
              </td>
              <td>
                <select name="tarifas[<?=$i?>][idMoneda]">
                  <option value="1">ARS</option>
                  <option value="2">USD</option>
                  <option value="3">BRL</option>
                </select>
              </td>
              <td>
                <input type="checkbox" name="tarifas[<?=$i?>][comisiona]" value="1" checked>
              </td>
            </tr>
            <?php
            $i++;
          }
        }
      }
      ?>
    </tbody>
  </table>
  
  <button type="submit">Guardar Tarifas</button>
</form>
```

---

## 🔌 CONTROLADORES AJAX NECESARIOS

### `admin/ctrl/ctrlTerminales.php`

```php
<?php
require_once("../classes/transporte.php");

$action = $_REQUEST['action'] ?? '';

switch ($action) {
  case 'getAll':
    echo json_encode(getAllTerminales());
    break;
  
  case 'getByTipo':
    $idTipo = $_GET['idTipo'];
    $terminales = getTerminalesByTipo($idTipo);
    foreach ($terminales as $t) {
      echo "<option value='{$t['idTerminal']}'>{$t['nombre']} ({$t['ciudad']})</option>";
    }
    break;
  
  case 'insert':
    $datos = [
      'nombre' => $_POST['nombre'],
      'direccion' => $_POST['direccion'] ?? '',
      'latitud' => $_POST['latitud'] ?? null,
      'longitud' => $_POST['longitud'] ?? null,
      'idPais' => $_POST['idPais'] ?? null,
      'idEstado' => $_POST['idEstado'] ?? null,
      'ciudad' => $_POST['ciudad'],
      'codigo_iata' => $_POST['codigo_iata'] ?? null,
      'idTipoTransporte' => $_POST['idTipoTransporte'],
      'observaciones' => $_POST['observaciones'] ?? '',
      'habilitado' => 1
    ];
    $idTerminal = insertTerminal($datos);
    header("Location: ../terminalesLista.php?success=1");
    break;
}
?>
```

### `admin/ctrl/ctrlParadasRuta.php`

```php
<?php
require_once("../classes/transporte.php");

$action = $_REQUEST['action'] ?? '';
$idRuta = $_REQUEST['idRuta'] ?? 0;

switch ($action) {
  case 'getOrigenes':
    $origenes = getOrigenesRuta($idRuta);
    foreach ($origenes as $o) {
      echo "<option value='{$o['idTerminal']}'>{$o['terminal_nombre']} ({$o['ciudad']})</option>";
    }
    break;
  
  case 'getDestinos':
    $destinos = getDestinosRuta($idRuta);
    foreach ($destinos as $d) {
      echo "<option value='{$d['idTerminal']}'>{$d['terminal_nombre']} ({$d['ciudad']})</option>";
    }
    break;
}
?>
```

---

## 📝 CASOS DE USO REALES

### Caso 1: Ruta Simple (punto a punto)

**Ruta:** Buenos Aires (Retiro) → Mar del Plata

```sql
-- Insertar ruta
INSERT INTO ruta_transporte (nombre, idTipoTransporte, idEmpresa, duracion_estimada, distancia_km) 
VALUES ('Buenos Aires - Mar del Plata', 1, 1, '5h30min', 404);

-- Definir paradas (solo origen y destino)
INSERT INTO ruta_paradas (idRuta, idTerminal, orden, es_origen, es_destino) VALUES
(1, 1, 1, 1, 0), -- Retiro (solo origen)
(1, 2, 2, 0, 1); -- Mar del Plata (solo destino)

-- Crear viaje
INSERT INTO viaje_transporte (idRuta, fecha, hora_salida, hora_llegada, asientos_totales, asientos_disponibles, idTerminalOrigen, idTerminalDestino) 
VALUES (1, '2026-01-20', '10:00:00', '15:30:00', 40, 40, 1, 2);

-- Definir tarifas
INSERT INTO viaje_tarifa (idViaje, idTerminalOrigen, idTerminalDestino, idTipoTarifa, precio, idMoneda) VALUES
(1, 1, 2, 1, 5000.00, 1), -- Adulto
(1, 1, 2, 2, 3000.00, 1); -- Niño
```

---

### Caso 2: Ruta con paradas múltiples

**Ruta:** Buenos Aires → Bahía Blanca → Mar del Plata

```sql
-- Insertar ruta
INSERT INTO ruta_transporte (nombre, idTipoTransporte, duracion_estimada) 
VALUES ('Buenos Aires - Bahía Blanca - Mar del Plata', 1, '5h30min');

-- Definir paradas
INSERT INTO ruta_paradas (idRuta, idTerminal, orden, es_origen, es_destino, tiempo_desde_inicio) VALUES
(2, 1, 1, 1, 0, '0h'),         -- Retiro (solo origen)
(2, 3, 2, 1, 1, '3h'),         -- Bahía Blanca (origen O destino)
(2, 2, 3, 0, 1, '5h30min');    -- Mar del Plata (solo destino)

-- Crear viaje
INSERT INTO viaje_transporte (idRuta, fecha, hora_salida, idTerminalOrigen, idTerminalDestino, asientos_totales) 
VALUES (2, '2026-01-21', '08:00:00', 1, 2, 40);

-- Definir tarifas POR TRAMO
INSERT INTO viaje_tarifa (idViaje, idTerminalOrigen, idTerminalDestino, idTipoTarifa, precio, idMoneda) VALUES
-- Tramo completo: Retiro → Mar del Plata
(2, 1, 2, 1, 5000.00, 1),

-- Tramo parcial 1: Retiro → Bahía Blanca
(2, 1, 3, 1, 2500.00, 1),

-- Tramo parcial 2: Bahía Blanca → Mar del Plata
(2, 3, 2, 1, 2800.00, 1);
```

**Cliente puede comprar:**
- Retiro → Mar del Plata: $5000
- Retiro → Bahía Blanca: $2500
- Bahía Blanca → Mar del Plata: $2800

---

### Caso 3: Vuelo con múltiples aeropuertos

**Ruta:** São Paulo (Congonhas O Guarulhos) → Río de Janeiro (Santos Dumont O Galeão)

```sql
-- Terminales
INSERT INTO terminal_transporte (nombre, ciudad, codigo_iata, idTipoTransporte) VALUES
('Aeropuerto Congonhas', 'São Paulo', 'CGH', 2),
('Aeropuerto Guarulhos', 'São Paulo', 'GRU', 2),
('Aeropuerto Santos Dumont', 'Río de Janeiro', 'SDU', 2),
('Aeropuerto Galeão', 'Río de Janeiro', 'GIG', 2);

-- Ruta (permite combinaciones)
INSERT INTO ruta_transporte (nombre, idTipoTransporte, idEmpresa, duracion_estimada) 
VALUES ('São Paulo - Río de Janeiro', 2, 3, '1h10min');

-- Paradas (ambos aeropuertos son origen Y destino)
INSERT INTO ruta_paradas (idRuta, idTerminal, orden, es_origen, es_destino) VALUES
(3, 4, 1, 1, 0), -- CGH origen
(3, 5, 1, 1, 0), -- GRU origen (mismo orden = misma ciudad)
(3, 6, 2, 0, 1), -- SDU destino
(3, 7, 2, 0, 1); -- GIG destino

-- Viaje específico: GRU → SDU
INSERT INTO viaje_transporte (idRuta, fecha, hora_salida, idTerminalOrigen, idTerminalDestino, numero_vuelo_bus) 
VALUES (3, '2026-01-25', '14:30:00', 5, 6, 'LA3456');

-- Tarifas
INSERT INTO viaje_tarifa (idViaje, idTerminalOrigen, idTerminalDestino, idTipoTarifa, precio, idMoneda) VALUES
(3, 5, 6, 1, 450.00, 3); -- BRL
```

---

## 🚀 IMPLEMENTACIÓN EN FASES

### Fase 1: Backend Básico (HECHO)
- ✅ Tablas creadas en BD experimental
- ✅ Clase PHP `transporte.php` con funciones CRUD
- ✅ Migraciones SQL

### Fase 2: Admin - ABM de Terminales
- [ ] `admin/terminalesLista.php` (listado)
- [ ] `admin/terminalAlta.php` (alta)
- [ ] `admin/terminalEditar.php` (edición)
- [ ] `admin/ctrl/ctrlTerminales.php` (controlador)

### Fase 3: Admin - ABM de Rutas
- [ ] `admin/rutasTransporteLista.php`
- [ ] `admin/rutaTransporteAlta.php` (con gestión de paradas)
- [ ] `admin/rutaTransporteEditar.php`
- [ ] `admin/ctrl/ctrlRutasTransporte.php`

### Fase 4: Admin - Gestión de Viajes
- [ ] `admin/viajesTransporteLista.php`
- [ ] `admin/viajeTransporteAlta.php`
- [ ] `admin/viajeTransporteEditar.php`
- [ ] `admin/viajeTransporteTarifas.php` (editor de tarifas por tramo)

### Fase 5: Frontend - Búsqueda y Compra
- [ ] `transporte_buscar.php` (home de transporte)
- [ ] `transporte_resultados.php` (listado de viajes)
- [ ] `transporte_detalle.php` (detalle de viaje)
- [ ] `transporte_comprar.php` (checkout integrado)
- [ ] `guardaReservaTransporte.php` (guardar en BD)

### Fase 6: Integración con Sistema Actual
- [ ] Vincular `reserva_transporte` con `reservas`
- [ ] Adaptar `carrito.php` para soportar transporte
- [ ] Emails de confirmación específicos
- [ ] Dashboard de reservas mixtas (actividades + transporte)

### Fase 7: Features Avanzadas
- [ ] Selección de asientos (mapa de bus/avión)
- [ ] Equipaje adicional
- [ ] Gestión de overbooking
- [ ] Notificaciones de cambio de horario
- [ ] Integración con APIs reales (Busbud, Skyscanner)

---

## ⚠️ CONSIDERACIONES IMPORTANTES

### 1. Disponibilidad de Asientos

```php
// Al crear reserva, descontar asientos
UPDATE viaje_transporte 
SET asientos_disponibles = asientos_disponibles - :cantidad
WHERE idViaje = :idViaje 
AND asientos_disponibles >= :cantidad;

// Validar antes de confirmar pago
if ($asientos_disponibles < $cantidad_solicitada) {
  die("No hay suficientes asientos disponibles");
}
```

### 2. Precios por Tramo

```php
// Calcular precio total según tramo elegido
$origen = $_POST['idTerminalOrigen'];
$destino = $_POST['idTerminalDestino'];

$consulta = "SELECT precio FROM viaje_tarifa 
             WHERE idViaje = :idViaje 
             AND idTerminalOrigen = :origen 
             AND idTerminalDestino = :destino
             AND idTipoTarifa = :tipoTarifa";
```

### 3. Conversión de Monedas

Usar funciones existentes:
```php
require_once("admin/classes/moneda.php");
$precioConvertido = ConvierteMoneda($precio, $idMonedaOrigen, $idMonedaDestino);
```

### 4. Comisiones

Igual que actividades:
```php
// En viaje_tarifa hay campo `comisiona`
if ($tarifa['comisiona']) {
  $comision = calcularComisionPrestador($idPrestador, $precio);
}
```

---

## 📊 MÉTRICAS Y REPORTES

### Dashboard Admin - Nuevas Métricas

```sql
-- Total pasajes vendidos
SELECT COUNT(*) FROM reserva_transporte WHERE estado = 'confirmada';

-- Ingresos por tipo de transporte
SELECT tt.nombre, SUM(rt.precio_total) as total
FROM reserva_transporte rt
INNER JOIN viaje_transporte vt ON rt.idViaje = vt.idViaje
INNER JOIN ruta_transporte r ON vt.idRuta = r.idRuta
INNER JOIN tipo_transporte tt ON r.idTipoTransporte = tt.idTipoTransporte
GROUP BY tt.idTipoTransporte;

-- Rutas más vendidas
SELECT r.nombre, COUNT(*) as ventas
FROM reserva_transporte rt
INNER JOIN viaje_transporte vt ON rt.idViaje = vt.idViaje
INNER JOIN ruta_transporte r ON vt.idRuta = r.idRuta
GROUP BY r.idRuta
ORDER BY ventas DESC
LIMIT 10;

-- Ocupación promedio de viajes
SELECT AVG((asientos_totales - asientos_disponibles) / asientos_totales * 100) as ocupacion_pct
FROM viaje_transporte
WHERE fecha < CURDATE();
```

---

## 🔐 SEGURIDAD

1. **Validar disponibilidad antes de confirmar:**
   ```php
   $viaje = getViaje($idViaje);
   if ($viaje['asientos_disponibles'] < $cantidad_pasajeros) {
     throw new Exception("No hay suficientes asientos");
   }
   ```

2. **Transacciones para reserva:**
   ```php
   $pdo->beginTransaction();
   try {
     insertReservaTransporte($datos);
     updateAsientosDisponibles($idViaje, -$cantidad);
     $pdo->commit();
   } catch (Exception $e) {
     $pdo->rollBack();
     throw $e;
   }
   ```

3. **Validar tramos existentes:**
   ```php
   // Verificar que el tramo origen-destino existe en ruta_paradas
   $paradaOrigen = getParadaRuta($idRuta, $idTerminalOrigen);
   $paradaDestino = getParadaRuta($idRuta, $idTerminalDestino);
   
   if ($paradaOrigen['orden'] >= $paradaDestino['orden']) {
     die("Tramo inválido");
   }
   ```

---

## 🎯 SIGUIENTES PASOS

1. **Revisar esta documentación** y validar arquitectura
2. **Implementar Fase 2** (ABM Terminales en admin)
3. **Testing en BD experimental** con datos reales
4. **Crear páginas frontend** básicas
5. **Iterar y mejorar** según feedback

---

## 📞 SOPORTE

Para dudas sobre implementación:
- Revisar `admin/classes/transporte.php` (todas las funciones documentadas)
- Consultar `migrations/001_sistema_transporte.sql` (estructura completa)
- Ver copilot-instructions.md para patrones del proyecto

---

**¡Sistema de transporte listo para desarrollar!** 🚀

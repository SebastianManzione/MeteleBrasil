<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$demoPath = __DIR__ . DIRECTORY_SEPARATOR . 'planos_ocr_salida_demo.json';
$mapa = [];
$titulo = 'Mapa de asientos';
if (file_exists($demoPath)) {
    $json = file_get_contents($demoPath);
    $data = json_decode($json, true);
    if (is_array($data) && !empty($data)) {
        $first = $data[0];
        $titulo = isset($first['fabricante']) && isset($first['modelo']) ? ($first['fabricante'] . ' ' . $first['modelo']) : $titulo;
        if (isset($first['distribucion']) && is_array($first['distribucion'])) {
            $mapa = $first['distribucion'];
        } elseif (isset($first['filas']) && isset($first['columnas'])) {
            $filas = (int)$first['filas'];
            $cols = (int)$first['columnas'];
            for ($i=0; $i<$filas; $i++) {
                $fila = [];
                for ($j=0; $j<$cols; $j++) {
                    $fila[] = [
                        'id' => ($i+1) . '-' . ($j+1),
                        'estado' => 'libre'
                    ];
                }
                $mapa[] = $fila;
            }
        }
    }
}
if (empty($mapa)) {
    $filas = 12; $cols = 4;
    for ($i=0; $i<$filas; $i++) {
        $fila = [];
        for ($j=0; $j<$cols; $j++) {
            $fila[] = [
                'id' => ($i+1) . '-' . ($j+1),
                'estado' => ($i%3===0 && $j===2) ? 'ocupado' : 'libre'
            ];
        }
        $mapa[] = $fila;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($titulo); ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  body{font-family:system-ui, -apple-system, Segoe UI, Roboto, sans-serif; margin:0; background:#f6f7f9}
  .container{max-width:1024px; margin:24px auto; padding:0 16px}
  .header{display:flex; align-items:center; justify-content:space-between; margin-bottom:16px}
  .legend{display:flex; gap:16px; align-items:center}
  .legend .item{display:flex; gap:8px; align-items:center}
  .seat-map{display:grid; gap:8px}
  .row{display:grid; grid-template-columns:repeat(auto-fit, minmax(32px, 1fr)); gap:8px}
  .seat{width:40px; height:40px; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:12px; cursor:pointer; user-select:none; border:1px solid #dcdfe4; background:#fff}
  .seat.libre{background:#fff}
  .seat.ocupado{background:#f0f2f5; color:#9aa1a9; cursor:not-allowed}
  .seat.seleccionado{background:#029ce2; color:#fff; border-color:#0290d5}
  .actions{display:flex; justify-content:flex-end; gap:12px; margin-top:16px}
  .btn{padding:10px 14px; border:none; border-radius:6px; cursor:pointer}
  .btn.primary{background:#029ce2; color:#fff}
  .summary{margin-top:8px; color:#333}
</style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2 style="margin:0;"><?php echo htmlspecialchars($titulo); ?></h2>
      <div class="legend">
        <div class="item"><span class="seat libre" style="width:20px; height:20px;"></span> Libre</div>
        <div class="item"><span class="seat ocupado" style="width:20px; height:20px;"></span> Ocupado</div>
        <div class="item"><span class="seat seleccionado" style="width:20px; height:20px;"></span> Seleccionado</div>
      </div>
    </div>
    <div id="seatMap" class="seat-map">
      <?php foreach ($mapa as $fila): ?>
        <div class="row">
          <?php foreach ($fila as $s): ?>
            <div class="seat <?php echo htmlspecialchars($s['estado']); ?>" data-seat-id="<?php echo htmlspecialchars($s['id']); ?>" data-estado="<?php echo htmlspecialchars($s['estado']); ?>">
              <?php echo htmlspecialchars($s['id']); ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="summary">
      <span>Asientos seleccionados: <strong id="asientosSeleccionadosCount">0</strong></span>
    </div>
    <div class="actions">
      <button id="btnContinuar" class="btn primary"><i class="fa fa-ticket"></i> Continuar</button>
    </div>
  </div>
<script src="js/gestor_asientos.js"></script>
<script>
  window.AsientosGestor && window.AsientosGestor.init('#seatMap', '#asientosSeleccionadosCount');
  document.getElementById('btnContinuar').addEventListener('click', function(){
    var sel = (window.AsientosGestor && window.AsientosGestor.getSeleccionados) ? window.AsientosGestor.getSeleccionados() : [];
    alert('Seleccionados: ' + sel.join(', '));
  });
</script>
</body>
</html>

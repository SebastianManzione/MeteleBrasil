<?php
require_once 'admin/classes/transporte.php';
$rows = getServiciosAdicionalesViaje(5);
foreach($rows as $r){
  echo ($r['nombre'] ?? 'N/A') . ' - ' . ($r['moneda_simbolo'] ?? '') . ' ' . ($r['precio'] ?? '') . "\n";
}

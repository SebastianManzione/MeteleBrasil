<?php
$m = new mysqli('localhost', 'root', '', 'metelebrasil');
$r = $m->query('SHOW TABLES LIKE "hoteles"');
echo ($r->num_rows > 0 ? '✓ Tabla hoteles EXISTE' : '✗ Tabla hoteles NO EXISTE') . "\n";
if($r->num_rows > 0) {
    $r2 = $m->query('SELECT COUNT(*) as cnt FROM hoteles');
    $cnt = $r2->fetch_assoc()['cnt'];
    echo "Total hoteles: $cnt\n";
    $m->query('DESCRIBE hoteles');
}
?>

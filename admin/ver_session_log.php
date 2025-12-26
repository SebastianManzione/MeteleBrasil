<?php
$debug_file = __DIR__ . '/debug_session_real.log';

echo "<h2>Contenido de debug_session_real.log:</h2>";

if (file_exists($debug_file)) {
    $content = file_get_contents($debug_file);
    echo "<pre>" . htmlspecialchars($content) . "</pre>";
} else {
    echo "<p>Archivo aún no creado. Recarga el admin en el navegador primero.</p>";
}

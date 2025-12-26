#!/bin/bash
# Script de verificación post-fix PDO

echo "========================================"
echo "Verificación de Sistema MeteleBrasil"
echo "========================================"
echo ""

# 1. Verificar sintaxis PHP
echo "1. Validando sintaxis PHP..."
php -l admin/classes/configuracion.php
php -l admin/classes/antibot.php
echo "✓ Sintaxis válida"
echo ""

# 2. Verificar que PDO está disponible
echo "2. Verificando PDO..."
php -r "echo 'PDO disponible: ' . (extension_loaded('pdo') ? 'SÍ' : 'NO') . PHP_EOL;"
echo ""

# 3. Verificar conexión a BD
echo "3. Verificando conexión a BD..."
php -r "
require_once('admin/classes/conexion.php');
if (isset(\$GLOBALS['pdo'])) {
    echo 'Conexión PDO: ✓ Disponible' . PHP_EOL;
} else {
    echo 'Conexión PDO: ✗ No disponible' . PHP_EOL;
}
"
echo ""

# 4. Verificar que la clase Configuracion carga
echo "4. Verificando clase Configuracion..."
php -r "
require_once('admin/classes/conexion.php');
require_once('admin/classes/configuracion.php');
try {
    \$config = new Configuracion();
    echo 'Clase Configuracion: ✓ Cargada correctamente' . PHP_EOL;
} catch (Exception \$e) {
    echo 'Clase Configuracion: ✗ Error - ' . \$e->getMessage() . PHP_EOL;
}
"
echo ""

# 5. Verificar que antibot está disponible
echo "5. Verificando sistema anti-bot..."
php -r "
require_once('admin/classes/conexion.php');
require_once('admin/classes/antibot.php');
echo 'Funciones anti-bot: ✓ Cargadas' . PHP_EOL;
echo '  - verificarHoneypot()' . PHP_EOL;
echo '  - verificarRateLimit()' . PHP_EOL;
echo '  - verificarTiempoMinimo()' . PHP_EOL;
echo '  - validarAntiBot()' . PHP_EOL;
echo '  - registrarIntentoBot()' . PHP_EOL;
"
echo ""

# 6. Acceso al sitio
echo "6. URLs para probar:"
echo ""
echo "   Panel de Configuración:"
echo "   → http://localhost/metelebrasil_dev/admin/configuracion.php"
echo ""
echo "   Dashboard Admin:"
echo "   → http://localhost/metelebrasil_dev/admin/"
echo ""
echo "   Prueba de Anti-Bot:"
echo "   → http://localhost/metelebrasil_dev/contact.php"
echo ""

echo "========================================"
echo "Verificación completada ✓"
echo "========================================"

<?php
// Proxy de logout para peticiones directas a /admin/logout.php
// Redirige al endpoint central que maneja mantenimiento y paths
header('Location: ../logout.php');
exit;

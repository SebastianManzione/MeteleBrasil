<?php
session_start();
session_destroy();
// Reutilizar el logout central que maneja mantenimiento y base path
header("Location: ../logout.php");
exit;
?>
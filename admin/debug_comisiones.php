<?php
session_start();
require_once("classes/prestador.php");

echo "<h2>DEBUG COMISIONES</h2>";
echo "<h3>Sesión actual:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>Variables de control:</h3>";
$isAdmin = isset($_SESSION["login"]["rol"]) && $_SESSION["login"]["rol"] == 1;
$isPrestador = isset($_SESSION["login"]["idPrestador"]) && $_SESSION["login"]["idPrestador"] > 0;
echo "isAdmin: " . ($isAdmin ? 'SI' : 'NO') . "<br>";
echo "isPrestador: " . ($isPrestador ? 'SI' : 'NO') . "<br>";

if (isset($_SESSION["login"]["idPrestador"])) {
    echo "idPrestador en sesión: " . $_SESSION["login"]["idPrestador"] . "<br>";
}

echo "<h3>Prestadores en BD:</h3>";
$prestadores = getPrestadores();
echo "Total prestadores encontrados: " . count($prestadores) . "<br>";
echo "<pre>";
print_r($prestadores);
echo "</pre>";

if (!empty($prestadores)) {
    $idPrestadorTest = $prestadores[0]['idPrestador'];
    echo "<h3>Test getPrestador($idPrestadorTest):</h3>";
    $prestadorTest = getPrestador($idPrestadorTest);
    echo "<pre>";
    print_r($prestadorTest);
    echo "</pre>";
}
?>

<?php 
include("../classes/usuario.php");
session_start();

if ($_SERVER["REQUEST_METHOD"]=="POST") {
  // Solo admin
  if (!isset($_SESSION["login"]["rol"]) || $_SESSION["login"]["rol"] != 1) {
    http_response_code(403);
    echo 0; exit;
  }

  if (isset($_POST["borraUsuario"]) && is_numeric($_POST["borraUsuario"])) {
    $idUsuario = intval($_POST["borraUsuario"]);
    // No permitir borrar el propio usuario logueado
    if (isset($_SESSION["login"]["idUsuario"]) && $_SESSION["login"]["idUsuario"] == $idUsuario) {
      echo 0; exit;
    }
    $resultado = borraUsuario($idUsuario);
    echo $resultado;
  }
}
?>
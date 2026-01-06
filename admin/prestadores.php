<?php 
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('prestadores');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require("classes/functions.php");
require("classes/prestador.php");
require("classes/usuario.php");

if (!$_SESSION["login"]["rol"]==1) {
  alertar("Usted no tiene acceso a esta seccion del software", "error");
  redireccionarLento("index");
}
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Prestadores</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Prestadores</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="card shadow-sm rounded-lg border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title m-0">Prestadores</h3>
          <div class="card-tools">
            <!-- Botón de colapsar queda -->
            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            <!-- Botón de cerrar eliminado -->
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table id="example1" class="table table-striped table-hover table-bordered w-100">
              <thead class="thead-dark">
                <tr class="text-center">
                  <th>Nombre</th>
                  <th>RSocial</th>
                  <th>CNPJ/CUIL/CUIT</th>
                  <th>Telefono</th>
                  <th>Email</th>
                  <th>Usuario sistema</th>
                  <th>Celular</th>
                  <th>Acciones</th>
<th></th>
<th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $prestadores = getPrestadores();
                for($i=0;$i < count($prestadores); $i++){
                  $idPrestador = $prestadores[$i]["idPrestador"];
                  $idUsuario = $prestadores[$i]["idUsuario"];
                  $usuario = getUsuario($idUsuario);
                ?>
                <tr >
                  <td><?= $prestadores[$i]["nombre"]; ?></td>
                  <td><?= $prestadores[$i]["razonSocial"]; ?></td>
                  <td><?= $prestadores[$i]["documento"]; ?></td>
                  <td><?= $prestadores[$i]["telefono"]; ?></td>
                  <td><?= $prestadores[$i]["email"]; ?></td>
                  <td><?= $usuario[0]["email"]; ?></td>
                  <td><?= $prestadores[$i]["celular"]; ?></td>
                  <td class="form-group">
                    <div class="form-group form-inline">
                     <form method="post" action="altaPrestador" >
                      <button name="editaPrestador" value="<?=$idPrestador;?>" type="submit" class="btn btn-sm btn-success">Editar</button>
                    </form>
                   
                </div>
                  </td>
                  <td>
                     <form method="post" action="comisionesprestador">
                      <button name="idPrestador" value="<?=$idPrestador;?>" type="submit" class="btn btn-sm btn-primary">Comisiones</button>
                    </form>
                  </td>
                  <td>
                      <button class="btn btn-xs btn-danger" onclick="borraPrestador('<?=$idPrestador;?>')">Eliminar</button> 
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php include("includes/footer.php"); ?>

<style>
/* Modernización y responsive */
.table-modern {
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 0 15px rgba(0,0,0,0.05);
}
.table-modern th {
  background: linear-gradient(90deg, #4a90e2, #357ab7);
  color: #fff;
  text-align: center;
}
.table-modern td {
  vertical-align: middle !important;
}
.table-modern tbody tr:hover {
  background-color: #f1f1f1;
  transition: 0.3s;
}
.card-header.bg-primary {
  background: linear-gradient(90deg, #4a90e2, #357ab7);
}
.btn-success, .btn-danger, .btn-primary {
  border-radius: 6px;
  transition: transform 0.2s, box-shadow 0.2s;
}
.btn-success:hover, .btn-danger:hover, .btn-primary:hover {
  transform: scale(1.05);
  box-shadow: 0 0 10px rgba(0,0,0,0.2);
}
@media (max-width: 768px) {
  .table-modern th, .table-modern td {
    font-size: 0.85rem;
    padding: 0.4rem;
  }
  .btn-sm {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
  }
}
</style>

<script>
function borraPrestador(idPrestador){
  var parametros={"borraPrestador" : idPrestador};   
  Swal.fire({
    title: 'Está seguro?',
    text: 'Esta acción no se puede revertir!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, borrar!'
  }).then((result) => {
    if (result.value) {
      $.post("./ctrl/ctrl_prestador.php", parametros, function(data, status){
        if (data>0) location.href = 'prestadores.php';
      });
      Swal.fire('Eliminado!', 'El prestador se eliminó.', 'success')
    }
  })   
}
</script>

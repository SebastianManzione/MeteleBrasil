<?php
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/navbar.php');
require_once(__DIR__ . '/includes/sidebar.php');
require_once(__DIR__ . '/classes/menu.php');

// Solo Admin puede acceder
if ($_SESSION['login']['rol'] != 1) {
    header('Location: index');
    exit;
}

$archivo_actual = 'rolesPermisos.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Administrador de Roles y Permisos</h1>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <!-- Tabs -->
      <ul class="nav nav-tabs" id="rolesTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="roles-tab" data-toggle="tab" href="#roles" role="tab">
            <i class="fas fa-users-cog"></i> Roles
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="permisos-tab" data-toggle="tab" href="#permisos" role="tab">
            <i class="fas fa-shield-alt"></i> Permisos de Menú
          </a>
        </li>
      </ul>

      <div class="tab-content" id="rolesTabContent">
        <!-- Tab: Roles -->
        <div class="tab-pane fade show active" id="roles" role="tabpanel">
          <div class="card mt-3">
            <div class="card-header">
              <h3 class="card-title">Lista de Roles</h3>
              <div class="card-tools">
                <button class="btn btn-sm btn-primary" onclick="abrirModalRol()">
                  <i class="fas fa-plus"></i> Nuevo Rol
                </button>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped" id="tablaRoles">
                <thead>
                  <tr>
                    <th width="80">ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th width="150">Acciones</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Tab: Permisos -->
        <div class="tab-pane fade" id="permisos" role="tabpanel">
          <div class="card mt-3">
            <div class="card-header">
              <h3 class="card-title">Asignar Permisos de Menú por Rol</h3>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label>Seleccionar Rol:</label>
                <select class="form-control" id="selRol" onchange="cargarPermisosRol()">
                  <option value="">-- Seleccione un rol --</option>
                </select>
              </div>

              <div id="permisos-container" style="display:none;">
                <h5>Permisos de Menú</h5>
                <p class="text-muted">Seleccione los ítems del menú a los que este rol tendrá acceso:</p>
                <div id="menuPermisos"></div>
                <button class="btn btn-success mt-3" onclick="guardarPermisos()">
                  <i class="fas fa-save"></i> Guardar Permisos
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Editar Rol -->
<div class="modal fade" id="modalRol" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Rol</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formRol">
          <input type="hidden" id="editIdRol">
          <div class="form-group">
            <label>ID del Rol</label>
            <input type="number" class="form-control" id="editRolId" required>
          </div>
          <div class="form-group">
            <label>Nombre del Rol</label>
            <input type="text" class="form-control" id="editRolNombre" required>
          </div>
          <div class="form-group">
            <label>Descripción</label>
            <textarea class="form-control" id="editRolDesc" rows="3"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="guardarRol()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>

<script>
$(document).ready(function() {
  cargarRoles();
  cargarRolesSelect();
});

function cargarRoles() {
  $.get('ctrl/ctrl_roles.php?action=getRoles', function(res) {
    if (res.success) {
      let html = '';
      res.data.forEach(function(rol) {
        html += `<tr>
          <td>${rol.idRol}</td>
          <td><strong>${rol.rol}</strong></td>
          <td>${rol.descripcion || '-'}</td>
          <td>
            <button class="btn btn-sm btn-warning" onclick="editarRol(${rol.idRol})">
              <i class="fas fa-edit"></i>
            </button>
          </td>
        </tr>`;
      });
      $('#tablaRoles tbody').html(html);
    }
  });
}

function cargarRolesSelect() {
  $.get('ctrl/ctrl_roles.php?action=getRoles', function(res) {
    if (res.success) {
      let html = '<option value="">-- Seleccione un rol --</option>';
      res.data.forEach(function(rol) {
        html += `<option value="${rol.idRol}">${rol.rol}</option>`;
      });
      $('#selRol').html(html);
    }
  });
}

function abrirModalRol() {
  $('#editIdRol').val('');
  $('#editRolId').val('').prop('readonly', false);
  $('#editRolNombre').val('');
  $('#editRolDesc').val('');
  $('#modalRol').modal('show');
}

function editarRol(idRol) {
  $.get('ctrl/ctrl_roles.php?action=getRol&id=' + idRol, function(res) {
    if (res.success && res.data) {
      $('#editIdRol').val(res.data.idRol);
      $('#editRolId').val(res.data.idRol).prop('readonly', true);
      $('#editRolNombre').val(res.data.rol);
      $('#editRolDesc').val(res.data.descripcion);
      $('#modalRol').modal('show');
    }
  });
}

function guardarRol() {
  const data = {
    action: 'saveRol',
    idRol: $('#editRolId').val(),
    rol: $('#editRolNombre').val(),
    descripcion: $('#editRolDesc').val()
  };

  $.post('ctrl/ctrl_roles.php', data, function(res) {
    if (res.success) {
      alert('Rol guardado correctamente');
      $('#modalRol').modal('hide');
      cargarRoles();
      cargarRolesSelect();
    } else {
      alert('Error: ' + (res.error || 'Error desconocido'));
    }
  });
}

function cargarPermisosRol() {
  const roleId = $('#selRol').val();
  if (!roleId) {
    $('#permisos-container').hide();
    return;
  }

  // Cargar todos los menús
  $.get('ctrl/ctrl_menu.php?action=getAll', function(menuRes) {
    if (!menuRes.success) return;

    // Cargar permisos actuales del rol
    $.get('ctrl/ctrl_roles.php?action=getPermisosRol&roleId=' + roleId, function(permRes) {
      const permisos = permRes.success ? permRes.data : [];
      renderMenuPermisos(menuRes.data, permisos);
      $('#permisos-container').show();
    });
  });
}

function renderMenuPermisos(tree, permisos) {
  let html = '<div class="list-group">';
  
  function renderItem(item, level = 0) {
    const hasChildren = item.children && item.children.length > 0;
    const checked = permisos.includes(item.id) ? 'checked' : '';
    const indent = level * 20;
    
    html += `<div class="list-group-item" style="padding-left: ${10 + indent}px;">
      <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input menu-checkbox" 
               id="menu_${item.id}" value="${item.id}" ${checked}>
        <label class="custom-control-label" for="menu_${item.id}">
          <i class="${item.icon}"></i> ${item.label} 
          <small class="text-muted">(${item.route})</small>
        </label>
      </div>
    </div>`;
    
    if (hasChildren) {
      item.children.forEach(child => renderItem(child, level + 1));
    }
  }
  
  tree.forEach(item => renderItem(item));
  html += '</div>';
  
  $('#menuPermisos').html(html);
}

function guardarPermisos() {
  const roleId = $('#selRol').val();
  const menuIds = [];
  $('.menu-checkbox:checked').each(function() {
    menuIds.push($(this).val());
  });

  $.post('ctrl/ctrl_roles.php', {
    action: 'savePermisosRol',
    roleId: roleId,
    menuIds: menuIds
  }, function(res) {
    if (res.success) {
      alert('Permisos guardados correctamente');
    } else {
      alert('Error: ' + (res.error || 'Error desconocido'));
    }
  });
}
</script>

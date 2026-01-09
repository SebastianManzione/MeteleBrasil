<?php
// Verificar permisos ANTES de cualquier salida
require_once(__DIR__ . '/classes/permisos.php');
require_once(__DIR__ . '/includes/permisos_helper.php');
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('adminMenuRoles');

require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/navbar.php');
require_once(__DIR__ . '/includes/sidebar.php');

require_once(__DIR__ . '/classes/menu.php');

// Solo Admin puede acceder
if ($_SESSION['login']['rol'] != 1) {
    header('Location: index');
    exit;
}

$archivo_actual = 'adminMenuRoles.php';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Administración de Menú y Roles</h1>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <!-- Tabs -->
      <ul class="nav nav-tabs" id="adminTabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="estructura-tab" data-toggle="tab" href="#estructura" role="tab">
            <i class="fas fa-sitemap"></i> Estructura del Menú
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="roles-tab" data-toggle="tab" href="#roles" role="tab">
            <i class="fas fa-users-cog"></i> Roles
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="permisos-tab" data-toggle="tab" href="#permisos" role="tab">
            <i class="fas fa-shield-alt"></i> Asignación de Permisos
          </a>
        </li>
      </ul>

      <div class="tab-content" id="adminTabContent">

        <!-- TAB 1: Estructura del Menú -->
        <div class="tab-pane fade show active" id="estructura" role="tabpanel">
          <div class="card mt-3">
            <div class="card-header">
              <h3 class="card-title">Elementos del Menú</h3>
              <div class="card-tools">
                <button class="btn btn-sm btn-primary" onclick="abrirModalMenu()">
                  <i class="fas fa-plus"></i> Nuevo Elemento
                </button>
                <button class="btn btn-sm btn-secondary" onclick="recargarMenu()">
                  <i class="fas fa-sync"></i> Recargar
                </button>
              </div>
            </div>
            <div class="card-body">
              <div id="menuContainer" style="max-height: 600px; overflow-y: auto;"></div>
            </div>
          </div>
        </div>

        <!-- TAB 2: Roles -->
        <div class="tab-pane fade" id="roles" role="tabpanel">
          <div class="card mt-3">
            <div class="card-header">
              <h3 class="card-title">Gestión de Roles</h3>
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

        <!-- TAB 3: Asignación de Permisos -->
        <div class="tab-pane fade" id="permisos" role="tabpanel">
          <div class="card mt-3">
            <div class="card-header">
              <h3 class="card-title">Asignar Permisos de Menú a Roles</h3>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label><strong>Seleccionar Rol:</strong></label>
                <select class="form-control" id="selRol" onchange="cargarPermisosRol()">
                  <option value="">-- Seleccione un rol --</option>
                </select>
              </div>

              <div id="permisos-container" style="display:none;">
                <h5>Permisos de Menú Disponibles</h5>
                <p class="text-muted">Marque los ítems del menú a los que este rol tendrá acceso:</p>
                <div id="menuPermisos" style="max-height: 500px; overflow-y: auto;"></div>
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

<!-- Modal para editar Menú -->
<div class="modal fade" id="editModalMenu" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Elemento del Menú</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editFormMenu">
          <input type="hidden" id="editId">
          <div class="form-group">
            <label>Etiqueta *</label>
            <input type="text" class="form-control" id="editLabel" required>
          </div>
          <div class="form-group">
            <label>Ruta * (ej: prestadores o # para menú padre)</label>
            <input type="text" class="form-control" id="editRoute" required>
          </div>
          <div class="form-group">
            <label>Icono</label>
            <input type="text" class="form-control" id="editIcon" placeholder="ej: fas fa-home">
          </div>
          <div class="form-group">
            <label>Color CSS</label>
            <input type="text" class="form-control" id="editColor" placeholder="ej: text-info">
          </div>
          <div class="form-group">
            <label>Menú Padre</label>
            <select class="form-control" id="editParent">
              <option value="">-- Ninguno (Menú raíz) --</option>
            </select>
          </div>
          <div class="form-group">
            <label>Orden de Visualización</label>
            <input type="number" class="form-control" id="editSort" value="100">
          </div>
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="editEnabled" checked>
            <label class="form-check-label">Habilitado</label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-danger" id="btnEliminar" onclick="eliminarItem()" style="display:none;">
          <i class="fas fa-trash"></i> Eliminar
        </button>
        <button type="button" class="btn btn-primary" onclick="guardarItemMenu()">
          <i class="fas fa-save"></i> Guardar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para editar Rol -->
<div class="modal fade" id="editModalRol" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Rol</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editFormRol">
          <div class="form-group">
            <label>ID del Rol</label>
            <input type="number" class="form-control" id="editIdRol" required>
          </div>
          <div class="form-group">
            <label>Nombre *</label>
            <input type="text" class="form-control" id="editNombreRol" required>
          </div>
          <div class="form-group">
            <label>Descripción</label>
            <textarea class="form-control" id="editDescRol" rows="3"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="guardarRol()">
          <i class="fas fa-save"></i> Guardar
        </button>
      </div>
    </div>
  </div>
</div>

<style>
  .menu-item {
    padding: 12px 15px;
    border: 1px solid #ddd;
    margin: 5px 0;
    background: #f9f9f9;
    border-radius: 4px;
    cursor: pointer;
    transition: 0.2s;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .menu-item:hover {
    background: #f0f0f0;
    border-color: #0066cc;
  }
  .menu-item.parent {
    background: #e3f2fd;
    font-weight: bold;
  }
  .menu-item-children {
    margin-left: 30px;
    margin-top: 5px;
  }
  .opacity-50 {
    opacity: 0.5;
  }
  .menu-item-content {
    flex: 1;
  }
  .menu-item-actions {
    display: flex;
    gap: 5px;
  }
  .menu-item-actions button {
    padding: 2px 8px;
    font-size: 12px;
  }
  
  /* Checkboxes jerárquicos */
  .permiso-item {
    padding: 10px;
    background: #f9f9f9;
    margin: 3px 0;
    border-radius: 3px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .permiso-item.nivel-hijo {
    margin-left: 30px;
  }
  .permiso-item.nivel-padre {
    background: #e3f2fd;
    font-weight: bold;
  }
</style>

<script>
let allMenuItems = [];

// ============= TAB 1: ESTRUCTURA DEL MENÚ =============

function recargarMenu() {
  $.get('ctrl/ctrl_menu.php?action=getAll', function(res) {
    if (res.success) {
      allMenuItems = res.data;
      renderMenu(res.data);
    } else {
      alert('Error: ' + (res.error || 'Error desconocido'));
    }
  }).fail(function(err) {
    console.error('Error:', err);
    alert('Error al cargar el menú');
  });
}

function renderMenu(tree) {
  let html = '';
  function renderItems(items) {
    items.forEach(item => {
      const hasChildren = item.children && item.children.length > 0;
      const cls = item.enabled ? '' : 'opacity-50';
      html += `<div class="menu-item ${hasChildren ? 'parent' : ''} ${cls}">
        <div class="menu-item-content" onclick="abrirEditMenu(event, ${item.id})">
          <i class="${item.icon}" style="margin-right: 10px;"></i>
          <strong>${item.label}</strong>
          <code style="color: #999; font-size: 11px;">${item.route}</code>
          ${item.color_class ? '<span class="badge badge-info" style="margin-left: 10px;">'+item.color_class+'</span>' : ''}
        </div>
        <div class="menu-item-actions">
          <button class="btn btn-sm btn-warning" onclick="abrirEditMenu(event, ${item.id})">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn btn-sm btn-danger" onclick="confirmarEliminar(event, ${item.id})">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>`;
      if (hasChildren) {
        html += '<div class="menu-item-children">';
        renderItems(item.children);
        html += '</div>';
      }
    });
  }
  renderItems(tree);
  $('#menuContainer').html(html || '<p class="text-muted">No hay elementos</p>');
}

function abrirModalMenu() {
  $('#editId').val('');
  $('#editLabel').val('');
  $('#editRoute').val('');
  $('#editIcon').val('');
  $('#editColor').val('');
  $('#editSort').val('100');
  $('#editEnabled').prop('checked', true);
  $('#btnEliminar').hide();

  let parentHtml = '<option value="">-- Ninguno (Menú raíz) --</option>';
  function addParents(items) {
    items.forEach(it => {
      parentHtml += `<option value="${it.id}">${it.label}</option>`;
      if (it.children) addParents(it.children);
    });
  }
  addParents(allMenuItems);
  $('#editParent').html(parentHtml);

  $('#editModalMenu').modal('show');
}

function abrirEditMenu(e, id) {
  e.stopPropagation();
  const item = findItem(allMenuItems, id);
  if (!item) { alert('No encontrado'); return; }

  $('#editId').val(id);
  $('#editLabel').val(item.label);
  $('#editRoute').val(item.route);
  $('#editIcon').val(item.icon || '');
  $('#editColor').val(item.color_class || '');
  $('#editSort').val(item.sort_order || 100);
  $('#editEnabled').prop('checked', item.enabled === 1);

  let parentHtml = '<option value="">-- Ninguno (Menú raíz) --</option>';
  function addParents(items) {
    items.forEach(it => {
      if (it.id !== id) {
        parentHtml += `<option value="${it.id}">${it.label}</option>`;
        if (it.children) addParents(it.children);
      }
    });
  }
  addParents(allMenuItems);
  $('#editParent').html(parentHtml);
  $('#editParent').val(item.parent_id || '');

  $('#btnEliminar').show();
  $('#editModalMenu').modal('show');
}

function guardarItemMenu() {
  $.post('ctrl/ctrl_menu.php', {
    action: 'save',
    id: $('#editId').val(),
    label: $('#editLabel').val(),
    route: $('#editRoute').val(),
    icon: $('#editIcon').val(),
    color_class: $('#editColor').val(),
    parent_id: $('#editParent').val() || null,
    sort_order: $('#editSort').val(),
    enabled: $('#editEnabled').is(':checked') ? 1 : 0
  }, function(res) {
    if (res.success) {
      alert('Guardado correctamente');
      $('#editModalMenu').modal('hide');
      recargarMenu();
    } else {
      alert('Error: ' + (res.error || 'Error desconocido'));
    }
  }).fail(function(err) {
    console.error(err);
    alert('Error al guardar');
  });
}

function confirmarEliminar(e, id) {
  e.stopPropagation();
  if (!confirm('¿Estás seguro de que deseas eliminar este elemento?')) return;
  eliminarItem(id);
}

function eliminarItem(id) {
  $.post('ctrl/ctrl_menu.php', {
    action: 'delete',
    id: id
  }, function(res) {
    if (res.success) {
      alert('Eliminado correctamente');
      $('#editModalMenu').modal('hide');
      recargarMenu();
    } else {
      alert('Error: ' + (res.error || 'Error desconocido'));
    }
  }).fail(function(err) {
    console.error(err);
    alert('Error al eliminar');
  });
}

function findItem(tree, id) {
  for (let item of tree) {
    if (item.id === id) return item;
    if (item.children) {
      const found = findItem(item.children, id);
      if (found) return found;
    }
  }
  return null;
}

// ============= TAB 2: ROLES =============

function cargarRoles() {
  $.get('ctrl/ctrl_roles.php?action=getRoles', function(res) {
    if (res.success) {
      let html = '';
      res.data.forEach(rol => {
        html += `<tr>
          <td>${rol.idRol}</td>
          <td>${rol.rol}</td>
          <td>${rol.descripcion || '-'}</td>
          <td>
            <button class="btn btn-sm btn-primary" onclick="editarRol(${rol.idRol})">
              <i class="fas fa-edit"></i>
            </button>
          </td>
        </tr>`;
      });
      $('#tablaRoles tbody').html(html);
    } else {
      alert('Error: ' + (res.error || 'Error desconocido'));
    }
  });
}

function abrirModalRol() {
  $('#editIdRol').val('');
  $('#editNombreRol').val('');
  $('#editDescRol').val('');
  $('#editModalRol').modal('show');
}

function editarRol(idRol) {
  $.get('ctrl/ctrl_roles.php?action=getRol&id=' + idRol, function(res) {
    if (res.success) {
      $('#editIdRol').val(res.data.idRol);
      $('#editNombreRol').val(res.data.rol);
      $('#editDescRol').val(res.data.descripcion || '');
      $('#editModalRol').modal('show');
    } else {
      alert('Error: ' + (res.error || 'Error desconocido'));
    }
  });
}

function guardarRol() {
  const idRol = $('#editIdRol').val();
  const rol = $('#editNombreRol').val();
  const desc = $('#editDescRol').val();

  if (!idRol || !rol) {
    alert('El ID y Nombre del rol son requeridos');
    return;
  }

  $.post('ctrl/ctrl_roles.php', {
    action: 'saveRol',
    idRol: idRol,
    rol: rol,
    descripcion: desc
  }, function(res) {
    if (res.success) {
      alert('Rol guardado correctamente');
      $('#editModalRol').modal('hide');
      cargarRoles();
      cargarRolesSelect();
    } else {
      alert('Error: ' + (res.error || 'Error desconocido'));
    }
  });
}

// ============= TAB 3: PERMISOS =============

function cargarRolesSelect() {
  $.get('ctrl/ctrl_menu.php?action=getRoles', function(res) {
    if (res.success) {
      let html = '<option value="">-- Seleccione un rol --</option>';
      res.data.forEach(rol => {
        const id = rol.id ?? rol.idRol;
        const nombre = rol.rol ?? rol.nombre ?? ('Rol ' + id);
        html += `<option value="${id}">${nombre}</option>`;
      });
      $('#selRol').html(html);
    }
  });
}

function cargarPermisosRol() {
  const roleId = $('#selRol').val();
  if (!roleId) {
    $('#permisos-container').hide();
    return;
  }

  $.get('ctrl/ctrl_roles.php?action=getPermisosRol&roleId=' + roleId, function(res) {
    if (res.success) {
      const permisosActuales = res.data || [];
      renderMenuPermisos(allMenuItems, permisosActuales);
      $('#permisos-container').show();
    } else {
      alert('Error: ' + (res.error || 'Error desconocido'));
    }
  });
}

function renderMenuPermisos(items, permisosActuales = []) {
  let html = '';
  const permisosSet = new Set(permisosActuales.map(p => parseInt(p, 10)));

  function renderItems(items, nivel = 0) {
    items.forEach(item => {
      const itemId = parseInt(item.id, 10);
      const checked = permisosSet.has(itemId) ? 'checked' : '';
      const nivelClass = nivel === 0 ? 'nivel-padre' : 'nivel-hijo';
      const marginLeft = nivel > 0 ? `margin-left: ${nivel * 20}px;` : '';

      html += `<div class="permiso-item ${nivelClass}" style="${marginLeft}">
        <input type="checkbox" class="menu-checkbox" value="${itemId}" ${checked}>
        <i class="${item.icon}"></i>
        <label>${item.label}</label>
      </div>`;

      if (item.children && item.children.length > 0) {
        renderItems(item.children, nivel + 1);
      }
    });
  }

  renderItems(items);
  $('#menuPermisos').html(html);
}

function guardarPermisos() {
  const roleId = $('#selRol').val();
  if (!roleId) {
    alert('Seleccione un rol primero');
    return;
  }

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
      alert('Permisos actualizados correctamente');
      cargarPermisosRol();
    } else {
      alert('Error: ' + (res.error || 'Error desconocido'));
    }
  });
}

// ============= Inicialización =============

$(document).ready(function() {
  // Cargar menú al abrir TAB 1
  recargarMenu();

  // Cargar roles al cambiar a TAB 2
  $('#roles-tab').on('click', function() {
    cargarRoles();
  });

  // Cargar roles en selector al cambiar a TAB 3
  $('#permisos-tab').on('click', function() {
    cargarRolesSelect();
  });
});
</script>

<?php
include("includes/footer.php");
?>

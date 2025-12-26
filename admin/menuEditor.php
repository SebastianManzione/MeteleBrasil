<?php
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/navbar.php');
require_once(__DIR__ . '/includes/sidebar.php');
require_once(__DIR__ . '/classes/menu.php');

// Verificar rol admin
if ($_SESSION['login']['rol'] != 1) {
    header('Location: index');
    exit;
}

$archivo_actual = 'menuEditor.php';
?>

<!-- Main content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Editor de Menú</h1>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Elementos del menú</h3>
              <div class="card-tools">
                <button class="btn btn-sm btn-primary" onclick="location.reload()">
                  <i class="fas fa-sync"></i> Recargar
                </button>
              </div>
            </div>
            <div class="card-body">
              <div id="menuContainer" style="max-height: 500px; overflow-y: auto;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal para editar -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar elemento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          <input type="hidden" id="editId">
          <div class="form-group">
            <label>Etiqueta *</label>
            <input type="text" class="form-control" id="editLabel" required>
          </div>
          <div class="form-group">
            <label>Ruta * (ej: prestadores o #)</label>
            <input type="text" class="form-control" id="editRoute" required>
          </div>
          <div class="form-group">
            <label>Icono</label>
            <input type="text" class="form-control" id="editIcon" placeholder="ej: fas fa-home">
          </div>
          <div class="form-group">
            <label>Color</label>
            <input type="text" class="form-control" id="editColor" placeholder="ej: text-info">
          </div>
          <div class="form-group">
            <label>Padre</label>
            <select class="form-control" id="editParent"></select>
          </div>
          <div class="form-group">
            <label>Orden</label>
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
        <button type="button" class="btn btn-danger" onclick="eliminarItem()">Eliminar</button>
        <button type="button" class="btn btn-primary" onclick="guardarItem()">Guardar</button>
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
</style>

<script>
let allMenuItems = [];

function recargarMenu() {
  $.get('ctrl/ctrl_menu.php?action=getAll', function(res) {
    console.log('Response:', res);
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
        <div class="menu-item-content" onclick="abrirEdit(event, ${item.id})">
          <i class="${item.icon}" style="margin-right: 10px;"></i> 
          <strong>${item.label}</strong> 
          <code style="color: #999; font-size: 11px;">${item.route}</code>
          ${item.color_class ? '<span class="badge badge-info" style="margin-left: 10px;">'+item.color_class+'</span>' : ''}
        </div>
        <div class="menu-item-actions">
          <button class="btn btn-sm btn-warning" onclick="abrirEdit(event, ${item.id})">
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

function abrirEdit(e, id) {
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
  
  // Cargar padres
  let parentHtml = '<option value="">Ninguno</option>';
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
  
  $('#editModal').modal('show');
}

function guardarItem() {
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
      $('#editModal').modal('hide');
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

$(document).ready(function() {
  recargarMenu();
});
</script>

<?php
include("includes/footer.php");
?>

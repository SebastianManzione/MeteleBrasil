# 🔐 Sistema de Administración de Roles y Permisos - MeteleBrasil

## 📋 Descripción General

Sistema completo para gestionar roles de usuario y sus permisos de acceso al menú del panel administrativo. Permite crear, editar y asignar permisos de forma visual sin necesidad de modificar código o ejecutar scripts SQL.

---

## 🏗️ Arquitectura del Sistema

### Base de Datos

#### Tabla: `roles`
```sql
- idRol (INT): ID del rol
- rol (VARCHAR 80): Nombre del rol
- descripcion (VARCHAR 80): Descripción del rol
```

**Roles actuales:**
- **ID 0**: Usuario (cliente final, sin acceso al admin)
- **ID 1**: Administrador (acceso completo al sistema)
- **ID 2**: Prestador (proveedor de servicios)
- **ID 3**: Agente (intermediario)
- **ID 5**: Vendedor (personal de ventas)

#### Tabla: `admin_menu`
```sql
- id (INT): ID del ítem del menú
- label (VARCHAR): Etiqueta visible
- route (VARCHAR): Archivo/ruta PHP
- icon (VARCHAR): Clase Font Awesome
- parent_id (INT): ID del padre (NULL = raíz)
- sort_order (INT): Orden de visualización
- enabled (TINYINT): 1=activo, 0=inactivo
```

**Ítems actuales:** 37 (36 heredados + 1 nuevo "Roles y Permisos")

#### Tabla: `admin_menu_roles`
```sql
- id (INT AUTO_INCREMENT): ID único
- menu_id (INT): FK a admin_menu
- role_id (INT): FK a roles
```

**Distribución de permisos:**
- Administrador (1): 37 permisos (todos)
- Prestador (2): 6 permisos
- Agente (3): 3 permisos
- Usuario (0) y Vendedor (5): 0 permisos

---

## 🚀 Acceso al Sistema

### URL
```
http://localhost/metelebrasil_dev/admin/rolesPermisos.php
```

### Requisitos
- Sesión activa con `$_SESSION['login']['rol'] = 1` (Administrador)
- Si no eres Admin, serás redirigido a `index.php`

### Navegación
El enlace "Roles y Permisos" aparece en el menú lateral del admin (sección "Administración" o como ítem raíz).

---

## 📱 Interfaz de Usuario

### Pestaña 1: Roles

#### Vista Principal
- **Tabla de Roles:** Lista todos los roles con ID, Nombre, Descripción
- **Botón "Nuevo Rol":** Abre modal para crear un rol
- **Botones por fila:**
  - ✏️ **Editar:** Abre modal con datos del rol
  - 🗑️ **Eliminar:** Borra el rol (con confirmación)

#### Modal de Rol
**Campos:**
- **ID del Rol:** Número único (manual, puede coincidir con IDs existentes para UPDATE)
- **Nombre:** Ej. "Supervisor", "Auditor"
- **Descripción:** Detalle del rol

**Acciones:**
- **Guardar:** Inserta o actualiza (ON DUPLICATE KEY UPDATE)
- **Cancelar:** Cierra modal sin cambios

### Pestaña 2: Permisos de Menú

#### Selector de Rol
Dropdown con todos los roles. Al seleccionar uno, carga los permisos actuales.

#### Lista de Permisos
- **Checkboxes jerárquicos:** 
  - Padres con indentación 0
  - Hijos con indentación izquierda
- **Icono + Label:** Muestra el nombre e ícono del menú
- **Estado:** Marcado = rol tiene permiso

#### Botón Guardar Permisos
- Elimina todos los permisos anteriores del rol
- Inserta los nuevos permisos seleccionados
- Muestra mensaje de éxito/error

---

## ⚙️ Backend: Controlador `ctrl/ctrl_roles.php`

### Endpoints (GET/POST `?action=`)

#### 1. `getRoles`
**Descripción:** Lista todos los roles  
**Método:** GET  
**Respuesta:**
```json
{
  "success": true,
  "data": [
    {"idRol": 1, "rol": "Administrador", "descripcion": "Acceso completo"},
    ...
  ]
}
```

#### 2. `getRol`
**Descripción:** Obtiene un rol por ID  
**Método:** GET  
**Parámetros:** `id=1`  
**Respuesta:**
```json
{
  "success": true,
  "data": {"idRol": 1, "rol": "Administrador", "descripcion": "..."}
}
```

#### 3. `saveRol`
**Descripción:** Inserta o actualiza un rol  
**Método:** POST  
**Parámetros:**
```
idRol=6
rol=Supervisor
descripcion=Supervisor de operaciones
```
**SQL:**
```sql
INSERT INTO roles (idRol, rol, descripcion) 
VALUES (?, ?, ?) 
ON DUPLICATE KEY UPDATE rol=VALUES(rol), descripcion=VALUES(descripcion)
```
**Respuesta:**
```json
{"success": true, "message": "Rol guardado"}
```

#### 4. `getPermisosRol`
**Descripción:** Lista los menu_id asignados a un rol  
**Método:** GET  
**Parámetros:** `roleId=1`  
**Respuesta:**
```json
{
  "success": true,
  "data": [1, 2, 3, 4, 5, ..., 37]
}
```

#### 5. `savePermisosRol`
**Descripción:** Reemplaza los permisos de un rol  
**Método:** POST  
**Parámetros:**
```
roleId=3
menuIds[]=1
menuIds[]=5
menuIds[]=12
```
**Lógica:**
1. `DELETE FROM admin_menu_roles WHERE role_id = ?`
2. `INSERT INTO admin_menu_roles (menu_id, role_id) VALUES (?, ?)`  (por cada menuId)

**Respuesta:**
```json
{"success": true, "message": "Permisos actualizados"}
```

### Seguridad
- Verifica `$_SESSION['login']['rol'] == 1` en todas las acciones
- Retorna `403 Acceso denegado` si no es Admin
- Usa PDO prepared statements contra SQL injection

---

## 🔧 Funciones JavaScript (rolesPermisos.php)

### Gestión de Roles

#### `cargarRoles()`
- Llama a `ctrl_roles.php?action=getRoles`
- Puebla DataTable con botones de acción por fila
- Maneja errores con toast/alert

#### `abrirModalRol(nuevoRol = true)`
- Limpia el formulario si `nuevoRol == true`
- Muestra el modal `#modalRol`

#### `editarRol(idRol)`
- Llama a `ctrl_roles.php?action=getRol&id=${idRol}`
- Rellena el formulario con datos del rol
- Abre el modal en modo edición

#### `guardarRol()`
- Valida campos (idRol, nombre)
- Envía POST a `ctrl_roles.php?action=saveRol`
- Recarga tabla de roles
- Cierra modal

#### `eliminarRol(idRol)`
- **PENDIENTE:** Implementar acción `deleteRol` en backend
- Requiere confirmación (ej. SweetAlert2)
- Verificar que no haya usuarios con ese rol

### Gestión de Permisos

#### `cargarRolesSelect()`
- Puebla el `<select id="selRol">` con roles
- Se ejecuta al cargar la pestaña Permisos

#### `cargarPermisosRol()`
- Obtiene `roleId` del select
- Llama a `getPermisosRol` y `renderMenuPermisos()`
- Muestra el contenedor de permisos

#### `renderMenuPermisos(todosLosMenus, permisosActuales)`
- Crea checkboxes jerárquicos (padres e hijos)
- Marca como checked los permisos actuales
- Indenta visualmente los hijos

#### `guardarPermisos()`
- Recolecta todos los checkboxes marcados
- Envía POST a `savePermisosRol` con `menuIds[]`
- Muestra mensaje de éxito

---

## 📊 Flujo de Trabajo Típico

### Caso 1: Crear un nuevo rol "Auditor"
1. Ir a pestaña **Roles**
2. Click en **Nuevo Rol**
3. Ingresar:
   - ID: `6`
   - Nombre: `Auditor`
   - Descripción: `Auditor financiero con acceso solo a reportes`
4. Click **Guardar**
5. Ir a pestaña **Permisos de Menú**
6. Seleccionar "Auditor" en el dropdown
7. Marcar checkboxes: `Home`, `Financiero` (padre), `Lista Financiero` (hijo)
8. Click **Guardar Permisos**
9. Resultado: Usuario con rol 6 solo verá esos 3 ítems en el menú

### Caso 2: Editar permisos de "Prestador"
1. Ir a pestaña **Permisos de Menú**
2. Seleccionar "Prestador" (ID 2)
3. Sistema carga permisos actuales (6 ítems marcados)
4. Desmarcar/marcar ítems según necesidad
5. Click **Guardar Permisos**
6. Prestadores verán menú actualizado en próximo login

### Caso 3: Modificar descripción de rol existente
1. Ir a pestaña **Roles**
2. Click en ✏️ del rol "Vendedor"
3. Cambiar descripción a "Personal de ventas y atención al cliente"
4. Click **Guardar**
5. Cambio reflejado en la tabla

---

## 🧪 Testing y Verificación

### Script de Verificación
```bash
php admin/verificar_roles_permisos.php
```

**Output esperado:**
```
=== VERIFICACIÓN DEL SISTEMA DE ROLES Y PERMISOS ===
✓ Conexión a base de datos: OK

Roles en sistema: 5
Roles existentes:
  ID 0: Usuario - Usuario
  ID 1: Administrador - Administrador del sistema
  ID 2: Prestador - Prestador de servicios
  ID 3: Agente - Agente
  ID 5: Vendedor -

Menús activos: 36
Asignaciones de permisos: 51

Distribución de permisos:
  Usuario: 0 permisos
  Administrador: 36 permisos
  Prestador: 6 permisos
  Agente: 3 permisos
  Vendedor: 0 permisos

✓ Sistema listo para usar en rolesPermisos.php
```

### Verificar Permisos en el Menú
1. Iniciar sesión como usuario con rol específico
2. Verificar que el menú lateral solo muestre ítems autorizados
3. Intentar acceder manualmente a una ruta sin permiso (debe redirigir o denegar)

### SQL para Consultar Permisos
```sql
-- Ver permisos de un rol
SELECT am.label, am.route 
FROM admin_menu am
INNER JOIN admin_menu_roles amr ON am.id = amr.menu_id
WHERE amr.role_id = 2
ORDER BY am.parent_id, am.sort_order;

-- Roles sin permisos asignados
SELECT r.* FROM roles r
LEFT JOIN admin_menu_roles amr ON r.idRol = amr.role_id
WHERE amr.id IS NULL;
```

---

## 🚨 Consideraciones Importantes

### Rol Administrador (ID 1)
- **Nunca quitar permisos** del Administrador
- Siempre debe tener acceso a `rolesPermisos.php`
- Al agregar nuevos ítems al menú, asignar permiso a Admin automáticamente

### Roles con Campos Especiales
- **Prestador:** Usuario con `$_SESSION['login']['idPrestador'] > 0`
- **Vendedor:** Usuario con `$_SESSION['login']['idVendedor'] > 0`
- **Cobrador:** Usuario con `$_SESSION['login']['idCobrador'] > 0`
- La lógica en `admin/classes/menu.php` detecta estos campos y asigna permisos automáticamente

### Herencia de Permisos
Si un usuario tiene:
```php
$_SESSION['login'] = [
  'rol' => 0,
  'idPrestador' => 15,
  'idVendedor' => 0,
  'idCobrador' => 0
]
```
El sistema le asigna permisos del rol **Prestador (2)** aunque su `rol` sea 0.

### Menús Deshabilitados
- Ítems con `enabled = 0` no aparecen en el sidebar
- Los permisos siguen en `admin_menu_roles` pero no se aplican

### Eliminación de Roles
- **NO implementado** por seguridad
- Si se necesita, verificar que no haya usuarios con ese rol:
  ```sql
  SELECT COUNT(*) FROM usuario WHERE rol = ?
  ```

---

## 🔐 Seguridad

### Control de Acceso
- **Página:** `rolesPermisos.php` verifica `$_SESSION['login']['rol'] == 1`
- **Backend:** `ctrl_roles.php` verifica rol Admin en cada endpoint

### Recomendaciones
1. **HTTPS:** Usar en producción para proteger datos de sesión
2. **CSRF Tokens:** Implementar tokens en formularios (futuro)
3. **Logs de Auditoría:** Registrar cambios de permisos (futuro)
4. **Rate Limiting:** Limitar intentos de acceso no autorizado

---

## 📚 Archivos del Sistema

```
admin/
├── rolesPermisos.php             # Interfaz principal
├── ctrl/
│   └── ctrl_roles.php           # Controlador AJAX
├── classes/
│   ├── menu.php                 # Lógica de menú y permisos
│   └── conexion.php             # Conexión PDO
├── includes/
│   ├── header.php               # Header HTML
│   ├── navbar.php               # Barra superior
│   └── sidebar_db.php           # Menú lateral dinámico
├── verificar_roles_permisos.php  # Script de diagnóstico
└── agregar_menu_roles.php       # Script de instalación
```

---

## 🛠️ Mantenimiento

### Agregar un Nuevo Ítem al Menú
1. Insertar en `admin_menu`:
   ```sql
   INSERT INTO admin_menu (label, route, icon, parent_id, sort_order, enabled)
   VALUES ('Reportes', 'reportes', 'fas fa-chart-bar', NULL, 100, 1);
   ```
2. Asignar permiso a Admin:
   ```sql
   INSERT INTO admin_menu_roles (menu_id, role_id)
   VALUES (LAST_INSERT_ID(), 1);
   ```
3. Ir a `rolesPermisos.php` → Permisos → Asignar a otros roles

### Resetear Permisos a Estado Original
```bash
php admin/assign_role_permissions.php
```
Esto restaura:
- Admin: 36 permisos
- Vendedor: 6 permisos
- Prestador: 3 permisos
- Cobrador: 6 permisos

---

## 📞 Soporte

Para problemas o dudas:
1. Revisar logs de PHP: `logs/php_error.log`
2. Verificar estado con `verificar_roles_permisos.php`
3. Consultar estructura de BD:
   ```sql
   DESCRIBE roles;
   DESCRIBE admin_menu;
   DESCRIBE admin_menu_roles;
   ```

---

## ✅ Estado Actual

- ✅ Backend completo (`ctrl_roles.php`)
- ✅ Frontend completo (`rolesPermisos.php`)
- ✅ Menú agregado al admin (ID 37)
- ✅ 5 roles configurados
- ✅ 51 asignaciones de permisos
- ✅ Sistema de herencia por `idPrestador/idVendedor/idCobrador`
- ⚠️ Falta: Eliminación de roles
- ⚠️ Falta: Logs de auditoría
- ⚠️ Falta: Control de acceso a páginas individuales (solo menú por ahora)

---

**Versión:** 1.0  
**Fecha:** 2024  
**Autor:** Sistema MeteleBrasil

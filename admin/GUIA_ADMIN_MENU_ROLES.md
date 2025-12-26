# 📋 Administración de Menú y Roles - Sistema Unificado

## 🎯 Resumen Ejecutivo

Se ha creado una **página unificada** que consolida toda la administración de:
- **Estructura del Menú** (crear, editar, eliminar elementos)
- **Gestión de Roles** (crear y editar roles de usuario)
- **Asignación de Permisos** (vincular menús a roles)

Todo en una sola interfaz con 3 pestañas intuitivas, eliminando la necesidad de navegar entre `menuEditor.php` y `rolesPermisos.php`.

---

## 🚀 Acceso

**URL:** `http://localhost/metelebrasil_dev/admin/adminMenuRoles.php`

**Requisitos:** Sesión activa como **Administrador** (rol = 1)

**Ubicación en menú:** Aparece en la sección "Administración" del panel admin

---

## 📱 Interfaz de Usuario

### 📑 Pestaña 1: Estructura del Menú

**Función:** Gestionar todos los elementos del menú del admin

#### Vista Principal
- **Tabla jerárquica** con todos los ítems del menú
  - Menús padres destacados en azul
  - Menús hijos indentados 30px a la derecha
  - Estado visual: gris opaco si está deshabilitado

#### Botones de Acción

**Por elemento:**
- ✏️ **Editar:** Abre modal con formulario del menú
- 🗑️ **Eliminar:** Borra el elemento (con confirmación)

**Generales:**
- ➕ **Nuevo Elemento:** Crea un menú desde cero
- 🔄 **Recargar:** Actualiza la lista desde BD

#### Modal de Edición

**Campos disponibles:**
- **Etiqueta:** Nombre visible en el menú (ej. "Dashboard")
- **Ruta:** Archivo PHP o `#` para menús padres (ej. "index" o "#")
- **Icono:** Clase Font Awesome (ej. "fas fa-home")
- **Color CSS:** Clase para estilo (ej. "text-info")
- **Menú Padre:** Selector para hacer este ítem hijo de otro
- **Orden:** Número para ordenar visualización
- **Habilitado:** Checkbox para activar/desactivar

**Acciones del modal:**
- **Guardar:** Inserta o actualiza según corresponda
- **Eliminar:** Solo visible si edita elemento existente
- **Cerrar:** Descarta cambios

---

### 👥 Pestaña 2: Roles

**Función:** Crear y editar definiciones de roles de usuario

#### Vista Principal
- **DataTable** con lista de todos los roles
  - Columnas: ID, Nombre, Descripción, Acciones

#### Roles Actuales
```
ID 0: Usuario (cliente final, sin acceso admin)
ID 1: Administrador (acceso completo)
ID 2: Prestador (proveedor de servicios)
ID 3: Agente (intermediario)
ID 5: Vendedor (personal de ventas)
```

#### Botones

**Por rol:**
- ✏️ **Editar:** Abre modal para modificar datos del rol

**Generales:**
- ➕ **Nuevo Rol:** Crea un rol nuevo

#### Modal de Edición

**Campos:**
- **ID del Rol:** Número único (usar para INSERT/UPDATE)
- **Nombre:** Etiqueta del rol (ej. "Supervisor")
- **Descripción:** Detalle opcional (ej. "Supervisor de operaciones")

---

### 🔐 Pestaña 3: Asignación de Permisos

**Función:** Vincular menús a roles (qué menús puede ver cada rol)

#### Selector de Rol
- Dropdown con todos los roles
- Al seleccionar, carga los permisos actuales del rol

#### Lista de Permisos
- **Checkboxes jerárquicos** para cada menú
  - Menús padres en negrita con fondo azul
  - Menús hijos indentados 30px
- **Ícono + Label** por menú
- **Estado:** Marcado = rol tiene acceso a ese menú

#### Guardar
- **Botón "Guardar Permisos":** 
  - Elimina todos los permisos anteriores del rol
  - Inserta los nuevos (solo los marcados)
  - Muestra confirmación

---

## ⚙️ Backend: Controlador AJAX

**Archivo:** `admin/ctrl/ctrl_roles.php`

Se reutiliza el controlador existente con los siguientes endpoints:

### Menú (ctrl_menu.php)
- `getAll` - Lista todos los menús en estructura jerárquica
- `save` - Inserta o actualiza un menú
- `delete` - Elimina un menú y sus permisos

### Roles (ctrl_roles.php)
- `getRoles` - Lista todos los roles
- `getRol` - Obtiene un rol por ID
- `saveRol` - Inserta o actualiza rol (ON DUPLICATE KEY)
- `getPermisosRol` - Lista IDs de menús permitidos para un rol
- `savePermisosRol` - Reemplaza permisos de un rol

---

## 🗄️ Base de Datos

### Tabla: `admin_menu` (36 elementos activos)
```sql
id | label | route | icon | parent_id | sort_order | enabled
```

### Tabla: `admin_menu_roles` (permisos)
```sql
menu_id | role_id
```

### Tabla: `roles` (5 roles definidos)
```sql
idRol | rol | descripcion
```

### Distribución Actual
```
Administrador: 36 permisos (todos)
Prestador: 5 permisos
Agente: 3 permisos
Usuario/Vendedor: 0 permisos
```

---

## 🔧 Funciones JavaScript

### Menú (TAB 1)

#### `recargarMenu()`
- Carga estructura completa desde `ctrl_menu.php?action=getAll`
- Renderiza árbol jerárquico

#### `renderMenu(tree)`
- Dibuja checkboxes y botones
- Aplica estilos según padre/hijo/estado

#### `abrirModalMenu()` / `abrirEditMenu(id)`
- Abre formulario (nuevo o edición)
- Carga lista de padres disponibles

#### `guardarItemMenu()`
- Valida campos
- POST a `ctrl_menu.php?action=save`
- Recarga árbol si éxito

#### `eliminarItem(id)`
- POST a `ctrl_menu.php?action=delete`
- Confirmación previa

### Roles (TAB 2)

#### `cargarRoles()`
- GET `ctrl_roles.php?action=getRoles`
- Puebla DataTable

#### `abrirModalRol()` / `editarRol(id)`
- Abre modal (nuevo o edición)
- GET `ctrl_roles.php?action=getRol&id=X`

#### `guardarRol()`
- POST `ctrl_roles.php?action=saveRol`
- Recarga tabla y selector

### Permisos (TAB 3)

#### `cargarRolesSelect()`
- Puebla dropdown selector
- Se ejecuta al abrir TAB 3

#### `cargarPermisosRol()`
- GET `ctrl_roles.php?action=getPermisosRol&roleId=X`
- Carga permisos actuales del rol

#### `renderMenuPermisos(items, permisosActuales)`
- Dibuja checkboxes jerárquicos
- Marca items según permisos actuales

#### `guardarPermisos()`
- Recolecta checkboxes marcados
- POST `ctrl_roles.php?action=savePermisosRol`
- Muestra confirmación

---

## 💡 Casos de Uso

### 1. Crear nuevo rol "Supervisor"
1. Pestaña **Roles** → **Nuevo Rol**
2. ID: `6`, Nombre: `Supervisor`, Desc: `Supervisor regional`
3. Guardar
4. Pestaña **Permisos** → Seleccionar "Supervisor"
5. Marcar: Home, Reservas (padre), Estado Reservas (hijo), Financiero
6. Guardar Permisos
7. ✓ Usuarios con rol=6 solo verán esos 4 menús

### 2. Agregar nuevo menú "Auditoría"
1. Pestaña **Menú** → **Nuevo Elemento**
2. Etiqueta: `Auditoría`
3. Ruta: `auditoria`
4. Icono: `fas fa-book`
5. Menú Padre: `Administración`
6. Orden: `150`
7. Guardar
8. Pestaña **Permisos** → Asignar a roles necesarios

### 3. Modificar permisos de Prestador
1. Pestaña **Permisos** → Seleccionar "Prestador"
2. Ver menús actuales marcados (5)
3. Marcar/desmarcar según necesidad
4. Guardar Permisos
5. ✓ Cambio efectivo en próximo login de Prestadores

### 4. Deshabilitar un menú sin eliminarlo
1. Pestaña **Menú** → Editar elemento
2. Desmarcar "Habilitado"
3. Guardar
4. ✓ Menú ocultado pero datos intactos en BD

---

## 🧪 Testing

### Verificación Rápida
```bash
php admin/verificar_sistema_unificado.php
```

### Acceso a URL
```
http://localhost/metelebrasil_dev/admin/adminMenuRoles.php
```

**Expected:** 
- ✓ Carga página con 3 pestañas
- ✓ TAB 1: Muestra 36 menús jerárquicos
- ✓ TAB 2: Muestra 5 roles en tabla
- ✓ TAB 3: Selector de roles funcional

---

## 🔐 Seguridad

- **Verificación de rol:** Solo `rol = 1` (Administrador)
- **PDO prepared statements:** Protección SQL injection
- **Control de sesión:** Verificación de `$_SESSION['login']`

---

## 📋 Scripts Asociados

| Archivo | Propósito |
|---------|-----------|
| `adminMenuRoles.php` | **Página principal unificada** |
| `ctrl/ctrl_menu.php` | AJAX para operaciones de menú |
| `ctrl/ctrl_roles.php` | AJAX para operaciones de roles/permisos |
| `verificar_sistema_unificado.php` | Diagnóstico de estado |
| `unificar_menus.php` | Script de migración (ya ejecutado) |
| `limpiar_menus_antiguos.php` | Script de limpieza (ya ejecutado) |

---

## ⚠️ Notas Importantes

### Cambio de Arquitectura
- ✅ `menuEditor.php` y `rolesPermisos.php` ya **no se usan**
- ✅ Toda la funcionalidad está en `adminMenuRoles.php`
- ✅ Base de datos migrada correctamente

### Herencia de Permisos
La clase `menu.php` considera:
- Usuarios con `idPrestador > 0` → Permisos de Prestador (rol 2)
- Usuarios con `idVendedor > 0` → Permisos de Vendedor (rol 5)
- Usuarios con `idCobrador > 0` → Permisos de Cobrador (rol 4)

Esto significa que un usuario puede tener múltiples permisos según sus campos en `usuario`.

### Rollback
Si necesitas volver a dos páginas separadas:
1. Restaurar backup de BD
2. Restaurar archivos `menuEditor.php` y `rolesPermisos.php`

---

## 🎓 Referencias Rápidas

### Tabla de Permisos por Rol
```
Administrador (1): 36 permisos ✓ TODOS
Prestador (2): 5 permisos (Home, Estado Reservas, Comisiones, etc.)
Agente (3): 3 permisos (Home + 2 específicos)
Usuario (0): 0 permisos (cliente final)
Vendedor (5): 0 permisos (Sin acceso admin aún)
```

### Ruta de Menús Comunes
- Home → `index`
- Prestadores → `#` (padre)
  - Lista → `prestadores`
  - Alta → `prestadoresAlta`
- Servicios → `#` (padre)
  - Lista → `serviciosLista`
  - Alta → `altaServicio`

---

## ✅ Status de Implementación

| Componente | Estado | Detalles |
|-----------|--------|---------|
| Frontend UI | ✅ Completo | 3 pestañas, modales, DataTables |
| Backend APIs | ✅ Funcional | 8 endpoints AJAX |
| BD Migración | ✅ Completado | Datos transferidos, antiguos eliminados |
| Menú Admin | ✅ Activo | ID 38, accesible |
| Testing | ✅ Verificado | Script de diagnóstico OK |
| Documentación | ✅ Incluida | Guía completa |

---

**Versión:** 2.0 Unificada  
**Fecha:** 26 de diciembre de 2025  
**Stack:** PHP + jQuery + AdminLTE3 + Bootstrap 4

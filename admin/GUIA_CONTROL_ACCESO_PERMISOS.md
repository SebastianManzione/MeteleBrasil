# 🔐 Sistema de Control de Acceso por Permisos - Implementación Práctica

## 📌 Visión General

El sistema de permisos configurado en `adminMenuRoles.php` ahora **controla el acceso directo a las páginas** del admin. No solo controla qué aparece en el menú, sino que **bloquea el acceso a URLs** si el usuario no tiene permiso.

---

## ⚡ Componentes

### 1. **Clase PermisosManager** ([admin/classes/permisos.php](admin/classes/permisos.php))
- Gestiona toda la lógica de permisos
- Métodos principales:
  - `tienePermiso($ruta)` - Verifica sin redireccionar
  - `verificarAcceso($ruta, $redirect)` - Verifica y redirige si no tiene permiso
  - `obtenerMenusPermitidos()` - Lista de menús permitidos
  - `obtenerArbolMenusPermitidos()` - Árbol jerárquico de menús

### 2. **Helper de Funciones** ([admin/includes/permisos_helper.php](admin/includes/permisos_helper.php))
- Facilita el uso en las páginas
- Proporciona funciones simples:
  - `verificarPermisoAcceso($ruta)` 
  - `tienePermiso($ruta)`
  - `obtenerUsuarioActual()`
  - `debugRolesUsuario()`

### 3. **Página Protegida Ejemplo** ([admin/altaServicio.php](admin/altaServicio.php))
- Muestra cómo implementar la verificación

---

## 🚀 Implementación Paso a Paso

### Paso 1: Asegurar que la página tiene ruta en el menú

Ir a **adminMenuRoles.php** → **Pestaña: Estructura del Menú**

Verificar que existe un elemento con:
- **Ruta:** `altaServicio` (sin .php)
- **Habilitado:** Sí

⚠️ **IMPORTANTE:** La ruta debe coincidir exactamente sin extensión .php

### Paso 2: Agregar el código de verificación

En la página a proteger (ej. `altaServicio.php`), después de los `include` del header:

```php
<?php
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

// ✅ AGREGAR ESTAS 3 LÍNEAS
require("classes/permisos.php");
require("includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('altaServicio'); // Redirecciona a index si sin permiso

// ... resto del código de la página
```

### Paso 3: Configurar permisos para roles

En **adminMenuRoles.php** → **Pestaña: Asignación de Permisos**

1. Seleccionar un rol (ej. "Prestador")
2. Marcar el checkbox de "Alta Servicio"
3. Guardar Permisos

**Resultado:** Solo Prestadores con permiso podrán acceder a `altaServicio.php`

---

## 📋 Patrones de Implementación

### Patrón 1: Redireccionar si no tiene permiso (Recomendado)

```php
<?php
require("classes/permisos.php");
require("includes/permisos_helper.php");

$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);

// Si no tiene permiso, redirecciona a index automáticamente
$permisos->verificarAcceso('altaServicio');

// El código continúa solo si tiene permiso
echo "Acceso permitido";
?>
```

### Patrón 2: Mostrar mensaje de error

```php
<?php
require("classes/permisos.php");
require("includes/permisos_helper.php");

$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);

if (!$permisos->tienePermiso('altaServicio')) {
    echo "<div class='alert alert-danger'>No tienes permiso para acceder aquí</div>";
    exit;
}

// Continúa si tiene permiso
?>
```

### Patrón 3: Mostrar contenido condicional

```php
<?php
require("classes/permisos.php");
require("includes/permisos_helper.php");

$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);

// Muestra botones solo si tiene permiso
if ($permisos->tienePermiso('altaServicio')) {
    echo "<button>Crear Servicio</button>";
}
?>
```

---

## 🎯 Páginas a Proteger (Ejemplos)

### Servicios
```
serviciosLista.php    → ruta: "serviciosLista"
altaServicio.php      → ruta: "altaServicio"
servicioVer.php       → ruta: "servicioVer"
```

### Salidas
```
salidasLista.php      → ruta: "salidasLista"
altaSalidas.php       → ruta: "altaSalidas"
salidasEditar.php     → ruta: "salidasEditar"
```

### Reservas
```
carritosLista.php     → ruta: "carritosLista"
reservaDetalles.php   → ruta: "reservaDetalles"
```

### Prestadores
```
prestadores.php       → ruta: "prestadores"
prestadoresAlta.php   → ruta: "prestadoresAlta"
```

**Nota:** Las rutas deben existir en la tabla `admin_menu` y estar habilitadas.

---

## 📊 Cómo Funciona Internamente

```
Usuario accede a:
↓
http://localhost/admin/altaServicio.php
↓
altaServicio.php incluye permisos.php
↓
PermisosManager obtiene la ruta actual ("altaServicio")
↓
Busca en admin_menu dónde route = "altaServicio"
↓
Obtiene el menu_id (ej. 12)
↓
Verifica en admin_menu_roles si el rol del usuario tiene menu_id = 12
↓
✓ Si tiene permiso → Continúa en la página
✗ Si NO tiene permiso → Redirecciona a index.php
```

---

## 🧪 Testing y Verificación

### Script de Diagnóstico

Crear archivo `admin/test_permisos.php`:

```php
<?php
require("classes/conexion.php");
require("classes/permisos.php");

// Simular sesión de usuario
$_SESSION['login'] = [
    'rol' => 0,
    'idPrestador' => 1, // Usuario es prestador
    'idVendedor' => 0,
    'idCobrador' => 0,
    'usuario' => 'Test Prestador'
];

$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login']);

echo "=== TEST DE PERMISOS ===\n\n";
echo "Usuario: " . $_SESSION['login']['usuario'] . "\n";
echo "Roles aplicables: " . json_encode($permisos->debugRoles()) . "\n\n";

// Probar rutas
$rutas_test = ['altaServicio', 'serviciosLista', 'prestadores', 'financieroLista'];

foreach ($rutas_test as $ruta) {
    $tiene = $permisos->tienePermiso($ruta);
    echo "Ruta '$ruta': " . ($tiene ? "✓ SÍ" : "✗ NO") . "\n";
}

echo "\n=== FIN TEST ===\n";
?>
```

Ejecutar: `php admin/test_permisos.php`

### Verificación Manual

1. Abrir el navegador como Prestador (usuario con `idPrestador > 0`)
2. Intentar acceder a: `http://localhost/admin/altaServicio.php`
3. Resultados esperados:
   - ✓ Si tiene permiso → Accede a la página
   - ✗ Si NO tiene permiso → Redirecciona a `index.php`

---

## ⚙️ Configuración Avanzada

### Obtener Menús Permitidos del Usuario

```php
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login']);

// Obtener array simple de menu_ids
$menus_permitidos = $permisos->obtenerMenusPermitidos();
echo "IDs permitidos: " . json_encode($menus_permitidos);

// Obtener árbol jerárquico de menús
$arbol = $permisos->obtenerArbolMenusPermitidos();
foreach ($arbol as $menu) {
    echo "- " . $menu['label'] . "\n";
    foreach ($menu['children'] as $hijo) {
        echo "  - " . $hijo['label'] . "\n";
    }
}
```

### Debugging - Ver Roles del Usuario

```php
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login']);
$roles = $permisos->debugRoles();

foreach ($roles as $rol) {
    echo "Rol: " . $rol['rol'] . " - " . $rol['descripcion'] . "\n";
}
```

### Personalizar URL de Redirección

```php
// Redirige a página de acceso denegado personalizada
$permisos->verificarAcceso('altaServicio', '../acceso_denegado.php');

// Redirige a login si no está autenticado
if (empty($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}
```

---

## 🛠️ Script de Automatización

Crear archivo `admin/proteger_todas_paginas.php` (uso manual):

```php
<?php
require("classes/conexion.php");

// Obtener todas las rutas en el menú
$stmt = $GLOBALS['pdo']->query("
    SELECT DISTINCT route FROM admin_menu 
    WHERE route NOT IN ('#', '') AND enabled = 1
");

$rutas = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "=== RUTAS PROTEGIBLES ===\n\n";

foreach ($rutas as $ruta) {
    $archivo = $ruta . ".php";
    
    // Verificar si existe
    if (file_exists($archivo)) {
        echo "✓ $archivo\n";
    } else {
        echo "✗ $archivo (no encontrado)\n";
    }
}

echo "\n=== IMPLEMENTACIÓN MANUAL ===\n";
echo "Para cada archivo, agregar al inicio después de los includes:\n";
echo "require(\"classes/permisos.php\");\n";
echo "require(\"includes/permisos_helper.php\");\n";
echo "\$permisos = new PermisosManager(\$GLOBALS['pdo'], \$_SESSION['login'] ?? []);\n";
echo "\$permisos->verificarAcceso('RUTA_AQUI');\n";
?>
```

---

## 📝 Checklist de Implementación

Para cada página a proteger:

- [ ] Crear/verificar entrada en tabla `admin_menu` con `route = nombre_pagina`
- [ ] Asegurar que `enabled = 1`
- [ ] Agregar verificación de permisos en la página
- [ ] Asignar permisos a roles en `adminMenuRoles.php`
- [ ] Probar acceso como usuario con y sin permisos
- [ ] Verificar que redirecciona correctamente si no tiene permiso

---

## 🔐 Consideraciones de Seguridad

### ✅ Implementado
- Verificación de rol en cada página
- Caché de permisos para evitar queries repetidas
- Logging de intentos no autorizados (opcional)
- Support para múltiples roles por usuario

### ⚠️ Recomendaciones
- Usar HTTPS en producción
- Validar tokens CSRF en formularios
- Log de cambios de permisos en adminMenuRoles
- Auditoría de accesos sensibles
- Resetear permisos en caché cuando se cambian en BD

### 🚫 Lo que NO hace
- No valida permisos a nivel de registro (solo rutas)
- No valida permisos en AJAX calls (debe hacerse en controladores)
- No expira sesiones automáticamente

---

## 📚 Archivos del Sistema

```
admin/
├── classes/
│   └── permisos.php               # Clase PermisosManager
├── includes/
│   └── permisos_helper.php        # Helper de funciones
├── adminMenuRoles.php             # Configurar permisos aquí
├── altaServicio.php               # Ejemplo implementado ✓
└── [otras páginas a proteger]
```

---

## 🎓 Flujo Completo

### 1. Crear Menú
Admin → `adminMenuRoles.php` → TAB "Estructura del Menú" → Nuevo Elemento
- Route: `altaServicio`

### 2. Crear Rol
Admin → `adminMenuRoles.php` → TAB "Roles" → Nuevo Rol
- ID: `6`, Nombre: `Supervisor`

### 3. Asignar Permisos
Admin → `adminMenuRoles.php` → TAB "Permisos" → Selector rol → Marcar menús

### 4. Proteger Página
Editar `admin/altaServicio.php` → Agregar 3 líneas de verificación

### 5. Verificar
Usuario Supervisor intenta acceder a `altaServicio.php`:
- ✓ Si tiene permiso → Accede
- ✗ Si NO tiene permiso → Redirecciona

---

## ✅ Status

| Componente | Estado | Detalles |
|-----------|--------|---------|
| PermisosManager | ✅ Completo | Clase funcionando |
| Helper Functions | ✅ Listo | Funciones disponibles |
| altaServicio.php | ✅ Ejemplo | Implementación demostrada |
| adminMenuRoles.php | ✅ Configura | Gestión de permisos |
| Documentación | ✅ Incluida | Esta guía |

---

**Versión:** 1.0  
**Fecha:** 26 de diciembre de 2025  
**Sistema:** MeteleBrasil

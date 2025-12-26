# ✅ Sistema de Configuración y Mantenimiento - MeteleBrasil

## 📦 Qué Se Implementó

He creado un **sistema completo de configuración centralizada** compatible con AdminLTE3 para MeteleBrasil con:

### 🎛️ **Gestión de Credenciales**
- 🔐 Google OAuth 2.0
- 🔐 Facebook OAuth 2.0  
- 🔐 reCAPTCHA v3
- 🔐 Email SMTP
- 🔐 Stripe API Keys
- 📱 Información general del sitio

### 🛠️ **Modo Mantenimiento**
- Activar/desactivar con un click
- Mensaje personalizado
- Página profesional para usuarios
- Auto-reload cada 30 segundos
- Solo admins ven el sitio en mantenimiento

### 📊 **Dashboard Widget**
- Estado de todas las integraciones
- Barra de progreso de configuración
- Enlaces rápidos para configurar
- Alerta si mantenimiento está activo

---

## 📂 Archivos Creados

```
admin/
├── classes/
│   ├── configuracion.php              ← Clase principal (OOP)
│   ├── middleware_mantenimiento.php   ← Verificador de mantenimiento
│   └── check_maintenance.php          ← Endpoint AJAX
├── configuracion.php                  ← Interfaz AdminLTE3
├── widgets/
│   └── widget_configuracion.php       ← Widget para dashboard
└── scripts/
    └── init_configuracion.sql        ← Script SQL inicial

root/
├── mantenimiento.php                  ← Página pública de mantenimiento
├── CONFIGURACION_README.md            ← Documentación completa
└── INTEGRACION_CONFIGURACION.md      ← Guía de integración

```

---

## 🚀 Instalación en 3 Pasos

### 1️⃣ **Crear Tabla en BD**

```sql
CREATE TABLE IF NOT EXISTS configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) UNIQUE NOT NULL,
    valor LONGTEXT,
    tipo ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    descripcion TEXT,
    visible_admin BOOLEAN DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_clave (clave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 2️⃣ **Modificar `includes/navbar.php`**

Después de `session_start();`, agregar:

```php
<?php
session_start();

// Verificar mantenimiento
require_once(__DIR__ . '/../admin/classes/middleware_mantenimiento.php');

// resto del código...
```

### 3️⃣ **Agregar en Sidebar Admin**

En `admin/includes/sidebar.php`, agregar opción en el menú:

```html
<li class="nav-item">
    <a href="configuracion.php" class="nav-link">
        <i class="fas fa-cog nav-icon"></i>
        <p>Configuración</p>
    </a>
</li>
```

**¡Listo!** Accede a: `http://localhost/metelebrasil_dev/admin/configuracion.php`

---

## 🎯 Características Principales

### 📋 Interfaz AdminLTE3

```
┌─────────────────────────────────────────────┐
│ 🎛️ Configuración del Sistema                 │
├─────────────────────────────────────────────┤
│ ✅ Información General                       │
│   - Nombre del sitio                        │
│   - Email principal                         │
│   - Teléfono                                │
├─────────────────────────────────────────────┤
│ 🔐 Google OAuth 2.0                         │
│   - Client ID                               │
│   - Client Secret                           │
│   + Instrucciones de obtención              │
├─────────────────────────────────────────────┤
│ 🔐 Facebook OAuth                           │
│   - App ID                                  │
│   - App Secret                              │
│   + Instrucciones de obtención              │
├─────────────────────────────────────────────┤
│ 🛡️ reCAPTCHA v3                             │
│   - Site Key                                │
│   - Secret Key                              │
│   + Instrucciones                           │
├─────────────────────────────────────────────┤
│ 📧 Email SMTP                               │
│   - Host                                    │
│   - Puerto                                  │
│   - Usuario/Contraseña                      │
│   + Ejemplo Gmail                           │
├─────────────────────────────────────────────┤
│ 💳 Stripe                                   │
│   - Public Key                              │
│   - Secret Key                              │
├─────────────────────────────────────────────┤
│ 🔧 Modo Mantenimiento                       │
│   [✓] Activar Mantenimiento                 │
│   [Mensaje personalizado]                   │
├─────────────────────────────────────────────┤
│ [💾 Guardar Configuración] [Cancelar]       │
└─────────────────────────────────────────────┘
```

### 🛡️ Página de Mantenimiento

```
┌──────────────────────────────┐
│                              │
│    🔧 (girando)              │
│                              │
│   MeteleBrasil               │
│                              │
│   El sitio está en           │
│   mantenimiento.             │
│   Intenta más tarde.         │
│                              │
│   📧 info@metelebrasil.com   │
│                              │
│   Verificando... 14:32:45    │
│                              │
└──────────────────────────────┘
```

---

## 💻 Cómo Usar en Tu Código

### Obtener Configuración

```php
<?php
require_once('admin/classes/configuracion.php');
$config = new Configuracion();

$email = $config->obtener('sitio_email');
$nombre = $config->obtener('sitio_nombre', 'Default');
?>
```

### Guardar Configuración

```php
<?php
$config->guardar('google_client_id', 'xxx.apps.googleusercontent.com');
$config->guardar('smtp_port', 587, 'number');
$config->guardar('mantenimiento_activo', true, 'boolean');
?>
```

### Verificar Mantenimiento

```php
<?php
if ($config->mantenimientoActivo()) {
    header('Location: mantenimiento.php');
    exit;
}
?>
```

---

## 🎨 Widget en Dashboard

Opcional: Para mostrar estado en el dashboard de admin:

```php
<?php
// En admin/index.php, en la sección de widgets
include('widgets/widget_configuracion.php');
?>
```

Muestra:
- ✅/❌ Estado de cada integración
- 📊 Barra de progreso
- ⚠️ Alerta si mantenimiento activo
- 🔗 Enlaces para configurar

---

## 📊 Métodos Disponibles

### `obtener($clave, $default = null)`
```php
$email = $config->obtener('sitio_email');
$port = $config->obtener('smtp_port', 587);
```

### `guardar($clave, $valor, $tipo = 'string', $descripcion = '')`
```php
$config->guardar('google_client_id', 'xxx.apps.googleusercontent.com');
$config->guardar('smtp_port', 587, 'number');
```

### `obtenerTodas($visibles_solo = true)`
```php
$todas = $config->obtenerTodas();
```

### `eliminar($clave)`
```php
$config->eliminar('test_clave');
```

### `mantenimientoActivo()`
```php
if ($config->mantenimientoActivo()) { ... }
```

### `obtenerMensajeMantenimiento()`
```php
$msg = $config->obtenerMensajeMantenimiento();
```

---

## 🔐 Credenciales Preconfiguradas

Para testing, ya incluye **TEST KEYS** de reCAPTCHA:
- Site Key: `6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI`
- Secret Key: `6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe`

Para producción, obtener claves reales en: https://www.google.com/recaptcha/admin

---

## 🔄 Flujo de Mantenimiento

```
Usuario accede al sitio
        ↓
navbar.php incluye middleware
        ↓
middleware verifica mantenimiento
        ↓
   ¿Está activo?
    ↙        ↘
   No        Sí
   ↓          ↓
  OK      ¿Es admin?
         ↙        ↘
        No        Sí
        ↓         ↓
   mantenimiento.php  Sitio normal
        ↓
   Página profesional
   + Email de contacto
   + Verificación cada 30s
```

---

## 📚 Documentación

Para **guías completas** de obtención de credenciales:
- Ver `CONFIGURACION_README.md`
- Ver `INTEGRACION_CONFIGURACION.md`

Incluye instrucciones paso a paso para:
- ✅ Google OAuth
- ✅ Facebook OAuth
- ✅ reCAPTCHA v3
- ✅ Email SMTP (Gmail, SendGrid)
- ✅ Stripe

---

## 🆘 Integración Rápida

```bash
# 1. Copiar todo a tu proyecto ✓ (ya hecho)

# 2. Ejecutar SQL
# mysql -u root -p metelebrasil < admin/scripts/init_configuracion.sql

# 3. Editar includes/navbar.php
# Agregar: require_once(__DIR__ . '/../admin/classes/middleware_mantenimiento.php');

# 4. Editar admin/includes/sidebar.php
# Agregar opción de configuración al menú

# 5. Acceder
# http://localhost/metelebrasil_dev/admin/configuracion.php
```

---

## ✨ Lo Que Puedes Hacer Ahora

1. **Ir a configuración:** `/admin/configuracion.php`
2. **Completar información:** Nombre, email, teléfono
3. **Agregar Google OAuth:** Client ID y Secret
4. **Agregar Facebook OAuth:** App ID y Secret
5. **Configurar Email SMTP:** Para envíos automáticos
6. **Configurar Stripe:** Para pagos
7. **Activar mantenimiento:** Cuando sea necesario

---

## 🎁 Bonus

### Dashboard Widget (Opcional)

Si quieres mostrar estado en dashboard:
```php
<?php include('widgets/widget_configuracion.php'); ?>
```

Muestra barra de progreso y estado de todas las integraciones.

### SQL de Inicialización

Ya incluido en: `admin/scripts/init_configuracion.sql`

Ejecutar:
```bash
mysql -u root metelebrasil < admin/scripts/init_configuracion.sql
```

---

## 📝 Checklist Final

- [ ] Crear tabla `configuracion` en BD
- [ ] Agregar middleware en `navbar.php`
- [ ] Agregar opción en sidebar
- [ ] Acceder a `/admin/configuracion.php`
- [ ] Completar información general
- [ ] Obtener y configurar Google OAuth
- [ ] Obtener y configurar Facebook OAuth
- [ ] Obtener y configurar reCAPTCHA (actualizar de test)
- [ ] Configurar Email SMTP
- [ ] Configurar Stripe
- [ ] Probar modo mantenimiento
- [ ] Incluir widget en dashboard (opcional)

---

## 🎉 ¡Listo!

El sistema está **completamente implementado y listo para usar**. 

**Próximo paso:** Integrar los 3 cambios en tu código y acceder a la configuración.


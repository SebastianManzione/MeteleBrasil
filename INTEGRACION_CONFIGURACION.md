# 🔧 Guía de Integración - Sistema de Configuración

## 📌 Resumen

He creado un sistema completo de configuración centralizada para MeteleBrasil. Esta guía te muestra cómo integrarlo en 3 pasos.

---

## ⚡ Instalación Rápida (5 minutos)

### Paso 1: Crear Tabla de BD

Ejecuta este SQL en tu BD:

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

-- Insertar valores iniciales
INSERT INTO configuracion (clave, valor, tipo, descripcion, visible_admin) VALUES
('sitio_nombre', 'MeteleBrasil', 'string', 'Nombre del sitio web', 1),
('sitio_email', '', 'string', 'Email principal del sitio', 1),
('sitio_telefono', '', 'string', 'Teléfono principal de contacto', 1),
('google_client_id', '', 'string', 'Google OAuth 2.0 Client ID', 1),
('google_client_secret', '', 'string', 'Google OAuth 2.0 Client Secret', 1),
('facebook_app_id', '', 'string', 'Facebook App ID', 1),
('facebook_app_secret', '', 'string', 'Facebook App Secret', 1),
('recaptcha_site_key', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', 'string', 'reCAPTCHA v3 Site Key', 1),
('recaptcha_secret_key', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe', 'string', 'reCAPTCHA v3 Secret Key', 1),
('smtp_host', '', 'string', 'SMTP Host', 1),
('smtp_port', '587', 'number', 'SMTP Port', 1),
('smtp_usuario', '', 'string', 'SMTP Username', 1),
('smtp_password', '', 'string', 'SMTP Password', 1),
('stripe_public_key', '', 'string', 'Stripe Publishable Key', 1),
('stripe_secret_key', '', 'string', 'Stripe Secret Key', 1),
('mantenimiento_activo', '0', 'boolean', 'Modo mantenimiento', 1),
('mantenimiento_mensaje', 'El sitio está en mantenimiento...', 'string', 'Mensaje mantenimiento', 1);
```

### Paso 2: Agregar Middleware en navbar.php

En `includes/navbar.php`, **después de `session_start();`**, agregar:

```php
<?php
session_start();

// Verificar si sitio está en mantenimiento
require_once(__DIR__ . '/../admin/classes/middleware_mantenimiento.php');

// resto del código...
```

### Paso 3: Agregar Opción en Sidebar

En `admin/includes/sidebar.php`, agregar en el menú (al final de opciones):

```html
<!-- Configuración (solo admin) -->
<li class="nav-item">
    <a href="configuracion.php" class="nav-link">
        <i class="fas fa-cog nav-icon"></i>
        <p>Configuración</p>
    </a>
</li>
```

**¡Listo!** Ya puedes acceder a:
- 🎛️ http://localhost/metelebrasil_dev/admin/configuracion.php

---

## 📚 Archivos Incluidos

| Archivo | Descripción |
|---------|-----------|
| `admin/classes/configuracion.php` | Clase principal (OOP) |
| `admin/configuracion.php` | Interfaz AdminLTE3 |
| `admin/classes/middleware_mantenimiento.php` | Middleware para verificar mantenimiento |
| `admin/classes/check_maintenance.php` | Endpoint AJAX |
| `mantenimiento.php` | Página pública de mantenimiento |
| `admin/widgets/widget_configuracion.php` | Widget para dashboard |
| `CONFIGURACION_README.md` | Documentación completa |

---

## 🎯 Cómo Usar en Tu Código

### Obtener una Configuración

```php
<?php
require_once('admin/classes/configuracion.php');
$config = new Configuracion();

// Forma simple
$email = $config->obtener('sitio_email');
$nombre = $config->obtener('sitio_nombre', 'Sitio Sin Nombre'); // con fallback

// En tu código
mail($email, 'Asunto', 'Contenido');
?>
```

### Guardar una Configuración

```php
<?php
$config = new Configuracion();

// Guardar strings
$config->guardar('sitio_email', 'nuevo@email.com');

// Guardar números
$config->guardar('smtp_port', 587, 'number');

// Guardar booleanos
$config->guardar('mantenimiento_activo', true, 'boolean');

// Guardar JSON
$config->guardar('servicios_activos', ['id1', 'id2'], 'json');
?>
```

### Verificar Mantenimiento

```php
<?php
$config = new Configuracion();

if ($config->mantenimientoActivo()) {
    echo $config->obtenerMensajeMantenimiento();
    exit;
}
?>
```

---

## 🔐 Credenciales Preconfiguradas

### reCAPTCHA (TEST)
- **Site Key:** `6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI`
- **Secret Key:** `6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe`
- **Uso:** Funcionan en localhost. Para producción, obtener claves reales.

---

## 🎨 Widget para Dashboard

Para mostrar estado de configuración en `admin/index.php`, agregar:

```php
<?php
// Después de incluir includes
include('widgets/widget_configuracion.php');
?>

<!-- En el HTML, donde quieras mostrar el widget -->
<div class="row">
    <!-- Otros widgets... -->
    
    <!-- Widget de Configuración -->
    <?php include('widgets/widget_configuracion.php'); ?>
</div>
```

El widget mostrará:
- ✅ Estado de cada integración (Google, Facebook, reCAPTCHA, SMTP, Stripe)
- 📊 Barra de progreso
- ⚠️ Alerta si mantenimiento está activo
- 🔗 Enlaces rápidos para configurar

---

## 🚨 Modo Mantenimiento

### Activar

1. Ir a: `http://localhost/metelebrasil_dev/admin/configuracion.php`
2. Bajar a "Modo Mantenimiento"
3. Marcar checkbox "Activar Mantenimiento"
4. Escribir mensaje personalizado
5. Click "Guardar Configuración"

### Resultado

- **Admin (rol 0):** Ve el sitio normalmente
- **Otros usuarios:** Redirigidos a página de mantenimiento
- **Sin login:** Ven página de mantenimiento

### Página de Mantenimiento

- **URL:** `http://localhost/metelebrasil_dev/mantenimiento.php`
- **Estilo:** Profesional, responsive, animado
- **Auto-reload:** Cada 30 segundos comprueba si terminó el mantenimiento

---

## 📝 Ejemplos de Integración Real

### Email SMTP Dinámico

```php
<?php
// En tu función de envío de emails
require_once('admin/classes/configuracion.php');

function enviarEmail($para, $asunto, $cuerpo) {
    $config = new Configuracion();
    
    // Obtener config de SMTP
    $smtp_host = $config->obtener('smtp_host');
    $smtp_port = $config->obtener('smtp_port', 587);
    $smtp_user = $config->obtener('smtp_usuario');
    $smtp_pass = $config->obtener('smtp_password');
    
    // Usar con PHPMailer
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = $smtp_host;
    $mail->Port = $smtp_port;
    $mail->SMTPAuth = true;
    $mail->Username = $smtp_user;
    $mail->Password = $smtp_pass;
    
    // ... resto de config
    return $mail->send();
}
?>
```

### Google OAuth Dinámico

```php
<?php
require_once('admin/classes/configuracion.php');

$config = new Configuracion();

$client_id = $config->obtener('google_client_id');
$client_secret = $config->obtener('google_client_secret');

if (!$client_id || !$client_secret) {
    die('Google OAuth no configurado. Ir a admin/configuracion.php');
}

// Usar en cliente Google
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
// ...
?>
```

### Verificar Stripe

```php
<?php
require_once('admin/classes/configuracion.php');

$config = new Configuracion();

$stripe_key = $config->obtener('stripe_secret_key');

if (empty($stripe_key)) {
    echo 'Stripe no configurado';
    return;
}

// Configurar Stripe
\Stripe\Stripe::setApiKey($stripe_key);
?>
```

---

## 🔒 Seguridad

✅ **Credenciales protegidas:**
- Se almacenan en BD local
- Mostradas como `password` en formularios
- Solo admin puede ver/editar
- No se registran en logs

✅ **Acceso restringido:**
- Solo rol 0 (admin) puede acceder a configuración.php
- Middleware previene acceso no autenticado

✅ **Validaciones:**
- Campos requeridos según tipo
- Validación de emails
- Protección CSRF (si usas formularios estándar)

---

## 🧪 Testing

### Probar Configuración

```php
<?php
// Script de prueba: admin/test_config.php
require_once('classes/configuracion.php');
$config = new Configuracion();

echo "Test 1: Obtener valor existente\n";
echo "Sitio: " . $config->obtener('sitio_nombre') . "\n";

echo "\nTest 2: Guardar nuevo valor\n";
$config->guardar('test_clave', 'test_valor');
echo "Guardado: " . $config->obtener('test_clave') . "\n";

echo "\nTest 3: Obtener con fallback\n";
echo "No existe: " . $config->obtener('no_existe', 'FALLBACK') . "\n";

echo "\nTest 4: Obtener todas las configuraciones\n";
$todas = $config->obtenerTodas();
echo "Total de configuraciones: " . count($todas) . "\n";
?>
```

---

## 📊 Base de Datos

### Tabla `configuracion`

```
id (INT) - ID único
├─ clave (VARCHAR 100) - Nombre de la configuración
├─ valor (LONGTEXT) - Valor (puede ser JSON)
├─ tipo (ENUM) - string, number, boolean, json
├─ descripcion (TEXT) - Descripción para admin
├─ visible_admin (BOOLEAN) - Mostrar en admin UI
├─ created_at (DATETIME) - Fecha de creación
└─ updated_at (DATETIME) - Fecha de actualización
```

### Consultas Útiles

```sql
-- Ver todas las configuraciones
SELECT clave, valor, tipo FROM configuracion ORDER BY clave;

-- Ver solo las visibles
SELECT * FROM configuracion WHERE visible_admin = 1;

-- Ver últimos cambios
SELECT clave, updated_at FROM configuracion 
ORDER BY updated_at DESC LIMIT 10;

-- Buscar por clave
SELECT * FROM configuracion WHERE clave LIKE '%google%';

-- Eliminar configuración
DELETE FROM configuracion WHERE clave = 'test_clave';
```

---

## 🆘 Solución de Problemas

### "Página de configuración no carga"
- ✓ Verificar que eres admin (rol = 0)
- ✓ Verificar que tabla `configuracion` existe
- ✓ Ver error en `admin/includes/header.php`

### "Cambios no se guardan"
- ✓ Verificar permisos de BD (UPDATE)
- ✓ Revisar console.log del navegador
- ✓ Ver si hay error en `configuracion.php`

### "Mantenimiento no funciona"
- ✓ Verificar que middleware está en `navbar.php`
- ✓ Limpiar cookies/sesión del navegador
- ✓ Probar en navegador anónimo

### "Email no se envía"
- ✓ Verificar credenciales SMTP en configuración
- ✓ Verificar puerto correcto (587 o 465)
- ✓ En Gmail, activar "Contraseñas de aplicación"

---

## 📈 Próximos Pasos

1. **Completar información general:**
   - Nombre del sitio
   - Email principal
   - Teléfono

2. **Configurar OAuth (opcional):**
   - Google OAuth (para login)
   - Facebook OAuth (para login)

3. **Configurar Email:**
   - SMTP para poder enviar emails automáticos

4. **Configurar Pagos:**
   - Stripe para procesar pagos

5. **Configurar Anti-Spam:**
   - reCAPTCHA v3 (ya tiene TEST keys)

6. **Activar modo mantenimiento solo cuando sea necesario**

---

## 📞 Soporte

Para preguntas sobre:
- **Credenciales:** Ver `CONFIGURACION_README.md`
- **Clase Configuracion:** Ver docstrings en `admin/classes/configuracion.php`
- **OAuth/Email/Pagos:** Google las instrucciones de cada servicio

---

**¡Listo!** El sistema está completamente integrado y funcional. 🎉


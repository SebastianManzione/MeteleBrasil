# 🎛️ Sistema de Configuración y Mantenimiento - MeteleBrasil

## 📋 Descripción General

Sistema completo de configuración centralizada para MeteleBrasil que permite:
- 🔐 Gestionar credenciales OAuth (Google, Facebook)
- 🛡️ Configurar reCAPTCHA v3
- 📧 Configurar SMTP para envío de emails
- 💳 Gestionar claves Stripe
- 🔧 Activar modo mantenimiento con mensaje personalizado
- 💾 Almacenar configuraciones en BD de forma segura

---

## 📂 Archivos Incluidos

### Backend
- **`admin/classes/configuracion.php`** - Clase OOP para gestionar configuraciones
- **`admin/configuracion.php`** - Página AdminLTE3 para editar configuraciones
- **`admin/classes/middleware_mantenimiento.php`** - Middleware para verificar mantenimiento
- **`admin/classes/check_maintenance.php`** - Endpoint AJAX de verificación
- **`mantenimiento.php`** - Página pública cuando sitio está en mantenimiento

### BD
Se crea automáticamente tabla `configuracion` con:
```sql
id, clave, valor, tipo, descripcion, visible_admin, created_at, updated_at
```

---

## 🚀 Inicio Rápido

### 1. Integrar en el Sitio

**En `includes/navbar.php` (al inicio, después de `session_start()`), agregar:**

```php
<?php
session_start();

// Verificar mantenimiento
require_once(__DIR__ . '/../admin/classes/middleware_mantenimiento.php');

// resto del código...
```

**En `admin/includes/sidebar.php`, agregar al menú:**

```php
<!-- Opción en el sidebar -->
<li class="nav-item">
    <a href="configuracion.php" class="nav-link">
        <i class="fas fa-cog nav-icon"></i>
        <p>Configuración</p>
    </a>
</li>
```

### 2. Acceder a la Página

```
http://localhost/metelebrasil_dev/admin/configuracion.php
```

**Solo administradores** pueden acceder.

---

## 🔐 Usar las Credenciales en el Código

### Ejemplo: Google OAuth

**En archivo donde uses login con Google:**

```php
<?php
require_once(__DIR__ . '/admin/classes/configuracion.php');
$config = new Configuracion();

$client_id = $config->obtener('google_client_id');
$client_secret = $config->obtener('google_client_secret');

// Usar en tu lógica...
?>
```

### Ejemplo: reCAPTCHA

**En `config/recaptcha.php`:**

```php
<?php
require_once(__DIR__ . '/../admin/classes/configuracion.php');
$config = new Configuracion();

define('RECAPTCHA_SITE_KEY', $config->obtener('recaptcha_site_key', 'fallback_key'));
define('RECAPTCHA_SECRET_KEY', $config->obtener('recaptcha_secret_key', 'fallback_key'));
?>
```

### Ejemplo: Email SMTP

**En función de envío de emails:**

```php
<?php
require_once(__DIR__ . '/admin/classes/configuracion.php');
$config = new Configuracion();

$smtp_host = $config->obtener('smtp_host');
$smtp_port = $config->obtener('smtp_port', 587);
$smtp_usuario = $config->obtener('smtp_usuario');
$smtp_password = $config->obtener('smtp_password');

// Configurar PHPMailer o similar...
?>
```

---

## 🔧 Cómo Obtener Credenciales

### Google OAuth 2.0

1. **Crear Proyecto:**
   - Ir a: https://console.developers.google.com
   - Click "Crear Proyecto"
   - Nombre: "MeteleBrasil"

2. **Habilitar API:**
   - Buscar "Google+ API"
   - Click "Habilitar"

3. **Crear Credenciales:**
   - Click "Crear Credenciales" → OAuth 2.0 Client ID
   - Tipo: Aplicación web
   - Authorized redirect URIs:
     ```
     http://localhost/metelebrasil_dev/admin/configuracion.php
     https://metelebrasil.com/admin/configuracion.php
     ```

4. **Copiar:**
   - Client ID: `xxx.apps.googleusercontent.com`
   - Client Secret: `GOCSPX-...`

---

### Facebook OAuth

1. **Crear App:**
   - Ir a: https://developers.facebook.com/apps
   - Click "Mis aplicaciones" → "Crear aplicación"
   - Tipo: **Consumer** (importante!)
   - Nombre: "MeteleBrasil"

2. **Agregar Producto:**
   - En el dashboard, buscar "Facebook Login"
   - Click "Configurar"

3. **Obtener Credenciales:**
   - Settings → Basic
   - App ID: `123456789`
   - App Secret: `abc123...` (oculto)

4. **Configurar URIs:**
   - Settings → Basic → App Domains:
     ```
     localhost
     metelebrasil.com
     ```
   - En Facebook Login → Settings → Valid OAuth Redirect URIs:
     ```
     http://localhost/metelebrasil_dev/googleLogin.php
     https://metelebrasil.com/googleLogin.php
     ```

---

### reCAPTCHA v3

1. **Ir a:** https://www.google.com/recaptcha/admin/create

2. **Crear Sitio:**
   - Label: "MeteleBrasil"
   - reCAPTCHA type: **v3** (invisible)
   - Dominios:
     ```
     metelebrasil.com
     www.metelebrasil.com
     localhost
     ```

3. **Copiar Claves:**
   - Site Key (pública): `6Le...`
   - Secret Key (privada): `6Le...`

---

### Email SMTP

#### Gmail

1. **Habilitar 2FA:**
   - Ir a: https://myaccount.google.com/security
   - Buscar "Contraseñas de aplicación"

2. **Generar Contraseña:**
   - Seleccionar: Mail + Windows Computer
   - Click "Generar"
   - Copiar contraseña (16 caracteres)

3. **Configuración:**
   ```
   Host: smtp.gmail.com
   Puerto: 587 (TLS) o 465 (SSL)
   Usuario: tu_email@gmail.com
   Contraseña: aaaa bbbb cccc dddd
   ```

#### Otros Proveedores

**SendGrid:**
```
Host: smtp.sendgrid.net
Puerto: 587
Usuario: apikey
Contraseña: SG.xxxx...
```

**Mailtrap (Testing):**
```
Host: smtp.mailtrap.io
Puerto: 587
Usuario: (desde panel)
Contraseña: (desde panel)
```

---

### Stripe

1. **Crear Cuenta:**
   - Ir a: https://stripe.com
   - Click "Empezar" → Sign up

2. **Obtener Claves:**
   - Dashboard → Developers → API Keys
   - Publishable Key: `pk_live_...` o `pk_test_...`
   - Secret Key: `sk_live_...` o `sk_test_...`

3. **Test vs Live:**
   - Para desarrollo, usar **claves de prueba** (test)
   - Las claves empiezan con `pk_test_` o `sk_test_`

---

## 🛠️ Modo Mantenimiento

### Activar Mantenimiento

1. Ir a: `/admin/configuracion.php`
2. Bajar hasta "Modo Mantenimiento"
3. Marcar "Activar Mantenimiento"
4. Escribir mensaje personalizado
5. Click "Guardar Configuración"

### Comportamiento

**Con mantenimiento ACTIVADO:**
- ✅ Admin (rol 0) ve el sitio normalmente
- ✅ Otros usuarios logueados ven página de mantenimiento
- ❌ Usuarios sin login ven página de mantenimiento
- 🔄 Página se recarga cada 30 segundos para detectar cuando termina

**Con mantenimiento DESACTIVADO:**
- ✅ Todos los usuarios ven el sitio normalmente

### Página de Mantenimiento

- **Ruta:** `http://localhost/metelebrasil_dev/mantenimiento.php`
- **Estilo:** Profesional, responsive, animado
- **Auto-reload:** Cada 30 segundos verifica si mantenimiento terminó
- **Email:** Muestra email de contacto desde configuración

---

## 💾 Métodos de la Clase Configuracion

### `obtener($clave, $default = null)`
Obtiene valor de una configuración.

```php
$email = $config->obtener('sitio_email', 'info@default.com');
$numero = $config->obtener('sitio_telefono');
```

### `guardar($clave, $valor, $tipo = 'string', $descripcion = '')`
Guarda o actualiza configuración.

```php
$config->guardar('google_client_id', 'xxx.apps.googleusercontent.com');
$config->guardar('sitio_nombre', 'MeteleBrasil');
$config->guardar('smtp_port', 587, 'number');
$config->guardar('mantenimiento_activo', true, 'boolean');
```

### `obtenerTodas($visibles_solo = true)`
Obtiene todas las configuraciones.

```php
$todas = $config->obtenerTodas();
// Retorna: ['clave' => ['valor' => ..., 'tipo' => ..., 'descripcion' => ...], ...]
```

### `eliminar($clave)`
Elimina una configuración.

```php
$config->eliminar('google_client_id');
```

### `mantenimientoActivo()`
Verifica si modo mantenimiento está activo.

```php
if ($config->mantenimientoActivo()) {
    echo "Sistema en mantenimiento";
}
```

### `obtenerMensajeMantenimiento()`
Obtiene mensaje personalizado de mantenimiento.

```php
$msg = $config->obtenerMensajeMantenimiento();
```

---

## 🔒 Seguridad

### Credenciales Privadas

Todas las claves secretas:
- ✅ Se almacenan en BD local
- ✅ Se muestran como `password` en formularios
- ✅ No se registran en logs
- ✅ Solo admin puede ver/editar

### Protección de Acceso

El archivo `configuracion.php` verifica:
```php
if ($_SESSION['login']['rol'] != 0) {
    die('Acceso denegado');
}
```

### Mejoras Recomendadas

1. **Encriptación de claves secretas:**
```php
// En guardar()
if (in_array($clave, ['google_client_secret', 'stripe_secret_key'])) {
    $valor = openssl_encrypt($valor, 'AES-256-CBC', SECRET_KEY);
}
```

2. **Audit log:**
```php
// Registrar cambios
INSERT INTO config_audit (admin_id, clave, valor_anterior, valor_nuevo)
```

3. **Validación de email:**
```php
// Al guardar sitio_email
if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Email inválido');
}
```

---

## 📊 Ejemplos de Uso Completo

### Email con Configuración Dinámica

```php
<?php
require_once(__DIR__ . '/admin/classes/configuracion.php');

class Mailer {
    private $config;
    
    public function __construct() {
        $this->config = new Configuracion();
    }
    
    public function enviar($para, $asunto, $cuerpo) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $this->config->obtener('smtp_host');
        $mail->Port = $this->config->obtener('smtp_port', 587);
        $mail->SMTPAuth = true;
        $mail->Username = $this->config->obtener('smtp_usuario');
        $mail->Password = $this->config->obtener('smtp_password');
        $mail->setFrom($this->config->obtener('smtp_usuario'));
        
        $mail->addAddress($para);
        $mail->Subject = $asunto;
        $mail->Body = $cuerpo;
        
        return $mail->send();
    }
}

$mailer = new Mailer();
$mailer->enviar('usuario@ejemplo.com', 'Hola', 'Contenido del email');
?>
```

### Login con Google + Configuración

```php
<?php
require_once('admin/classes/configuracion.php');

$config = new Configuracion();

$client_id = $config->obtener('google_client_id');
$client_secret = $config->obtener('google_client_secret');

// Usar en Google Client...
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri('http://localhost/callback.php');
?>
```

---

## 🐛 Troubleshooting

### "No se guarda la configuración"
- ✓ Verificar que usuario es admin (rol = 0)
- ✓ Verificar permisos de BD (WRITE)
- ✓ Ver error en consola del navegador

### "Email no se envía"
- ✓ Verificar credenciales SMTP
- ✓ Verificar puerto correcto (587 TLS o 465 SSL)
- ✓ En Gmail, activar "Contraseñas de aplicación"
- ✓ Probar con: `telnet smtp.gmail.com 587`

### "OAuth no funciona"
- ✓ Verificar Client ID y Secret correctos
- ✓ Revisar que redirect URI coincida exactamente
- ✓ En navegador, revisar consola para errores
- ✓ reCAPTCHA debe estar también configurado

### "Mantenimiento no se muestra"
- ✓ Verificar que middleware está en navbar.php
- ✓ Limpiar cookies/cache del navegador
- ✓ Cerrar sesión si estás logueado
- ✓ Abrir en navegador anónimo

---

## 📈 Configuraciones Recomendadas por Entorno

### Desarrollo
```
mantenimiento_activo: false
sitio_email: tu_email_personal@gmail.com (con contraseña de app)
recaptcha: Usar TEST KEYS
stripe: Usar claves de PRUEBA (pk_test_, sk_test_)
oauth: Client IDs locales
```

### Producción
```
mantenimiento_activo: false (solo cuando sea necesario)
sitio_email: no-reply@metelebrasil.com (con servidor corporativo)
recaptcha: Usar claves REALES
stripe: Usar claves LIVE (pk_live_, sk_live_)
oauth: Client IDs de producción
```

---

## 📝 Checklist de Configuración

- [ ] Crear tabla `configuracion` (automática)
- [ ] Agregar middleware en `includes/navbar.php`
- [ ] Agregar enlace en sidebar admin
- [ ] Obtener Google OAuth credentials
- [ ] Obtener Facebook OAuth credentials
- [ ] Obtener reCAPTCHA v3 keys
- [ ] Configurar SMTP (Gmail/SendGrid)
- [ ] Obtener Stripe API keys
- [ ] Completar información general (nombre, email)
- [ ] Probar mantenimiento (activar/desactivar)
- [ ] Verificar que obtener() devuelve valores correctos en código
- [ ] Hacer backup de tabla `configuracion`

---

**Versión:** 1.0  
**Fecha:** 25 de diciembre de 2025  
**Compatible:** AdminLTE 3, MeteleBrasil


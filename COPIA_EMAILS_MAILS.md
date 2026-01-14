# Sistema de Copia de Emails a mails@metelebrasil.com

## Objetivo
Garantizar que **TODOS** los emails enviados por el sistema MeteleBrasil tengan una copia oculta (BCC) en `mails@metelebrasil.com` para auditoría, seguimiento y cumplimiento normativo.

## Cambios Implementados (14 enero 2026)

### Archivo: `admin/classes/reservaEmail.php`

#### 1. Función `enviaMail()` (línea 59)
**Antes:**
```php
$mail->addBCC($smtpConfig['from']);
```

**Después:**
```php
// Garantizar copia en mails@metelebrasil.com
$mail->addBCC('mails@metelebrasil.com');
```

**Motivo:** La configuración SMTP podía variar. Se hardcodea `mails@metelebrasil.com` para garantizar consistencia.

---

#### 2. Función `enviaMailPagos()` (línea 80)
**Antes:**
```php
$mail->addAddress($smtpConfig['from']);
```

**Después:**
```php
// Garantizar copia en mails@metelebrasil.com (BCC para no exponer email al cliente)
$mail->addBCC('mails@metelebrasil.com');
```

**Cambios:**
- `addAddress()` → `addBCC()`: Cambia de CC visible a BCC oculta para no exponer el email al cliente
- Hardcodea `mails@metelebrasil.com` para consistencia

---

## Cobertura de Emails

### Emails que RECIBEN copia en mails@metelebrasil.com:

| Ubicación | Tipo | Función | Descripción |
|-----------|------|---------|-------------|
| `registro.php:30` | Bienvenida | `enviaMail()` | Confirmación de nuevo registro |
| `recuperar_contrasena.php:26` | Recuperación | `enviaMail()` | Envío de link de reset de contraseña |
| `recibePago.php:121` | Pago | `enviaMail()` | Confirmación de pago recibido |
| `guardaReservas.php:315` | Reserva | `enviaMail()` | Confirmación de reserva exitosa |
| `admin/cobroSignal.php:117` | Pago | `enviaMail()` | Confirmación de pago signal |
| `admin/cobroSignalSebi.php:114` | Pago | `enviaMail()` | Confirmación pago Sebi |
| `admin/configuracion.php:40` | Admin | `enviaMail()` | Emails de prueba SMTP |
| `admin/classes/comprobantes.php:194` | Comprobante | `enviaMail()` | Envío de comprobante a cliente |
| `admin/classes/comprobantes.php:219` | Comisión | `enviaMail()` | Notificación a vendedor |
| `admin/classes/comprobantes.php:247` | Pago | `enviaMail()` | Notificación a prestador |
| `admin/pasarelas/mercadopagoBrasil/notificaciones.php:177` | Pago | `enviaMailPagos()` | Confirmación pago MercadoPago Brasil |
| `guias.php:41` | Guía | `enviaMailAdjunto()` | Envío de guía con adjunto |

### Total: 12 tipos de emails cubiertos

---

## Emails con Adjuntos

### Archivo: `admin/classes/email_guias.php`

La función `enviaMailAdjunto()` ya estaba configurada correctamente con:
```php
$mail->addBCC('mails@metelebrasil.com');
```

**Sin cambios necesarios** - Ya funciona como esperado.

---

## Flujo Técnico

### Proceso de Envío:
1. Sistema genera email con contenido específico
2. Llama a `enviaMail()` o `enviaMailPagos()` o `enviaMailAdjunto()`
3. Función obtiene configuración SMTP desde base de datos
4. Configura PHPMailer con credenciales
5. **Agrega receptor principal** con `addAddress()`
6. **Agrega copia oculta** con `addBCC('mails@metelebrasil.com')`
7. Envía email
8. Retorna mensaje de éxito o error

### Resultado Visual para el Receptor:
- **Para:** usuario@ejemplo.com
- **CC:** (vacío)
- **BCC:** (oculto - mails@metelebrasil.com no ve)

---

## Configuración SMTP Requerida

**Archivo:** `admin/configuracion.php`

Requiere tener configurados en la base de datos:
- `smtp_host`: mail.metelebrasil.com
- `smtp_port`: 465
- `smtp_usuario`: mails@metelebrasil.com
- `smtp_password`: (credencial activa)
- `smtp_secure`: ssl

---

## Testing

### Para verificar que funciona:

1. **Crear una reserva de prueba** desde `carrito.php`
2. **Revisar bandeja de entrada de:**
   - Usuario que hizo reserva
   - mails@metelebrasil.com (debe tener copia)
3. **Verificar logs** en `logs/db_bootstrap.log` si hay errores SMTP

### Comandos útiles (Terminal):
```bash
# Ver últimos errores SMTP
tail -f C:\xampp\logs\db_bootstrap.log

# Ver estado del servidor SMTP
nslookup mail.metelebrasil.com
```

---

## Beneficios

✅ **Auditoría completa** de todos los emails enviados
✅ **Cumplimiento normativo** (GDPR, LGPD, etc.)
✅ **Recuperación de comunicaciones** en caso de disputas
✅ **Seguimiento de confirmaciones** de pago y reservas
✅ **Implementación centralizada** - un punto de control único

---

## Notas Importantes

⚠️ **La dirección `mails@metelebrasil.com`:**
- Debe tener **buzón activo** en el servidor
- Debe tener **cuota de almacenamiento** suficiente
- Se recomienda **revisar periódicamente** para no saturar

⚠️ **Privacy & GDPR:**
- Los receptores originales **NO ven** que se envió copia a mails@metelebrasil.com (BCC)
- Documentar en **Política de Privacidad** que se conservan copias de comunicaciones
- Este almacenamiento es **obligatorio en muchas jurisdicciones** para cumplimiento

---

## Mantener & Extender

### Al agregar nuevos emails:

1. **Usar siempre** una de estas 3 funciones:
   - `enviaMail()` - para confirmaciones generales
   - `enviaMailPagos()` - para comunicaciones de pago
   - `enviaMailAdjunto()` - para emails con archivos adjuntos

2. **Nunca usar** `mail()` de PHP directamente
   - Solo usar las funciones centralizadas

3. **Ejemplo correcto:**
```php
require_once 'admin/classes/reservaEmail.php';

$resultado = enviaMail(
    'cliente@ejemplo.com',
    'Asunto del email',
    '<html>Contenido HTML</html>',
    $_SERVER['HTTP_HOST']
);

echo $resultado; // "Mensaje enviado correctamente" o error
```

---

## Documentación Relacionada

- `admin/classes/reservaEmail.php` - Funciones principales
- `admin/classes/email_guias.php` - Función con adjuntos
- `config/config.php` - Configuración de entorno
- `.github/copilot-instructions.md` - Instrucciones globales del proyecto

---

**Última actualización:** 14 enero 2026
**Estado:** ✅ Implementado y testeado
**Responsable:** Sistema MeteleBrasil

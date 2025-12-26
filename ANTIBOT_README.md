# Sistema Anti-Bot para MeteleBrasil

## 🛡️ Descripción

Sistema completo de protección anti-spam con **3 capas de seguridad** implementado en todos los formularios públicos de MeteleBrasil.

---

## 🔐 Capas de Seguridad

### 1. **Google reCAPTCHA v3** (Invisible)
- Analiza el comportamiento del usuario sin interrumpir la experiencia
- Score de 0.0 a 1.0 (1.0 = muy probablemente humano)
- Umbral configurado en 0.5

### 2. **Honeypot Field** (Campo Trampa)
- Campo `website` oculto con CSS
- Los bots lo llenan, los humanos no lo ven
- Detección inmediata de bots automatizados

### 3. **Rate Limiting**
- Máximo 5 intentos por hora por IP
- Protección contra ataques de fuerza bruta
- Tabla `form_rate_limit` para tracking

### 4. **Tiempo Mínimo de Llenado**
- Mínimo 3 segundos para completar formulario
- Detecta bots que envían instantáneamente
- Campo timestamp oculto

---

## 📋 Formularios Protegidos

✅ **contact.php** - Formulario de contacto  
✅ **registro.php** - Registro de usuarios  
✅ **registroAgencias.php** - Registro de agencias  
✅ **registroPrestadores.php** - Registro de prestadores  
✅ **registroFreelancers.php** - Registro de freelancers  
✅ **registroOperadores.php** - Registro de operadores  
✅ **recuperar_contrasena.php** - Recuperación de contraseña

---

## 🚀 Configuración Inicial

### **IMPORTANTE: Obtener Claves Reales de reCAPTCHA**

Las claves actuales son **TEST KEYS** de Google. Para producción:

#### Paso 1: Registrar Sitio en Google
1. Ir a: https://www.google.com/recaptcha/admin/create
2. Iniciar sesión con cuenta de Google
3. Completar formulario:
   - **Label:** MeteleBrasil
   - **reCAPTCHA type:** reCAPTCHA v3
   - **Domains:** 
     - `metelebrasil.com`
     - `www.metelebrasil.com`
     - `localhost` (para desarrollo)
   - Aceptar términos y enviar

#### Paso 2: Obtener las Claves
Después de crear, verás:
- **Site Key** (clave pública) - ej: `6Lc8X...ejemplo`
- **Secret Key** (clave secreta) - ej: `6Lc8X...ejemplo`

#### Paso 3: Reemplazar en 2 Archivos

**Archivo 1:** `config/recaptcha.php`
```php
// Líneas 7-8: Reemplazar con tus claves reales
define('RECAPTCHA_SITE_KEY', 'TU_SITE_KEY_AQUI');
define('RECAPTCHA_SECRET_KEY', 'TU_SECRET_KEY_AQUI');
```

**Archivo 2:** `js/antibot.js`
```javascript
// Línea 7: Reemplazar con tu SITE KEY
const RECAPTCHA_SITE_KEY = 'TU_SITE_KEY_AQUI';
```

**Archivo 3:** `includes/navbar.php`
```html
<!-- Línea ~739: Reemplazar en la URL del script -->
<script src="https://www.google.com/recaptcha/api.js?render=TU_SITE_KEY_AQUI"></script>
```

---

## 📂 Archivos del Sistema

### Backend (PHP)
- **`config/recaptcha.php`** - Configuración y verificación reCAPTCHA
- **`admin/classes/antibot.php`** - Sistema completo anti-bot
  - `verificarHoneypot()` - Validar campo trampa
  - `verificarRateLimit()` - Límite por IP
  - `verificarTiempoMinimo()` - Tiempo de llenado
  - `validarAntiBot()` - Validación completa (todas las capas)
  - `registrarIntentoBot()` - Log de intentos sospechosos
  - `generarCamposAntiBot()` - HTML de campos ocultos

### Frontend (JavaScript)
- **`js/antibot.js`** - Integración reCAPTCHA v3
  - Auto-detección de página
  - Ejecución automática al submit
  - Validación honeypot

### Base de Datos
Se crean automáticamente:
- **`form_rate_limit`** - Control de intentos por IP
  ```sql
  id, ip_address, action, attempt_time
  ```
- **`bot_attempts`** - Log de intentos de bots
  ```sql
  id, ip_address, tipo, detalles, user_agent, attempt_time
  ```

---

## 🧪 Testing

### Desarrollo Local (Test Keys)
Las test keys actuales funcionan en:
- ✅ `localhost`
- ✅ `127.0.0.1`

**Comportamiento:**
- **SIEMPRE** pasan la validación (score 1.0)
- Útil para desarrollo sin configuración

### Producción (Keys Reales)
Una vez configuradas las claves reales:
1. Probar formulario normalmente (debe funcionar)
2. Intentar enviar múltiples veces rápido (debe bloquear después de 5)
3. Revisar `bot_attempts` para ver logs

### Verificar Protección Activa
```sql
-- Ver intentos bloqueados de bots
SELECT * FROM bot_attempts ORDER BY attempt_time DESC LIMIT 50;

-- Ver rate limiting activo
SELECT ip_address, action, COUNT(*) as intentos, MAX(attempt_time) as ultimo
FROM form_rate_limit 
WHERE attempt_time > DATE_SUB(NOW(), INTERVAL 1 HOUR)
GROUP BY ip_address, action
HAVING intentos >= 3;
```

---

## 🎯 Flujo de Validación

Cuando un usuario envía un formulario:

```
1. JavaScript ejecuta reCAPTCHA v3 (invisible)
   ↓
2. Token agregado al formulario
   ↓
3. PHP recibe POST con token
   ↓
4. validarAntiBot() ejecuta:
   ├─ ✓ Honeypot vacío?
   ├─ ✓ Tiempo > 3 segundos?
   ├─ ✓ Rate limit OK? (< 5 en 1 hora)
   └─ ✓ reCAPTCHA score >= 0.5?
       ↓
5a. TODO OK → Procesar formulario
5b. FALLA → Rechazar + registrar en bot_attempts
```

---

## 📊 Monitoreo y Análisis

### Revisar Bloqueos de Bots
```sql
-- Bots bloqueados por tipo
SELECT tipo, COUNT(*) as total, 
       COUNT(DISTINCT ip_address) as ips_unicas
FROM bot_attempts 
WHERE attempt_time > DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY tipo;

-- IPs más problemáticas
SELECT ip_address, 
       COUNT(*) as intentos,
       MAX(attempt_time) as ultimo_intento,
       GROUP_CONCAT(DISTINCT tipo) as tipos
FROM bot_attempts
GROUP BY ip_address
ORDER BY intentos DESC
LIMIT 20;
```

### Dashboard Recomendado
Agregar en `admin/index.php`:
```php
// Bots bloqueados hoy
$bots_hoy = $conn->query("SELECT COUNT(*) FROM bot_attempts 
                          WHERE DATE(attempt_time) = CURDATE()")->fetch_row()[0];

// Bots últimos 7 días
$bots_semana = $conn->query("SELECT COUNT(*) FROM bot_attempts 
                             WHERE attempt_time > DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch_row()[0];
```

---

## ⚙️ Configuración Avanzada

### Ajustar Umbral de reCAPTCHA
En `config/recaptcha.php`:
```php
// Más estricto (menos falsos positivos, más bots pasan)
define('RECAPTCHA_SCORE_THRESHOLD', 0.7);

// Más permisivo (más falsos positivos, menos bots pasan)
define('RECAPTCHA_SCORE_THRESHOLD', 0.3);
```

### Ajustar Rate Limiting
En llamadas a `verificarRateLimit()`:
```php
// Más estricto: 3 intentos en 30 minutos
verificarRateLimit($action, 3, 1800);

// Más permisivo: 10 intentos en 2 horas
verificarRateLimit($action, 10, 7200);
```

### Cambiar Tiempo Mínimo
En `admin/classes/antibot.php` línea ~72:
```php
// Más estricto: 5 segundos
function verificarTiempoMinimo($minSeconds = 5)

// Más permisivo: 2 segundos
function verificarTiempoMinimo($minSeconds = 2)
```

---

## 🐛 Troubleshooting

### "Validación de seguridad fallida"
**Causa:** reCAPTCHA score bajo o sin token  
**Solución:**
1. Verificar que `js/antibot.js` esté cargando
2. Verificar consola del navegador por errores
3. Revisar que Site Key esté correcta en 3 lugares

### "Has excedido el límite de intentos"
**Causa:** Rate limiting activado (> 5 intentos/hora)  
**Solución:**
1. Esperar 1 hora
2. O limpiar manualmente:
```sql
DELETE FROM form_rate_limit WHERE ip_address = 'IP_DEL_USUARIO';
```

### Honeypot detectado en usuarios reales
**Causa:** Autocompletado del navegador llenando campo oculto  
**Solución:** Campo `website` ya tiene `autocomplete="off"`, verificar que no haya extensiones de navegador llenando campos

### reCAPTCHA no carga
**Causa:** Bloqueadores de ads/scripts  
**Solución:** Sistema tiene fallback - funciona sin reCAPTCHA usando solo honeypot y rate limiting

---

## 📈 Mejoras Futuras Sugeridas

1. **Blacklist de IPs** - Bloquear automáticamente IPs con > X intentos fallidos
2. **Whitelist** - Permitir IPs confiables sin validación
3. **Email Alerts** - Notificar admin cuando se detecten patrones de ataque
4. **CAPTCHA Visual Fallback** - Si reCAPTCHA v3 falla, mostrar v2 checkbox
5. **Análisis de User-Agent** - Detectar bots por firma de navegador
6. **Fingerprinting Avanzado** - Canvas, WebGL, Audio para identificación única

---

## 🔒 Seguridad y Privacidad

- ✅ No se almacenan datos personales en logs de seguridad
- ✅ IPs hasheadas opcionalmente para GDPR compliance
- ✅ Tablas de seguridad auto-limpian después de 30 días
- ✅ Rate limiting por IP previene DDoS
- ✅ reCAPTCHA v3 invisible = mejor UX

---

## 📞 Soporte

Para dudas sobre reCAPTCHA:
- Documentación oficial: https://developers.google.com/recaptcha/docs/v3
- Panel admin: https://www.google.com/recaptcha/admin

Para ajustes del sistema anti-bot, revisar código en:
- `admin/classes/antibot.php` (lógica backend)
- `js/antibot.js` (lógica frontend)

---

## ✅ Checklist Post-Implementación

- [ ] Obtener claves reales de reCAPTCHA
- [ ] Reemplazar claves en 3 archivos (recaptcha.php, antibot.js, navbar.php)
- [ ] Probar cada formulario manualmente
- [ ] Verificar que tablas `form_rate_limit` y `bot_attempts` se crean
- [ ] Intentar 6 envíos rápidos para verificar rate limiting
- [ ] Revisar logs de `bot_attempts` después de 1 semana
- [ ] Ajustar umbrales según necesidad
- [ ] Configurar monitoreo en dashboard admin
- [ ] Documentar IPs legítimas si alguna es bloqueada

---

**Implementado:** 25 de diciembre de 2025  
**Versión:** 1.0  
**Autor:** Sistema automatizado anti-spam MeteleBrasil

# Sincronización de Imágenes a Producción

## Resumen

Este sistema permite sincronizar automáticamente todas las imágenes del sitio (servicios, blog, categorías) desde tu entorno local a la producción vía SSH/SFTP.

### Datos de Conexión
- **Servidor:** 185.173.111.212:65002
- **Usuario:** u925692129
- **Rutas remotas:**
  - Servicios: `/home/u925692129/public_html/admin/classes/imgServicio/`
  - Blog: `/home/u925692129/public_html/admin/classes/imgBlog/`
  - Categorías: `/home/u925692129/public_html/admin/img/categoria_servicio/` (opcional)

---

## Instalación (Una sola vez)

1. **Instalar phpseclib** (ya hecho):
```bash
composer require phpseclib/phpseclib
```

2. **Configurar credenciales** en [`config/ftp.php`](config/ftp.php):
   - `SSH_HOST`
   - `SSH_PORT`
   - `SSH_USER`
   - `SSH_PASS`

---

## Uso

### 1. Probar Conexión SSH
```bash
php sync_images.php test
```

Esto verifica que la conexión a producción funciona sin sincronizar nada.

### 2. Sincronizar Imágenes Específicas

**Solo servicios:**
```bash
php sync_images.php servicios
```

**Solo blog:**
```bash
php sync_images.php blog
```

**Solo categorías:**
```bash
php sync_images.php categorias
```

### 3. Sincronizar TODAS las Imágenes
```bash
php sync_images.php all
```

O simplemente:
```bash
php sync_images.php
```

---

## Output Esperado

```
╔════════════════════════════════════════════════════╗
║   SINCRONIZADOR DE IMÁGENES - METELEBRASIL (SSH)  ║
╚════════════════════════════════════════════════════╝

→ VERIFICACIÓN DE ENTORNO
ℹ Ambiente detectado: prod

→ VERIFICACIÓN DE DEPENDENCIAS
✓ phpseclib disponible

→ CONEXIÓN SSH/SFTP
✓ Conexión SSH/SFTP establecida ✓

→ DETALLES DE SINCRONIZACIÓN
✓ Subido: 645_img0.jpg
✓ Subido: 645_img1.jpg
...
✓ Archivos subidos: 1349
✓ Desconexión SSH completada

✓ Sincronización finalizada exitosamente
```

---

## Arquitectura

### Archivos Clave

| Archivo | Descripción |
|---------|-----------|
| `sync_images.php` | Script CLI principal (ejecutable) |
| `config/ftp.php` | Configuración SSH (credenciales) |
| `admin/classes/SSHSync.php` | Clase que maneja SFTP |
| `vendor/` | Dependencias (phpseclib) |

### Flujo de Sincronización

```
sync_images.php
    ↓
SSHSync::__construct()
    ├─ Conecta a SSH/SFTP
    └─ Autentica con contraseña
    ↓
syncAll() / syncImagenesServicios() / ...
    ├─ Lee imágenes locales
    ├─ Crea carpetas remotas
    └─ Sube archivos uno a uno
    ↓
disconnect()
    └─ Cierra conexión SSH
```

---

## Seguridad

⚠️ **IMPORTANTE:**

1. **NO comitear credenciales a Git:**
   ```bash
   echo "config/ftp.php" >> .gitignore
   ```

2. **Proteger el archivo:**
   ```bash
   chmod 600 config/ftp.php
   ```

3. **Usar variables de entorno en producción** (opcional):
   ```php
   define('SSH_PASS', getenv('SSH_PASS') ?: 'fallback');
   ```

---

## Troubleshooting

### Error: "phpseclib NO está instalado"
```bash
composer require phpseclib/phpseclib
```

### Error: "Credenciales SSH inválidas"
- Verifica usuario y contraseña en `config/ftp.php`
- Comprueba que el puerto 65002 no está bloqueado

### Error: "No se pudo conectar a 185.173.111.212:65002"
- Verifica conectividad: `ping 185.173.111.212`
- El servidor SSH debe estar activo
- Firewall podría estar bloqueando el puerto

### Conexión lenta
- Phpseclib usa criptografía pura (sin extensión nativa)
- Sincronizar ~1000+ imágenes puede tardar minutos
- Es normal, se completa exitosamente

---

## Próximos Pasos

### Automatizar con cron (Linux/macOS)
```bash
# Ejecutar sincronización diariamente a las 3 AM
0 3 * * * cd /var/www/metelebrasil && php sync_images.php all > /var/log/sync_images.log 2>&1
```

### Windows Scheduler
1. Crear tarea scheduled con: `php C:\xampp\htdocs\metelebrasil_dev\sync_images.php all`
2. Ejecutar diariamente

### Git pre-commit hook (evitar inconsistencias)
```bash
#!/bin/bash
# .git/hooks/pre-commit
php sync_images.php all
if [ $? -ne 0 ]; then
    echo "Fallo sincronización de imágenes"
    exit 1
fi
```

---

## Estadísticas Iniciales

**Sincronización exitosa (26/12/2025):**
- ✓ Servicios: 1,141 imágenes
- ✓ Blog: 208 imágenes
- ✓ Total: **1,349 imágenes**
- ✓ Errores: 0

---

## Soporte

Para problemas:
1. Revisa el log del último comando
2. Verifica credenciales SSH
3. Prueba conexión: `php sync_images.php test`
4. Revisa `config/ftp.php`

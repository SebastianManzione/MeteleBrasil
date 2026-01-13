# Git Workflow - MeteleBrasil

## 📋 Estructura de Branches

```
main (production)       → Código en producción (metelebrasil.com)
  ↑
staging (pre-prod)      → Testing antes de deploy
  ↑
dev (development)       → Rama principal de desarrollo
  ↑
feature/* (features)    → Nuevas funcionalidades
```

---

## 🔄 Flujo Completo: De Local a Producción

### 1️⃣ DESARROLLO LOCAL (XAMPP)

```bash
# Asegurar que estás en dev
git checkout dev
git pull origin dev

# Crear feature branch para nueva funcionalidad
git checkout -b feature/nombre-funcionalidad

# Desarrollar, hacer cambios...

# Commitear con mensajes descriptivos
git add .
git commit -m "feat: descripción del cambio"

# Push a remote (backup en GitHub)
git push origin feature/nombre-funcionalidad
```

**Tipos de commits:**
- `feat:` Nueva funcionalidad
- `fix:` Corrección de bugs
- `docs:` Cambios en documentación
- `style:` Cambios de formato (CSS, espacios)
- `refactor:` Refactorización de código
- `test:` Agregar tests
- `chore:` Tareas de mantenimiento

---

### 2️⃣ TESTING Y MERGE A DEV

```bash
# Volver a dev
git checkout dev

# Mergear tu feature
git merge feature/nombre-funcionalidad

# Push dev actualizado
git push origin dev
```

**¿Eliminar la feature branch?**
```bash
# Local
git branch -d feature/nombre-funcionalidad

# Remoto (si la pusheaste)
git push origin --delete feature/nombre-funcionalidad
```

---

### 3️⃣ PREPARAR STAGING

```bash
# Cambiar a staging
git checkout staging

# Mergear dev completo
git merge dev

# Push staging
git push origin staging
```

**Testing exhaustivo en staging:**
- ✅ Todas las funcionalidades nuevas
- ✅ Flujo completo de reserva/pago
- ✅ Multi-idioma (ES/EN/PT/IT)
- ✅ Responsive (desktop/tablet/móvil)
- ✅ Integración de pagos (sandbox)

---

### 4️⃣ DEPLOY A PRODUCCIÓN

```bash
# Solo después de aprobar staging
git checkout main

# Mergear staging (nunca dev directamente!)
git merge staging

# Crear tag de versión
git tag -a v1.2.0 -m "Release: descripción de cambios importantes"

# Push main con tags
git push origin main --tags
```

---

### 5️⃣ DEPLOY EN SERVIDOR

**Credenciales de Producción:**
- **SSH:** `ssh -p 65002 u925692129@185.173.111.212`
- **Dominio:** https://slateblue-snail-645791.hostingersite.com/
- **BD User:** u925692129_metelebr
- **BD Name:** u925692129_metelebr

**Opción A: SSH Manual**
```bash
# Conectar al servidor
ssh -p 65002 u925692129@185.173.111.212

# Ir al directorio del proyecto
cd public_html

# IMPORTANTE: Backup de BD primero
mysqldump -u u925692129_metelebr -p u925692129_metelebr > backup_$(date +%Y%m%d).sql

# Pull de main
git pull origin main

# Verificar APP_ENV en .htaccess
grep APP_ENV .htaccess
# Debe mostrar: SetEnv APP_ENV prod

# Verificar permisos
chmod 755 logs/
chmod 755 admin/classes/imgServicio/
chmod 755 img/uploads/

# Probar en navegador: https://slateblue-snail-645791.hostingersite.com/
```

**Opción B: Automatizado (Futuro - CI/CD)**
- GitHub Actions en push a `main`
- Deploy automático vía FTP/SSH
- Testing automatizado

---

## 🚨 ROLLBACK DE EMERGENCIA

**Si algo falla en producción:**

```bash
# Conectar al servidor
ssh -p 65002 u925692129@185.173.111.212
cd public_html

# Ver últimos commits y volver al anterior
git log --oneline -5
git reset --hard COMMIT_SHA_BUENO

# Restaurar BD si es necesario
mysql -u u925692129_metelebr -p u925692129_metelebr < backup_20260112.sql
```

**Desde local (force push):**
```bash
git checkout main
git reset --hard v1.1.0  # Último tag bueno
git push origin main --force  # ⚠️ USAR CON CUIDADO
```

---

## 📝 Comandos Útiles Diarios

### Ver estado actual
```bash
git status                    # Archivos modificados
git branch                    # Branch actual
git log --oneline -10         # Últimos 10 commits
```

### Comparar branches
```bash
git diff main..dev            # Ver diferencias
git log main..dev --oneline   # Commits únicos en dev
```

### Sincronizar con remoto
```bash
git fetch origin              # Traer cambios sin merge
git pull origin dev           # Traer y mergear dev
```

### Deshacer cambios
```bash
# Deshacer archivo específico
git checkout -- archivo.php

# Deshacer todos los cambios (¡cuidado!)
git reset --hard HEAD

# Guardar cambios temporalmente
git stash
git stash pop  # Recuperarlos después
```

---

## 🔧 Configuración de Entornos

### Local (XAMPP)
**`.htaccess`:**
```apache
# SetEnv APP_ENV dev  # Comentado o dev
```

**`config/config.php`:**
```php
// Detecta automáticamente: si APP_ENV no está, usa prod
define('APP_ENV', 'dev');
define('DB_HOST', 'localhost');
define('DB_NAME', 'metelebrasil');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Producción
**`.htaccess`:**
```apache
SetEnv APP_ENV prod  # CRÍTICO
```

**`config/config.php`:**
```php
// Cuando APP_ENV = prod, usa estas:
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'u925692129_metelebr');
define('DB_USER', 'u925692129_metelebr');
define('DB_PASS', 'Nueva3322112233');
```

---

## ✅ Checklist Pre-Deploy

### Antes de mergear a staging:
- [ ] Código testeado localmente
- [ ] Sin errores en logs (`logs/db_bootstrap.log`)
- [ ] Commits con mensajes claros
- [ ] Push a `dev` completo

### Antes de mergear a main:
- [ ] Testing completo en staging
- [ ] Flujo de compra end-to-end OK
- [ ] Sin errores críticos
- [ ] Tag de versión creado

### Después del deploy:
- [ ] Verificar homepage carga
- [ ] Probar búsqueda y filtros
- [ ] Testing de reserva completa
- [ ] Verificar logs: `tail -f logs/db_bootstrap.log`
- [ ] Monitorear primeras 24h

---

## 📊 Ver Historial de Releases

```bash
# Ver todos los tags
git tag

# Ver detalles de un tag
git show v1.2.0

# Ver qué hay en producción
git log origin/main --oneline -5
```

---

## 🆘 Ayuda Rápida

**¿En qué branch estoy?**
```bash
git branch
# El que tiene * es el actual
```

**¿Qué cambié desde el último commit?**
```bash
git diff
```

**¿Quiero cancelar todo y empezar de nuevo?**
```bash
git stash  # Guarda cambios
git checkout dev
git pull origin dev
```

**¿Cómo veo qué está en producción?**
```bash
git checkout main
git log --oneline -10
```

---

## 📚 Recursos

- **Instrucciones completas:** `.github/copilot-instructions.md`
- **Sistema de precios:** `PRICING_SYSTEM.md`
- **Disponibilidad:** `DISPONIBILIDAD_SALIDAS.md`
- **Quick Start:** `QUICK_START.md`

---

**Última actualización:** Enero 2026

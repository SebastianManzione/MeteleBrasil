# 📋 CHECKLIST EJECUTABLE - PLANOS REALES

**Objetivo:** Integrar 3,090 planos reales de micros en MeteleBrasil  
**Tiempo estimado:** 7-9 horas  
**Complejidad:** Media  
**Status:** 🟢 LISTO PARA EMPEZAR

---

## PASO 1: PREPARACIÓN (15 min)

### ✅ Paso 1.1: Leer documentación (5 min)
```
[ ] Leer c:\xampp\htdocs\metelebrasil_dev\README_PLANOS_REALES.md
[ ] Leer c:\xampp\htdocs\metelebrasil_dev\PLAN_PLANOS_REALES_MICROS.md
[ ] Entender estructura de datos (tabla carroceria_planos)
```

### ✅ Paso 1.2: Instalar Python (5 min)
```
[ ] Descargar Python 3.8+ desde https://www.python.org/downloads/
[ ] Instalar (checkbox "Add to PATH")
[ ] Verificar: python --version
```

### ✅ Paso 1.3: Instalar dependencias Python (5 min)
```bash
pip install requests beautifulsoup4 pillow numpy opencv-python pytesseract
```
```
[ ] Ejecutar comando en terminal
[ ] Verificar sin errores
```

---

## PASO 2: BASE DE DATOS (5 min)

### ✅ Paso 2.1: Crear tabla en BD
```bash
mysql -u root metelebrasil_experimental < c:\xampp\htdocs\metelebrasil_dev\migrations\014_planos_reales_mundocolectivo.sql
```

**O alternativamente, copiar/pegar en phpMyAdmin:**
- Conectar a metelebrasil_experimental
- Ir a pestaña SQL
- Copiar contenido de `migrations\014_planos_reales_mundocolectivo.sql`
- Ejecutar

```
[ ] Tabla carroceria_planos creada
[ ] FK en modelo_vehiculo_transporte agregado
[ ] View v_modelos_con_planos creado
[ ] Verificar: SELECT * FROM carroceria_planos LIMIT 5;
```

---

## PASO 3: DESCARGAR PLANOS (2 horas)

### ✅ Paso 3.1: Ejecutar script de descarga

```bash
cd c:\xampp\htdocs\metelebrasil_dev
python descargar_planos_mundocolectivo.py
```

**Qué esperar:**
```
[*] Preparando lista de fabricantes...
[*] Total: 40 fabricantes
[Marcopolo]
  [→] Paradiso G7: [✓] Guardado (156.2 KB)
  ...
[✓] Descarga completada: 150 planos (o más)
[✓] CSV generado: planos_descargados.csv
```

```
[ ] Script ejecutado sin errores
[ ] Carpeta img/planos_carroceria/ creada
[ ] Carpetas por fabricante creadas (marcopolo/, mercedes-benz/, etc.)
[ ] Al menos 20+ imágenes descargadas
[ ] Archivo planos_descargados.csv generado
```

### ✅ Paso 3.2: Verificar descarga
```bash
# En PowerShell:
Get-ChildItem c:\xampp\htdocs\metelebrasil_dev\img\planos_carroceria\ -Recurse | Measure-Object
```

Debería mostrar cantidad de archivos descargados.

```
[ ] Verificar cantidad de archivos > 10
[ ] Verificar extensiones .jpg/.png
[ ] Verificar tamaños > 50KB
```

---

## PASO 4: PROCESAR CON OCR (3 horas)

### ✅ Paso 4.1: Ejecutar script OCR

```bash
cd c:\xampp\htdocs\metelebrasil_dev
python procesar_planos_ocr.py
```

**Qué esperar:**
```
[*] Procesando planos OCR...
[1/150] marcopolo/paradiso_g7.jpg
  [OCR] Detectadas 5 filas x 6 columnas
  [→] 47 asientos identificados
  [→] 1 baño, 1 TV detectados
  [✓] Insertado en BD
...
[✓] Procesamiento completado
[✓] 150 planos en BD
```

⏱️ **Durará varios minutos** (10-30 min dependiendo de cantidad de planos)

```
[ ] Script ejecutado sin errores
[ ] Logs generados en logs/planos_ocr.log
[ ] Ver output sin "ERROR" ni "FAIL"
```

### ✅ Paso 4.2: Verificar BD actualizada
```sql
-- En phpMyAdmin o MySQL:
SELECT COUNT(*) as total_planos FROM carroceria_planos;
SELECT fabricante, COUNT(*) FROM carroceria_planos GROUP BY fabricante;
SELECT * FROM carroceria_planos LIMIT 3 \G
```

```
[ ] Al menos 20+ planos en tabla carroceria_planos
[ ] Distribucion_json contiene JSON válido
[ ] Asientos_total > 0
```

---

## PASO 5: LINKAR MODELOS (10 min)

### ✅ Paso 5.1: Actualizar modelo_vehiculo_transporte

La migración ya hizo esto, pero verificar:

```sql
SELECT idModelo, nombre, asientos_capacidad, idCarroceríaPlano 
FROM modelo_vehiculo_transporte;
```

```
[ ] Modelos 1-7 tienen idCarroceríaPlano asignado
[ ] Valores no son NULL
```

---

## PASO 6: FRONTEND - PARTE 1 (1 hora)

### ✅ Paso 6.1: Crear pasaje_detalle.php

Crear archivo: `c:\xampp\htdocs\metelebrasil_dev\pasaje_detalle.php`

**Usar template de:** `PLAN_PLANOS_REALES_MICROS.md` (FASE 3.3)

```php
<?php
require_once '../config/config.php';
require_once '../admin/classes/conexion.php';
require_once '../admin/classes/transporte.php';

$idModelo = $_GET['idModelo'] ?? 1;
$idViaje = $_GET['idViaje'] ?? 1;

$transporte = new Transporte();
$modelo = $transporte->getModelo($idModelo);

$stmt = $pdo->prepare("SELECT * FROM carroceria_planos WHERE idCarroceria = ?");
$stmt->execute([$modelo['idCarroceríaPlano']]);
$plano = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!-- HTML, CSS, JavaScript aquí (ver template) -->
```

```
[ ] Archivo pasaje_detalle.php creado
[ ] Incluye require correcto de clases
[ ] HTML layout con 2 columnas
[ ] Plano visual a la izquierda
[ ] Grid interactivo a la derecha
```

### ✅ Paso 6.2: Crear js/gestor_asientos.js

Crear archivo: `c:\xampp\htdocs\metelebrasil_dev\js\gestor_asientos.js`

**Usar template de:** `PLAN_PLANOS_REALES_MICROS.md` (FASE 3.3)

```javascript
class GestorAsientos {
  constructor(containerSelector, distribucion) {
    this.container = document.getElementById(containerSelector);
    this.distribucion = distribucion;
    this.asientosSeleccionados = [];
    this.renderizar();
  }
  
  renderizar() {
    // Código aquí (ver template)
  }
  
  toggleAsiento(elemento) {
    // Código aquí (ver template)
  }
}
```

```
[ ] Archivo js/gestor_asientos.js creado
[ ] Clase GestorAsientos con método renderizar()
[ ] Método toggleAsiento() implementado
[ ] JSON parsing correcto
```

### ✅ Paso 6.3: Agregar CSS

En `css/styles.css` o en `<style>` de pasaje_detalle.php, agregar:

```css
.container-asientos {
  padding: 20px;
}

.piso-asientos {
  margin-bottom: 30px;
}

.grid-piso {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(40px, 1fr));
  gap: 8px;
  margin-top: 15px;
}

.asiento {
  width: 40px;
  height: 40px;
  background-color: #e8f4f8;
  border: 2px solid #029ce2;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 12px;
  font-weight: bold;
  transition: all 0.2s;
}

.asiento:hover {
  background-color: #029ce2;
  color: white;
  transform: scale(1.1);
}

.asiento.seleccionado {
  background-color: #029ce2;
  color: white;
  border-color: #016fa0;
}

.elemento-especial {
  width: 40px;
  height: 40px;
  background-color: #f5f5f5;
  border: 1px solid #ccc;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  cursor: default;
}

.plano-referencia {
  position: sticky;
  top: 20px;
}
```

```
[ ] CSS agregado
[ ] Estilos responsivos
[ ] Colores coherentes con tema MeteleBrasil (#029ce2)
```

---

## PASO 7: TESTING (1 hora)

### ✅ Paso 7.1: Test local

```
Abrir en navegador: http://localhost/metelebrasil_dev/pasaje_detalle.php?idModelo=1
```

**Verificar:**
```
[ ] Página carga sin errores (revisar F12 Console)
[ ] Plano real se muestra a la izquierda
[ ] Grid de asientos se renderiza a la derecha
[ ] Hover en asientos funciona
[ ] Click en asientos cambia color
[ ] Elementos especiales (baño, TV) se muestran correctamente
[ ] Layout responsive en tablet (F12 → Device toolbar)
[ ] Layout responsive en móvil (simulado)
```

### ✅ Paso 7.2: Test múltiples modelos

```
[ ] Probar http://localhost/metelebrasil_dev/pasaje_detalle.php?idModelo=2
[ ] Probar http://localhost/metelebrasil_dev/pasaje_detalle.php?idModelo=3
[ ] Probar http://localhost/metelebrasil_dev/pasaje_detalle.php?idModelo=10
[ ] Todos cargan correctamente
[ ] Diferentes distribuciones se muestran
```

### ✅ Paso 7.3: Test navegadores

```
[ ] Chrome
[ ] Firefox
[ ] Edge
[ ] Safari (si está disponible)
[ ] Mobile (Chrome emulation)
```

### ✅ Paso 7.4: Test BD

```sql
-- Verificar datos insertados
SELECT COUNT(*) as total FROM carroceria_planos;
SELECT fabricante, COUNT(*) as cantidad FROM carroceria_planos GROUP BY fabricante;

-- Verificar JSON válido
SELECT idCarroceria, fabricante, JSON_EXTRACT(distribucion_json, '$.pisos[0].asientos') 
FROM carroceria_planos LIMIT 1;
```

```
[ ] Queries ejecutan sin errores
[ ] JSON válido en BD
[ ] Total de planos > 20
```

---

## PASO 8: INTEGRACIÓN CON RESERVA (1.5 horas)

### ✅ Paso 8.1: Linkar desde búsqueda de pasajes

En `buscar_pasajes.php` (cuando se cree), agregar link:

```php
<a href="pasaje_detalle.php?idModelo=<?= $viaje['idModelo'] ?>&idViaje=<?= $viaje['idViaje'] ?>">
  Ver asientos disponibles
</a>
```

```
[ ] Link funcionando
[ ] Parámetros URL correctos
[ ] Redirige a pasaje_detalle.php
```

### ✅ Paso 8.2: Guardar selección en sesión/localStorage

En `js/gestor_asientos.js`, agregar al final de toggleAsiento():

```javascript
// Guardar en localStorage
localStorage.setItem('asientosSeleccionados', JSON.stringify(this.asientosSeleccionados));

// O enviar al servidor con AJAX
fetch('admin/ctrl/ctrlPasajes.php?action=guardarAsientos', {
  method: 'POST',
  body: JSON.stringify({ asientos: this.asientosSeleccionados })
});
```

```
[ ] Selección persiste en página
[ ] Selección visible en devtools
[ ] Datos enviados al servidor correctamente
```

---

## PASO 9: DEPLOY A PRODUCCIÓN (30 min)

### ✅ Paso 9.1: Backup de BD producción

```bash
mysqldump -u usuario_prod -p base_prod > backup_antes_planos_$(date +%Y%m%d).sql
```

```
[ ] Backup creado
[ ] Archivo > 1 MB
```

### ✅ Paso 9.2: Deploy script SQL

```bash
ssh -p 65002 usuario@servidor.com
cd public_html
mysql -u usuario -p base_datos < migrations/014_planos_reales_mundocolectivo.sql
```

```
[ ] Tabla creada en producción
[ ] FK actualizada
```

### ✅ Paso 9.3: Deploy archivos

```bash
git add pasaje_detalle.php js/gestor_asientos.js
git commit -m "feat: integración planos reales mundocolectivo.com.ar"
git push origin main

# Luego SSH al servidor y git pull
```

```
[ ] Archivos en repositorio
[ ] Deployed a producción
[ ] Sin errores en logs
```

---

## PASO 10: MONITOREO (30 min)

### ✅ Paso 10.1: Verificar en producción

```
[ ] https://slateblue-snail-645791.hostingersite.com/pasaje_detalle.php
[ ] Página carga correctamente
[ ] Planos muestran
[ ] Sin errores en console (F12)
[ ] Responsive funciona
```

### ✅ Paso 10.2: Monitorear logs

```bash
tail -f logs/planos_ocr.log
tail -f /var/log/apache2/error.log
```

```
[ ] Sin errores nuevos
[ ] Performance aceptable
[ ] Uptime 100% primeras 24h
```

### ✅ Paso 10.3: Feedback de usuarios

```
[ ] Compartir link con equipo
[ ] Recopilar feedback
[ ] Realizar ajustes menores
```

---

## 🎉 ¡LISTO!

**Felicitaciones!** Acabas de integrar planos reales en MeteleBrasil.

### Próximos pasos opcionales:
- [ ] Descarga completa de 3,090 planos (puede correr de noche)
- [ ] Advanced filtering (ventana/pasillo/piso superior-inferior)
- [ ] Rating y reviews de rutas
- [ ] Historial de preferencias de usuario

---

## 📊 Métricas a Monitorear

```
Métrica                  Antes    Objetivo  Actual
─────────────────────────────────────────────────
Conversión %             2.5%     3.2-3.5%  ___
Tiempo promedio (min)    4.2      2.8-3.2   ___
Satisfacción (1-10)      6.8      8.5-9.0   ___
Cancelaciones %          15%      <10%      ___
CTR plano real           -        >60%      ___
```

---

## 🔧 Troubleshooting Rápido

| Problema | Solución |
|----------|----------|
| No descarga planos | Verificar internet, permisos de carpeta, robots.txt |
| OCR muy lento | Reducir cantidad de planos, aumentar RAM |
| BD llena | Hacer cleanup, backup, restore |
| Grid no renderiza | Verificar JSON válido (F12 console) |
| Imágenes no se muestran | Verificar ruta_imagen_local, permisos 755 |
| Timeout descarga | Aumentar timeout en config, hacer en lotes |

---

## 📞 Preguntas Frecuentes

**P: ¿Puedo omitir el OCR y hacer JSON manual?**  
R: Sí, es más rápido para primeras 9 modelos. Después automáticamente.

**P: ¿Cuánta banda ancha necesita?**  
R: ~500 MB para 3,090 planos (manejable).

**P: ¿Qué pasa si mundocolectivo.com.ar cambia?**  
R: Ajustar script, pero lo más probable es que siga igual por años.

**P: ¿Funciona offline?**  
R: Sí, después de descargar. Las imágenes están locales.

**P: ¿Se pueden actualizar planos?**  
R: Sí, reejecutar script con flag --update.

---

## ✨ Resultado Final

```
MeteleBrasil = Única plataforma Latam con planos REALES
    ↓
Usuarios confían más
    ↓
+20-40% conversión
    ↓
💰 Más ingresos
    ↓
🚀 ZARPADO! 🚀
```

---

**¡A trabajar!** 💪

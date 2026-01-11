# Log de Correcciones en Base de Datos

## 11 de Enero de 2026

### Problema Identificado
Las salidas de **paquetes turísticos (categoría 4)** tenían duraciones guardadas inconsistentemente:
- Algunas salidas: duraciones en **días** (10, 7) ✓ CORRECTO
- Otras salidas: duraciones en **horas sin convertir** (240, 168) ✗ INCORRECTO

Esto causaba que la visualización mostrara "240 Días - 168 Noches" en lugar de "10 Días - 7 Noches".

### Solución Aplicada

#### 1. Corrección de Datos en BD
- Identificadas: **44 salidas de paquetes** con `duracionMinima > 24`
- Ejecutado UPDATE:
  ```sql
  UPDATE servicio_salidas ss 
  JOIN servicio s ON ss.idServicio = s.idServicio 
  SET ss.duracionMinima = ss.duracionMinima/24, 
      ss.duracionMaxima = ss.duracionMaxima/24 
  WHERE s.idCategoria_servicio = 4 AND ss.duracionMinima > 24;
  ```
- Resultado: Todas las duraciones convertidas correctamente

#### 2. Mejoras en Código

**archivo: admin/classes/servicio.php**
- Agregados casteos explícitos a `float` e `int` en comparaciones
- Mejorada lógica de formateo para paquetes

**archivo: admin/ctrl/ctrlHorarios.php**
- Agregado `idCategoria_servicio` a respuesta AJAX de horarios
- Permite que JavaScript formatee duraciones correctamente según categoría

**archivo: js/traeHorariosDev.js**
- Agregado `parseInt()` para convertir `idCategoria_servicio` a número
- Mejorada lógica de formateo para paquetes (divide por 24 si > 24 horas)

### Verificación
- ✅ Servicio 735 (CÓRDOBA → CAMBORIÚ): Todas las salidas ahora muestran 10 y 7
- ✅ Todos los paquetes: Duraciones consistentes

### Archivos Modificados
- `admin/classes/servicio.php` (función `getDuracionServicio()`)
- `admin/ctrl/ctrlHorarios.php` (respuesta AJAX de horarios)
- `js/traeHorariosDev.js` (formateo de duración en JavaScript)
- Base de datos: 44 registros actualizados en `servicio_salidas`

### Próximos Pasos
- Verificar que no hay inconsistencias en otras categorías (¿duraciones mal guardadas en categorías 1, 2, 3, 5, 6?)

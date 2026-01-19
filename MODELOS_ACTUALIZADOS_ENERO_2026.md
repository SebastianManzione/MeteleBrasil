# ✓ Actualización de Modelos de Transporte - Enero 2026

## Resumen de Cambios

Se han actualizado todos los **9 modelos de vehículos** con nuevas distribuciones JSON realistas que incluyen los 5 nuevos elementos de infraestructura:

### Elementos Agregados
- **Y** = Volante del conductor (Black/Gold)
- **G** = Parabrisas (Sky Blue)
- **X** = Puerta de entrada (Magenta)
- **T** = TV (Blue)
- **K** = Cocina (Orange)
- **B** = Baño (Yellow)
- **C** = Cafetera (Brown)
- **P** = Pasillo (Dark Gray)
- **W** = Panorámico (Orange)
- **1** = Asiento estándar (Blue)
- **0** = Espacio vacío (Gray)

---

## Modelos Actualizados

### 1. **Chevallier King Premium** (idModelo=1)
- **Tipo:** Bus Ejecutivo
- **Capacidad:** 12 filas × 3 columnas = 36 asientos
- **Piso:** Único
- **Distribución:** 
  - Fila 1: Parabrisas - Volante - Parabrisas
  - Fila 2: Puerta - Asiento - Puerta (acceso frontal)
  - Filas 3-7: Asientos normales con pasillo central
  - Fila 8: TV - Pasillo - TV
  - Filas 9-12: Asientos traseros con pasillo

**Visualización en editor:** Próxima vez que entres a `admin/modeloVehiculosLista.php`, verás este layout gráficamente.

---

### 2. **Marcopolo Paradiso 1350** (idModelo=2)
- **Tipo:** Bus Estándar de Larga Distancia
- **Capacidad:** 10 filas × 4 columnas = 40 asientos
- **Piso:** Único
- **Distribución:**
  - Fila 1: Parabrisas × 4 (sección de cabina)
  - Fila 2: Puerta - 2 Asientos - Puerta (doble acceso)
  - Filas 3-6: Asientos con 2 pasillos
  - Fila 7: Cafetera - Pasillo - Pasillo - Cafetera (servicios)
  - Filas 8-10: Asientos restantes

---

### 3. **Scania K340** (idModelo=3)
- **Tipo:** Bus Intercity Premium
- **Capacidad:** 16 filas × 3 columnas = 48 asientos
- **Piso:** Único
- **Distribución:**
  - Fila 1: Parabrisas - Volante - Parabrisas
  - Fila 2: Puerta - Asiento - Puerta
  - Filas 3-8: Asientos normales
  - Fila 9: Baño - Pasillo - Baño (doble baño)
  - Filas 10-16: Asientos traseros con pasillo

---

### 4. **Boeing 737-800** (idModelo=4)
- **Tipo:** Avión Comercial
- **Capacidad:** 12 filas × 5 columnas = 60 asientos
- **Piso:** Único
- **Distribución:**
  - Fila 1: Parabrisas × 3 - Vacío - TV (cabina cockpit)
  - Fila 2: Puerta × 2 - 3 Asientos - Puerta (salida emergencia)
  - Filas 3-6: Asientos con pasillos
  - Fila 7: Baño - Pasillos - Baño (servicios)
  - Filas 8-12: Asientos de cola

---

### 5. **Ferry Estándar - Bac3000** (idModelo=5)
- **Tipo:** Ferri Fluvial/Costero
- **Capacidad:** 14 filas × 5 columnas = 70 pasajeros
- **Piso:** Único (Cubierta Principal)
- **Distribución:**
  - Fila 1: Parabrisas × 5 (cabina capitanía)
  - Fila 2: Puerta × 2 - Asientos - Puerta
  - Filas 3-7: Asientos con pasillos
  - Fila 8: Cafetera en extremos (servicio de snacks)
  - Filas 9-14: Asientos adicionales para pasajeros

---

### 6. **Marcopolo Doble Piso G7** (idModelo=6)
- **Tipo:** Bus Doble Piso Premium
- **Capacidad:** 2 Pisos (Doble Deck)
  - **Piso 1 (Superior):** 5 filas × 6 columnas
  - **Piso 2 (Inferior):** 5 filas × 6 columnas
- **Total:** ~50 asientos
- **Distribución Superior (Planta Alta):**
  - Fila 1: Parabrisas × 3 - Volante - Parabrisas × 1
  - Fila 2: Puerta - 4 Asientos - Puerta
  - Filas 3-5: Asientos con Panorámicos y TV
- **Distribución Inferior (Planta Baja):**
  - Filas 1-4: Asientos normales
  - Fila 5: Baños - Pasillo - Baños

---

### 7. **Mercedes Doble Piso Comfort** (idModelo=7) ⭐ PREMIUM
- **Tipo:** Bus Doble Piso Ejecutivo
- **Capacidad:** 2 Pisos
  - **Piso 1 (Superior):** 5 filas × 5 columnas
  - **Piso 2 (Inferior):** 5 filas × 5 columnas
- **Total:** ~48 asientos
- **Distribución Superior (Planta Alta):**
  - Fila 1: Parabrisas × 3 - Volante - Parabrisas
  - Fila 2: Puerta - Panorámicos × 3 - Puerta (ventanas grandes)
  - Filas 3-5: Panorámicos laterales + Asientos centrales + TV
- **Distribución Inferior (Planta Baja):**
  - Filas 1-3: Asientos normales
  - Fila 4: Cocina - Pasillo - Pasillo - Cafetera
  - Fila 5: Baños - Pasillo - Baños

**Este es el modelo más lujoso con TV, vista panorámica, cocina completa y servicios.**

---

### 8. **Micro Ejecutivo Brasileño** (idModelo=8)
- **Tipo:** Minibus de Lujo
- **Capacidad:** 8 filas × 5 columnas = 40 asientos
- **Piso:** Único
- **Distribución:**
  - Fila 1: Parabrisas × 3 - Volante - Parabrisas
  - Fila 2: Puerta - Panorámicos × 3 - Puerta
  - Filas 3-5: Panorámicos + Asientos + TV
  - Fila 6: Baños - Pasillo - Baños
  - Fila 7: Cocina - Pasillo - Cafetera
  - Fila 8: Panorámicos - Pasillo - Panorámicos

**Modelo ejecutivo brasileño con todos los servicios.**

---

### 9. **Ferry Fluvial** (idModelo=9) 🚢 ESPECIAL
- **Tipo:** Ferri Grande (Río/Lago)
- **Capacidad:** 20 filas × 20 columnas = 400+ pasajeros
- **Piso:** Único (Cubierta Ferry)
- **Distribución:** Layout tipo matriz con:
  - Fila 1: Parabrisas × 20 (cabina)
  - Filas 2-20: Asientos en bloques de 4 columnas separados por pasillos
  - Servicios distribuidos: Cafeterías (C) cada 5 columnas
  - Baños (B) cada 5 columnas también
  - Múltiples puertas de acceso

**Ferry masivo con capacidad para 400+ pasajeros, múltiples servicios y salidas de emergencia.**

---

## Verificación en Base de Datos

```sql
SELECT idModelo, nombre, JSON_LENGTH(distribucion_json, '$.pisos[0].asientos') as filas_json 
FROM modelo_vehiculo_transporte 
WHERE habilitado=1 
ORDER BY idModelo;
```

**Resultado:**
| idModelo | nombre                      | filas_json |
|----------|-----------------------------| ---------- |
| 1        | Chevallier King Premium     | 12         |
| 2        | Marcopolo Paradiso 1350     | 10         |
| 3        | Scania K340                 | 16         |
| 4        | Boeing 737-800              | 12         |
| 5        | Ferry Estándar - Bac3000    | 14         |
| 6        | Marcopolo Doble Piso G7     | 5 (por piso)|
| 7        | Mercedes Doble Piso Comfort | 5 (por piso)|
| 8        | Micro Ejecutivo Brasileño   | 8          |
| 9        | Ferry Fluvial               | 20         |

✅ **Todos los modelos actualizados correctamente**

---

## Próximos Pasos

1. **Visualizar en el Editor** → Ir a `admin/modeloVehiculosLista.php` y hacer click en cada modelo para ver su nuevo layout gráfico
2. **Crear Viajes** → Usar `admin/viajeTransporteAlta.php` y seleccionar un modelo
3. **Configurar Tarifas por Segmento** → Usar `admin/viajeSegmentosPreciosEditor.php` con los nuevos modelos
4. **Testing Frontend** → Próxima fase será crear búsqueda de pasajes con estos modelos

---

## Commits Git Recomendados

```bash
git add migrations/actualizar_modelos_completos.sql
git commit -m "feat: actualizar 9 modelos de transporte con distribuciones JSON realistas

- Agregados elementos de infraestructura: Volante (Y), Parabrisas (G), Puerta (X), TV (T), Cocina (K)
- Distribuciones realistas para cada tipo de transporte
- Modelos doble piso con separación de plantas
- Ferry fluvial con layout masivo (400+ pasajeros)
- Modelos ejecutivos con servicios premium (TV, cocina, panorámicos)
"
```

---

## Visualización Rápida en Admin

**Para ver los modelos con sus nuevos layouts:**
1. Ir a: `http://localhost/metelebrasil_dev/admin/modeloVehiculosLista.php`
2. Hacer click en botón "Editar" de cada modelo
3. Ver el grid visual con los 15 elementos diferentes
4. El layout debe reflejar la estructura realista de cada vehículo

---

**Estado:** ✅ COMPLETADO - Todos los 9 modelos actualizados con infraestructura realista
**Fecha:** Enero 2026
**Base de datos:** metelebrasil_experimental

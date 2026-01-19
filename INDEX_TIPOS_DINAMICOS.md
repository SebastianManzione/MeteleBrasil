# 📚 Índice Completo: Sistema de Tipos Dinámicos

**Última actualización:** 12 Enero 2026

---

## 🎯 Acceso Rápido

| Recurso | URL/Archivo |
|---------|------------|
| **Gestionar Tipos** | [admin/tiposParadaLista.php](admin/tiposParadaLista.php) |
| **Nuevo Tipo** | [admin/tiposParadaAlta.php](admin/tiposParadaAlta.php) |
| **Terminales** | [admin/terminalesLista.php](admin/terminalesLista.php) |
| **Rutas** | [admin/rutasTransporteLista.php](admin/rutasTransporteLista.php) |
| **Validación** | [validar_tipos_dinamicos.php](validar_tipos_dinamicos.php) |

---

## 📖 Documentación

### Documentos de Referencia
1. **[IMPLEMENTACION_TIPOS_DINAMICOS.md](IMPLEMENTACION_TIPOS_DINAMICOS.md)**
   - Documentación técnica completa
   - Arquitectura de BD
   - Funciones backend
   - Ejemplos de uso
   - ~400 líneas

2. **[RESUMEN_TIPOS_DINAMICOS_ENERO_2026.md](RESUMEN_TIPOS_DINAMICOS_ENERO_2026.md)**
   - Resumen ejecutivo de la sesión
   - Trabajo realizado fase por fase
   - Validación ejecutada
   - Checklist final
   - ~250 líneas

3. **Este archivo (INDEX_TIPOS_DINAMICOS.md)**
   - Guía de navegación
   - Referencias cruzadas
   - Estructura del sistema

---

## 🗂️ Estructura de Archivos

### Administración de Tipos

```
admin/
├── tiposParadaLista.php          (211 líneas)
│   └── DataTable de tipos
│   └── Ver, editar, eliminar
│   └── Mostrar distribución de paradas
│
├── tiposParadaAlta.php           (320 líneas)
│   ├── Formulario de creación/edición
│   ├── Selector de icono Font Awesome
│   ├── Color picker
│   └── Presets sugeridos
│
├── tiposParadaEdita.php          (redireccionador)
│   └── Redirige a tiposParadaAlta.php?id=X
│
└── ctrl/
    └── ctrlTiposParada.php       (190 líneas)
        ├── action=insert
        ├── action=update
        ├── action=delete
        └── action=get_all
```

### Terminales y Paradas

```
admin/
├── terminalesLista.php           (ACTUALIZADO - tipos dinámicos)
│   └── Ahora usa getAllParadas()
│   └── Muestra tipo, icono, color
│
└── rutaTransporteParadas.php     (ACTUALIZADO - agrupación)
    └── Selector groupby tipo
    └── Atributos data-icon y data-color
```

### Backend

```
admin/classes/
├── transporte.php                (ACTUALIZADO - 7 funciones nuevas)
│   ├── getAllParadas()            ← JOINs con tipo_parada
│   ├── getParada()                ← JOINs con tipo_parada
│   ├── getParadasPorTipo()        ← JOINs con tipo_parada
│   ├── getParadasPorTipoPrada()   ← Filtro por ID tipo
│   ├── getParadasPorCiudad()      ← JOINs con tipo_parada
│   ├── getAllTiposParada()        ← Nueva función
│   ├── getTipoParada()            ← Nueva función
│   ├── getTipoParadaPorNombre()   ← Nueva función
│   ├── insertTipoParada()         ← Nueva función
│   ├── updateTipoParada()         ← Nueva función
│   └── deleteTipoParada()         ← Nueva función (smart delete)
```

### Scripts de Soporte

```
Raíz/
├── migrar_tipos_paradas_dinamicos_v2.php    (320 líneas)
│   └── Ejecutar vía browser
│   └── 5 pasos de migración
│   └── Crea tabla + tipos + FK
│
├── validar_tipos_dinamicos.php              (260 líneas)
│   └── Ejecutar vía browser
│   └── 9 validaciones automáticas
│   └── Muestra distribución
│
├── IMPLEMENTACION_TIPOS_DINAMICOS.md        (400 líneas)
│   └── Documentación técnica
│
└── RESUMEN_TIPOS_DINAMICOS_ENERO_2026.md    (250 líneas)
    └── Resumen ejecutivo
```

---

## 🗄️ Estructura de Base de Datos

### Tabla `tipo_parada` (NUEVA)
```sql
CREATE TABLE tipo_parada (
  idTipoPrada INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  icono VARCHAR(50) DEFAULT 'fa-map-marker-alt',
  color VARCHAR(7) DEFAULT '#6c757d',
  habilitado TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índices
CREATE INDEX idx_nombre ON tipo_parada(nombre);
CREATE INDEX idx_habilitado ON tipo_parada(habilitado);
```

### Tabla `parada` (MODIFICADA)
```sql
ALTER TABLE parada ADD COLUMN idTipoPrada INT DEFAULT NULL;
ALTER TABLE parada ADD INDEX idx_idTipoPrada(idTipoPrada);
ALTER TABLE parada ADD CONSTRAINT fk_parada_tipo 
  FOREIGN KEY (idTipoPrada) REFERENCES tipo_parada(idTipoPrada) 
  ON DELETE RESTRICT ON UPDATE CASCADE;
```

### Tipos Predefinidos
```sql
INSERT INTO tipo_parada (nombre, icono, color) VALUES
('terminal', 'fa-map-marker-alt', '#007bff'),
('hotel', 'fa-hotel', '#ff6b6b'),
('estación', 'fa-train', '#ffc107'),
('puerto', 'fa-anchor', '#17a2b8'),
('aeropuerto', 'fa-plane', '#28a745'),
('casa', 'fa-home', '#6c757d'),
('intermedia', 'fa-dot-circle', '#6f42c1');
```

---

## 🔗 Flujos de Trabajo

### Flujo 1: Crear Nuevo Tipo
```
1. admin/tiposParadaAlta.php
   ├── Ingresar nombre (validate unique)
   ├── Seleccionar icono Font Awesome
   ├── Elegir color con color picker
   ├── Marcar como habilitado
   └── POST a ctrl/ctrlTiposParada.php?action=insert
2. Validación en controller
   ├── Verificar nombre unique
   ├── Validar icono y color
   └── Insertar en tipo_parada
3. Redirecciona a tiposParadaLista.php
```

### Flujo 2: Editar Tipo
```
1. admin/tiposParadaLista.php → botón Editar
2. Redirecciona a tiposParadaAlta.php?id=X
3. Cargar datos en formulario
4. Modificar nombre, icono, color, estado
5. POST a ctrl/ctrlTiposParada.php?action=update
6. Validación + actualización
7. Redirecciona a lista
```

### Flujo 3: Eliminar Tipo
```
1. admin/tiposParadaLista.php → botón Eliminar
2. Verificar si hay paradas vinculadas
3. Si NO hay: DELETE directamente
4. Si SÍ hay: mostrar error "No se puede eliminar"
5. POST a ctrl/ctrlTiposParada.php?action=delete
6. Smart delete en controller
```

### Flujo 4: Agregar Parada con Tipo
```
1. admin/terminalesLista.php → Nuevo
2. Llenar datos básicos (nombre, dirección, etc.)
3. En dropdown de tipo: seleccionar del tipo_parada
4. Guardador automáticamente asigna idTipoPrada
5. Mostrar con icono y color del tipo
```

### Flujo 5: Usar Tipo en Ruta
```
1. admin/rutasTransporteLista.php → Paradas
2. Select agrupa paradas por tipo (optgroup)
3. Seleccionar parada de tipo específico
4. Agregar a ruta con su tipo
5. Parada vinculada preserva tipo
```

---

## 🔐 Validaciones Implementadas

### En Controller (`ctrlTiposParada.php`)
- ✅ Nombre unique (INSERT y UPDATE)
- ✅ Nombre required
- ✅ Icono valida formato Font Awesome
- ✅ Color valida hexadecimal
- ✅ ID válido en UPDATE/DELETE
- ✅ FK validation antes de DELETE (impide orfandad)

### En Base de Datos
- ✅ UNIQUE constraint en nombre
- ✅ Foreign Key ON DELETE RESTRICT
- ✅ Foreign Key ON UPDATE CASCADE
- ✅ Índices en campos de búsqueda

### En Frontend (`tiposParadaAlta.php`)
- ✅ Nombre required
- ✅ Icono required
- ✅ Color required
- ✅ Preview en tiempo real
- ✅ Presets sugeridos

---

## 📊 Datos Actuales

### Tipos Creados (7)
| ID | Nombre | Icono | Color | Paradas |
|----|--------|-------|-------|---------|
| 1 | terminal | fa-map-marker-alt | #007bff | 32 |
| 2 | hotel | fa-hotel | #ff6b6b | 0 |
| 3 | estación | fa-train | #ffc107 | 0 |
| 4 | puerto | fa-anchor | #17a2b8 | 0 |
| 5 | aeropuerto | fa-plane | #28a745 | 0 |
| 6 | casa | fa-home | #6c757d | 0 |
| 7 | intermedia | fa-dot-circle | #6f42c1 | 0 |

### Paradas por Tipo
- terminal: 32 (Rosario, Buenos Aires, Córdoba, etc.)
- hotel: 0 (disponible para nuevas)
- estación: 0 (disponible para nuevas)
- puerto: 0 (disponible para nuevas)
- aeropuerto: 0 (disponible para nuevas)
- casa: 0 (disponible para nuevas)
- intermedia: 0 (disponible para nuevas)

---

## 🧪 Testing Completado

### Pruebas Unitarias
- ✅ `validar_tipos_dinamicos.php` ejecutado
  - 9/9 validaciones pasaron
  - 7/7 tipos verificados
  - 32/32 paradas con tipo
  - FK constraint activo

### Pruebas Funcionales
- ✅ Crear tipo nuevo
- ✅ Editar tipo existente
- ✅ Listar tipos con distribución
- ✅ Eliminar tipo (con validación FK)
- ✅ Ver paradas con tipo dinámico
- ✅ Agregar parada a ruta filtrando por tipo
- ✅ Preview de icono y color

### Pruebas de Integración
- ✅ getAllParadas() retorna tipo_nombre, icono, color
- ✅ terminalesLista.php muestra tipos dinámicos
- ✅ rutaTransporteParadas.php agrupa por tipo
- ✅ FK previene orfandad de paradas

---

## 🚀 Cómo Empezar

### 1. Ver el Sistema en Acción
```
Paso 1: http://localhost/metelebrasil_dev/validar_tipos_dinamicos.php
Paso 2: http://localhost/metelebrasil_dev/admin/terminalesLista.php
Paso 3: http://localhost/metelebrasil_dev/admin/tiposParadaLista.php
```

### 2. Crear Nuevo Tipo
```
1. admin/tiposParadaAlta.php
2. Ingresar datos (ej: "campamento")
3. Seleccionar icono y color
4. Guardar
```

### 3. Usar en Parada
```
1. admin/terminalesLista.php
2. Crear nueva parada
3. Seleccionar tipo "campamento"
4. Ver resultado con icono y color
```

---

## 📋 Checklist de Funcionalidad

- ✅ Tabla tipo_parada creada
- ✅ 7 tipos estándar insertados
- ✅ FK constraint activo
- ✅ Funciones de lectura retornan tipo
- ✅ Funciones de CRUD de tipos
- ✅ terminalesLista.php muestra tipos
- ✅ rutaTransporteParadas.php agrupa tipos
- ✅ Controller AJAX completo
- ✅ Validaciones activas
- ✅ Scripts de migración ejecutados
- ✅ Scripts de validación pasados

---

## 🔗 Referencias Cruzadas

### Funciones Relacionadas
- `getAllParadas()` → retorna con tipo_nombre, icono, color
- `getParada($id)` → retorna parada completa con tipo
- `getParadasRuta($idRuta)` → sigue usando getAllParadas()
- `getAllTiposParada()` → nuevo: lista todos los tipos

### Tablas Relacionadas
- `tipo_parada` → FK desde parada.idTipoPrada
- `parada` → contiene idTipoPrada
- `ruta_paradas` → usa parada.idParada (sin cambios)
- `servicio_salidas` → usa ruta_paradas (sin cambios)

### Controllers
- `ctrlRutasTransporte.php` → usa getAllParadas()
- `ctrlParadasRuta.php` → usa getAllParadas()
- `ctrlTiposParada.php` → nuevo: CRUD de tipos

---

## 📞 Soporte

### Para Agregar Nuevo Tipo
1. admin/tiposParadaAlta.php
2. Rellenar nombre, icono, color
3. Guardar

### Para Listar Tipos
1. admin/tiposParadaLista.php
2. Ver distribución de paradas

### Para Validar Sistema
1. validar_tipos_dinamicos.php
2. Ver status de todas las tablas

### Para Debuggear
1. Revisar `admin/classes/transporte.php`
2. Revisar `admin/ctrl/ctrlTiposParada.php`
3. Ejecutar `validar_tipos_dinamicos.php`

---

## 💾 Scripts de Utilidad

### Migración
```
Ejecutar en: http://localhost/metelebrasil_dev/migrar_tipos_paradas_dinamicos_v2.php
Crea: tabla tipo_parada + 7 tipos + FK constraint
```

### Validación
```
Ejecutar en: http://localhost/metelebrasil_dev/validar_tipos_dinamicos.php
Verifica: todas las tablas, funciones, datos
```

---

## 🎓 Casos de Uso

1. **Hotel:** Tipo=2, Icono=fa-hotel, Color=#ff6b6b
2. **Estación de Tren:** Tipo=3, Icono=fa-train, Color=#ffc107
3. **Puerto:** Tipo=4, Icono=fa-anchor, Color=#17a2b8
4. **Aeropuerto:** Tipo=5, Icono=fa-plane, Color=#28a745
5. **Casa Particular:** Tipo=6, Icono=fa-home, Color=#6c757d
6. **Parada Intermedia:** Tipo=7, Icono=fa-dot-circle, Color=#6f42c1
7. **Campamento (Personalizado):** Tipo=8+, Icono=fa-tent, Color=#2ecc71

---

## 🔄 Próximos Pasos

1. **Frontend:** Mostrar tipos en búsqueda de paradas
2. **Reportes:** Estadísticas por tipo de parada
3. **Integración:** Pasar tipo a carrito/checkout
4. **Mobile:** Mostrar tipos en app móvil
5. **Automatización:** Scripts de backup por tipo

---

*Índice generado: 12 Enero 2026*  
*Sistema: MeteleBrasil Transporte v2.0*  
*Branch: feature/cambios-grosos*

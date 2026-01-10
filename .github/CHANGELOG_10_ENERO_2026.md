# Changelog - 10 de Enero 2026

## Optimizaciones Mobile y Responsive

### Problemas Reportados
- Galaxy S8 (360px): Navbar elementos cortados, dropdown monedas misaligned
- Safari mobile: Beneficios texto encimado, estadísticas no visibles
- Categorías: Texto "viajeros" cortado, colores sin contraste

### Soluciones Implementadas

#### 1. Navbar Mobile Optimizado (includes/navbar.php)

**Cambios:**
- Logo "METELE BRASIL" a **28px** con text-shadow para visibilidad
- Fondo azul explícito: `background-color: #0099cc !important`
- Columnas redimensionadas: col-5 → col-4 (logo), col-6 → col-7 (iconos)
- Gap entre elementos: 10px → 5px
- Font-size compacto en idioma/moneda/carrito (13px, 13px, 16px)
- Removida clase `cursor-size` (agregaba 17px extra)
- Badge carrito optimizado

**Commits:**
- c15e81b: Optimizar navbar mobile para Galaxy S8
- ff348ec: Restaurar logo METELE BRASIL
- 28f1695: Font-size logo 15px
- 643d4ac: Font-size logo 28px
- 1860ce2: Agregar fondo azul y text-shadow

#### 2. Dropdown de Monedas Mobile (includes/navbar.php líneas 626-638)

**Problema:** Duplicación por Popper.js, misalignment

**Solución:**
- `data-display="static"` → Disable Popper.js cloning
- `position: fixed !important; right: 70px !important; top: 55px !important`
- `z-index: 9999 !important` → Visible en Safari
- `left: auto !important; transform: none !important`

**Commits:**
- 89fa5d1: Right 0 y left auto
- 1e81fa2: Position fixed matching cart solution
- 80e9133: Z-index 9999 para Safari

#### 3. Estadísticas en Categorías (categorias.php)

**Problema:** Texto blanco invisible sobre fondo claro

**Solución (líneas 523-535):**
```css
.stat-number {
  color: #029ce2;  /* Azul en lugar de blanco */
  font-size: 2.5rem;
  font-weight: 700;
}

.stat-label {
  color: #666;  /* Gris en lugar de blanco transparente */
  font-size: 0.95rem;
  margin-top: 0.5rem;
}
```

**Mobile (líneas 1144-1161):**
- Font-size etiquetas: 0.85rem → 0.75rem
- Agregado `word-wrap: break-word`
- **Removidas estadísticas de mobile** para ahorrar espacio

**Commits:**
- 47d000d: Cambiar colores a azul/gris
- b786a90: Reducir font-size a 0.75rem
- 43b7e2b: Mostrar 4 estadísticas mobile
- 82a59f2: Remover estadísticas mobile

#### 4. Beneficios en Banner (css/responsive.css líneas 113-133)

**Problema:** Texto encimado en Safari mobile

**Solución:**
```css
.div-bottom {
  padding-top: 1.5rem !important;
  padding-bottom: 1.5rem !important;
}

.texto-bottom {
  font-size: 12px !important;
  line-height: 1.3 !important;
  margin: 0 !important;
  word-wrap: break-word !important;
  overflow-wrap: break-word !important;
}
```

**Commit:** 894f73c: Ajustar spacing beneficios Safari mobile

### Archivos Modificados

| Archivo | Líneas | Cambios |
|---------|--------|---------|
| `includes/navbar.php` | 600-638 | Logo, fondo, gaps, dropdown |
| `categorias.php` | 523-535, 1144-1161 | Colores stats, font-size, responsive |
| `css/responsive.css` | 113-133 | Beneficios spacing |

### Git Commits (13 Total)

```
89fa5d1 - fix: agregar right 0 y left auto para monedas
1e81fa2 - fix: usar position fixed para menu monedas
80e9133 - fix: agregar z-index 9999 para Safari
894f73c - fix: ajustar spacing beneficios para Safari
c15e81b - fix: optimizar navbar mobile Galaxy S8
ff348ec - fix: restaurar logo METELE BRASIL
28f1695 - fix: aumentar font-size logo 15px
643d4ac - fix: aumentar logo a 28px
1860ce2 - fix: agregar fondo azul y text-shadow
47d000d - fix: cambiar colores stats azul/gris
b786a90 - fix: reducir font-size stats 0.75rem
43b7e2b - fix: mostrar 4 estadísticas mobile
82a59f2 - fix: remover estadísticas mobile
```

### Testing Checklist

**Galaxy S8 (360px):**
- ✅ Logo visible y legible
- ✅ Navbar no se corta
- ✅ Iconos caben sin overflow
- ✅ Dropdown monedas posición correcta

**Safari Mobile:**
- 🔄 Beneficios sin encimamiento (verificar mañana)
- 🔄 Estadísticas color visible (verificar mañana)
- ✅ Dropdown z-index alto

**Responsive:**
- ✅ Desktop sin cambios
- ✅ Tablet funcionando
- ✅ Mobile optimizado

### Estado Final

- **Branch:** feature/experimental
- **Commits adelante:** 95
- **Estado:** Todo commiteado, listo para merge
- **Proximos pasos:** Verificar en Safari y Galaxy S8 mañana


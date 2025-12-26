# CKEditor 5 - Implementación en MeteleBrasil

## Ubicación de archivos

- **Editor copiado desde optimo_dev:** `admin/ckeditor/` (CKEditor 4 - LOCAL)
- **CKEditor 5 (CDN - RECOMENDADO):** Se carga desde CDN en `includes/ckeditor_init.php`
- **Configuración:** `admin/includes/ckeditor_init.php`
- **Ejemplo de uso:** `admin/ckeditor_ejemplo.php`

## Instalación

✅ **Ya está instalado y configurado**

Los archivos se copiaron desde el proyecto `optimo_dev` y se crearon los helpers necesarios.

## Uso básico

### 1. En tu archivo PHP, incluir el helper

```php
<?php include("includes/ckeditor_init.php"); ?>
```

Incluir este archivo DESPUÉS del `footer.php` para que jQuery esté disponible.

### 2. Crear un textarea con un ID único

```html
<textarea id="miEditor" name="contenido">
  Contenido inicial aquí...
</textarea>
```

### 3. Inicializar el editor con JavaScript

**Editor completo (todas las funciones):**
```javascript
let editorInstance;

$(document).ready(function() {
  initCKEditor('miEditor').then(editor => {
    editorInstance = editor;
    console.log('Editor inicializado');
  });
});
```

**Editor simple (solo funciones básicas):**
```javascript
let editorInstance;

$(document).ready(function() {
  initSimpleCKEditor('miEditor').then(editor => {
    editorInstance = editor;
    console.log('Editor simple inicializado');
  });
});
```

### 4. Obtener contenido para guardar

```javascript
// Obtener HTML
const contenidoHTML = editorInstance.getData();

// Enviar vía AJAX
$.post('ctrl/guardar.php', {
  contenido: contenidoHTML
}, function(res) {
  if (res.success) {
    alert('Guardado correctamente');
  }
});
```

## Configuración personalizada

Puedes pasar opciones personalizadas al inicializador:

```javascript
initCKEditor('miEditor', {
  placeholder: 'Escribe tu contenido aquí...',
  toolbar: {
    items: [
      'heading', '|',
      'bold', 'italic', '|',
      'link', 'bulletedList', 'numberedList'
    ]
  }
});
```

## Funciones disponibles

### initCKEditor(elementId, customConfig)
Inicializa un editor completo con todas las funciones:
- Formatos de texto (negrita, cursiva, subrayado)
- Encabezados (H1-H4)
- Listas (ordenadas y desordenadas)
- Enlaces e imágenes
- Tablas
- Colores de fuente y fondo
- Código y bloques de código
- Y muchas más...

### initSimpleCKEditor(elementId)
Inicializa un editor simplificado con solo:
- Encabezados básicos
- Negrita, cursiva, subrayado
- Enlaces
- Listas
- Deshacer/rehacer

## Ejemplo completo

Ver el archivo `admin/ckeditor_ejemplo.php` para ver una implementación completa con:
- Editor completo
- Editor simple
- Guardado de contenido
- Vista previa

## Acceder al ejemplo

http://localhost/metelebrasil_dev/admin/ckeditor_ejemplo.php

## Uso en Blog, Servicios, etc.

### Para el Blog (blogAlta.php, blogEditar.php):

```php
<!-- En el form, después del campo título -->
<div class="form-group">
  <label>Contenido del artículo</label>
  <textarea id="editorBlog" name="contenido"><?= htmlspecialchars($contenido ?? '') ?></textarea>
</div>

<!-- Antes del cierre de body -->
<?php include("includes/ckeditor_init.php"); ?>

<script>
let editorBlog;
$(document).ready(function() {
  initCKEditor('editorBlog').then(editor => {
    editorBlog = editor;
  });
});

function guardarArticulo() {
  const contenido = editorBlog.getData();
  // Enviar vía AJAX o form submit
  $('#formBlog').submit();
}
</script>
```

### Para Servicios (descripción larga):

```php
<div class="form-group">
  <label>Descripción detallada</label>
  <textarea id="editorServicio" name="descripcion_larga"><?= htmlspecialchars($servicio['descripcion'] ?? '') ?></textarea>
</div>

<?php include("includes/ckeditor_init.php"); ?>

<script>
let editorServicio;
$(document).ready(function() {
  initCKEditor('editorServicio', {
    placeholder: 'Describe el servicio en detalle...',
    toolbar: {
      items: [
        'heading', '|',
        'bold', 'italic', 'underline', '|',
        'link', 'bulletedList', 'numberedList', '|',
        'undo', 'redo'
      ]
    }
  }).then(editor => {
    editorServicio = editor;
  });
});
</script>
```

## Tips importantes

1. **Guardar contenido:** Siempre usar `editor.getData()` para obtener el HTML
2. **Múltiples editores:** Crear una variable distinta para cada instancia
3. **Validación:** Verificar que el editor esté inicializado antes de obtener datos
4. **Altura mínima:** Ajustar con CSS `.ck-editor__editable { min-height: 300px; }`
5. **Idioma:** Ya está configurado en español ('es')

## Diferencias CKEditor 4 vs CKEditor 5

| Característica | CKEditor 4 (local) | CKEditor 5 (CDN) |
|----------------|-------------------|------------------|
| Ubicación | `/admin/ckeditor/` | CDN (internet) |
| Inicialización | `CKEDITOR.replace()` | `CKEDITOR.ClassicEditor.create()` |
| Obtener datos | `.getData()` | `.getData()` |
| Recomendación | ❌ Obsoleto | ✅ Usar este |

## Soporte

- Documentación oficial: https://ckeditor.com/docs/ckeditor5/latest/
- Versión usada: CKEditor 5.38.1 Super Build
- Traducción: Español (es)

## Archivos creados

1. ✅ `/admin/ckeditor/` - CKEditor 4 copiado desde optimo_dev
2. ✅ `/admin/includes/ckeditor_init.php` - Helper de inicialización CKEditor 5
3. ✅ `/admin/ckeditor_ejemplo.php` - Página de ejemplo
4. ✅ `/admin/CKEDITOR_README.md` - Esta documentación

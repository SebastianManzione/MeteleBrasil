# MeteleBrasil - Sistema de Reservas Online

## Descripción
Plataforma online de reserva de actividades, excursiones y paseos en barco en Brasil. Sistema completo con soporte multi-moneda, multi-idioma, geolocalización e integración de pagos.

## Stack Tecnológico
- **Backend**: PHP 7.x con PDO/MySQLi
- **Frontend**: jQuery 3.6.0, Bootstrap 4.6.0
- **Base de Datos**: MySQL
- **Pagos**: PayPal, MercadoPago (AR/BR)
- **Servidor**: XAMPP (desarrollo), Apache (producción)

## Estructura de Ramas

### `main`
Rama de **producción**. Código estable desplegado en servidor.

### `dev`
Rama de **desarrollo** activa. Incluye todas las funcionalidades probadas y estables:
- ✅ Sistema de filtros combinables (precio + distancia)
- ✅ Cupones de descuento con persistencia de sesión
- ✅ Fix de redondeo de precios (R$ 90,00 correcto)
- ✅ Botones de selección visual (horarios y puntos de embarque)
- ✅ Servicios relacionados "También te puede interesar"
- ✅ Sistema de geolocalización con Haversine
- ✅ Responsive design completo

### `developer`
Rama de experimentación y features en desarrollo.

## Flujo de Trabajo
1. Desarrollar nuevas features en `dev` o crear branch desde `dev`
2. Testing exhaustivo en ambiente local (manzftp.ddns.net)
3. Merge a `main` cuando esté listo para producción
4. Deploy manual a servidor de producción

## Instalación Local
```bash
# Clonar repositorio
git clone https://github.com/SebastianManzione/MeteleBrasil.git
cd MeteleBrasil

# Cambiar a rama dev
git checkout dev

# Configurar base de datos
# - Importar SQL en phpMyAdmin
# - Ajustar credenciales en config/db.php y admin/classes/conexion.php

# Iniciar XAMPP
# - Apache + MySQL
# - Acceder a http://localhost/metelebrasil_dev
```

## Documentación
- **Instrucciones Copilot**: `.github/copilot-instructions.md`
- **Filtros Combinables**: `INSTRUCCIONES_FILTROS_COMBINABLES.md`
- **Propuesta Comercial**: `PROPUESTA_COMERCIAL_VENTA.md`

## Contacto
**SisteManz** - Sistema de Reservas by Sebastian Manzione 
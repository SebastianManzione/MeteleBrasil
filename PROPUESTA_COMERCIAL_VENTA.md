# PROPUESTA COMERCIAL - PLATAFORMA METELEBRASIL

**Fecha:** 30 de diciembre de 2025  
**Versión:** 1.0  
**Tipo de Documento:** Propuesta Comercial

---

## 📋 RESUMEN EJECUTIVO

Se propone la **venta de código fuente + base de datos** de la plataforma MeteleBrasil, un sistema completo de reserva online de actividades, excursiones y paseos en barco.

La plataforma incluye:
- ✅ Código fuente completo (código PHP procedural + JavaScript)
- ✅ Base de datos MySQL con estructura relacional
- ✅ Panel administrativo totalmente funcional
- ✅ Integración de pasarelas de pago (PayPal, MercadoPago)
- ✅ Sistema multi-moneda y multi-idioma
- ✅ Documentación técnica completa

---

## 🎯 DESCRIPCIÓN DEL SISTEMA

### Características Principales

**1. Funcionalidad Core**
- Catálogo de servicios turísticos (excursiones, paseos en barco, actividades)
- Sistema de salidas con horarios y disponibilidad
- Tarifas dinámicas por rango de edad
- Servicios adicionales (traslados, comidas, tours extras)
- Carrito de compra integrado
- Gestión completa de reservas

**2. Pagos y Facturación**
- Integración PayPal (Sandbox y Producción)
- Integración MercadoPago multi-región (Argentina, Brasil)
- Webhooks para confirmación de pago
- Sistema de comisiones por prestador
- Reportes financieros y exportación CSV

**3. Gestión Administrativa** (Panel extranet)
- Dashboard con métricas (servicios, salidas, usuarios, visitantes)
- CRUD completo de servicios, salidas y tarifas
- Gestión de prestadores y agencias
- Listado de reservas con filtros avanzados
- Reportes de comisiones y financiero
- Gestión de usuarios (clientes, operadores, administradores)

**4. Experiencia de Usuario**
- Responsivo (desktop + mobile)
- Detección automática de geolocalización
- Selección dinámica de moneda y idioma (ES, EN, PT, IT)
- Sistema de filtros avanzados (precio, distancia, categoría)
- Búsqueda y recomendaciones personalizadas
- Comentarios y calificaciones de servicios

**5. Arquitectura Técnica**
- Backend: PHP procedural (Sin frameworks, código puro)
- Base de datos: MySQL/MySQLi/PDO
- Frontend: jQuery + JavaScript vanilla
- Control de versiones: Git
- Ambiente: XAMPP (desarrollo local)

---

## 💰 VALUACIÓN DEL SOFTWARE

### Metodología de Valuación

La valuación se basó en:

| Factor | Detalle | Aporte |
|--------|---------|--------|
| **Complejidad técnica** | 20+ tablas BD, 40+ módulos admin, APIs REST | +30% |
| **Funcionalidad de negocio** | Sistema completo turístico llave en mano | +25% |
| **Integración de pagos** | PayPal + MercadoPago multi-región | +15% |
| **Documentación** | Código comentado + instrucciones técnicas | +10% |
| **Tiempo desarrollo** | Estimado 400-500 horas programación | Base |
| **Transferencia IP** | Código fuente + BD completa | +20% |

### Rango de Precios

#### **Opción Base: $18,000 USD - $25,000 USD**
Incluye:
- ✅ Código fuente completo
- ✅ Base de datos estructura + datos demo
- ✅ Documentación técnica
- ✅ Manual de instalación
- ✅ Credenciales iniciales

**Entrega:** Carpeta comprimida con toda la plataforma

---

#### **Opción Mejorada: $28,000 USD - $40,000 USD**
Todo lo anterior + :
- ✅ Soporte técnico por 30 días (email/video calls)
- ✅ Asistencia en instalación en servidor del cliente
- ✅ Guías de integración PayPal y MercadoPago
- ✅ Capacitación básica al equipo del cliente (2 sesiones)
- ✅ Configuración de webhooks y dominios

**Entrega:** Anterior + sesiones en vivo

---

#### **Opción Premium: $45,000 USD - $65,000 USD**
Todo lo anterior +  :
- ✅ Soporte técnico por 90 días
- ✅ Instalación completa en servidor cliente
- ✅ Soporte de dominio y SSL
- ✅ Capacitación completa al equipo (5-10 sesiones)
- ✅ Customizaciones menores (<40 horas)
- ✅ Documentación blanca (sin referencias a desarrollador original)
- ✅ Derecho a venderlo como white-label

**Entrega:** Todo anterior + infraestructura configurada

---

### Justificación del Precio

💡 **¿Por qué estos precios?**

1. **Tiempo invertido:** 400-500 horas = $50-100 USD/hora (estándar industria)
2. **Complejidad:** Plataforma multi-país, multi-moneda, multi-idioma
3. **Pasarelas de pago:** Integración compleja (PayPal + MercadoPago webhooks)
4. **Mantenimiento:** El comprador no tendrá que desarrollar desde cero
5. **Competitive:** Plataformas SaaS similares cuestan $500-3000 USD/mes

**Análisis ROI para el comprador:**
- Si genera $1,000 USD/mes en comisiones → recupera inversión en 25-30 meses
- Si genera $5,000 USD/mes → recupera en 5-8 meses

---

## 📦 ALCANCE DE LA VENTA

### QUÉ INCLUYE

#### 1️⃣ **Código Fuente**
```
metelebrasil_codigo/
├── *.php (100+ archivos)
├── /admin (panel administrativo)
├── /js (JavaScript)
├── /css (estilos)
├── /img (imágenes y assets)
├── /config (configuración, sin credenciales)
├── /includes (funciones comunes)
└── .env.example
```

Archivos principales:
- `index.php` - Página de inicio
- `servicio.php` - Detalle de servicio
- `carrito.php` - Carrito de compra
- `admin/index.php` - Dashboard
- `admin/classes/` - Lógica de negocio (20+ clases)
- `admin/ctrl/` - AJAX controllers

#### 2️⃣ **Base de Datos**
```
metelebrasil_base/
├── metelebrasil_estructura.sql (tablas vacías)
├── metelebrasil_demo.sql (datos ejemplo)
└── MAPEO_TABLAS.pdf (documentación)
```

Tablas incluidas (25+):
- `servicio`, `categoria_servicio`, `servicio_salidas`, `servicio_salidas_tarifas`
- `reservas`, `reserva_horarios`, `reserva_tarifas`, `reserva_pasajeros`
- `usuario`, `prestadores`, `prestador_comision`, `servicios_adicionales`
- `moneda`, `moneda_cambio`, `impuestos_pais`, `paises`
- `comprobante`, `usuario_comisiones`, `config`, `estados_reserva`

#### 3️⃣ **Documentación**
- `README.md` - Guía de inicio rápido
- `INSTALACION.md` - Paso a paso instalación
- `ARQUITECTURA.md` - Explicación sistema
- `PAYPAL_SETUP.md` - Integración PayPal
- `MERCADOPAGO_SETUP.md` - Integración MercadoPago
- `USUARIOS_PRUEBA.md` - Credenciales demo
- `MAPEO_TABLAS.md` - Diccionario de datos

---

### QUÉ NO INCLUYE

❌ Hosting/servidor (el comprador debe conseguir su propio hosting)  
❌ Dominio (el comprador debe registrar su dominio)  
❌ Credenciales de pago reales (debe crear sus propias cuentas PayPal/MercadoPago)  
❌ Soporte indefinido (solo incluido en opción Premium)  
❌ Actualizaciones futuras (código es de propiedad del comprador)  

---

## ✅ CHECKLIST PRE-ENTREGA

Antes de transferir los archivos, completar:

### **Seguridad y Limpieza**
- [ ] Remover credenciales sensibles de `config/config.php`
- [ ] Remover datos de servidor productivo de `admin/classes/conexion.php`
- [ ] Limpiar histórico de Git (remover archivos desarrollador)
- [ ] Remover archivos temporales (`.DS_Store`, `Thumbs.db`, etc.)
- [ ] Revisar comentarios del código (sin info sensible)

### **Documentación**
- [ ] Crear `README.md` con instrucciones claras
- [ ] Crear `.env.example` con variables necesarias
- [ ] Documentar estructura de carpetas
- [ ] Crear guías de instalación paso a paso
- [ ] Incluir usuarios de prueba (admin/cliente/prestador)

### **Base de Datos**
- [ ] Exportar estructura sin datos sensibles
- [ ] Crear BD de demostración con datos ejemplo
- [ ] Documentar tablas principales y relaciones
- [ ] Incluir script de importación automática
- [ ] Documentar usuario/password temporales

### **Validación**
- [ ] Testear instalación en servidor limpio
- [ ] Verificar que PayPal Sandbox funcione
- [ ] Verificar que MercadoPago Sandbox funcione
- [ ] Probar flujo completo: login → buscar → reservar → pago
- [ ] Verificar reportes administrativos

---

## 📅 PROPUESTA COMERCIAL

### **Opción Recomendada: OPCIÓN MEJORADA ($28,000 - $40,000 USD)**

**Razón:** Mejor balance entre precio y servicios incluidos. El comprador no quedará solo con código, sino que tendrá asistencia para ponerlo en marcha.

### Timeline de Entrega

| Fase | Duración | Actividad |
|------|----------|-----------|
| **1. Preparación** | 5-7 días | Limpiar código, documentación, empaquetar |
| **2. Validación** | 3-5 días | Testear en ambiente limpio |
| **3. Entrega** | Día 1 | Transferencia de archivos + credenciales |
| **Soporte** | 30 días | Disponible para preguntas técnicas |

**Total:** 15 días hábiles desde confirmación de compra

### Método de Pago

- **Depósito:** 50% al firmar contrato
- **Entrega:** 50% restante al recibir los archivos
- Aceptar: Transferencia bancaria, PayPal, criptomonedas

### Transferencia de Propiedad

Al recibir el 100% del pago:
- ✅ Código fuente es completamente del comprador
- ✅ Puede modificarlo, venderlo, o usarlo como desee
- ✅ No tiene obligación de mantenerlo actualizado
- ✅ No depende de actualizaciones del desarrollador original

---

## 📝 TÉRMINOS Y CONDICIONES

### Lo que está permitido hacer con la plataforma
✅ Usar en servidor propio  
✅ Modificar el código  
✅ Venderlo como propio (white-label)  
✅ Revender a terceros  
✅ Hacer customizaciones  

### Lo que NO está permitido (si se acuerda en contrato)
❌ Reclama que eres el desarrollador original*  
❌ Vender el código fuente sin autorización*  
(*) Negociable en contrato

---

## 🎓 REQUISITOS TÉCNICOS PARA EL COMPRADOR

Para operar la plataforma, el comprador necesita:

### **Servidor/Hosting**
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Soporte de cupones SSL (HTTPS)
- mínimo 2 GB RAM
- Dominio propio registrado

**Costo estimado:** $50-300 USD/mes (depende del proveedor)

### **Cuentas Externas**
- Cuenta empresarial PayPal
- Cuenta empresarial MercadoPago (por país)
- Gestor de emails (para notificaciones)

**Costo estimado:** $0-50 USD/mes (mayoría son gratis)

### **Conocimientos Requeridos**
- Básico de PHP (para customizaciones futuras)
- Básico de MySQL (para consultas)
- Básico de FTP/SFTP (para subir archivos)
- Básico de DNS (para apuntar dominio)

---

## 📊 COMPARATIVA: COMPRAR vs DESARROLLAR DESDE CERO

| Aspecto | Comprar Plataforma | Desarrollar Nuevo |
|--------|-------------------|-------------------|
| **Costo inicial** | $25,000 | $60,000-100,000 |
| **Tiempo** | 30 días operativo | 6-12 meses |
| **Funcionalidad** | 100% completa | Según especificación |
| **Bugs/Issues** | Mínimos (ya testeado) | Muchos inicialmente |
| **Tiempo a rentabilidad** | 2-3 meses | 12-18 meses |
| **Riesgo** | Bajo | Alto |

---

## ❓ PREGUNTAS FRECUENTES

**P: ¿Qué pasa si la plataforma tiene bugs después de comprada?**  
R: El período de 30 días de soporte cubre esto. Después, el comprador es responsable.

**P: ¿Puedo revender la plataforma a otros?**  
R: Sí, pero debe negociarse en el contrato si es white-label o con attribution.

**P: ¿Cómo funciona la instalación?**  
R: En opción mejorada, incluye asistencia. El comprador aloja en su servidor.

**P: ¿Hay contrato de confidencialidad?**  
R: Sí, se firma antes de entregar acceso.

**P: ¿Qué pasa con las actualizaciones de PHP/MySQL?**  
R: El código es propiedad del comprador. Puede mantenerlo o contratar a otro dev.

**P: ¿Incluye SEO implementado?**  
R: Tiene estructura básica. Puede mejorarse con plugins/configuración.

**P: ¿Cuánto cuesta mantenerlo anualmente?**  
R: Depende del servidor ($600-3600/año). El código no tiene costo de licencia.

---

## 📞 PRÓXIMOS PASOS

1. **Revisar esta propuesta** con el equipo
2. **Aclarar dudas** vía email/llamada
3. **Elegir opción** (Base / Mejorada / Premium)
4. **Firmar contrato** con términos específicos
5. **Realizar pago** (depósito 50%)
6. **Recibir archivos** y comenzar instalación

---

## 📎 ANEXOS

### Anexo A: Estructura de Carpetas Entregable
```
metelebrasil_completo_20251230/
├── 01_CODIGO_FUENTE/
│   ├── *.php (todos los archivos)
│   ├── /admin
│   ├── /js
│   ├── /css
│   ├── /img
│   └── .env.example
├── 02_BASE_DATOS/
│   ├── metelebrasil_estructura.sql
│   ├── metelebrasil_datos_demo.sql
│   └── MAPEO_TABLAS.pdf
├── 03_DOCUMENTACION/
│   ├── README.md
│   ├── INSTALACION.md
│   ├── ARQUITECTURA.md
│   ├── PAYPAL_SETUP.md
│   ├── MERCADOPAGO_SETUP.md
│   └── USUARIOS_PRUEBA.md
└── 04_CONTRATO/
    └── ACUERDO_VENTA_CODIGO.docx
```

### Anexo B: Usuarios de Prueba Iniciales
```
ADMINISTRADOR
Email: admin@metelebrasil.local
Password: admin123
Permisos: Todo

CLIENTE
Email: cliente@metelebrasil.local
Password: cliente123
Permisos: Ver servicios, hacer reservas

PRESTADOR
Email: prestador@metelebrasil.local
Password: prestador123
Permisos: Gestionar propios servicios
```

### Anexo C: Tablas Principales de BD

| Tabla | Registros Demo | Descripción |
|-------|---|-----------|
| `servicio` | 15 | Servicios turísticos |
| `categoria_servicio` | 5 | Categorías (excursiones, paseos, etc.) |
| `servicio_salidas` | 45 | Horarios/fechas por servicio |
| `servicio_salidas_tarifas` | 135 | Precios por edad/salida |
| `usuario` | 10 | Usuarios registrados |
| `prestadores` | 5 | Operadores turísticos |
| `moneda` | 4 | USD, BRL, ARS, EUR |

---

**Documento preparado por:** Sistema MeteleBrasil  
**Fecha:** 30 de diciembre de 2025  
**Válido por:** 30 días  
**Contacto para negociación:** [Agregar email/whatsapp]

---

*Este documento es confidencial y solo para el destinatario. No copiar sin autorización.*

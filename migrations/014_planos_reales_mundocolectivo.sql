-- ============================================================
-- SCRIPT SQL: Preparar BD para integración de planos reales
-- Fuente: mundocolectivo.com.ar (3,090 planos)
-- Fecha: Enero 19, 2026
-- ============================================================

USE metelebrasil_experimental;

-- ============================================================
-- 1. NUEVA TABLA: carroceria_planos
-- ============================================================

CREATE TABLE IF NOT EXISTS carroceria_planos (
  idCarroceria INT PRIMARY KEY AUTO_INCREMENT,
  
  -- Información básica
  fabricante VARCHAR(100) NOT NULL COMMENT 'Marcopolo, Mercedes-Benz, etc.',
  modelo VARCHAR(150) NOT NULL COMMENT 'Paradiso G7, O500, etc.',
  
  -- URLs y almacenamiento local
  url_original VARCHAR(500) COMMENT 'URL de mundocolectivo.com.ar',
  ruta_imagen_local VARCHAR(255) COMMENT 'Ruta local: img/planos_carroceria/...',
  
  -- Características del micro
  asientos_total INT COMMENT 'Capacidad total de asientos',
  filas INT COMMENT 'Número de filas en plano',
  columnas INT COMMENT 'Número de columnas en plano',
  
  -- Distribución de asientos (JSON)
  distribucion_json JSON NOT NULL COMMENT 'Array [filas][columnas] con distribución',
  
  -- Atributos
  doble_piso BOOLEAN DEFAULT FALSE,
  categoria ENUM(
    'urbano',
    'turismo', 
    'ejecutivo',
    'doble_piso',
    'ferry',
    'avion',
    'otro'
  ) DEFAULT 'turismo',
  
  -- Auditoría
  fecha_ingreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  procesado BOOLEAN DEFAULT FALSE COMMENT '¿Ya fue procesado OCR?',
  errores_procesamiento TEXT COMMENT 'Errores durante OCR si los hay',
  
  -- Índices
  UNIQUE KEY uniq_fab_modelo (fabricante, modelo),
  KEY idx_categoria (categoria),
  KEY idx_procesado (procesado),
  KEY idx_doble_piso (doble_piso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Comentario de tabla (MySQL)
ALTER TABLE carroceria_planos COMMENT 'Planos reales de carrocerías de mundocolectivo.com.ar';

-- ============================================================
-- 2. ALTERAR tabla modelo_vehiculo_transporte
-- ============================================================

-- Agregar FK a carroceria_planos (si no existe)
ALTER TABLE modelo_vehiculo_transporte 
ADD COLUMN idCarroceriaPlano INT AFTER distribucion_json,
ADD CONSTRAINT fk_modelo_carroceria_plano 
  FOREIGN KEY (idCarroceriaPlano) REFERENCES carroceria_planos(idCarroceria);

-- ============================================================
-- 3. DATOS INICIALES: Planos Principales
-- ============================================================

-- Nota: Estos son ejemplos. Los datos reales vendrían de mundocolectivo.com.ar

INSERT IGNORE INTO carroceria_planos 
(fabricante, modelo, url_original, asientos_total, filas, columnas, 
 distribucion_json, doble_piso, categoria, procesado)
VALUES

-- Marcopolo (Brasil)
('Marcopolo', 'Paradiso G7', 'https://mundocolectivo.com.ar/planos.php?plano=Marcopolo&modelo=Paradiso%20G7', 
 47, 5, 6, 
 '{"pisos":[{"asientos":[[1,1,1,1,1,1],[1,1,1,1,1,1],[1,1,1,1,1,1],[1,1,1,1,1,1],[1,1,1,1,1,1]]}]}',
 TRUE, 'doble_piso', TRUE),

('Marcopolo', 'Paradiso 1350', 'https://mundocolectivo.com.ar/planos.php?plano=Marcopolo&modelo=Paradiso%201350',
 31, 10, 4,
 '{"pisos":[{"asientos":[[1,1,1,1],[1,1,1,1],[1,1,1,1],[1,1,1,1],[1,1,1,1],[1,1,1,1],[1,1,1,1],[1,1,1,1],[1,1,1,1],[1,1,1,1]]}]}',
 FALSE, 'turismo', TRUE),

-- Mercedes-Benz (Alemania)
('Mercedes-Benz', 'O500', 'https://mundocolectivo.com.ar/planos.php?plano=Mercedes-Benz&modelo=O500',
 38, 5, 5,
 '{"pisos":[{"asientos":[[1,1,1,1,1],[1,1,1,1,1],[1,1,1,1,1],[1,1,1,1,1],[1,1,1,1,1]]}]}',
 TRUE, 'doble_piso', TRUE),

('Mercedes-Benz', 'O400', 'https://mundocolectivo.com.ar/planos.php?plano=Mercedes-Benz&modelo=O400',
 30, 10, 3,
 '{"pisos":[{"asientos":[[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1]]}]}',
 FALSE, 'turismo', TRUE),

-- Scania (Suecia)
('Scania', 'K340', 'https://mundocolectivo.com.ar/planos.php?plano=Scania&modelo=K340',
 40, 16, 3,
 '{"pisos":[{"asientos":[[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1]]}]}',
 FALSE, 'turismo', TRUE),

-- Iveco (Italia)
('Iveco', 'Tector', 'https://mundocolectivo.com.ar/planos.php?plano=Iveco&modelo=Tector',
 35, 10, 3,
 '{"pisos":[{"asientos":[[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1]]}]}',
 FALSE, 'urbano', TRUE),

-- Neobus (Brasil)
('Neobus', 'Mega', 'https://mundocolectivo.com.ar/planos.php?plano=Neobus&modelo=Mega',
 50, 5, 6,
 '{"pisos":[{"asientos":[[1,1,1,1,1,1],[1,1,1,1,1,1],[1,1,1,1,1,1],[1,1,1,1,1,1],[1,1,1,1,1,1]]}]}',
 TRUE, 'doble_piso', TRUE),

-- Metalpar (Argentina)
('Metalpar', 'Uber', 'https://mundocolectivo.com.ar/planos.php?plano=Metalpar&modelo=Uber',
 32, 8, 4,
 '{"pisos":[{"asientos":[[1,1,1,1],[1,1,1,1],[1,1,1,1],[1,1,1,1],[1,1,1,1],[1,1,1,1],[1,1,1,1],[1,1,1,1]]}]}',
 FALSE, 'urbano', TRUE),

-- Comil (Brasil)
('Comil', 'Craftale', 'https://mundocolectivo.com.ar/planos.php?plano=Comil&modelo=Craftale',
 42, 14, 3,
 '{"pisos":[{"asientos":[[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1],[1,1,1]]}]}',
 FALSE, 'turismo', TRUE);

-- ============================================================
-- 4. LINK: Asociar modelos con planos reales
-- ============================================================

-- Actualizar modelos existentes que tengan planos disponibles
UPDATE modelo_vehiculo_transporte m
SET idCarroceriaPlano = (
  SELECT idCarroceria FROM carroceria_planos cp 
  WHERE LOWER(cp.modelo) LIKE CONCAT('%', LOWER(m.nombre), '%')
  LIMIT 1
)
WHERE m.idModelo IN (1, 2, 3, 4, 5, 6, 7);

-- Verificar actualizaciones
SELECT 
  m.idModelo,
  m.nombre,
  cp.idCarroceria,
  cp.fabricante,
  cp.modelo,
  m.idCarroceríaPlano
FROM modelo_vehiculo_transporte m
LEFT JOIN carroceria_planos cp ON m.idCarroceriaPlano = cp.idCarroceria
ORDER BY m.idModelo;

-- ============================================================
-- 5. VISTAS ÚTILES
-- ============================================================

-- Vista: Modelos con y sin planos
CREATE OR REPLACE VIEW v_modelos_con_planos AS
SELECT 
  m.idModelo,
  m.nombre as modelo_nombre,
  m.capacidad_total,
  m.distribucion_json as distribucion_generada,
  COALESCE(cp.idCarroceria, 0) as tiene_plano_real,
  cp.fabricante,
  cp.modelo as carroceria_modelo,
  cp.asientos_total as capacidad_plano,
  cp.ruta_imagen_local
FROM modelo_vehiculo_transporte m
LEFT JOIN carroceria_planos cp ON m.idCarroceriaPlano = cp.idCarroceria
ORDER BY m.idModelo;

-- ============================================================
-- 6. VERIFICACIÓN
-- ============================================================

-- Contar planos por categoría
SELECT categoria, COUNT(*) as total
FROM carroceria_planos
GROUP BY categoria;

-- Listar planos disponibles
SELECT 
  idCarroceria,
  CONCAT(fabricante, ' ', modelo) as plano_completo,
  asientos_total,
  doble_piso,
  categoria,
  procesado
FROM carroceria_planos
ORDER BY fabricante, modelo;

-- ============================================================
-- 7. NOTAS IMPORTANTES
-- ============================================================

/*
NOTAS PARA IMPLEMENTACIÓN:

1. ESTRUCTURA DE CARPETAS:
   img/
   ├── planos_carroceria/
   │   ├── marcopolo/
   │   │   ├── paradiso_g7.jpg
   │   │   └── paradiso_1350.jpg
   │   ├── mercedes-benz/
   │   │   ├── o500.jpg
   │   │   └── o400.jpg
   │   ├── scania/
   │   └── iveco/

2. PROCESAMIENTO OCR:
   - Ejecutar: python procesar_planos_ocr.py
   - Actualiza: carroceria_planos.distribucion_json
   - Valida: asientos_total = capacidad

3. FRONT-END:
   - Verificar: idCarroceríaPlano en modelo_vehiculo_transporte
   - Si existe: mostrar plano real + grid interactivo
   - Si no existe: mostrar solo grid generado (fallback)

4. MIGRACIÓN:
   - Ejecutar una vez en producción
   - Hacer backup antes
   - Validar FKs después

5. PERFORMANCE:
   - Índices en: fabricante, modelo, categoria, procesado
   - Caché de imágenes descargadas
   - Lazy-load de imágenes en frontend
*/

-- ============================================================
-- FIN DEL SCRIPT
-- ============================================================

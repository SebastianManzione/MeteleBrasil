-- Corrige modelo 6 a Marcopolo Paradiso 1050 (single piso, 47 asientos)
-- 4 filas × 6 columnas con pasillo central
USE metelebrasil_experimental;

UPDATE modelo_vehiculo_transporte 
SET nombre = 'Marcopolo Paradiso 1050',
    filas = 4,
    columnas = 6,
    capacidad_total = 24,
    distribucion_json = '{
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Planta única",
      "filas": 4,
      "columnas": 6,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["Y", 1, "P", "P", "E", 1],
        [1, 1, "P", "P", 1, 1],
        [1, 1, "P", "P", 1, 1],
        [1, 1, "P", "P", 1, 1]
      ]
    }
  ]
}'
WHERE idModelo = 6;

-- Verificacion
SELECT idModelo, nombre, filas, columnas, capacidad_total, 
       JSON_EXTRACT(distribucion_json, '$.doblePiso') AS doble_piso
FROM modelo_vehiculo_transporte WHERE idModelo = 6;

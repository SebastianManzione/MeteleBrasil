-- Corrige modelo 10: Paradiso 1800 DD con pisos en orden correcto
-- Piso Inferior (abajo): 2 filas × 4 asientos con especiales = 8 asientos
-- Piso Superior (arriba): 12 filas × 4 asientos = 48 asientos
-- Total: 56 asientos

USE metelebrasil_experimental;

UPDATE modelo_vehiculo_transporte 
SET filas = 14,
    columnas = 5,
    capacidad_total = 56,
    distribucion_json = '{
  "doblePiso": true,
  "pisos": [
    {
      "nombre": "Piso Inferior - Clase Cama",
      "filas": 2,
      "columnas": 5,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["Y", 1, "P", 1, "E"],
        ["B", 0, "P", "C", "X"]
      ]
    },
    {
      "nombre": "Piso Superior - Clase Semicama",
      "filas": 12,
      "columnas": 5,
      "letraInicial": "C",
      "numeroInicial": 1,
      "asientos": [
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1]
      ]
    }
  ]
}'
WHERE idModelo = 10;

SELECT idModelo, nombre, filas, columnas, capacidad_total,
       JSON_EXTRACT(distribucion_json, '$.pisos[0].nombre') as piso_inf,
       JSON_EXTRACT(distribucion_json, '$.pisos[0].filas') as filas_inf,
       JSON_EXTRACT(distribucion_json, '$.pisos[1].nombre') as piso_sup,
       JSON_EXTRACT(distribucion_json, '$.pisos[1].filas') as filas_sup
FROM modelo_vehiculo_transporte WHERE idModelo = 10;

-- Actualiza modelo 10: Marcopolo Paradiso 1800 DD (distribución real)
-- Piso Inferior: 3 filas, cabina, escalera, baño, cafetería (8 asientos)
-- Piso Superior: 12 filas × 4 asientos (48 asientos)
-- Total: 56 asientos

USE metelebrasil_experimental;

UPDATE modelo_vehiculo_transporte 
SET filas = 15,
    columnas = 5,
    capacidad_total = 56,
    distribucion_json = '{
  "doblePiso": true,
  "pisos": [
    {
      "nombre": "Piso Inferior - Clase Cama",
      "filas": 3,
      "columnas": 5,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["Y", 1, "P", 1, "E"],
        [1, 1, "P", 1, 1],
        ["B", 0, "P", "C", "X"]
      ]
    },
    {
      "nombre": "Piso Superior - Clase Semicama",
      "filas": 12,
      "columnas": 5,
      "letraInicial": "D",
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
       JSON_LENGTH(distribucion_json, '$.pisos') as pisos
FROM modelo_vehiculo_transporte WHERE idModelo = 10;

-- Actualiza modelo 10: Marcopolo Paradiso 1800 DD (double piso)
-- Piso Superior: 9 filas × 5 columnas, pasillo central en col 3 = ~34 asientos
-- Piso Inferior: 9 filas × 5 columnas, pasillo central en col 3 = ~34 asientos
-- Total: ~68-70 asientos

USE metelebrasil_experimental;

UPDATE modelo_vehiculo_transporte 
SET filas = 18,
    columnas = 5,
    capacidad_total = 68,
    distribucion_json = '{
  "doblePiso": true,
  "pisos": [
    {
      "nombre": "Piso Superior",
      "filas": 9,
      "columnas": 5,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["Y", 1, "P", 1, "E"],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1]
      ]
    },
    {
      "nombre": "Piso Inferior",
      "filas": 9,
      "columnas": 5,
      "letraInicial": "J",
      "numeroInicial": 1,
      "asientos": [
        ["G", 1, "P", 1, "B"],
        [1, 1, "P", 1, "X"],
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

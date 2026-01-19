-- Actualiza modelo 6: Marcopolo Paradiso 1050 (single piso)
-- 10 filas × 5 columnas, pasillo central en col 3
-- ~40 asientos totales

USE metelebrasil_experimental;

UPDATE modelo_vehiculo_transporte 
SET filas = 10,
    columnas = 5,
    capacidad_total = 40,
    distribucion_json = '{
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Planta única",
      "filas": 10,
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
        [1, 1, "P", 1, 1],
        [1, 1, "P", 1, 1]
      ]
    }
  ]
}'
WHERE idModelo = 6;

SELECT idModelo, nombre, filas, columnas, capacidad_total FROM modelo_vehiculo_transporte WHERE idModelo = 6;

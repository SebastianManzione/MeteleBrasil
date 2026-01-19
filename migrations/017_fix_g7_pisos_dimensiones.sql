-- Fix dimensiones por piso y globales para Marcopolo Doble Piso G7
USE metelebrasil_experimental;

UPDATE modelo_vehiculo_transporte 
SET filas = 10,
    columnas = 6,
    distribucion_json = '{
  "doblePiso": true,
  "pisos": [
    {
      "nombre": "Piso Superior",
      "filas": 5,
      "columnas": 6,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        [1,1,1,1,1,1],
        [1,1,"T",1,1,0],
        [1,1,1,1,0,1],
        [1,1,1,0,"B",1],
        [1,1,0,1,1,0]
      ]
    },
    {
      "nombre": "Piso Inferior",
      "filas": 5,
      "columnas": 6,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["Y","G",1,1,"X",1],
        [1,1,1,"X",1,1],
        [1,1,1,1,1,1],
        [1,1,"T",1,1,1],
        [1,1,0,1,1,0]
      ]
    }
  ]
}'
WHERE idModelo = 6;

-- Verificación rápida tras aplicar
SELECT idModelo, nombre, filas, columnas, capacidad_total,
       JSON_LENGTH(distribucion_json, '$.pisos') AS pisos,
       JSON_EXTRACT(distribucion_json, '$.pisos[0].filas') AS piso1_filas,
       JSON_EXTRACT(distribucion_json, '$.pisos[0].columnas') AS piso1_columnas,
       JSON_EXTRACT(distribucion_json, '$.pisos[1].filas') AS piso2_filas,
       JSON_EXTRACT(distribucion_json, '$.pisos[1].columnas') AS piso2_columnas
FROM modelo_vehiculo_transporte WHERE idModelo = 6;

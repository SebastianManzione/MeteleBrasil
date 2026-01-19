-- Actualiza Marcopolo Doble Piso G7 con distribucion_json completa (47 asientos)
USE metelebrasil_experimental;

UPDATE modelo_vehiculo_transporte 
SET filas = 5,
    columnas = 6,
    capacidad_total = 47,
    distribucion_json = '{
  "doblePiso": true,
  "pisos": [
    {
      "nombre": "Piso Superior",
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
WHERE idModelo = 6 OR nombre LIKE '%Marcopolo Doble Piso G7%';

-- Validaciones rápidas
SELECT idModelo, nombre, capacidad_total, JSON_LENGTH(distribucion_json, '$.pisos') as pisos
FROM modelo_vehiculo_transporte WHERE idModelo = 6;
-- Marcopolo Doble Piso G7 - Distribucion realista ajustada a plano real
-- 4 filas largas por piso, pasillo central col 3-4, escalera arriba derecha
-- Piso superior: col 1-2 izquierda, pasillo col 3-4, col 5-6 derecha
-- Escalera arriba a la derecha (fila 1, col 5-6)
-- Conductor adelante izquierda (fila 1, col 1)
-- Total: 47 asientos activos

USE metelebrasil_experimental;

UPDATE modelo_vehiculo_transporte 
SET filas = 8,
    columnas = 6,
    capacidad_total = 47,
    distribucion_json = '{
  "doblePiso": true,
  "pisos": [
    {
      "nombre": "Piso Superior",
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
    },
    {
      "nombre": "Piso Inferior",
      "filas": 4,
      "columnas": 6,
      "letraInicial": "F",
      "numeroInicial": 1,
      "asientos": [
        ["G", 1, "P", "P", "B", "X"],
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
       JSON_LENGTH(distribucion_json, '$.pisos') AS pisos,
       JSON_EXTRACT(distribucion_json, '$.pisos[0].nombre') AS piso1_nombre,
       JSON_EXTRACT(distribucion_json, '$.pisos[0].filas') AS piso1_filas,
       JSON_EXTRACT(distribucion_json, '$.pisos[1].nombre') AS piso2_nombre,
       JSON_EXTRACT(distribucion_json, '$.pisos[1].filas') AS piso2_filas
FROM modelo_vehiculo_transporte WHERE idModelo = 6;

-- Conteo manual de asientos activos
SELECT 
  SUM(
    JSON_EXTRACT(JSON_EXTRACT(distribucion_json, '$.pisos[0].asientos'), '$[0]') LIKE '%1%' +
    JSON_EXTRACT(JSON_EXTRACT(distribucion_json, '$.pisos[0].asientos'), '$[1]') LIKE '%1%' +
    JSON_EXTRACT(JSON_EXTRACT(distribucion_json, '$.pisos[0].asientos'), '$[2]') LIKE '%1%' +
    JSON_EXTRACT(JSON_EXTRACT(distribucion_json, '$.pisos[0].asientos'), '$[3]') LIKE '%1%' +
    JSON_EXTRACT(JSON_EXTRACT(distribucion_json, '$.pisos[1].asientos'), '$[0]') LIKE '%1%' +
    JSON_EXTRACT(JSON_EXTRACT(distribucion_json, '$.pisos[1].asientos'), '$[1]') LIKE '%1%' +
    JSON_EXTRACT(JSON_EXTRACT(distribucion_json, '$.pisos[1].asientos'), '$[2]') LIKE '%1%' +
    JSON_EXTRACT(JSON_EXTRACT(distribucion_json, '$.pisos[1].asientos'), '$[3]') LIKE '%1%'
  ) as total_asientos
FROM modelo_vehiculo_transporte WHERE idModelo = 6;

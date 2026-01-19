-- Crea modelo 10: Marcopolo Paradiso 1800 DD (double piso, 52 asientos)
-- Piso superior: 7 filas × 6 cols = 26 asientos
-- Piso inferior: 7 filas × 6 cols = 26 asientos
-- Total: 52 asientos activos

USE metelebrasil_experimental;

INSERT INTO modelo_vehiculo_transporte 
(nombre, tipo_transporte, filas, columnas, descripcion, distribucion_json, capacidad_total, habilitado)
VALUES 
(
  'Marcopolo Paradiso 1800 DD',
  1,
  14,
  6,
  'Autocar de larga distancia doble piso premium con 52 asientos',
  '{
  "doblePiso": true,
  "pisos": [
    {
      "nombre": "Piso Superior",
      "filas": 7,
      "columnas": 6,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["Y", 1, "P", "P", "E", 1],
        [1, 1, "P", "P", 1, 1],
        [1, 1, "P", "P", 1, 1],
        [1, 1, "P", "P", 1, 1],
        [1, 1, "P", "P", 1, 1],
        [1, 1, "P", "P", 1, 1],
        [1, 1, "P", "P", 1, 1]
      ]
    },
    {
      "nombre": "Piso Inferior",
      "filas": 7,
      "columnas": 6,
      "letraInicial": "H",
      "numeroInicial": 1,
      "asientos": [
        ["G", 1, "P", "P", "B", "X"],
        [1, 1, "P", "P", 1, 1],
        [1, 1, "P", "P", 1, 1],
        [1, 1, "P", "P", 1, 1],
        [1, 1, "P", "P", 1, 1],
        [1, 1, "P", "P", 1, 1],
        [1, 1, "P", "P", 1, 1]
      ]
    }
  ]
  }',
  52,
  1
);

-- Verificacion
SELECT idModelo, nombre, filas, columnas, capacidad_total,
       JSON_LENGTH(distribucion_json, '$.pisos') AS pisos,
       JSON_EXTRACT(distribucion_json, '$.doblePiso') AS doble_piso
FROM modelo_vehiculo_transporte WHERE nombre LIKE '%1800 DD%';

-- Actualizar modelos con nuevas distribuciones incluyendo Volante, Parabrisas, Puerta, TV, Cocina

-- Modelo 1: Chevallier King Premium (12x3 = 36 asientos, Ejecutivo)
UPDATE modelo_vehiculo_transporte SET distribucion_json = '{
  "filas": 12,
  "columnas": 3,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Planta Única",
      "filas": 12,
      "columnas": 3,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["G", "Y", "G"],
        ["X", 1, "X"],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1],
        ["T", "P", "T"],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1]
      ]
    }
  ]
}' WHERE idModelo = 1;

-- Modelo 2: Marcopolo Paradiso 1350 (10x4 = 40 asientos)
UPDATE modelo_vehiculo_transporte SET distribucion_json = '{
  "filas": 10,
  "columnas": 4,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Planta Única",
      "filas": 10,
      "columnas": 4,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["G", "Y", "G", "G"],
        ["X", 1, 1, "X"],
        [1, "P", "P", 1],
        [1, "P", "P", 1],
        [1, "P", "P", 1],
        [1, "P", "P", 1],
        ["C", "P", "P", "C"],
        [1, "P", "P", 1],
        [1, "P", "P", 1],
        [1, "P", "P", 1]
      ]
    }
  ]
}' WHERE idModelo = 2;

-- Modelo 3: Scania K340 (16x3 = 48 asientos)
UPDATE modelo_vehiculo_transporte SET distribucion_json = '{
  "filas": 16,
  "columnas": 3,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Planta Única",
      "filas": 16,
      "columnas": 3,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["G", "Y", "G"],
        ["X", 1, "X"],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1],
        ["B", "P", "B"],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1],
        [1, "P", 1]
      ]
    }
  ]
}' WHERE idModelo = 3;

-- Modelo 4: Boeing 737-800 (Avión 12x5 = 60 asientos)
UPDATE modelo_vehiculo_transporte SET distribucion_json = '{
  "filas": 12,
  "columnas": 5,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Cabina Avión",
      "filas": 12,
      "columnas": 5,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["G", "Y", "G", 0, "T"],
        ["X", 1, 1, 1, "X"],
        [1, "P", "P", 1, 1],
        [1, "P", "P", 1, 1],
        [1, "P", "P", 1, 1],
        [1, "P", "P", 1, 1],
        ["B", "P", "P", "B", 0],
        [1, "P", "P", 1, 1],
        [1, "P", "P", 1, 1],
        [1, "P", "P", 1, 1],
        [1, "P", "P", 1, 1],
        [1, "P", "P", 1, 1]
      ]
    }
  ]
}' WHERE idModelo = 4;

-- Modelo 5: Ferry Fluvial (14x5 = 70 pasajeros)
UPDATE modelo_vehiculo_transporte SET distribucion_json = '{
  "filas": 14,
  "columnas": 5,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Cubierta Principal",
      "filas": 14,
      "columnas": 5,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["G", "Y", "G", "G", "G"],
        ["X", 1, 1, 1, "X"],
        [1, "P", "P", "P", 1],
        [1, "P", "P", "P", 1],
        [1, "P", "P", "P", 1],
        [1, "P", "P", "P", 1],
        [1, "P", "P", "P", 1],
        ["C", "P", "P", "P", "C"],
        [1, "P", "P", "P", 1],
        [1, "P", "P", "P", 1],
        [1, "P", "P", "P", 1],
        [1, "P", "P", "P", 1],
        [1, "P", "P", "P", 1],
        [1, "P", "P", "P", 1]
      ]
    }
  ]
}' WHERE idModelo = 5;

-- Modelo 6: Marcopolo Doble Piso G7 (27x6 = 50 asientos doble piso)
UPDATE modelo_vehiculo_transporte SET distribucion_json = '{
  "filas": 27,
  "columnas": 6,
  "doblePiso": true,
  "pisos": [
    {
      "nombre": "Planta Superior",
      "filas": 5,
      "columnas": 6,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["G", "Y", "G", "G", "G", "G"],
        ["X", 1, 1, 1, 1, "X"],
        [1, "P", "P", "P", "P", 1],
        ["T", "P", "P", "P", "P", "T"],
        [1, "P", "P", "P", "P", 1]
      ]
    },
    {
      "nombre": "Planta Inferior",
      "filas": 5,
      "columnas": 6,
      "letraInicial": "F",
      "numeroInicial": 1,
      "asientos": [
        [1, "P", "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1],
        ["B", "P", "P", "P", "P", "B"],
        [1, "P", "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1]
      ]
    }
  ]
}' WHERE idModelo = 6;

-- Modelo 7: Mercedes Doble Piso Comfort (10x5 = 48 asientos)
UPDATE modelo_vehiculo_transporte SET distribucion_json = '{
  "filas": 10,
  "columnas": 5,
  "doblePiso": true,
  "pisos": [
    {
      "nombre": "Planta Superior",
      "filas": 5,
      "columnas": 5,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["G", "Y", "G", "G", "G"],
        ["X", "W", "W", "W", "X"],
        ["W", "P", "P", "P", "W"],
        ["T", "P", "P", "P", "T"],
        ["W", "P", "P", "P", "W"]
      ]
    },
    {
      "nombre": "Planta Inferior",
      "filas": 5,
      "columnas": 5,
      "letraInicial": "F",
      "numeroInicial": 1,
      "asientos": [
        [1, "P", "P", "P", 1],
        [1, "P", "P", "P", 1],
        ["B", "P", "P", "P", "B"],
        ["K", "P", "P", "P", "C"],
        [1, "P", "P", "P", 1]
      ]
    }
  ]
}' WHERE idModelo = 7;

-- Modelo 8: Micro Ejecutivo Brasileño (8x5 = 40 asientos)
UPDATE modelo_vehiculo_transporte SET distribucion_json = '{
  "filas": 8,
  "columnas": 5,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Cabina Ejecutiva",
      "filas": 8,
      "columnas": 5,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["G", "Y", "G", "G", "G"],
        ["X", "W", "W", "W", "X"],
        ["W", "P", "P", "P", "W"],
        ["T", "P", "P", "P", "T"],
        ["W", "P", "P", "P", "W"],
        ["B", "P", "P", "P", "B"],
        ["K", "P", "P", "P", "C"],
        ["W", "P", "P", "P", "W"]
      ]
    }
  ]
}' WHERE idModelo = 8;

-- Modelo 9: Ferry Fluvial (20x20 = 400 pasajeros, muchos espacios)
UPDATE modelo_vehiculo_transporte SET distribucion_json = '{
  "filas": 20,
  "columnas": 20,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Cubierta Ferry",
      "filas": 20,
      "columnas": 20,
      "letraInicial": "A",
      "numeroInicial": 1,
      "asientos": [
        ["G", "Y", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G"],
        ["X", 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, "X"],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        ["C", "P", "P", "P", "P", "C", "P", "P", "P", "P", "C", "P", "P", "P", "P", "C", "P", "P", "P", "C"],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        ["B", "P", "P", "P", "P", "B", "P", "P", "P", "P", "B", "P", "P", "P", "P", "B", "P", "P", "P", "B"],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        [1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", "P", 1, "P", "P", "P", 1],
        ["X", "P", "P", "P", "P", "X", "P", "P", "P", "P", "X", "P", "P", "P", "P", "X", "P", "P", "P", "X"]
      ]
    }
  ]
}' WHERE idModelo = 9;

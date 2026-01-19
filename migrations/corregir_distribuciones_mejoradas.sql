-- Actualizar distribuciones JSON con mejor balance asientos vs elementos
-- Mantener realismo pero asegurar capacidades correctas

-- Modelo 1: Chevallier King Premium (36 asientos - reducir especiales)
UPDATE modelo_vehiculo_transporte SET filas=12, columnas=3, capacidad_total=32, distribucion_json = '{
  "filas": 12,
  "columnas": 3,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Planta Única",
      "filas": 12,
      "columnas": 3,
      "asientos": [
        ["G", "Y", "G"],
        ["X", 1, 1],
        [1, 1, 1],
        [1, 1, 1],
        [1, 1, 1],
        [1, 1, 1],
        [1, 1, 1],
        ["T", 1, "T"],
        [1, 1, 1],
        [1, 1, 1],
        [1, 1, 1],
        [1, 1, 1]
      ]
    }
  ]
}' WHERE idModelo = 1;

-- Modelo 2: Marcopolo Paradiso 1350 (40 asientos)
UPDATE modelo_vehiculo_transporte SET filas=10, columnas=4, capacidad_total=36, distribucion_json = '{
  "filas": 10,
  "columnas": 4,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Planta Única",
      "filas": 10,
      "columnas": 4,
      "asientos": [
        ["G", "Y", "G", "G"],
        ["X", 1, 1, 1],
        [1, 1, 1, 1],
        [1, 1, 1, 1],
        [1, 1, 1, 1],
        [1, 1, 1, 1],
        [1, "T", "T", 1],
        [1, 1, 1, 1],
        [1, 1, 1, 1],
        ["C", 1, 1, "B"]
      ]
    }
  ]
}' WHERE idModelo = 2;

-- Modelo 3: Scania K340 (48 asientos)
UPDATE modelo_vehiculo_transporte SET filas=16, columnas=3, capacidad_total=44, distribucion_json = '{
  "filas": 16,
  "columnas": 3,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Planta Única",
      "filas": 16,
      "columnas": 3,
      "asientos": [
        ["G", "Y", "G"],
        ["X", 1, 1],
        [1, 1, 1],
        [1, 1, 1],
        [1, 1, 1],
        [1, 1, 1],
        [1, 1, 1],
        [1, 1, 1],
        ["B", 1, "B"],
        [1, 1, 1],
        [1, 1, 1],
        [1, 1, 1],
        [1, 1, 1],
        [1, 1, 1],
        ["T", 1, "C"],
        [1, 1, 1]
      ]
    }
  ]
}' WHERE idModelo = 3;

-- Modelo 4: Boeing 737-800 (60 asientos)
UPDATE modelo_vehiculo_transporte SET filas=12, columnas=5, capacidad_total=52, distribucion_json = '{
  "filas": 12,
  "columnas": 5,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Cabina Avión",
      "filas": 12,
      "columnas": 5,
      "asientos": [
        ["G", "Y", "G", "G", "G"],
        ["X", 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        ["B", 1, 1, 1, "B"],
        [1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        ["T", 1, 1, 1, "T"],
        [1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1]
      ]
    }
  ]
}' WHERE idModelo = 4;

-- Modelo 5: Ferry Estándar - Bac3000 (70 pasajeros)
UPDATE modelo_vehiculo_transporte SET filas=14, columnas=5, capacidad_total=62, distribucion_json = '{
  "filas": 14,
  "columnas": 5,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Cubierta Principal",
      "filas": 14,
      "columnas": 5,
      "asientos": [
        ["G", "Y", "G", "G", "G"],
        ["X", 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        ["C", 1, 1, 1, "C"],
        [1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        ["B", 1, 1, 1, "B"],
        [1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1]
      ]
    }
  ]
}' WHERE idModelo = 5;

-- Modelo 6: Marcopolo Doble Piso G7 (50+ asientos - 25 por piso)
UPDATE modelo_vehiculo_transporte SET filas=10, columnas=6, capacidad_total=50, distribucion_json = '{
  "filas": 10,
  "columnas": 6,
  "doblePiso": true,
  "pisos": [
    {
      "nombre": "Planta Superior",
      "filas": 5,
      "columnas": 6,
      "asientos": [
        ["G", "Y", "G", "G", "G", "G"],
        ["X", 1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1, 1],
        ["T", 1, 1, 1, 1, "T"],
        [1, 1, 1, 1, 1, 1]
      ]
    },
    {
      "nombre": "Planta Inferior",
      "filas": 5,
      "columnas": 6,
      "asientos": [
        [1, 1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1, 1],
        ["B", 1, 1, 1, 1, "B"],
        [1, 1, 1, 1, 1, 1],
        ["K", 1, 1, 1, "C", 1]
      ]
    }
  ]
}' WHERE idModelo = 6;

-- Modelo 7: Mercedes Doble Piso Comfort (48 asientos)
UPDATE modelo_vehiculo_transporte SET filas=10, columnas=5, capacidad_total=48, distribucion_json = '{
  "filas": 10,
  "columnas": 5,
  "doblePiso": true,
  "pisos": [
    {
      "nombre": "Planta Superior",
      "filas": 5,
      "columnas": 5,
      "asientos": [
        ["G", "Y", "G", "G", "G"],
        ["X", 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        ["T", 1, 1, 1, "T"],
        [1, 1, 1, 1, 1]
      ]
    },
    {
      "nombre": "Planta Inferior",
      "filas": 5,
      "columnas": 5,
      "asientos": [
        [1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        ["B", 1, 1, 1, "B"],
        ["K", 1, 1, 1, "C"],
        [1, 1, 1, 1, 1]
      ]
    }
  ]
}' WHERE idModelo = 7;

-- Modelo 8: Micro Ejecutivo Brasileño (40 asientos)
UPDATE modelo_vehiculo_transporte SET filas=8, columnas=5, capacidad_total=36, distribucion_json = '{
  "filas": 8,
  "columnas": 5,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Cabina Ejecutiva",
      "filas": 8,
      "columnas": 5,
      "asientos": [
        ["G", "Y", "G", "G", "G"],
        ["X", 1, 1, 1, 1],
        [1, 1, 1, 1, 1],
        ["T", 1, 1, 1, "T"],
        [1, 1, 1, 1, 1],
        ["B", 1, 1, 1, "B"],
        ["K", 1, 1, 1, "C"],
        [1, 1, 1, 1, 1]
      ]
    }
  ]
}' WHERE idModelo = 8;

-- Modelo 9: Ferry Fluvial (400+ pasajeros - usar 20x20 con mejor ratio)
UPDATE modelo_vehiculo_transporte SET filas=20, columnas=20, capacidad_total=360, distribucion_json = '{
  "filas": 20,
  "columnas": 20,
  "doblePiso": false,
  "pisos": [
    {
      "nombre": "Cubierta Ferry",
      "filas": 20,
      "columnas": 20,
      "asientos": [
        ["G", "Y", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G", "G"],
        ["X", 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, "X"],
        [1, 1, 1, 1, 1, "C", 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, "C", 1, 1, 1],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        ["B", 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, "B"],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        ["K", 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, "C"],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        ["X", 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, "X"]
      ]
    }
  ]
}' WHERE idModelo = 9;

-- Migración: Cambiar viaje_transporte para usar idModelo en lugar de idVehiculo

-- 1. Agregar columna idModelo
ALTER TABLE viaje_transporte ADD COLUMN idModelo INT NULL AFTER idRuta;

-- 2. Copiar modelo desde vehículo asignado
UPDATE viaje_transporte vt
SET vt.idModelo = (
    SELECT vm.idModelo 
    FROM vehiculo_transporte vm 
    WHERE vm.idVehiculo = vt.idVehiculo
)
WHERE vt.idVehiculo IS NOT NULL;

-- 3. Agregar constraint de FK
ALTER TABLE viaje_transporte 
ADD CONSTRAINT FK_viaje_modelo 
FOREIGN KEY (idModelo) REFERENCES modelo_vehiculo_transporte(idModelo);

-- 4. Crear índice
ALTER TABLE viaje_transporte ADD INDEX idx_idModelo (idModelo);

-- 5. Hacer idModelo NOT NULL si todos los viajes tienen modelo
UPDATE viaje_transporte SET idModelo = 1 WHERE idModelo IS NULL;
ALTER TABLE viaje_transporte MODIFY COLUMN idModelo INT NOT NULL;

-- 6. Remover columna idVehiculo (comentar si quieres mantenerla por compatibilidad)
-- ALTER TABLE viaje_transporte DROP COLUMN idVehiculo;

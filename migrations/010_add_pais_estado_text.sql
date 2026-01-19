-- Agregar campos de texto para país y estado
-- (Mantener idPais e idEstado para referencias, pero agregar versión texto para autocompletar)

ALTER TABLE terminal_transporte
ADD COLUMN pais VARCHAR(100) NULL AFTER ciudad,
ADD COLUMN estado VARCHAR(200) NULL AFTER ciudad;

-- Actualizar registros existentes con datos conocidos
UPDATE terminal_transporte 
SET pais = 'Argentina', estado = 'Buenos Aires'
WHERE ciudad = 'Buenos Aires' AND pais IS NULL;

UPDATE terminal_transporte 
SET pais = 'Argentina', estado = 'Córdoba'
WHERE ciudad = 'Córdoba' AND pais IS NULL;

UPDATE terminal_transporte 
SET pais = 'Argentina', estado = 'Mendoza'
WHERE ciudad = 'Mendoza' AND pais IS NULL;

UPDATE terminal_transporte 
SET pais = 'Brasil', estado = 'São Paulo'
WHERE ciudad = 'São Paulo' AND pais IS NULL;

UPDATE terminal_transporte 
SET pais = 'Brasil', estado = 'Rio de Janeiro'
WHERE ciudad = 'Rio de Janeiro' AND pais IS NULL;

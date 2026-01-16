-- =========================================
-- TERMINALES DE EJEMPLO PARA TESTING
-- Fecha: 2026-01-16
-- =========================================

-- ARGENTINA - BUENOS AIRES
INSERT INTO terminal_transporte 
(nombre, direccion, ciudad, latitud, longitud, idTipoTransporte, habilitado) 
VALUES 
('Terminal de Retiro', 'Av. Ramos Mejía 1680, Buenos Aires', 'Buenos Aires', -34.588886, -58.373993, 1, 1),
('Aeropuerto Ezeiza', 'Autopista Teniente General Pablo Riccheri, Ezeiza', 'Buenos Aires', -34.822222, -58.535833, 2, 1),
('Aeropuerto Aeroparque', 'Av. Rafael Obligado, Buenos Aires', 'Buenos Aires', -34.559171, -58.415600, 2, 1),
('Estación Retiro - Mitre', 'Av. Ramos Mejía 1358, Buenos Aires', 'Buenos Aires', -34.591944, -58.374444, 3, 1);

-- ARGENTINA - MAR DEL PLATA
INSERT INTO terminal_transporte 
(nombre, ciudad, latitud, longitud, idTipoTransporte, habilitado) 
VALUES 
('Terminal Mar del Plata', 'Mar del Plata', -38.002530, -57.550130, 1, 1),
('Aeropuerto Astor Piazzolla', 'Mar del Plata', -37.934167, -57.573333, 2, 1);

-- ARGENTINA - BARILOCHE
INSERT INTO terminal_transporte 
(nombre, ciudad, latitud, longitud, idTipoTransporte, habilitado) 
VALUES 
('Terminal Bariloche', 'San Carlos de Bariloche', -41.133333, -71.300000, 1, 1),
('Aeropuerto Bariloche', 'San Carlos de Bariloche', -41.151111, -71.157500, 2, 1);

-- ARGENTINA - MENDOZA
INSERT INTO terminal_transporte 
(nombre, ciudad, latitud, longitud, idTipoTransporte, habilitado) 
VALUES 
('Terminal del Sol Mendoza', 'Mendoza', -32.889722, -68.845278, 1, 1),
('Aeropuerto El Plumerillo', 'Mendoza', -32.831667, -68.793333, 2, 1);

-- BRASIL - RÍO DE JANEIRO
INSERT INTO terminal_transporte 
(nombre, direccion, ciudad, codigo_iata, latitud, longitud, idTipoTransporte, habilitado) 
VALUES 
('Rodoviária Novo Rio', 'Av. Francisco Bicalho, 1, Santo Cristo', 'Río de Janeiro', NULL, -22.898333, -43.222222, 1, 1),
('Aeroporto Santos Dumont', 'Praça Senador Salgado Filho', 'Río de Janeiro', 'SDU', -22.910461, -43.163133, 2, 1),
('Aeroporto do Galeão', 'Av. Vinte de Janeiro', 'Río de Janeiro', 'GIG', -22.809444, -43.250556, 2, 1);

-- BRASIL - SÃO PAULO
INSERT INTO terminal_transporte 
(nombre, direccion, ciudad, codigo_iata, latitud, longitud, idTipoTransporte, habilitado) 
VALUES 
('Terminal Tietê', 'Av. Cruzeiro do Sul, 1800', 'São Paulo', NULL, -23.514722, -46.626111, 1, 1),
('Terminal Barra Funda', 'Rua Mário de Andrade, 664', 'São Paulo', NULL, -23.525556, -46.669444, 1, 1),
('Aeroporto de Congonhas', 'Av. Washington Luís', 'São Paulo', 'CGH', -23.626111, -46.655833, 2, 1),
('Aeroporto de Guarulhos', 'Rod. Hélio Smidt, Cumbica', 'São Paulo', 'GRU', -23.432222, -46.469444, 2, 1);

-- BRASIL - PORTO ALEGRE
INSERT INTO terminal_transporte 
(nombre, ciudad, codigo_iata, latitud, longitud, idTipoTransporte, habilitado) 
VALUES 
('Rodoviária de Porto Alegre', 'Porto Alegre', NULL, -30.027222, -51.228611, 1, 1),
('Aeroporto Salgado Filho', 'Porto Alegre', 'POA', -29.994444, -51.171389, 2, 1);

-- BRASIL - FLORIANÓPOLIS
INSERT INTO terminal_transporte 
(nombre, ciudad, codigo_iata, latitud, longitud, idTipoTransporte, habilitado) 
VALUES 
('Terminal Rita Maria', 'Florianópolis', NULL, -27.593889, -48.518611, 1, 1),
('Aeroporto Hercílio Luz', 'Florianópolis', 'FLN', -27.670278, -48.552500, 2, 1);

-- URUGUAY - MONTEVIDEO
INSERT INTO terminal_transporte 
(nombre, ciudad, codigo_iata, latitud, longitud, idTipoTransporte, habilitado) 
VALUES 
('Terminal Tres Cruces', 'Montevideo', NULL, -34.893889, -56.166667, 1, 1),
('Aeropuerto de Carrasco', 'Montevideo', 'MVD', -34.838333, -56.030556, 2, 1);

-- URUGUAY - PUNTA DEL ESTE
INSERT INTO terminal_transporte 
(nombre, ciudad, latitud, longitud, idTipoTransporte, habilitado) 
VALUES 
('Terminal Punta del Este', 'Punta del Este', -34.966667, -54.950000, 1, 1),
('Aeropuerto Capitán Corbeta', 'Punta del Este', -34.855000, -55.093333, 2, 1);

-- CHILE - SANTIAGO
INSERT INTO terminal_transporte 
(nombre, ciudad, codigo_iata, latitud, longitud, idTipoTransporte, habilitado) 
VALUES 
('Terminal San Borja', 'Santiago', NULL, -33.451111, -70.662222, 1, 1),
('Terminal Alameda', 'Santiago', NULL, -33.448611, -70.678056, 1, 1),
('Aeropuerto Arturo Merino Benítez', 'Santiago', 'SCL', -33.393056, -70.785833, 2, 1);

-- PERÚ - LIMA
INSERT INTO terminal_transporte 
(nombre, ciudad, codigo_iata, latitud, longitud, idTipoTransporte, habilitado) 
VALUES 
('Terminal Plaza Norte', 'Lima', NULL, -11.994444, -77.062500, 1, 1),
('Aeropuerto Jorge Chávez', 'Lima', 'LIM', -12.021944, -77.114444, 2, 1);

-- PARAGUAY - ASUNCIÓN
INSERT INTO terminal_transporte 
(nombre, ciudad, codigo_iata, latitud, longitud, idTipoTransporte, habilitado) 
VALUES 
('Terminal de Ómnibus Asunción', 'Asunción', NULL, -25.286389, -57.633611, 1, 1),
('Aeropuerto Silvio Pettirossi', 'Asunción', 'ASU', -25.240000, -57.519444, 2, 1);

-- Verificar cuántas se insertaron
SELECT 'Total terminales insertadas:' as info, COUNT(*) as cantidad FROM terminal_transporte;
SELECT ciudad, COUNT(*) as cantidad FROM terminal_transporte GROUP BY ciudad ORDER BY cantidad DESC;

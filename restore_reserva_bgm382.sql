SET FOREIGN_KEY_CHECKS=0;
SET @codigo := 'BGM382';
SET @fechaAlta := '2025-12-23 20:19:35';

INSERT INTO reservas (idUsuario,codigoAmigable,fechaAlta,nombreResponsable,apellidoResponsable,emailResponsable,idCountry,telefonoResponsable,monedaSel,impuestosPais,idCuponDescuento,idUsuarioCupon,total,total_dolares,impuestos,idEstado,nota_interna,idioma)
VALUES (1,@codigo,@fechaAlta,'Daniela','Freitas Batista','djdanyfreitas@hotmail.com',30,'43996491047',283,0,0,0,598,99.67,0,3,NULL,'PT');
SET @reserva_id := LAST_INSERT_ID();

INSERT INTO reserva_horarios (idReserva,idServicioSalidas,idServicioSeleccionado,CodigoVoucherServicio,cantidadPasajeros,nombre,fecha,horaSalida,horaCheckIn,direccion,latitud,longitud,comentario)
VALUES (@reserva_id,64271,589,CONCAT(@codigo,'-000589'),0,'Meia Alta Temporada 11/12 - 25/12/2025','2025-12-23','10:00:00','09:55:00','R. Amaro Coelho, 67 - Barra da Lagoa, Florianópolis - SC, 88061-090, Brasil','-27.575059','-48.423394','');
SET @horario_id := LAST_INSERT_ID();

INSERT INTO reserva_tarifas (idReservaHorarios,idServicioSalidasTarifas,nombre,cantidad,monedaSel,valor,valorSinIva,valorDeIva,idFromEdad,idToEdad,comisionVendedor,comisionSistema,nombrePasajero)
VALUES (@horario_id,183113,'INTEGRAL',2,283,598,598,0,17,13,59.8,59.8,'');
SET @tarifa_id := LAST_INSERT_ID();

INSERT INTO reserva_pasajeros (idReservaTarifas,nombrePasajero,apellidoPasajero) VALUES
(@tarifa_id,'Daniela','Freitas Batista'),
(@tarifa_id,'Gerlandio','');

INSERT INTO reserva_adicionales (idReservaHorarios,idServiciosAdicionales,nombre,descripcion,cantidad,precio,precioUnitarioSIva,valorIva,precioIva) VALUES
(@horario_id,18,'Staff receptivo','',0,0,0,0,0),
(@horario_id,151,'Marineiros especializados','',0,0,0,0,0),
(@horario_id,152,'Agua mineral libre','',0,0,0,0,0),
(@horario_id,153,'Embarcación autorizada','',0,0,0,0,0),
(@horario_id,154,'Permiso municipal y ambiental','',0,0,0,0,0),
(@horario_id,158,'Toaletes masculino e feminino','',0,0,0,0,0),
(@horario_id,160,'Bar a bordo','',0,0,0,0,0),
(@horario_id,217,'Tasa de preservación','',0,0,0,0,0),
(@horario_id,250,'Tasa de desembarque en la isla','',0,0,0,0,0),
(@horario_id,179,'Chalecos salvavidas','',0,0,0,0,0),
(@horario_id,288,'Banheiro à bordo','',0,0,0,0,0);

SET FOREIGN_KEY_CHECKS=1;

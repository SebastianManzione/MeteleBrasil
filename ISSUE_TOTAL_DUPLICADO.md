# Issue: Total Duplicado en Reservas (BGM382: 1196 en lugar de 598)

## Descripción del Problema
Reserva BGM382 muestra valor total de **1196** cuando debería ser **598** (exactamente el doble).

## Investigación

### Posibles Causas
1. **guardaReservas.php**: El total se calcula sumando para cada horario (`$precioTotalReserva+=$valor`)
   - Pero esto debería ser correcto si solo hay 1 horario
   - Si hay 2 horarios, sería correcto sumar ambos
   - El problema sería si: 
     - El MISMO horario aparece duplicado en `getReservaHorarios()`
     - O se itera dos veces sobre el mismo horario

2. **reservasEstado.php**: Muestra el total recalculado por horario, no el de BD
   - Loop sobre `$horarios` suma `$total` para cada uno
   - Si una reserva aparece dos veces, se muestra duplicada

### Tablas Involucradas
- `reservas`: `idReserva`, `total`, `total_dolares`, `monedaSel`
- `reserva_horarios`: `idReservaHorarios`, `idReserva`, `idServicioSalidas`
- `reserva_tarifas`: `idReservaHorarios`, `valor`, `cantidad`

##Archivos a Revisar

### 1. guardaReservas.php (línea 150)
```php
for ($i=0; $i < count($servicios); $i++) {
    for ($j=0; $j < count($servicios[$i][0]); $j++) {
        $precioTotalReserva+=$valor;  // ← Aquí se suma
    }
}
```
**Acción**: Verificar si `$servicios` contiene duplicados

### 2. reservasEstado.php (línea 150, 220, 700)
Actualmente suma `$total` para cada horario de una reserva.
```php
for ($j=0; $j < count($horarios); $j++) {
    $total += ConvierteMoneda(...);
    // Luego muestra $total
}
```
**Acción**: Cambiar para mostrar `$reservas[$i]["total"]` convertido, no recalculado

### 3. carritosLista.php
```php
$total=$reservas[$i]["total"];
$precio=ConvierteMoneda(188,$_SESSION["moneda_sel"], $total_dolares);
```
**Acción**: Verificar que esto muestra el total correcto de la BD

### 4. admin/classes/reserva.php - getReservaHorarios()
**Acción**: Verificar que no retorna duplicados

## Solución Propuesta

### Opción 1: Fix en guardaReservas.php
Agregar validación para evitar sumar horarios duplicados:
```php
$horariosYaContados = [];
for ($i=0; $i < count($servicios); $i++) {
    for ($j=0; $j < count($servicios[$i][0]); $j++) {
        if (!in_array($idServicioSalidas, $horariosYaContados)) {
            $precioTotalReserva+=$valor;
            $horariosYaContados[] = $idServicioSalidas;
        }
    }
}
```

### Opción 2: Fix en reservasEstado.php
Mostrar una sola fila por reserva, usando total de BD:
```php
$reservasYaMostradas = [];
foreach ($reservas as $reserva) {
    if (in_array($reserva['idReserva'], $reservasYaMostradas)) continue;
    $reservasYaMostradas[] = $reserva['idReserva'];
    // Mostrar fila con $reserva['total'] convertido
}
```

## Testing
```sql
SELECT idReserva, codigoAmigable, total, 
  (SELECT COUNT(*) FROM reserva_horarios WHERE idReserva = r.idReserva) as cantidad_horarios
FROM reservas r
WHERE codigoAmigable = 'BGM382';
```

## Status
- [ ] Identificar si el problema está en guardaReservas.php o reservasEstado.php
- [ ] Implementar fix
- [ ] Verificar en carritosLista.php, reservaDetalles.php, financieroLista.php
- [ ] Test end-to-end

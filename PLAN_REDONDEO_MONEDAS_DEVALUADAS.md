# Plan: Redondeo de Monedas Devaluadas (ARS, CLP, PYG)

**Fecha:** 11 de enero de 2026  
**BD:** metelebrasil_experimental  
**Backups:** 
- backup_metelebrasil_20260111_041835.sql (~85MB)
- backup_metelebrasil_20260111_041805.sql (~85MB)

## IDs de Monedas Afectadas
| ID | Código | Nombre | Símbolo |
|----|--------|--------|---------|
| 270 | ARS | Peso Argentino | AR$ |
| 271 | CLP | Peso Chileno | CH$ |
| 225 | PYG | Guaraní Paraguayo | G$ |

## Cambios Requeridos

### 1. **BD - Agregar campos a tabla reservas**
✅ COMPLETADO

**Campos agregados:**
```sql
ALTER TABLE reservas ADD COLUMN descuento_redondeo DECIMAL(10,2) DEFAULT 0;
ALTER TABLE reservas ADD COLUMN moneda_redondeo INT DEFAULT NULL;
```

**Uso:**
- `descuento_redondeo` = Monto del descuento por redondeo
- `moneda_redondeo` = ID de la moneda (270=ARS, 271=CLP, 225=PYG)

### 2. **Backend - admin/ctrl/ctrlHorarios.php**
**Ubicación:** Líneas 305-320

**Cambio:**
```
ANTES: Redondeo solo para ARS (270) a 500 pesos
DESPUÉS: Redondeo para ARS/CLP/PYG a 1000 unidades
```

**Lógica nueva:**
```php
$redondeoDiferencia = 0;
if (in_array($idMoneda, [270, 271, 225])) { // ARS, CLP, PYG
    $redondeoDiferencia = ceil($valorOriginal / 1000) * 1000 - $valorOriginal;
    $precio = $valorOriginal + $redondeoDiferencia;
}
```

**Retorno:** 
```php
$retorno[$i]['redondeoDiferencia'] = $redondeoDiferencia;
```

### 3. **Frontend - datosPersonales.php**
**Ubicación:** Línea 55 (init), 728 (acumular), antes de pago (guardar)

**Cambios:**
- Inicializar: `$descuentoGanado = 0;`
- Acumular: `$descuentoGanado += $tarifa[0]['redondeoDiferencia'];`
- Guardar en BD antes de redirigir a pago

**Guardar descuento en BD:**
```php
if ($descuentoGanado > 0 && in_array($_SESSION['moneda_sel'], [270, 271, 225])) {
    $sqlUpdate = "UPDATE reservas 
                  SET descuento_redondeo = :monto, 
                      moneda_redondeo = :moneda 
                  WHERE idReserva = :idReserva";
    $cmdUpdate = $pdo->prepare($sqlUpdate);
    $cmdUpdate->execute([
        ':monto' => round($descuentoGanado),
        ':moneda' => $_SESSION['moneda_sel'],
        ':idReserva' => $idReserva
    ]);
}
```

### 4. **Vouchers/Carrito - voucherCarrito.php, voucherSalida.php**
Mostrar descuento en resumen:
```
Descuento por redondeo: -AR$1,000.00
TOTAL A PAGAR: XX.XX
```

### 5. **Email Confirmación - admin/classes/email_reserva_confirmada.php**
Incluir descuento en email de confirmación.

### 6. **Reportes Financieros - admin/financieroLista.php**
Mostrar descuentos de redondeo por moneda.

## Pasos de Implementación

### Paso 1: ✅ BD - Campos en tabla reservas
```bash
# YA COMPLETADO
# - Agregados: descuento_redondeo, moneda_redondeo
# - Eliminada tabla descuento_redondeo_reserva (no necesaria)
```

### Paso 2: Backend - ctrlHorarios.php
- Cambiar múltiplo de 500 → 1000
- Agregar IDs 271 (CLP) y 225 (PYG) a la condición

### Paso 3: Frontend - datosPersonales.php
- Acumular descuento
- Guardar en reservas.descuento_redondeo antes de redirigir a pago

### Paso 4: Vouchers - voucherCarrito.php y voucherSalida.php
- Mostrar descuento en el resumen
- Descontar del total final

### Paso 5: Email - email_reserva_confirmada.php
- Mostrar descuento en confirmación

### Paso 6: Reportes - financieroLista.php
- Columna con descuentos por moneda

## Testing
- [ ] Crear reserva con ARS → verificar redondeo a 1000
- [ ] Crear reserva con CLP → verificar redondeo a 1000
- [ ] Crear reserva con PYG → verificar redondeo a 1000
- [ ] Verificar descuento guardado en BD
- [ ] Verificar descuento en email
- [ ] Verificar descuento en reportes financieros
- [ ] USD/BRL NO deben redondearse

## Rollback
```bash
# Si falla, restaurar:
mysql -u root metelebrasil_experimental < backup_metelebrasil_20260111_041835.sql
git reset --hard HEAD~N  # Volver N commits atrás
```

## Commits Git (orden)
1. `feat: crear tabla descuento_redondeo_reserva`
2. `feat: cambiar redondeo ARS/CLP/PYG de 500 a 1000 en ctrlHorarios`
3. `feat: guardar descuento en BD desde datosPersonales`
4. `feat: mostrar descuento en email_reserva_confirmada`
5. `feat: agregar descuentos en reportes financieros`

---

**Estado:** ✅ LISTO PARA IMPLEMENTAR (Backups creados, Plan documentado)

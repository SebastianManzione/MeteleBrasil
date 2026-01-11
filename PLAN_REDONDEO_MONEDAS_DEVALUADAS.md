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

### 1. **Backend - admin/ctrl/ctrlHorarios.php**
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

### 2. **Nueva Tabla BD - descuento_redondeo_reserva**
Guardar el descuento por redondeo en cada reserva.

```sql
CREATE TABLE descuento_redondeo_reserva (
    idDescuento INT AUTO_INCREMENT PRIMARY KEY,
    idReserva INT NOT NULL,
    idMoneda INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fechaAlta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idReserva) REFERENCES reservas(idReserva),
    FOREIGN KEY (idMoneda) REFERENCES moneda(idMoneda)
);
```

### 3. **Frontend - datosPersonales.php**
**Ubicación:** Línea 55-730

**Cambios:**
- Inicializar: `$descuentoGanado = 0;`
- Acumular: `$descuentoGanado += $tarifa[0]['redondeoDiferencia'];`
- Guardar en BD antes de pagar

**Guardar descuento antes de pago:**
```php
if ($descuentoGanado > 0 && in_array($_SESSION['moneda_sel'], [270, 271, 225])) {
    $sqlDescuento = "INSERT INTO descuento_redondeo_reserva 
                     (idReserva, idMoneda, monto) 
                     VALUES (:idReserva, :idMoneda, :monto)";
    $cmdDescuento = $pdo->prepare($sqlDescuento);
    $cmdDescuento->execute([
        ':idReserva' => $idReserva,
        ':idMoneda' => $_SESSION['moneda_sel'],
        ':monto' => round($descuentoGanado)
    ]);
}
```

### 4. **Ticket/Comprobante - admin/email/email_reserva_confirmada.php**
Mostrar descuento de redondeo en el email/ticket:
```
Descuento por redondeo: AR$1,000.00
```

### 5. **Reportes Financieros - admin/financieroLista.php**
Mostrar descuentos de redondeo por moneda en reportes.

## Pasos de Implementación

### Paso 1: BD
```bash
# Crear tabla nueva
mysql -u root metelebrasil_experimental < script_crear_tabla.sql
```

### Paso 2: Backend
- Actualizar: `admin/ctrl/ctrlHorarios.php` (línea 309)
- Cambiar múltiplo de 500 → 1000
- Agregar IDs 271 y 225

### Paso 3: Frontend
- Actualizar: `datosPersonales.php` 
- Guardar descuento en BD antes de redirigir a pago

### Paso 4: Email/Ticket
- Mostrar descuento en confirmación

### Paso 5: Reportes
- Agregar columna descuento en financiero

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

#!/bin/bash
# Script para restaurar servicios en producción

DB_USER="u925692129_metelebrasil"
DB_PASS="Cambiar2026"
DB_NAME="u925692129_metelebrasil"

echo "🔄 Iniciando restauración de servicios..."

# Usar mariadb en lugar de mysql (deprecated)
/usr/bin/mariadb -u $DB_USER -p"$DB_PASS" $DB_NAME << 'EOSQL'

-- Copiar servicios desde tabla temporal (si existe)
-- O si tenemos los datos en otro lado, insertarlos directamente

-- Mostrar estado actual
SELECT 'Estado actual:' as status;
SELECT COUNT(*) as servicios_actuales FROM servicio;

EOSQL

echo "✅ Script ejecutado"

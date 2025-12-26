#!/bin/bash

echo "📊 Exportando Servicio 589 - ISLA DE CAMPECHE"
echo "=============================================="

# Crear archivo SQL
output_file="servicio_589_completo.sql"

# Encabezado
echo "-- ============================================" > $output_file
echo "-- Servicio ID 589 - ISLA DE CAMPECHE" >> $output_file
echo "-- Exportado: $(date)" >> $output_file
echo "-- ============================================" >> $output_file
echo "" >> $output_file

# Servicio
echo "-- SERVICIO" >> $output_file
/c/xampp/mysql/bin/mysqldump.exe -u root metelebrasil servicio --where="idServicio=589" --no-create-info >> $output_file 2>&1

echo "" >> $output_file

# Salidas
echo "-- SALIDAS (54 registros)" >> $output_file
/c/xampp/mysql/bin/mysqldump.exe -u root metelebrasil servicio_salidas --where="idServicio=589" --no-create-info >> $output_file 2>&1

echo "" >> $output_file

# Tarifas (obtener IDs de salidas primero)
salida_ids=$(/c/xampp/mysql/bin/mysql.exe -u root metelebrasil -e "SELECT GROUP_CONCAT(idServicioSalidas) FROM servicio_salidas WHERE idServicio=589;" 2>&1 | tail -1)

echo "-- TARIFAS" >> $output_file
/c/xampp/mysql/bin/mysqldump.exe -u root metelebrasil servicio_salidas_tarifas --where="idServicioSalidas IN ($salida_ids)" --no-create-info >> $output_file 2>&1

echo "" >> $output_file

# Imágenes
echo "-- IMÁGENES" >> $output_file
/c/xampp/mysql/bin/mysqldump.exe -u root metelebrasil servicio_img --where="idServicio=589" --no-create-info >> $output_file 2>&1

echo "✅ Exportación completada"
echo "📄 Archivo: $output_file"
ls -lh $output_file

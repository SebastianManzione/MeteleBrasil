#!/usr/bin/env python3
import re
import sys

backup_file = r'C:\Users\SisteMANZ\Documents\Downloads\metelebr_metelebrasil (1).sql'
service_id = 589

print(f"🔍 Buscando servicio ID {service_id} en el backup...")
print("="*80)

# Diccionarios para almacenar datos
services = {}
salidas = {}
tarifas = []
imagenes = []

try:
    with open(backup_file, 'r', encoding='utf-8', errors='ignore') as f:
        current_table = None
        line_num = 0
        
        for line in f:
            line_num += 1
            
            # Detectar tabla actual
            if 'INSERT INTO `servicio`' in line:
                current_table = 'servicio'
            elif 'INSERT INTO `servicio_salidas`' in line:
                current_table = 'servicio_salidas'
            elif 'INSERT INTO `servicio_salidas_tarifas`' in line:
                current_table = 'servicio_salidas_tarifas'
            elif 'INSERT INTO `servicio_img`' in line:
                current_table = 'servicio_img'
            
            # Procesar según tabla
            if current_table == 'servicio' and '(589,' in line:
                # Extraer servicio
                match = re.search(r'\(589,[^;]+\)', line)
                if match:
                    services[589] = match.group(0)
                    print(f"✅ Servicio encontrado en línea {line_num}")
            
            elif current_table == 'servicio_salidas' and ',589,' in line:
                # Extraer salidas
                matches = re.findall(r'\(\d+,589,[^;]+?\)', line)
                for m in matches:
                    id_match = re.match(r'\((\d+),', m)
                    if id_match:
                        sid = id_match.group(1)
                        salidas[sid] = m
                        print(f"   📍 Salida ID {sid} encontrada")
            
            elif current_table == 'servicio_salidas_tarifas':
                # Buscar tarifas de nuestras salidas
                for sid in salidas.keys():
                    if f',{sid},' in line:
                        matches = re.findall(r'\(\d+,' + sid + r',[^;]+?\)', line)
                        tarifas.extend(matches)
            
            elif current_table == 'servicio_img':
                # Buscar imágenes
                if ',589,' in line:
                    matches = re.findall(r'\(\d+,589,[^;]+?\)', line)
                    imagenes.extend(matches)
            
            # Mostrar progreso
            if line_num % 50000 == 0:
                print(f"   Procesadas {line_num} líneas...")

except UnicodeDecodeError:
    print("⚠️  Error de encoding. Reintentando con latin-1...")
    with open(backup_file, 'r', encoding='latin-1') as f:
        for line in f:
            if '(589,' in line and 'INSERT INTO `servicio`' in line:
                match = re.search(r'\(589,[^;]+\)', line)
                if match:
                    services[589] = match.group(0)
                    print(f"✅ Servicio encontrado")

print("\n" + "="*80)
print("📊 RESULTADOS")
print("="*80)
print(f"Servicios encontrados: {len(services)}")
print(f"Salidas encontradas: {len(salidas)}")
print(f"Tarifas encontradas: {len(tarifas)}")
print(f"Imágenes encontradas: {len(imagenes)}")

if len(services) == 0:
    print("\n⚠️  Servicio 589 NO encontrado en el backup")
    print("\n¿Quizás el ID es diferente? Verifica el ID correcto del servicio.")
else:
    print("\n✅ Servicio listo para extraer")

"""
Procesar Planos OCR - MeteleBrasil

Función: Analiza imágenes descargadas de mundocolectivo.com.ar, genera una
estructura JSON de distribución de asientos base y prepara salida/logs.

Primer MVP (seguro):
- Recorre img/planos_carroceria/ y lista imágenes
- Genera un JSON de ejemplo por imagen (sin inserción en BD)
- Graba logs en logs/planos_ocr.log

Para producción:
- Implementar detección real con OpenCV + Tesseract
- Insertar en BD (carroceria_planos)

Dependencias:
- requests, beautifulsoup4, pillow, numpy, opencv-python, pytesseract

Ejecución:
    python procesar_planos_ocr.py
"""
import os
import json
import time
from datetime import datetime

# Dependencias de imagen/OCR
try:
    import cv2
    import numpy as np
    from PIL import Image
    import pytesseract
except Exception as e:
    print("[ERROR] Falta alguna librería de Python requerida:", e)
    print("Instala con: pip install requests beautifulsoup4 pillow numpy opencv-python pytesseract")
    raise

# Intentar configurar Tesseract en Windows (ruta por defecto)
DEFAULT_TESSERACT = r"C:\\Program Files\\Tesseract-OCR\\tesseract.exe"
if os.path.exists(DEFAULT_TESSERACT):
    pytesseract.pytesseract.tesseract_cmd = DEFAULT_TESSERACT

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
IMG_DIR = os.path.join(BASE_DIR, 'img', 'planos_carroceria')
LOGS_DIR = os.path.join(BASE_DIR, 'logs')
LOG_FILE = os.path.join(LOGS_DIR, 'planos_ocr.log')

os.makedirs(LOGS_DIR, exist_ok=True)

def log(msg: str):
    ts = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    line = f"[{ts}] {msg}"
    print(line)
    try:
        with open(LOG_FILE, 'a', encoding='utf-8') as f:
            f.write(line + "\n")
    except Exception:
        pass

def listar_imagenes(base_path: str):
    exts = {'.jpg', '.jpeg', '.png'}
    files = []
    for root, _, filenames in os.walk(base_path):
        for fn in filenames:
            if os.path.splitext(fn)[1].lower() in exts:
                files.append(os.path.join(root, fn))
    return files

def generar_json_demo(width: int, height: int):
    """Genera una distribución JSON de ejemplo para pruebas."""
    filas = 5
    columnas = 6
    matriz = [[1 for _ in range(columnas)] for _ in range(filas)]
    # Marcar algunos elementos especiales en el borde inferior como demo
    matriz[-1][0] = 'B'  # Baño
    matriz[-1][-1] = 'T' # TV
    return {
        "pisos": [
            {
                "nombre": "Piso Único",
                "filas": filas,
                "columnas": columnas,
                "asientos": matriz
            }
        ]
    }


def procesar_imagen(path: str):
    try:
        img = cv2.imread(path)
        if img is None:
            log(f"[WARN] No se pudo abrir imagen: {path}")
            return None
        h, w = img.shape[:2]
        log(f"[INFO] Imagen {os.path.basename(path)} - {w}x{h}px")

        # Aquí iría la detección real (contornos, símbolos, etc.)
        # Por ahora generamos un JSON DEMO para avanzar en la integración.
        dist_json = generar_json_demo(w, h)
        return dist_json
    except Exception as e:
        log(f"[ERROR] procesando {path}: {e}")
        return None


def main():
    start = time.time()
    log("[*] Iniciando procesamiento OCR de planos...")

    if not os.path.isdir(IMG_DIR):
        log(f"[WARN] Carpeta no existe: {IMG_DIR} (se crea al descargar)")
        log("[HINT] Ejecuta: python descargar_planos_mundocolectivo.py")
        return

    imgs = listar_imagenes(IMG_DIR)
    if not imgs:
        log("[WARN] No se encontraron imágenes. Descarga primero los planos.")
        return

    log(f"[INFO] Total imágenes encontradas: {len(imgs)}")

    resultados = []
    for i, path in enumerate(imgs, 1):
        log(f"[PROC] ({i}/{len(imgs)}) {path}")
        dist = procesar_imagen(path)
        if dist:
            resultados.append({
                "imagen": os.path.relpath(path, BASE_DIR).replace('\\', '/'),
                "distribucion_json": dist
            })
        # Pequeña pausa para no saturar
        time.sleep(0.05)

    # Guardar salida JSON de prueba
    out_file = os.path.join(BASE_DIR, 'planos_ocr_salida_demo.json')
    try:
        with open(out_file, 'w', encoding='utf-8') as f:
            json.dump(resultados, f, ensure_ascii=False, indent=2)
        log(f"[OK] Salida demo guardada en: {out_file}")
    except Exception as e:
        log(f"[ERROR] Guardando salida demo: {e}")

    elapsed = time.time() - start
    log(f"[✓] Procesamiento finalizado en {elapsed:.2f}s. Total procesados: {len(resultados)}")
    log("[*] Para insertar en BD, ejecuta la migración y agrega la lógica de INSERT.")

if __name__ == '__main__':
    main()

import os

def crear_archivos_txt_recursivamente(carpeta):
    for root, _, files in os.walk(carpeta):
        for file in files:
            # Ignorar archivos con la extensión .txt
            if not file.endswith(".txt"):
                ruta_original = os.path.join(root, file)
                ruta_txt = ruta_original + ".txt"

                # Crear el archivo .txt si no existe
                if not os.path.exists(ruta_txt):
                    with open(ruta_txt, 'w') as archivo_txt:
                        pass
                    print(f"Creado: {ruta_txt}")
                else:
                    print(f"Ya existe: {ruta_txt}")

if __name__ == "__main__":
    carpeta_materiales = "materiales"  # Nombre de la carpeta principal

    if os.path.exists(carpeta_materiales):
        crear_archivos_txt_recursivamente(carpeta_materiales)
    else:
        print(f"La carpeta '{carpeta_materiales}' no existe.")

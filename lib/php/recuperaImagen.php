<?php

/**
 * Recupera el texto de un parámetro enviado al
 * servidor por medio de GET, POST o cookie.
 * 
 * Si el parámetro no se recibe, devuelve false.
 */
function recuperaImagen(string $parametro): false|string
{
 /* Si el parámetro está asignado en $_REQUEST,
  * devuelve su valor; de lo contrario, devuelve false.
  */
  $valor = false;

    if (isset($_FILES[$parametro])) {
        // Recupera la información del archivo
        $archivo = $_FILES[$parametro];

        // Verifica si hubo errores en la carga
        if ($archivo['error'] === UPLOAD_ERR_OK) {
            // Obtiene la ruta temporal del archivo
            $rutaTemporal = $archivo['tmp_name'];

            // Define la ruta base donde deseas guardar la imagen
            $nombreArchivo = basename($archivo['name']);
            $rutaDestino = '../public/ligas/' . $nombreArchivo;

            // Si el archivo ya existe, modifica el nombre
            $contador = 1; // Contador para el nuevo nombre
            while (file_exists($rutaDestino)) {
                // Crea un nuevo nombre añadiendo un sufijo
                $nombreArchivoSinExt = pathinfo($nombreArchivo, PATHINFO_FILENAME);
                $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                $nuevoNombre = $nombreArchivoSinExt . '_' . $contador . '.' . $extension;

                // Actualiza la ruta de destino con el nuevo nombre
                $rutaDestino = '../public/ligas/' . $nuevoNombre;
                $contador++; // Incrementa el contador
            }

            // Mueve el archivo de la ubicación temporal a la ruta deseada
            if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
                $valor = $rutaDestino;
            } 
        } 
    }

 return $valor;
}

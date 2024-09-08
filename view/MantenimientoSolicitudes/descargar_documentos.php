<?php
// Incluir el archivo de conexión a la base de datos
require_once '../../config/conexion.php'; // Cambia la ruta según la ubicación de tu archivo

// Iniciar la sesión
session_start();

// Verificar si se ha proporcionado un ID de usuario en la URL
$id_usuario = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : null;

// Verificar si se han proporcionado IDs válidos
if ($id_usuario && isset($_SESSION['id_solicitud'])) {
    $id_solicitud = $_SESSION['id_solicitud'];

    $id_solicitud = isset($id_solicitud) ? $id_solicitud : null;

    // Verificar si el ID de solicitud está definido y no es nulo
    if ($id_solicitud !== null) {
        // Generar el URL para el botón de descarga
        $descargar_url = "descargar_documentos.php?id_usuario=" . urlencode($id_usuario) . "&id_solicitud=" . urlencode($id_solicitud);
    } else {
        // Manejar el caso en que el ID de solicitud no esté definido
        $descargar_url = "#"; // O cualquier URL alternativa para manejar el error
    }

    try {
        // Crear una instancia de la conexión a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Preparar la consulta para obtener los archivos del usuario y solicitud
        $sql = "SELECT SOLICITUD, PLAN_ESTUDIOS, PLANTA_DOCENTE, DIAGNOSTICO 
                FROM tbl_archivos_adjuntos 
                WHERE IdUsuario = :id_usuario AND ID_SOLICITUD = :id_solicitud";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(":id_solicitud", $id_solicitud, PDO::PARAM_INT);
        $stmt->execute();

        // Obtener los resultados
        $archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verificar si se encontraron archivos
        if (count($archivos) > 0) {
            // Crear un archivo ZIP para almacenar los documentos
            $zip = new ZipArchive();
            $zip_filename = "documentos_usuario_" . $id_usuario . "_solicitud_" . $id_solicitud . ".zip";

            // Abrir el archivo ZIP
            if ($zip->open($zip_filename, ZipArchive::CREATE) !== TRUE) {
                exit("No se pudo crear el archivo ZIP.\n");
            }

            // Función para agregar archivos al ZIP si existen
            function agregarArchivoAlZip($zip, $archivo, $nombreArchivo)
            {
                if (file_exists($archivo) && !empty($archivo)) {
                    $zip->addFile($archivo, $nombreArchivo);
                }
            }

            // Agregar cada archivo al ZIP si existe
            foreach ($archivos as $archivo) {
                agregarArchivoAlZip($zip, $archivo['SOLICITUD'], 'Solicitud.docx');
                agregarArchivoAlZip($zip, $archivo['PLAN_ESTUDIOS'], 'Plan_de_Estudios.docx');
                agregarArchivoAlZip($zip, $archivo['PLANTA_DOCENTE'], 'Planta_Docente.docx');
                agregarArchivoAlZip($zip, $archivo['DIAGNOSTICO'], 'Diagnostico.docx');
            }

            // Cerrar el archivo ZIP
            $zip->close();

            // Configurar las cabeceras para la descarga
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zip_filename . '"');
            header('Content-Length: ' . filesize($zip_filename));

            // Enviar el archivo ZIP al navegador
            readfile($zip_filename);

            // Eliminar el archivo ZIP temporal
            unlink($zip_filename);
        } else {
            echo "No se encontraron archivos para este usuario y solicitud.";
        }

        // Cerrar la conexión
        $stmt = null;
        $conn = null;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ID de usuario o ID de solicitud no proporcionado.";
}

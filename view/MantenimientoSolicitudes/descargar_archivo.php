<?php
require_once("../../config/conexion.php"); // Incluye el archivo de conexión

// Crea una instancia de la clase Conectar
$conexion = new Conectar();
$pdo = $conexion->Conexion(); // Obtén la conexión PDO

if (isset($_GET['solicitud_id'])) {
    $solicitud_id = intval($_GET['solicitud_id']); // Sanitiza el ID de solicitud

    // Consulta para obtener los archivos asociados al ID de solicitud y verificar que exista en tbl_solicitudes
    $query = "
        SELECT 
            a.Diagnostico, 
            a.PlanEstudios, 
            a.PlantaDocente, 
            a.Solicitud
        FROM 
            `documentos.tblarchivosadjuntos` a
        INNER JOIN 
            `proceso.tblSolicitudes` s ON a.IdSolicitud = s.IdSolicitud
        WHERE 
            a.IdSolicitud = :solicitud_id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':solicitud_id', $solicitud_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $filesData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Define los directorios donde se encuentran los archivos
        $directories = [
            "Diagnostico" => "../../view/NuevoIngresoSolicitud/documentos/diagnostico/",
            "PlanEstudios" => "../../view/NuevoIngresoSolicitud/documentos/plan_estudios/",
            "PlantaDocente" => "../../view/NuevoIngresoSolicitud/documentos/planta_docente/",
            "Solicitud" => "../../view/NuevoIngresoSolicitud/documentos/solicitud/"
        ];

        // Nombre del archivo ZIP
        $zipFileName = 'Documentos_' . $solicitud_id . '.zip';
        $zip = new ZipArchive();

        if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
            foreach ($filesData as $key => $file) {
                if ($file && isset($directories[$key])) {
                    $filePath = $directories[$key] . basename($file);
                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, basename($filePath));
                    } else {
                        echo "Archivo no encontrado: " . $filePath . "<br>";
                    }
                }
            }
            $zip->close();

            // Verifica si el archivo ZIP se ha creado correctamente
            if (file_exists($zipFileName) && filesize($zipFileName) > 0) {
                // Configuración para la descarga del archivo ZIP
                header('Content-Description: File Transfer');
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($zipFileName));
                flush();
                readfile($zipFileName);

                // Elimina el archivo ZIP después de la descarga
                unlink($zipFileName);
                exit;
            } else {
                echo "El archivo ZIP no se ha creado correctamente o está vacío.";
            }
        } else {
            echo "No se pudo crear el archivo ZIP.";
        }
    } else {
        echo "No se encontraron archivos para el ID de solicitud especificado o la solicitud no existe.";
    }
} else {
    echo "No se ha especificado ningún ID de solicitud.";
}

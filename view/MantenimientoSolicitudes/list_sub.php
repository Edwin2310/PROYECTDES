<?php
require_once("../../config/conexion.php");

$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

if ($id) {
    try {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Determinar el estado de la solicitud
        $estadoSql = "SELECT IdEstado FROM `proceso.tblsolicitudes` WHERE IdSolicitud = :id";
        $estadoStmt = $conn->prepare($estadoSql);
        $estadoStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $estadoStmt->execute();
        $estadoRow = $estadoStmt->fetch(PDO::FETCH_ASSOC);

        if ($estadoRow) {
            // Obtener la última observacion para la solicitud
            $lastObservationSql = "SELECT MAX(IdObservacion) AS last_id FROM `documentos.tblobservaciones` WHERE IdSolicitud = :id";
            $lastObservationStmt = $conn->prepare($lastObservationSql);
            $lastObservationStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $lastObservationStmt->execute();
            $lastObservationRow = $lastObservationStmt->fetch(PDO::FETCH_ASSOC);

            if ($lastObservationRow) {
                $lastId = $lastObservationRow['last_id'];

                // Determinar qué tabla usar y obtener los archivos de la última observacion
                if ($estadoRow['IdEstado'] == 3) {
                    // Obtener archivos de tbl_observaciones
                    $sql = "SELECT Solicitud, PlanEstudios, PlantaDocente, Diagnostico FROM `documentos.tblobservaciones` WHERE IdObservacion = :last_id";
                    $basePath = "ConsultarSolicitudes/";
                } else {
                    // Obtener archivos de tbl_archivos_adjuntos
                    $sql = "SELECT Solicitud, PlanEstudios, PlantaDocente, Diagnostico FROM `documentos.tblarchivosadjuntos` WHERE IdSolicitud = :id";
                    $basePath = "NuevoIngresoSolicitud/";
                }

                $stmt = $conn->prepare($sql);
                if ($estadoRow['IdEstado'] == 3) {
                    $stmt->bindParam(':last_id', $lastId, PDO::PARAM_INT);
                } else {
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                }
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Construir la lista de archivos para enviar en formato JSON
                $files = [];
                foreach ($rows as $row) {
                    if ($row['Solicitud']) {
                        $files[] = [
                            'name' => basename($row['Solicitud']),
                            'url' => "../" . $basePath . $row['Solicitud']
                        ];
                    }
                    if ($row['PlanEstudios']) {
                        $files[] = [
                            'name' => basename($row['PlanEstudios']),
                            'url' => "../" . $basePath . $row['PlanEstudios']
                        ];
                    }
                    if ($row['PlantaDocente']) {
                        $files[] = [
                            'name' => basename($row['PlantaDocente']),
                            'url' => "../" . $basePath . $row['PlantaDocente']
                        ];
                    }
                    if ($row['Diagnostico']) {
                        $files[] = [
                            'name' => basename($row['Diagnostico']),
                            'url' => "../" . $basePath . $row['Diagnostico']
                        ];
                    }
                }

                // Devolver la lista de archivos en formato JSON
                echo json_encode($files);
            } else {
                echo json_encode(['error' => 'No se encontró ninguna observación para la solicitud.']);
            }
        } else {
            echo json_encode(['error' => 'Estado de la solicitud no encontrado.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error al procesar la solicitud.']);
    }
} else {
    echo json_encode(['error' => 'ID no válido.']);
}

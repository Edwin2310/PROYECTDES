<?php
require_once("../../config/conexion.php");

$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

if ($id) {
    try {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Determinar el estado de la solicitud
        $estadoSql = "SELECT ID_ESTADO FROM tbl_solicitudes WHERE ID_SOLICITUD = :id";
        $estadoStmt = $conn->prepare($estadoSql);
        $estadoStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $estadoStmt->execute();
        $estadoRow = $estadoStmt->fetch(PDO::FETCH_ASSOC);

        if ($estadoRow) {
            // Obtener la última ID_OBSERVACION para la solicitud
            $lastObservationSql = "SELECT MAX(ID_OBSERVACION) AS last_id FROM tbl_observaciones WHERE ID_SOLICITUD = :id";
            $lastObservationStmt = $conn->prepare($lastObservationSql);
            $lastObservationStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $lastObservationStmt->execute();
            $lastObservationRow = $lastObservationStmt->fetch(PDO::FETCH_ASSOC);

            if ($lastObservationRow) {
                $lastId = $lastObservationRow['last_id'];

                // Determinar qué tabla usar y obtener los archivos de la última ID_OBSERVACION
                if ($estadoRow['ID_ESTADO'] == 3) {
                    // Obtener archivos de tbl_observaciones
                    $sql = "SELECT SOLICITUD, PLAN_ESTUDIOS, PLANTA_DOCENTE, DIAGNOSTICO FROM tbl_observaciones WHERE ID_OBSERVACION = :last_id";
                    $basePath = "ConsultarSolicitudes/";
                } else {
                    // Obtener archivos de tbl_archivos_adjuntos
                    $sql = "SELECT SOLICITUD, PLAN_ESTUDIOS, PLANTA_DOCENTE, DIAGNOSTICO FROM tbl_archivos_adjuntos WHERE ID_SOLICITUD = :id";
                    $basePath = "NuevoIngresoSolicitud/";
                }

                $stmt = $conn->prepare($sql);
                if ($estadoRow['ID_ESTADO'] == 3) {
                    $stmt->bindParam(':last_id', $lastId, PDO::PARAM_INT);
                } else {
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                }
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Construir la lista de archivos para enviar en formato JSON
                $files = [];
                foreach ($rows as $row) {
                    if ($row['SOLICITUD']) {
                        $files[] = [
                            'name' => basename($row['SOLICITUD']),
                            'url' => "../" . $basePath . $row['SOLICITUD']
                        ];
                    }
                    if ($row['PLAN_ESTUDIOS']) {
                        $files[] = [
                            'name' => basename($row['PLAN_ESTUDIOS']),
                            'url' => "../" . $basePath . $row['PLAN_ESTUDIOS']
                        ];
                    }
                    if ($row['PLANTA_DOCENTE']) {
                        $files[] = [
                            'name' => basename($row['PLANTA_DOCENTE']),
                            'url' => "../" . $basePath . $row['PLANTA_DOCENTE']
                        ];
                    }
                    if ($row['DIAGNOSTICO']) {
                        $files[] = [
                            'name' => basename($row['DIAGNOSTICO']),
                            'url' => "../" . $basePath . $row['DIAGNOSTICO']
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

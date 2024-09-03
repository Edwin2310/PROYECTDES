<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idSolicitud = isset($_POST['idSolicitud']) ? htmlspecialchars($_POST['idSolicitud']) : '';
    $fileName = isset($_POST['fileName']) ? htmlspecialchars($_POST['fileName']) : '';
    $filePath = isset($_POST['filePath']) ? htmlspecialchars($_POST['filePath']) : '';
    $fileType = isset($_POST['fileType']) ? htmlspecialchars($_POST['fileType']) : '';

    // Obtener el ID de usuario y nombre de usuario de la sesión
    session_start();
    $idUsuario = isset($_SESSION["ID_USUARIO"]) ? $_SESSION["ID_USUARIO"] : null;
    $nombreUsuario = isset($_SESSION["NOMBRE_USUARIO"]) ? $_SESSION["NOMBRE_USUARIO"] : null;

    if ($idSolicitud && $fileName && $filePath) {
        // Conectar a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        try {
            // Iniciar una transacción
            $conn->beginTransaction();

            // Definir el campo para actualizar basado en el tipo de archivo
            $sql = "UPDATE tbl_observaciones 
                    SET ID_USUARIO = :idUsuario, CREADO_POR = :nombreUsuario, ";

            switch ($fileType) {
                case 'SUBSOLI':
                    $sql .= "SOLICITUD = :filePath";
                    break;
                case 'SUBPLAN':
                    $sql .= "PLAN_ESTUDIOS = :filePath";
                    break;
                case 'SUBPDOC':
                    $sql .= "PLANTA_DOCENTE = :filePath";
                    break;
                case 'SUBDIAG':
                    $sql .= "DIAGNOSTICO = :filePath";
                    break;
                default:
                    throw new Exception('Tipo de archivo desconocido.');
            }

            $sql .= " WHERE ID_SOLICITUD = :idSolicitud";

            $stmt = $conn->prepare($sql);

            // Enlazar los parámetros
            $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
            $stmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
            $stmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);

            if ($stmt->execute()) {
                // Actualizar el estado de la solicitud a 3
                $updateSql = "UPDATE tbl_solicitudes SET ID_ESTADO = :nuevoEstado WHERE ID_SOLICITUD = :id_solicitud";
                $updateStmt = $conn->prepare($updateSql);
                $nuevoEstado = 3;
                $updateStmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_INT);
                $updateStmt->bindParam(':id_solicitud', $idSolicitud, PDO::PARAM_INT);

                if ($updateStmt->execute()) {
                    // Confirmar la transacción
                    $conn->commit();
                    echo json_encode(['success' => true, 'message' => 'Archivos y estado actualizados correctamente.']);
                } else {
                    // Revertir la transacción si la actualización falla
                    $conn->rollBack();
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado de la solicitud.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la información en tbl_observaciones.']);
            }
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conn->rollBack();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos o archivo no válido.']);
    }
}
?>

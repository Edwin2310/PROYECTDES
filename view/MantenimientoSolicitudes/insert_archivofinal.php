<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idSolicitud = isset($_POST['idSolicitud']) ? htmlspecialchars($_POST['idSolicitud']) : '';
    $fileName = isset($_POST['fileName']) ? htmlspecialchars($_POST['fileName']) : '';
    $filePath = isset($_POST['filePath']) ? htmlspecialchars($_POST['filePath']) : '';

    // Obtener el ID de usuario y nombre de usuario de la sesión
    session_start();
    $idUsuario = isset($_SESSION["ID_USUARIO"]) ? $_SESSION["ID_USUARIO"] : null;
    $nombreUsuario = isset($_SESSION["NOMBRE_USUARIO"]) ? $_SESSION["NOMBRE_USUARIO"] : null;

    if ($idSolicitud && $filePath && $idUsuario && $nombreUsuario) {
        // Conectar a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        try {
            // Iniciar una transacción
            $conn->beginTransaction();

            // Verificar si el campo ADJUNTO_OBSERVACIONES ya tiene un valor
            $checkObservacionesSql = "SELECT ADJUNTO_OBSERVACIONES FROM tbl_opinion_razonada WHERE ID_SOLICITUD = :idSolicitud";
            $checkObservacionesStmt = $conn->prepare($checkObservacionesSql);
            $checkObservacionesStmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
            $checkObservacionesStmt->execute();
            $observacionesResult = $checkObservacionesStmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si el campo ADJUNTO_OR_DES ya tiene un valor
            $checkOrDesSql = "SELECT ADJUNTO_OR_DES FROM tbl_opinion_razonada WHERE ID_SOLICITUD = :idSolicitud";
            $checkOrDesStmt = $conn->prepare($checkOrDesSql);
            $checkOrDesStmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
            $checkOrDesStmt->execute();
            $orDesResult = $checkOrDesStmt->fetch(PDO::FETCH_ASSOC);

            // Actualizar ADJUNTO_OR_DES basado en ADJUNTO_OBSERVACIONES
            if ($observacionesResult && !empty($observacionesResult['ADJUNTO_OBSERVACIONES'])) {
                // Si ADJUNTO_OBSERVACIONES no está vacío, actualizar ADJUNTO_OR_DES
                $updateOrDesSql = "UPDATE tbl_opinion_razonada 
                                   SET ADJUNTO_OR_DES = :filePath 
                                   WHERE ID_SOLICITUD = :idSolicitud";
                $updateOrDesStmt = $conn->prepare($updateOrDesSql);
                $updateOrDesStmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);
                $updateOrDesStmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
                $updateOrDesStmt->execute();
            } else {
                // Si ADJUNTO_OBSERVACIONES está vacío, se procede a actualizar o insertar
                if ($orDesResult && !empty($orDesResult['ADJUNTO_OR_DES'])) {
                    // Si ADJUNTO_OR_DES ya tiene un valor, hacer un UPDATE
                    $updateOrDesSql = "UPDATE tbl_opinion_razonada 
                                       SET ADJUNTO_OR_DES = :filePath 
                                       WHERE ID_SOLICITUD = :idSolicitud";
                    $updateOrDesStmt = $conn->prepare($updateOrDesSql);
                    $updateOrDesStmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);
                    $updateOrDesStmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
                    $updateOrDesStmt->execute();
                } else {
                    // Si ADJUNTO_OR_DES está vacío, hacer un INSERT
                    $insertSql = "INSERT INTO tbl_opinion_razonada (ADJUNTO_OR_DES, ID_SOLICITUD, ID_USUARIO, CREADO_POR) 
                                  VALUES (:filePath, :idSolicitud, :idUsuario, :nombreUsuario)";
                    $insertStmt = $conn->prepare($insertSql);
                    $insertStmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);
                    $insertStmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
                    $insertStmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                    $insertStmt->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
                    $insertStmt->execute();
                }
            }

            // Verificar el estado actual de la solicitud
            $estadoSql = "SELECT ID_ESTADO FROM tbl_solicitudes WHERE ID_SOLICITUD = :idSolicitud";
            $estadoStmt = $conn->prepare($estadoSql);
            $estadoStmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
            $estadoStmt->execute();
            $estadoRow = $estadoStmt->fetch(PDO::FETCH_ASSOC);

            if ($estadoRow) {
                // Determinar el nuevo estado basado en el estado actual
                $estadoActual = $estadoRow['ID_ESTADO'];
                $nuevoEstado = ($estadoActual == 8) ? 13 :
                               (($estadoActual == 11) ? 13 : 
                               (($estadoActual == 12) ? 19 : 
                               (($estadoActual == 25) ? 21 : 
                               (($estadoActual == 26) ? 23 : $estadoActual))));


                // Actualizar el estado de la solicitud
                $updateEstadoSql = "UPDATE tbl_solicitudes 
                                    SET ID_ESTADO = :nuevoEstado 
                                    WHERE ID_SOLICITUD = :idSolicitud";
                $updateEstadoStmt = $conn->prepare($updateEstadoSql);
                $updateEstadoStmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_INT);
                $updateEstadoStmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);

                if ($updateEstadoStmt->execute()) {
                    // Confirmar la transacción
                    $conn->commit();
                    echo json_encode(['success' => true, 'message' => 'Archivo y estado actualizados correctamente.']);
                } else {
                    // Revertir la transacción si la actualización falla
                    $conn->rollBack();
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado de la solicitud.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No se encontró el estado de la solicitud.']);
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

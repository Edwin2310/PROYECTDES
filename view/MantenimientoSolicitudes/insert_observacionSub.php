<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $observaciones = isset($_POST['observaciones']) ? htmlspecialchars($_POST['observaciones']) : '';
    $idSolicitud = isset($_POST['idSolicitud']) ? htmlspecialchars($_POST['idSolicitud']) : '';
    $fileName = isset($_POST['fileName']) ? htmlspecialchars($_POST['fileName']) : '';
    $filePath = isset($_POST['filePath']) ? htmlspecialchars($_POST['filePath']) : '';

    // Obtener el ID de usuario y nombre de usuario de la sesión
    session_start();
    $idUsuario = isset($_SESSION["IdUsuario"]) ? $_SESSION["IdUsuario"] : null;
    $nombreUsuario = isset($_SESSION["NOMBRE_USUARIO"]) ? $_SESSION["NOMBRE_USUARIO"] : null;

    // Sanear las observaciones para eliminar caracteres '<' y '>'
    $observaciones = str_replace(['<', '>'], '', $observaciones);

    if ($observaciones && $idSolicitud && $fileName && $filePath) {
        // Conectar a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        try {
            // Iniciar una transacción
            $conn->beginTransaction();

            // Insertar en la base de datos
            $sqlInsert = "INSERT INTO tbl_observaciones (OBSERVACION, DOC_OBSERVACION, ID_SOLICITUD, IdUsuario, CREADO_POR, FECHA_OBSERVACION) 
                          VALUES (:observacion, :doc_observacion, :id_solicitud, :id_usuario, :creado_por, NOW())";
            $stmtInsert = $conn->prepare($sqlInsert);

            // Insertar datos
            $stmtInsert->bindParam(':observacion', $observaciones);
            $stmtInsert->bindParam(':doc_observacion', $filePath); // Corregir el nombre de la variable
            $stmtInsert->bindParam(':id_solicitud', $idSolicitud, PDO::PARAM_INT);
            $stmtInsert->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmtInsert->bindParam(':creado_por', $nombreUsuario, PDO::PARAM_STR);

            if ($stmtInsert->execute()) {
                // Actualizar el estado de la solicitud a 2
                $updateSql = "UPDATE tbl_solicitudes SET ID_ESTADO = 2 WHERE ID_SOLICITUD = :id_solicitud";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bindParam(':id_solicitud', $idSolicitud, PDO::PARAM_INT);

                if ($updateStmt->execute()) {
                    // Confirmar la transacción
                    $conn->commit();
                    echo json_encode(['success' => true, 'message' => 'Archivo, observaciones y estado actualizados correctamente.']);
                } else {
                    // Revertir la transacción si la actualización falla
                    $conn->rollBack();
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado de la solicitud.']);
                }
            } else {
                // Revertir la transacción en caso de error en la inserción
                $conn->rollBack();
                echo json_encode(['success' => false, 'message' => 'Error al insertar archivo y observaciones.']);
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

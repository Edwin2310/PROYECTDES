<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $observaciones = isset($_POST['observaciones']) ? htmlspecialchars($_POST['observaciones']) : '';
    $idSolicitud = isset($_POST['idSolicitud']) ? htmlspecialchars($_POST['idSolicitud']) : '';
    $fileName = isset($_POST['fileName']) ? htmlspecialchars($_POST['fileName']) : '';
    $filePath = isset($_POST['filePath']) ? htmlspecialchars($_POST['filePath']) : '';

    // Obtener el ID de usuario y nombre de usuario de la sesión
    session_start();
    $idUsuario = isset($_SESSION["ID_USUARIO"]) ? $_SESSION["ID_USUARIO"] : null;
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
            $sql = "INSERT INTO tbl_opinion_razonada (ADJUNTO_OBSERVACIONES, OBSERVACIONES, EMAIL, ID_SOLICITUD, ID_USUARIO, CREADO_POR) 
                    VALUES (:filePath, :observaciones, NULL, :idSolicitud, :idUsuario, :nombreUsuario)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);
            $stmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
            $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);

            if ($stmt->execute()) {
                // Actualizar el estado de la solicitud a 10
                $updateSql = "UPDATE tbl_solicitudes SET ID_ESTADO = :nuevoEstado WHERE ID_SOLICITUD = :id_solicitud";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_INT);
                $updateStmt->bindParam(':id_solicitud', $idSolicitud, PDO::PARAM_INT);

                // Definir el nuevo estado
                $nuevoEstado = 10; 

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





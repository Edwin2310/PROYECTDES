<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idSolicitud = isset($_POST['idSolicitud']) ? htmlspecialchars($_POST['idSolicitud']) : '';
    $nuevoEstado = isset($_POST['nuevoEstado']) ? htmlspecialchars($_POST['nuevoEstado']) : '';

    if ($idSolicitud && $nuevoEstado) {
        // Conectar a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        try {
            // Iniciar una transacción
            $conn->beginTransaction();

            // Actualizar el estado de la solicitud
            $updateEstadoSql = "UPDATE tbl_solicitudes 
                                SET ID_ESTADO = :nuevoEstado 
                                WHERE ID_SOLICITUD = :id_solicitud";
            $updateEstadoStmt = $conn->prepare($updateEstadoSql);
            $updateEstadoStmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_INT);
            $updateEstadoStmt->bindParam(':id_solicitud', $idSolicitud, PDO::PARAM_INT);

            if ($updateEstadoStmt->execute()) {
                // Confirmar la transacción
                $conn->commit();
                echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente.']);
            } else {
                // Revertir la transacción si la actualización falla
                $conn->rollBack();
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado de la solicitud.']);
            }
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conn->rollBack();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    }
}

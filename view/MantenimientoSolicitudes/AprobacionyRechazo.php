<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idSolicitud = isset($_POST['idSolicitud']) ? htmlspecialchars($_POST['idSolicitud']) : '';
    $nuevoEstado = isset($_POST['nuevoEstado']) ? htmlspecialchars($_POST['nuevoEstado']) : '';
    $idUsuario = isset($_SESSION["IdUsuario"]) ? $_SESSION["IdUsuario"] : null; // Asegúrate de que el usuario está autenticado

    if ($idSolicitud && $nuevoEstado && $idUsuario) {
        // Conectar a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        try {
            // Preparar llamada al procedimiento almacenado
            $callProcedureSql = "CALL `proceso.splAprobacionYRechazo`(:idSolicitud, :nuevoEstado, :idUsuario)";
            $callProcedureStmt = $conn->prepare($callProcedureSql);
            $callProcedureStmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
            $callProcedureStmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_INT);
            $callProcedureStmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            
            $callProcedureStmt->execute();

            // Obtener el resultado del procedimiento
            $result = $callProcedureStmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $result['Status'] == 'Success') {
                echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado de la solicitud.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    }
}

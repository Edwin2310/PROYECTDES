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
    $nombreUsuario = isset($_SESSION["NombreUsuario"]) ? $_SESSION["NombreUsuario"] : null;

    // Sanear las observaciones para eliminar caracteres '<' y '>'
    $observaciones = str_replace(['<', '>'], '', $observaciones);

    if ($observaciones && $idSolicitud && $fileName && $filePath) {
        // Conectar a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        try {
            // Iniciar una transacción
            $conn->beginTransaction();

            // Llamar al procedimiento para insertar observaciones
            $sqlInsert = "CALL `proceso.splObservacionInsertar`(:observacion, :doc_observacion, :id_solicitud, :id_usuario, :creado_por)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bindParam(':observacion', $observaciones);
            $stmtInsert->bindParam(':doc_observacion', $filePath);
            $stmtInsert->bindParam(':id_solicitud', $idSolicitud, PDO::PARAM_INT);
            $stmtInsert->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmtInsert->bindParam(':creado_por', $nombreUsuario, PDO::PARAM_STR);
            $stmtInsert->execute();

            // Llamar al procedimiento para actualizar el estado de la solicitud
            $sqlUpdate = "CALL `proceso.splEstadoObservacionActualizar`(:id_solicitud)";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':id_solicitud', $idSolicitud, PDO::PARAM_INT);
            $stmtUpdate->execute();

            // Confirmar la transacción
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Archivo, observaciones y estado actualizados correctamente.']);
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conn->rollBack();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos o archivo no válido.']);
    }
}

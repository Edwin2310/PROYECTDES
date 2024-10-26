<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numeroRegistro = isset($_POST['numeroRegistro']) ? htmlspecialchars($_POST['numeroRegistro']) : '';
    $idSolicitud = isset($_POST['idSolicitud']) ? htmlspecialchars($_POST['idSolicitud']) : '';
    $filePath = isset($_POST['filePath']) ? htmlspecialchars($_POST['filePath']) : '';
    $nuevoEstado = 17; // Estado a actualizar

    // Obtener el ID de usuario y nombre de usuario de la sesión
    session_start();
    $idUsuario = isset($_SESSION["IdUsuario"]) ? $_SESSION["IdUsuario"] : null;
    $nombreUsuario = isset($_SESSION["NombreUsuario"]) ? $_SESSION["NombreUsuario"] : null;

    if ($numeroRegistro && $idSolicitud && $filePath) {
        // Conectar a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        try {
            // Iniciar una transacción
            $conn->beginTransaction();

            // Llamar al procedimiento almacenado
            $sql = "CALL `proceso.splInsertarPlanEstudio`(:numeroRegistro, :filePath, :idSolicitud, :nuevoEstado)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':numeroRegistro', $numeroRegistro, PDO::PARAM_STR);
            $stmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
            $stmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Confirmar la transacción
                $conn->commit();
                echo json_encode(['success' => true, 'message' => 'Datos insertados y estado actualizado correctamente.']);
            } else {
                throw new Exception('Error al ejecutar el procedimiento almacenado.');
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

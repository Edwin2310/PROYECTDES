<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idSolicitud = isset($_POST['idSolicitud']) ? htmlspecialchars($_POST['idSolicitud']) : '';
    $fileName = isset($_POST['fileName']) ? htmlspecialchars($_POST['fileName']) : '';
    $filePath = isset($_POST['filePath']) ? htmlspecialchars($_POST['filePath']) : '';

    // Obtener el ID de usuario y nombre de usuario de la sesión
    session_start();
    $idUsuario = isset($_SESSION["IdUsuario"]) ? $_SESSION["IdUsuario"] : null;
    $nombreUsuario = isset($_SESSION["NombreUsuario"]) ? $_SESSION["NombreUsuario"] : null;

    if ($idSolicitud && $filePath && $idUsuario && $nombreUsuario) {
        // Conectar a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        try {
            // Iniciar una transacción
            $conn->beginTransaction();

            // Llamar al procedimiento almacenado
            $sql = "CALL `proceso.splEmisionOpinionRazonada`(:idSolicitud, :fileName, :filePath, :idUsuario, :nombreUsuario)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
            $stmt->bindParam(':fileName', $fileName, PDO::PARAM_STR);
            $stmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
            $stmt->execute();

            // Confirmar la transacción
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Archivo y estado actualizados correctamente.']);
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conn->rollBack();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos o archivo no válido.']);
    }
}

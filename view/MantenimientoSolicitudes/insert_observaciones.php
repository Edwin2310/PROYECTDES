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
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        try {
            // Llamar al procedimiento almacenado
            $sql = "CALL `proceso.splInsertarObservaciones`(:p_observaciones, :p_idSolicitud, :p_filePath, :p_idUsuario, :p_nombreUsuario)";
            $stmt = $conn->prepare($sql);

            // Asociar los parámetros
            $stmt->bindParam(':p_observaciones', $observaciones, PDO::PARAM_STR);
            $stmt->bindParam(':p_idSolicitud', $idSolicitud, PDO::PARAM_INT);
            $stmt->bindParam(':p_filePath', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':p_idUsuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(':p_nombreUsuario', $nombreUsuario, PDO::PARAM_STR);

            // Ejecutar el procedimiento almacenado
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Archivo, observaciones y estado actualizados correctamente.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos o archivo no válido.']);
    }
}





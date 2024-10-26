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

    if ($idSolicitud && $fileName && $filePath) {
        // Conectar a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        try {
            // Iniciar una transacción
            $conn->beginTransaction();

            // Llamar al procedimiento almacenado
            $sql = "CALL `proceso.splActualizarSolicitudSubsanada`(:idSolicitud, :filePath, :nuevoEstado)";
            $stmt = $conn->prepare($sql);
            $nuevoEstado = 11; // Estado deseado
            $stmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
            $stmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Confirmar la transacción
                $conn->commit();
                echo json_encode(['success' => true, 'message' => 'Archivo y estado actualizados correctamente.']);
            } else {
                // Revertir la transacción si la actualización falla
                $conn->rollBack();
                echo json_encode(['success' => false, 'message' => 'Error al actualizar los datos.']);
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





/*
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $observaciones = isset($_POST['observaciones']) ? htmlspecialchars($_POST['observaciones']) : '';
    $idSolicitud = isset($_POST['idSolicitud']) ? htmlspecialchars($_POST['idSolicitud']) : '';
    $file = isset($_FILES['file']) ? $_FILES['file'] : null;
    $fileName = isset($_POST['fileName']) ? htmlspecialchars($_POST['fileName']) : '';

    // Sanear las observaciones para eliminar caracteres '<' y '>'
    $observaciones = str_replace(['<', '>'], '', $observaciones);

    if ($observaciones && $idSolicitud && $file && $file['tmp_name']) {
        // Obtener el contenido del archivo
        $fileContent = file_get_contents($file['tmp_name']);

        // Conectar a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Insertar en la base de datos
        $sql = "INSERT INTO tbl_opinion_razonada (DESCRIPCION_ADJUNTO, ADJUNTO_OR_DES, OBSERVACIONES, EMAIL, ID_SOLICITUD) 
                VALUES (:fileName, :fileContent, :observaciones, NULL, :idSolicitud)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':fileContent', $fileContent, PDO::PARAM_LOB);
        $stmt->bindParam(':fileName', $fileName, PDO::PARAM_STR);
        $stmt->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);
        $stmt->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Archivo y observaciones insertados correctamente.";
        } else {
            echo "Error al insertar archivo y observaciones.";
        }
    } else {
        echo "Datos incompletos o archivo no válido.";
    }
}
*/
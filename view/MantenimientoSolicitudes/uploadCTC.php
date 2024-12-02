<?php
/* require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['dictamen']) && isset($_POST['id_usuario']) && isset($_POST['id_solicitud'])) {
        $id_usuario = $_POST['id_usuario'];
        $id_solicitud = $_POST['id_solicitud'];
        $file = $_FILES['dictamen'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        if ($fileError === 0) {
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = array('pdf', 'doc', 'docx');

            if (in_array($fileExt, $allowed)) {
                $fileNameNew = uniqid('', true) . "." . $fileExt;
                $fileDestination = 'uploads/' . $fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);

                // Insertar en la base de datos
                $conexion = new Conectar();
                $conn = $conexion->Conexion();
                $query = "INSERT INTO `documentos.tbladjuntosctc` (IdUsuario, FECHA_ADJUNTOCTC, DICTAMENADJU, ID_SOLICITUD) VALUES (:id_usuario, NOW(), :dictamen, :id_solicitud)";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id_usuario', $id_usuario);
                $stmt->bindParam(':dictamen', $fileNameNew);
                $stmt->bindParam(':id_solicitud', $id_solicitud);
                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error al insertar en la base de datos']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Tipo de archivo no permitido']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al subir el archivo']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos faltantes']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
} */


require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['dictamen']) && isset($_POST['id_usuario']) && isset($_POST['id_solicitud'])) {
        $id_usuario = $_POST['id_usuario'];
        $id_solicitud = $_POST['id_solicitud'];
        $file = $_FILES['dictamen'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        if ($fileError === 0) {
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = array('pdf','docx');

            if (in_array($fileExt, $allowed)) {
                $fileNameNew = uniqid('', true) . "." . $fileExt;
                $fileDestination = 'uploads/' . $fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);

                // Insertar en la base de datos
                $conexion = new Conectar();
                $conn = $conexion->Conexion();
                
                // Iniciar transacción
                $conn->beginTransaction();
                
                try {
                    // Insertar en `documentos.tbladjuntosctc`
                    $query = "INSERT INTO `documentos.tbladjuntosctc` (IdUsuario, FechaAdjuntoCTC, DictamenAdjunto, IdSolicitud) VALUES (:id_usuario, NOW(), :dictamen, :id_solicitud)";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':id_usuario', $id_usuario);
                    $stmt->bindParam(':dictamen', $fileNameNew);
                    $stmt->bindParam(':id_solicitud', $id_solicitud);
                    
                    if ($stmt->execute()) {
                        // Actualizar estado en `proceso.tblsolicitudes`
                        $updateSql = "UPDATE `proceso.tblsolicitudes` SET IdEstado = 8 WHERE IdSolicitud = :idSolicitud";
                        $updateStmt = $conn->prepare($updateSql);
                        $updateStmt->bindParam(':idSolicitud', $id_solicitud);
                        $updateStmt->execute();
                        
                        // Confirmar transacción
                        $conn->commit();
                        echo json_encode(['status' => 'success']);
                    } else {
                        throw new Exception('Error al insertar en la base de datos');
                    }
                } catch (Exception $e) {
                    // Revertir transacción en caso de error
                    $conn->rollBack();
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Tipo de archivo no permitido']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al subir el archivo']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos faltantes']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}



?>

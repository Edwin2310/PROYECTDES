<?php
require_once("../../config/conexion.php");

if (isset($_POST['observaciones']) && isset($_POST['idSolicitud'])) {
    $observaciones = $_POST['observaciones'];
    $idSolicitud = $_POST['idSolicitud'];
    $file = $_FILES['file'];

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Insertar observaciones
    $sqlInsert = "INSERT INTO tbl_observaciones (OBSERVACION, DOC_OBSERVACION, ID_SOLICITUD) VALUES (:observacion, :doc_observacion, :id_solicitud)";
    $stmtInsert = $conn->prepare($sqlInsert);

    // Leer el contenido del archivo
    $docObservacion = file_get_contents($file['tmp_name']);

    // Insertar datos
    $stmtInsert->bindParam(':observacion', $observaciones);
    $stmtInsert->bindParam(':doc_observacion', $docObservacion, PDO::PARAM_LOB);
    $stmtInsert->bindParam(':id_solicitud', $idSolicitud, PDO::PARAM_INT);
    
    if ($stmtInsert->execute()) {
        // Cambiar el estado de la solicitud
        $sqlUpdate = "UPDATE tbl_solicitudes SET ID_ESTADO = 2 WHERE ID_SOLICITUD = :id_solicitud";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':id_solicitud', $idSolicitud, PDO::PARAM_INT);
        $stmtUpdate->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al insertar observaciones.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos no válidos.']);
}
?>

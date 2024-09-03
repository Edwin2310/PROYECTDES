<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    $acuerdoAdmision = $_POST['acuerdo_admision'];
    $idSolicitud = $_POST['solicitud_id'];

    try {
        $conn->beginTransaction();

        // Verificar si el número de acuerdo ya existe para la solicitud
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM tbl_acuerdo_ces_admin WHERE ID_SOLICITUD = :idSolicitud");
        $stmtCheck->bindParam(':idSolicitud', $idSolicitud);
        $stmtCheck->execute();
        $exists = $stmtCheck->fetchColumn();

        

        // Insertar el número de acuerdo de admisión
        $stmtInsert = $conn->prepare("INSERT INTO tbl_acuerdo_ces_admin (ID_SOLICITUD, ACUERDO_ADMISION) VALUES (:idSolicitud, :acuerdoAdmision)");
        $stmtInsert->bindParam(':acuerdoAdmision', $acuerdoAdmision);
        $stmtInsert->bindParam(':idSolicitud', $idSolicitud);
        $stmtInsert->execute();

        // Actualizar el estado de la solicitud a 6
        $stmtUpdate = $conn->prepare("UPDATE tbl_solicitudes SET ID_ESTADO = 6 WHERE ID_SOLICITUD = :idSolicitud");
        $stmtUpdate->bindParam(':idSolicitud', $idSolicitud);
        $stmtUpdate->execute();

        $conn->commit();
        echo json_encode(['message' => 'Número de acuerdo guardado y estado actualizado correctamente.', 'success' => true]);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['message' => 'Hubo un error: ' . $e->getMessage(), 'success' => false]);
    }
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
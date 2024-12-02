<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    $acuerdoAdmision = $_POST['acuerdo_admision'];
    $idSolicitud = $_POST['solicitud_id'];

    try {
        $conn->beginTransaction();

        // Verificar si ya existe un acuerdo de admisión para la solicitud
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM `proceso.tblacuerdoscesadmin` WHERE IdSolicitud = :idSolicitud");
        $stmtCheck->bindParam(':idSolicitud', $idSolicitud);
        $stmtCheck->execute();
        $exists = $stmtCheck->fetchColumn();

        if ($exists > 0) {
            // Si el acuerdo ya existe, hacer un UPDATE
            $stmtUpdate = $conn->prepare("UPDATE `proceso.tblacuerdoscesadmin` SET AcuerdoAdmision = :acuerdoAdmision WHERE IdSolicitud = :idSolicitud");
            $stmtUpdate->bindParam(':acuerdoAdmision', $acuerdoAdmision);
            $stmtUpdate->bindParam(':idSolicitud', $idSolicitud);
            $stmtUpdate->execute();
        } 
        
        // Actualizar el estado de la solicitud a 6
        $stmtUpdateStatus = $conn->prepare("UPDATE `proceso.tblsolicitudes` SET IdEstado = 6 WHERE IdSolicitud = :idSolicitud");
        $stmtUpdateStatus->bindParam(':idSolicitud', $idSolicitud);
        $stmtUpdateStatus->execute();

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

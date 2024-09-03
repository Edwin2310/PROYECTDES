<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    $numeroSesion = $_POST['acuerdo_aprobacion'];
    $id_solicitud = $_POST['solicitud_id'];

    try {
        $conn->beginTransaction();

        // Verificar si el registro ya existe
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_acuerdo_ces_aprob WHERE ID_SOLICITUD = :id_solicitud");
        $stmt->bindParam(':id_solicitud', $id_solicitud);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Actualizar el registro existente
            $stmt = $conn->prepare("UPDATE tbl_acuerdo_ces_aprob SET ACUERDO_APROBACION = :numeroSesion WHERE ID_SOLICITUD = :id_solicitud");
        } else {
            // Insertar un nuevo registro
            $stmt = $conn->prepare("INSERT INTO tbl_acuerdo_ces_aprob (ID_SOLICITUD, ACUERDO_APROBACION) VALUES (:id_solicitud, :numeroSesion)");
        }

        $stmt->bindParam(':numeroSesion', $numeroSesion);
        $stmt->bindParam(':id_solicitud', $id_solicitud);
        $stmt->execute();

        // Actualizar el estado de la solicitud a 15 en tbl_solicitudes
        $stmtUpdate = $conn->prepare("UPDATE tbl_solicitudes SET ID_ESTADO = 15 WHERE ID_SOLICITUD = :id_solicitud");
        $stmtUpdate->bindParam(':id_solicitud', $id_solicitud);
        $stmtUpdate->execute();

        $conn->commit();
        echo json_encode(['message' => 'Acuerdo guardado y estado actualizado correctamente.', 'success' => true]);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['message' => 'Hubo un error al guardar el acuerdo o actualizar el estado: ' . $e->getMessage(), 'success' => false]);
    }
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>


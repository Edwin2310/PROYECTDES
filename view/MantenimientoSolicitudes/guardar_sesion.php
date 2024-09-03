<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    $numeroSesion = $_POST['numeroSesion'];
    $ids = explode(',', $_POST['ids']); // Lista de IDs seleccionados

    try {
        $conn->beginTransaction();

        foreach ($ids as $id) {
            // Insertar en la tabla tbl_acuerdo_ces_admin
            $stmt = $conn->prepare("INSERT INTO tbl_acuerdo_ces_admin (ID_SOLICITUD, NUM_ACTA_ADMIN) VALUES (:id, :numeroSesion)");
            $stmt->bindParam(':numeroSesion', $numeroSesion);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Actualizar el estado de la solicitud en tbl_solicitudes
            $stmt = $conn->prepare("UPDATE tbl_solicitudes SET ID_ESTADO = 5 WHERE ID_SOLICITUD = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }

        $conn->commit();
        echo json_encode(['message' => 'Sesión asignada y estado actualizado correctamente.', 'success' => true]);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['message' => 'Hubo un error al asignar la sesión: ' . $e->getMessage(), 'success' => false]);
    }
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>







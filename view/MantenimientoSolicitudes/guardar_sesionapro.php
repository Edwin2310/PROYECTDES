<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Obtener y validar los datos de entrada
    $numeroSesion = filter_input(INPUT_POST, 'numeroSesion', FILTER_SANITIZE_STRING);
    $ids = filter_input(INPUT_POST, 'ids', FILTER_SANITIZE_STRING);
    
    if (!$numeroSesion || !$ids) {
        echo json_encode(['message' => 'Datos de entrada no válidos.', 'success' => false]);
        exit;
    }

    $ids = explode(',', $ids); // Lista de IDs seleccionados

    try {
        $conn->beginTransaction();

        foreach ($ids as $id) {
            // Validar ID
            if (!is_numeric($id)) {
                throw new Exception("ID no válido: $id");
            }

            // Insertar un nuevo registro para cada ID_SOLICITUD con el número de sesión
            $stmt = $conn->prepare("INSERT INTO tbl_acuerdo_ces_aprob (ID_SOLICITUD, ACUERDO_APROBACION) VALUES (:id, :numeroSesion)");
            $stmt->bindParam(':numeroSesion', $numeroSesion);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Actualizar el estado de la solicitud a 14
            $stmt = $conn->prepare("UPDATE tbl_solicitudes SET ID_ESTADO = 14 WHERE ID_SOLICITUD = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }

        $conn->commit();
        echo json_encode(['message' => 'Sesión asignada correctamente y estado actualizado.', 'success' => true]);
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Error al asignar sesión: " . $e->getMessage()); // Registrar el error
        echo json_encode(['message' => 'Hubo un error al asignar la sesión: ' . $e->getMessage(), 'success' => false]);
    }
} else {
    header("Location:" . Conectar::ruta() . "index.php");
    exit;
}
?>


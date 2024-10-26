<?php
require_once("../../config/conexion.php");

// Ocultar errores visibles y manejar la respuesta como JSON
ini_set('display_errors', 0);
header('Content-Type: application/json');

// Obtener el ID desde los parámetros GET
$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

if ($id) {
    try {
        // Crear una nueva conexión
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Llamar al procedimiento almacenado para obtener PlanEstudios
        $sql = "CALL `proceso.splObtenerAdjuntoObsSubsanadas`(:id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && $row['PlanEstudios']) {
            // Procesar el nombre del archivo y la URL
            $relativePath = $row['PlanEstudios'];
            $fileName = basename($relativePath);
            $basePath = ($estadoRow['IdEstado'] == 11) ? "../ConsultarSolicitudes/" : "../NuevoIngresoSolicitud/";
            $fullPath = $basePath . $relativePath;

            // Devolver la respuesta en formato JSON
            echo json_encode([
                'name' => $fileName,
                'url' => $fullPath
            ]);
        } else {
            echo json_encode(['error' => 'No se encontró el archivo']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
} else {
    // Devolver un error si no se proporciona el ID
    echo json_encode(['error' => 'ID no proporcionado']);
}

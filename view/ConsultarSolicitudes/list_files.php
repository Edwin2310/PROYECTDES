<?php
require_once("../../config/conexion.php");

// Ocultar errores visibles
ini_set('display_errors', 0);

// Establecer el encabezado para JSON
header('Content-Type: application/json');

// Obtener el ID desde los parámetros GET
$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

if ($id) {
    try {
        // Crear una nueva conexión
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Consulta para obtener la última observación de la solicitud
        $sql = "CALL `proceso.splObtenerAdjuntoObservaciones`(:id)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Obtener el nombre y ruta del archivo
            $relativePath = $row['AdjuntoObservaciones'];
            $fileName = basename($relativePath);

            // Construir la URL completa del archivo
            $basePath = "../MantenimientoSolicitudes/";
            $fullPath = $basePath . $relativePath;

            // Devolver la respuesta en formato JSON
            echo json_encode([
                'name' => $fileName,
                'url' => $fullPath
            ]);
        } else {
            // No se encontró el archivo
            echo json_encode(['error' => 'No se encontró el archivo']);
        }
    } catch (Exception $e) {
        // Devolver cualquier error en formato JSON
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
} else {
    // Devolver un error si no se proporciona el ID
    echo json_encode(['error' => 'ID no proporcionado']);
}




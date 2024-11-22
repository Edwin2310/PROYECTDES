<?php
require_once(__DIR__ . '/Funciones_Solicitud.php');

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $IdCategoria = $data['IdCategoria'] ?? 0;

    if (!$IdCategoria) {
        echo json_encode(['success' => false, 'message' => 'ID de categoría no válido.']);
        exit;
    }

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Consultar el CodArbitrios según el IdCategoria
    $query = $conn->prepare('SELECT CodArbitrios FROM `mantenimiento.tblcategorias` WHERE IdCategoria = :IdCategoria');
    $query->bindValue(':IdCategoria', $IdCategoria, PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode(['success' => true, 'CodArbitrios' => $result['CodArbitrios']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró la categoría.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}

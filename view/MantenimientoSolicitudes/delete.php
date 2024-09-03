<?php
// delete.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['fileId'])) {
        $fileId = $_POST['fileId'];

        $filePath = 'uploads/' . $fileId; // Ajusta el path según tu configuración

        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                echo json_encode(['success' => true, 'message' => 'Archivo eliminado correctamente.']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el archivo.']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Archivo no encontrado.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No se ha proporcionado el ID del archivo.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}

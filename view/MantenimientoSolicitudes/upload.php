<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $uploadFile = $uploadDir . basename($file['name']);

            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                echo json_encode(['success' => true, 'fileId' => basename($file['name'])]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al mover el archivo.']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Error en el archivo.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No se ha enviado ningún archivo.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>

<?php
require_once("../config/conexion.php");
require_once("../Models/Usuario.php");

// Función para devolver respuestas JSON
function responderJSON($status, $message) {
    header('Content-Type: application/json');
    echo json_encode(array('status' => $status, 'message' => $message));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $correo = $_POST['correo'];
    $nueva_contrasena = $_POST['nueva_contrasena'];

    // Validación básica de campos
    if (empty($token) || empty($correo) || empty($nueva_contrasena)) {
        responderJSON('error', 'Todos los campos son obligatorios.');
    }

    // Encriptar la nueva contraseña
    $password_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

    // Actualizar la contraseña del usuario
    $usuario = new Usuario();
    $resultado = $usuario->actualizar_contraseña($correo, $password_hash);

    if ($resultado) {
        // Eliminar el token utilizado
        $conn = new Conectar();
        $conexion = $conn->Conexion();

        // Preparar y ejecutar la consulta para eliminar el token
        $stmt = $conexion->prepare("DELETE FROM `seguridad.tblresetcontraseñas` WHERE Token = :token");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            // Verificar si se eliminó alguna fila
            if ($stmt->rowCount() > 0) {
                responderJSON('success', 'Contraseña actualizada correctamente.');
            } else {
                responderJSON('error', 'El token no se encontró en la base de datos.');
            }
        } else {
            // Error al ejecutar la consulta de eliminación
            responderJSON('error', 'Error al eliminar el token: ' . implode(", ", $stmt->errorInfo()));
        }
    } else {
        // Error al actualizar la contraseña
        responderJSON('error', 'Error al actualizar la contraseña. Por favor, inténtalo de nuevo.');
    }
}
?>

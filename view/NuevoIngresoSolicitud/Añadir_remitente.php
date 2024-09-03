<?php
session_start();

include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . $conn->errorInfo()[2]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $remitente = filter_input(INPUT_POST, 'remitente', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $correo_remitente = filter_input(INPUT_POST, 'correo_remitente', FILTER_SANITIZE_EMAIL);

    // Consulta SQL para insertar el nuevo remitente
    $sql = "INSERT INTO tbl_datos_remitente (NOMBRE_REMITENTE, EMAIL_REMITENTE) VALUES (?, ?)";
    
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        if ($stmt->execute([$remitente, $correo_remitente])) {
            echo "Remitente agregado exitosamente";
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    } else {
        echo "Error en la preparación de la consulta: " . $conn->errorInfo()[2];
    }

    // Cerrar la conexión
    $stmt = null;
    $conn = null;
}

exit();
?>


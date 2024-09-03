<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

if (!$conn) {
    die("Conexión fallida: " . $conexion->Conexion()->errorInfo());
}

if (isset($_POST['id_objeto'])) {
    $id_objeto = $_POST['id_objeto'];
    $objeto = $_POST['objeto'];
    $tipo_objeto = $_POST['tipo_objeto'];
    $descripcion = $_POST['descripcion'];
    $id_usuario = $_POST['id_usuario']; // ID del usuario que está editando

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Consulta SQL para actualizar el objeto
    $sql = "UPDATE tbl_ms_objetos 
            SET objeto = :objeto, tipo_objeto = :tipo_objeto, descripcion = :descripcion, modificado_por = :modificado_por, fecha_modificacion = NOW() 
            WHERE id_objeto = :id_objeto";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':objeto', $objeto);
    $stmt->bindParam(':tipo_objeto', $tipo_objeto);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':modificado_por', $id_usuario); // Actualizar con el ID del usuario
    $stmt->bindParam(':id_objeto', $id_objeto);

    if ($stmt->execute()) {
        header("Location: ../../Seguridad/Objetos.php");
    } else {
        // Redirigir de vuelta a la página principal
        header("Location: ../../Seguridad/Objetos.php");
    }

    $conn = null;
}
?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

if (!$conn) {
    die("Conexión fallida: " . implode(" ", $conn->errorInfo()));
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre_modalidad = isset($_POST['NomModalidad']) ? $_POST['NomModalidad'] : '';

    // Validar datos
    if (empty($nombre_modalidad)) {
        header("Location: ../../MantenimientoSistema/Modalidades.php?action=error&message=El campo de nombre de modalidad es obligatorio");
        exit();
    }

    // Verificar si la modalidad ya existe
    $sql_check = "SELECT COUNT(*) FROM `mantenimiento.tblmodalidades` WHERE NomModalidad = :nombre_modalidad";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':nombre_modalidad', $nombre_modalidad);
    $stmt_check->execute();

    if ($stmt_check->fetchColumn() > 0) {
        header("Location: ../../MantenimientoSistema/Modalidades.php?action=duplicate");
        exit();
    }

    // Insertar el nuevo registro en la tabla `mantenimiento.tblmodalidades`
    $sql_insert = "INSERT INTO `mantenimiento.tblmodalidades` (NomModalidad) VALUES (:nombre_modalidad)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':nombre_modalidad', $nombre_modalidad);

    if ($stmt_insert->execute()) {
        header("Location: ../../MantenimientoSistema/Modalidades.php?action=add-success");
    } else {
        header("Location: ../../MantenimientoSistema/Modalidades.php?action=error&message=Error al agregar modalidad: " . implode(" ", $stmt_insert->errorInfo()));
    }

    $conn = null;
    exit();
} else {
    header("Location: ../../MantenimientoSistema/Modalidades.php?action=error&message=Solicitud no válida");
    exit();
}
?>

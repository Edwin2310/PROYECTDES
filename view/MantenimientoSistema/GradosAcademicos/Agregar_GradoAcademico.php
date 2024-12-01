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
    $nombre_grado = isset($_POST['NomGrado']) ? $_POST['NomGrado'] : '';

    // Validar datos
    if (empty($nombre_grado)) {
        header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=error&message=El campo de Grado Académico es obligatorio");
        exit();
    }

    // Verificar si el grado academico ya existe
    $sql_check = "SELECT COUNT(*) FROM `mantenimiento.tblgradosacademicos` WHERE NomGrado = :nombre_grado";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':nombre_grado', $nombre_grado);
    $stmt_check->execute();

    if ($stmt_check->fetchColumn() > 0) {
        header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=duplicate");
        exit();
    }

    // Insertar el nuevo registro en la tabla `mantenimiento.tblgradosacademicos`
    $sql_insert = "INSERT INTO `mantenimiento.tblgradosacademicos` (NomGrado) VALUES (:nombre_grado)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':nombre_grado', $nombre_grado);

    if ($stmt_insert->execute()) {
        header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=add-success");
    } else {
        header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=error&message=Error al agregar modalidad: " . implode(" ", $stmt_insert->errorInfo()));
    }

    $conn = null;
    exit();
} else {
    header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=error&message=Solicitud no válida");
    exit();
}
?>

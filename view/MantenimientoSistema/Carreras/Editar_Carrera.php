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
    $IdCarrera = isset($_POST['IdCarrera']) ? $_POST['IdCarrera'] : '';
    $nombre_carrera = isset($_POST['NomCarrera']) ? $_POST['NomCarrera'] : '';
    $IdUniversidad = isset($_POST['IdUniversidad']) ? $_POST['IdUniversidad'] : '';
    $IdModalidad = isset($_POST['IdModalidad']) ? $_POST['IdModalidad'] : '';
    $IdGrado = isset($_POST['IdGrado']) ? $_POST['IdGrado'] : '';

    if (empty($IdCarrera) || empty($nombre_carrera) || empty($IdUniversidad) || empty($IdModalidad) || empty($IdGrado)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios";
        header("Location: ../../MantenimientoSistema/Carreras.php?action=edit-error");
        exit();
    }

    $sql_update = "UPDATE `mantenimiento.tblcarreras` SET NomCarrera = :nombre_carrera, IdUniversidad = :IdUniversidad, IdModalidad = :IdModalidad, IdGrado = :IdGrado WHERE IdCarrera = :IdCarrera";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':nombre_carrera', $nombre_carrera);
    $stmt_update->bindParam(':IdUniversidad', $IdUniversidad);
    $stmt_update->bindParam(':IdModalidad', $IdModalidad);
    $stmt_update->bindParam(':IdGrado', $IdGrado);
    $stmt_update->bindParam(':IdCarrera', $IdCarrera);

    if ($stmt_update->execute()) {
        $_SESSION['success_message'] = "Carrera editada exitosamente";
        header("Location: ../../MantenimientoSistema/Carreras.php?action=edit-success");
    } else {
        $_SESSION['error_message'] = "Error al editar carrera: " . implode(" ", $stmt_update->errorInfo());
        header("Location: ../../MantenimientoSistema/Carreras.php?action=edit-error");
    }

    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/Carreras.php?action=edit-error");
    exit();
}
?>

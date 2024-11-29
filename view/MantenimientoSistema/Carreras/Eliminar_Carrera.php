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

// Verificar si se ha enviado la solicitud de actualización (en lugar de eliminación)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $IdCarrera = isset($_POST['IdCarrera']) ? $_POST['IdCarrera'] : '';

    if (empty($IdCarrera)) {
        $_SESSION['error_message'] = "El ID de la carrera es obligatorio";
        header("Location: ../../MantenimientoSistema/Carreras.php?action=delete-error");
        exit();
    }

    // Actualizar el campo IdVisibilidad a 2
    $sql_update = "UPDATE `mantenimiento.tblcarreras` SET IdVisibilidad = 2 WHERE IdCarrera = :IdCarrera";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':IdCarrera', $IdCarrera);

    if ($stmt_update->execute()) {
        $_SESSION['success_message'] = "Carrera bloqueada exitosamente. Podrás encontrarla en la pestaña de Carreras Bloqueadas.";
        header("Location: ../../MantenimientoSistema/Carreras.php?action=delete-success");
    } else {
        $_SESSION['error_message'] = "Error al bloquear carrera: " . implode(" ", $stmt_update->errorInfo());
        header("Location: ../../MantenimientoSistema/Carreras.php?action=delete-error");
    }

    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/Carreras.php?action=delete-error");
    exit();
}
?>

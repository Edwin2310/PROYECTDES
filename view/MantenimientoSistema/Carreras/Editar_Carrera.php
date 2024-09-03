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
    $id_carrera = isset($_POST['id_carrera']) ? $_POST['id_carrera'] : '';
    $nombre_carrera = isset($_POST['nom_carrera']) ? $_POST['nom_carrera'] : '';
    $id_universidad = isset($_POST['id_universidad']) ? $_POST['id_universidad'] : '';
    $id_modalidad = isset($_POST['id_modalidad']) ? $_POST['id_modalidad'] : '';
    $id_grado = isset($_POST['id_grado']) ? $_POST['id_grado'] : '';

    if (empty($id_carrera) || empty($nombre_carrera) || empty($id_universidad) || empty($id_modalidad) || empty($id_grado)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios";
        header("Location: ../../MantenimientoSistema/Carreras.php?action=edit-error");
        exit();
    }

    $sql_update = "UPDATE tbl_carrera SET NOM_CARRERA = :nombre_carrera, ID_UNIVERSIDAD = :id_universidad, ID_MODALIDAD = :id_modalidad, ID_GRADO = :id_grado WHERE ID_CARRERA = :id_carrera";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':nombre_carrera', $nombre_carrera);
    $stmt_update->bindParam(':id_universidad', $id_universidad);
    $stmt_update->bindParam(':id_modalidad', $id_modalidad);
    $stmt_update->bindParam(':id_grado', $id_grado);
    $stmt_update->bindParam(':id_carrera', $id_carrera);

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

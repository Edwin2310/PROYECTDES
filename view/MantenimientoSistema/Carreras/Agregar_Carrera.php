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
    $nombre_carrera = isset($_POST['nom_carrera']) ? $_POST['nom_carrera'] : '';
    $id_universidad = isset($_POST['id_universidad']) ? $_POST['id_universidad'] : '';
    $id_modalidad = isset($_POST['id_modalidad']) ? $_POST['id_modalidad'] : '';
    $id_grado = isset($_POST['id_grado']) ? $_POST['id_grado'] : '';

    // Validar datos
    if (empty($nombre_carrera) || empty($id_universidad) || empty($id_modalidad) || empty($id_grado)) {
        header("Location: ../../MantenimientoSistema/Carreras.php?action=error&message=Todos los campos son obligatorios");
        exit();
    }

    // Verificar si la carrera ya existe
    $sql_check = "SELECT COUNT(*) FROM tbl_carrera WHERE NOM_CARRERA = :nombre_carrera 
                  AND ID_UNIVERSIDAD = :id_universidad AND ID_MODALIDAD = :id_modalidad 
                  AND ID_GRADO = :id_grado";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':nombre_carrera', $nombre_carrera);
    $stmt_check->bindParam(':id_universidad', $id_universidad);
    $stmt_check->bindParam(':id_modalidad', $id_modalidad);
    $stmt_check->bindParam(':id_grado', $id_grado);
    $stmt_check->execute();

    if ($stmt_check->fetchColumn() > 0) {
        header("Location: ../../MantenimientoSistema/Carreras.php?action=duplicate");
        exit();
    }

    // Insertar el nuevo registro en la tabla tbl_carrera
    $sql_insert = "INSERT INTO tbl_carrera (NOM_CARRERA, ID_UNIVERSIDAD, ID_MODALIDAD, ID_GRADO) 
                   VALUES (:nombre_carrera, :id_universidad, :id_modalidad, :id_grado)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':nombre_carrera', $nombre_carrera);
    $stmt_insert->bindParam(':id_universidad', $id_universidad);
    $stmt_insert->bindParam(':id_modalidad', $id_modalidad);
    $stmt_insert->bindParam(':id_grado', $id_grado);

    if ($stmt_insert->execute()) {
        header("Location: ../../MantenimientoSistema/Carreras.php?action=add-success");
    } else {
        header("Location: ../../MantenimientoSistema/Carreras.php?action=error&message=Error al agregar carrera: " . implode(" ", $stmt_insert->errorInfo()));
    }

    $conn = null;
    exit();
} else {
    header("Location: ../../MantenimientoSistema/Carreras.php?action=error&message=Solicitud no válida");
    exit();
}
?>
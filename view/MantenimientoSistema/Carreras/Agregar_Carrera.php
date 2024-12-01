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
    $nombre_carrera = isset($_POST['NomCarrera']) ? $_POST['NomCarrera'] : '';
    $IdUniversidad = isset($_POST['IdUniversidad']) ? $_POST['IdUniversidad'] : '';
    $IdModalidad = isset($_POST['IdModalidad']) ? $_POST['IdModalidad'] : '';
    $IdGrado = isset($_POST['IdGrado']) ? $_POST['IdGrado'] : '';

    // Validar datos
    if (empty($nombre_carrera) || empty($IdUniversidad) || empty($IdModalidad) || empty($IdGrado)) {
        header("Location: ../../MantenimientoSistema/Carreras.php?action=error&message=Todos los campos son obligatorios");
        exit();
    }

    // Verificar si la carrera ya existe
    $sql_check = "SELECT COUNT(*) FROM `mantenimiento.tblcarreras` WHERE NomCarrera = :nombre_carrera 
                  AND IdUniversidad = :IdUniversidad AND IdModalidad = :IdModalidad 
                  AND IdGrado = :IdGrado";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':nombre_carrera', $nombre_carrera);
    $stmt_check->bindParam(':IdUniversidad', $IdUniversidad);
    $stmt_check->bindParam(':IdModalidad', $IdModalidad);
    $stmt_check->bindParam(':IdGrado', $IdGrado);
    $stmt_check->execute();

    if ($stmt_check->fetchColumn() > 0) {
        header("Location: ../../MantenimientoSistema/Carreras.php?action=duplicate");
        exit();
    }

    // Insertar el nuevo registro en la tabla `mantenimiento.tblcarreras`
    $sql_insert = "INSERT INTO `mantenimiento.tblcarreras` (NomCarrera, IdUniversidad, IdModalidad, IdGrado) 
                   VALUES (:nombre_carrera, :IdUniversidad, :IdModalidad, :IdGrado)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':nombre_carrera', $nombre_carrera);
    $stmt_insert->bindParam(':IdUniversidad', $IdUniversidad);
    $stmt_insert->bindParam(':IdModalidad', $IdModalidad);
    $stmt_insert->bindParam(':IdGrado', $IdGrado);

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
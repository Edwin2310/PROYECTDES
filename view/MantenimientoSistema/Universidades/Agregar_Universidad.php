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
    $NomUniversidad = isset($_POST['NomUniversidad']) ? trim($_POST['NomUniversidad']) : '';

    // Validar datos
    if (empty($NomUniversidad)) {
        header("Location: ../../MantenimientoSistema/Universidades.php?action=error&message=" . urlencode("Todos los campos son obligatorios"));
        exit();
    }

    // Verificar si la universidad ya existe
    $sql_check = "SELECT EXISTS(SELECT 1 FROM `mantenimiento.tbluniversidades` WHERE NomUniversidad = :NomUniversidad)";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':NomUniversidad', $NomUniversidad);
    $stmt_check->execute();

    if ($stmt_check->fetchColumn()) {
        header("Location: ../../MantenimientoSistema/Universidades.php?action=duplicate");
        exit();
    }

    // Insertar el nuevo registro en la tabla `mantenimiento.tbluniversidades`
    $sql_insert = "INSERT INTO `mantenimiento.tbluniversidades` (NomUniversidad) 
                   VALUES (:NomUniversidad)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':NomUniversidad', $NomUniversidad);

    if ($stmt_insert->execute()) {
        header("Location: ../../MantenimientoSistema/Universidades.php?action=add-success");
    } else {
        header("Location: ../../MantenimientoSistema/Universidades.php?action=error&message=" . urlencode("Error al agregar universidad: " . implode(" ", $stmt_insert->errorInfo())));
    }

    $conn = null;
    exit();
} else {
    header("Location: ../../MantenimientoSistema/Universidades.php?action=error&message=" . urlencode("Solicitud no válida"));
    exit();
}
?>

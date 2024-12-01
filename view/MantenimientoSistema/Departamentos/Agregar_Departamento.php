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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_departamento = isset($_POST['nom_departamento']) ? $_POST['nom_departamento'] : '';

    if (empty($nombre_departamento)) {
        header("Location: ../../MantenimientoSistema/Departamentos.php?action=error&message=El campo de nombre de departamento es obligatorio");
        exit();
    }

    // Verificar si el departamento ya existe
    $sql_check = "SELECT COUNT(*) FROM `mantenimiento.tbldeptos` WHERE NomDepto = :nombre_departamento";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':nombre_departamento', $nombre_departamento);
    $stmt_check->execute();

    if ($stmt_check->fetchColumn() > 0) {
        header("Location: ../../MantenimientoSistema/Departamentos.php?action=duplicate&message=El departamento ya existe");
        exit();
    }

    // Insertar el nuevo departamento en la tabla `mantenimiento.tbldeptos`
    $sql_insert = "INSERT INTO `mantenimiento.tbldeptos` (NomDepto) VALUES (:nombre_departamento)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':nombre_departamento', $nombre_departamento);

    if ($stmt_insert->execute()) {
        header("Location: ../../MantenimientoSistema/Departamentos.php?action=add-success&message=Departamento añadido exitosamente");
    } else {
        $errorInfo = implode(" ", $stmt_insert->errorInfo());
        header("Location: ../../MantenimientoSistema/Departamentos.php?action=error&message=Error al agregar departamento: " . urlencode($errorInfo));
    }

    $conn = null;
    exit();
} else {
    header("Location: ../../MantenimientoSistema/Departamentos.php?action=error&message=Solicitud no válida");
    exit();
}
?>

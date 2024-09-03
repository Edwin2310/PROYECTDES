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
    $nom_universidad = isset($_POST['nom_universidad']) ? $_POST['nom_universidad'] : '';
    $id_depto = isset($_POST['nom_depto']) ? $_POST['nom_depto'] : '';
    $id_municipio = isset($_POST['nom_municipio']) ? $_POST['nom_municipio'] : '';

    // Validar datos
    if (empty($nom_universidad) || empty($id_depto) || empty($id_municipio)) {
        header("Location: ../../MantenimientoSistema/Universidades.php?action=error&message=Todos los campos son obligatorios");
        exit();
    }

    // Verificar si la universidad ya existe
    $sql_check = "SELECT COUNT(*) FROM tbl_universidad_centro WHERE NOM_UNIVERSIDAD = :nom_universidad AND ID_DEPARTAMENTO = :id_depto AND ID_MUNICIPIO = :id_municipio";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':nom_universidad', $nom_universidad);
    $stmt_check->bindParam(':id_depto', $id_depto);
    $stmt_check->bindParam(':id_municipio', $id_municipio);
    $stmt_check->execute();

    if ($stmt_check->fetchColumn() > 0) {
        header("Location: ../../MantenimientoSistema/Universidades.php?action=duplicate");
        exit();
    }

    // Insertar el nuevo registro en la tabla tbl_universidad_centro
    $sql_insert = "INSERT INTO tbl_universidad_centro (NOM_UNIVERSIDAD, ID_DEPARTAMENTO, ID_MUNICIPIO) 
                   VALUES (:nom_universidad, :id_depto, :id_municipio)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':nom_universidad', $nom_universidad);
    $stmt_insert->bindParam(':id_depto', $id_depto);
    $stmt_insert->bindParam(':id_municipio', $id_municipio);

    if ($stmt_insert->execute()) {
        header("Location: ../../MantenimientoSistema/Universidades.php?action=add-success");
    } else {
        header("Location: ../../MantenimientoSistema/Universidades.php?action=error&message=Error al agregar universidad: " . implode(" ", $stmt_insert->errorInfo()));
    }

    $conn = null;
    exit();
} else {
    header("Location: ../../MantenimientoSistema/Universidades.php?action=error&message=Solicitud no válida");
    exit();
}
?>

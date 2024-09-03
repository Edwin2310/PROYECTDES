<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

if (!$conn) {
    die("Conexión fallida: " . $conexion->Conexion()->errorInfo());
}

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$id_universidad = $_POST['id_universidad'];
$categoria_cons = $_POST['categoria_cons'];
$correo_cons = $_POST['correo_cons'];
$estado = $_POST['estado'];

// Verifica que el ID_UNIVERSIDAD exista en tbl_universidad_centro
$sql_verify = "SELECT COUNT(*) FROM tbl_universidad_centro WHERE ID_UNIVERSIDAD = :id_universidad";
$stmt_verify = $conn->prepare($sql_verify);
$stmt_verify->bindParam(':id_universidad', $id_universidad);
$stmt_verify->execute();
$university_exists = $stmt_verify->fetchColumn();

if ($university_exists == 0) {
    $_SESSION['error_message'] = "Error: La universidad no existe.";
    header("Location: ../../Seguridad/Consejales.php");
    exit();
}

// Verifica que el correo no exista
$sql_check = "SELECT COUNT(*) FROM tbl_consejales WHERE CORREO_CONS = :correo_cons";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bindParam(':correo_cons', $correo_cons);
$stmt_check->execute();
$email_exists = $stmt_check->fetchColumn();

if ($email_exists > 0) {
    $_SESSION['error_message'] = "Error: El correo electrónico ya existe.";
    header("Location: ../../Seguridad/Consejales.php");
    exit();
} else {
    $sql = "INSERT INTO tbl_consejales (NOMBRE, APELLIDO, ID_UNIVERSIDAD, CATEGORIA_CONS, CORREO_CONS, ESTADO) 
            VALUES (:nombre, :apellido, :id_universidad, :categoria_cons, :correo_cons, :estado)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':id_universidad', $id_universidad);
    $stmt->bindParam(':categoria_cons', $categoria_cons);
    $stmt->bindParam(':correo_cons', $correo_cons);
    $stmt->bindParam(':estado', $estado);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Consejal agregado exitosamente";
    } else {
        $_SESSION['error_message'] = "Error al agregar consejal: " . $stmt->errorInfo()[2];
    }
}

$conn = null;

header("Location: ../../Seguridad/Consejales.php");
exit();
?>

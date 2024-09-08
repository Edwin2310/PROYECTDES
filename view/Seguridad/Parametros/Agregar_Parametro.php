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

$parametro = $_POST['parametro'];
$valor = $_POST['valor'];
$id_usuario = $_POST['id_usuario'];

// Obtener el nombre del usuario según el IdUsuario
$sql_nombre_usuario = "SELECT NOMBRE_USUARIO FROM tbl_ms_usuario WHERE IdUsuario = :id_usuario";
$stmt_nombre_usuario = $conn->prepare($sql_nombre_usuario);
$stmt_nombre_usuario->bindParam(':id_usuario', $id_usuario);
$stmt_nombre_usuario->execute();
$nombre_usuario = $stmt_nombre_usuario->fetchColumn();

if (!$nombre_usuario) {
    $_SESSION['error_message'] = "Error: No se encontró el usuario con ID $id_usuario.";
    header("Location: ../../Seguridad/Parametros.php");
    exit();
}

// Insertar el parámetro junto con el nombre del usuario como CREADO_POR
$sql_insert = "INSERT INTO tbl_ms_parametros (PARAMETRO, VALOR, IdUsuario, CREADO_POR) 
               VALUES (:parametro, :valor, :id_usuario, :nombre_usuario)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bindParam(':parametro', $parametro);
$stmt_insert->bindParam(':valor', $valor);
$stmt_insert->bindParam(':id_usuario', $id_usuario);
$stmt_insert->bindParam(':nombre_usuario', $nombre_usuario);

if ($stmt_insert->execute()) {
    $_SESSION['success_message'] = "Parámetro agregado exitosamente por $nombre_usuario";
} else {
    $_SESSION['error_message'] = "Error al agregar parámetro: " . $stmt_insert->errorInfo()[2];
}

$conn = null;

header("Location: ../../Seguridad/Parametros.php");
exit();

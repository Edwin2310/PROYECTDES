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

$Parametro = $_POST['Parametro'];
$Valor = $_POST['Valor'];
$IdUsuario = $_POST['IdUsuario'];

// Obtener el nombre del usuario según el IdUsuario
$sql_nombre_usuario = "SELECT NombreUsuario FROM `seguridad.tbldatospersonales` WHERE IdUsuario = :IdUsuario";
$stmt_nombre_usuario = $conn->prepare($sql_nombre_usuario);
$stmt_nombre_usuario->bindParam(':IdUsuario', $IdUsuario);
$stmt_nombre_usuario->execute();
$nombre_usuario = $stmt_nombre_usuario->fetchColumn();

if (!$nombre_usuario) {
    $_SESSION['error_message'] = "Error: No se encontró el usuario con ID $IdUsuario.";
    header("Location: ../../Seguridad/Parametros.php");
    exit();
}

// Insertar el parámetro junto con el nombre del usuario como CreadoPor
$sql_insert = "INSERT INTO `seguridad.tblparametros` (Parametro, Valor, IdUsuario, CreadoPor) 
               VALUES (:Parametro, :Valor, :IdUsuario, :nombre_usuario)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bindParam(':Parametro', $Parametro);
$stmt_insert->bindParam(':Valor', $Valor);
$stmt_insert->bindParam(':IdUsuario', $IdUsuario);
$stmt_insert->bindParam(':nombre_usuario', $nombre_usuario);

if ($stmt_insert->execute()) {
    $_SESSION['success_message'] = "Parámetro agregado exitosamente por $nombre_usuario";
} else {
    $_SESSION['error_message'] = "Error al agregar parámetro: " . $stmt_insert->errorInfo()[2];
}

$conn = null;

header("Location: ../../Seguridad/Parametros.php");
exit();

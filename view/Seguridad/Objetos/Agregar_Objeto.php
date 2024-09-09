<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

if (!$conn) {
    die("Conexión fallida: " . implode(":", $conn->errorInfo()));
}

// Obtener los datos del formulario
$Objeto = $_POST['Objeto'];
$TipoObjeto = $_POST['TipoObjeto'];
$Descripcion = $_POST['Descripcion'];
$IdUsuario = $_SESSION['IdUsuario']; // Obtener el ID del usuario de la sesión
$FechaCreacion = date('Y-m-d H:i:s'); // Fecha y hora actual

// Insertar los datos en la base de datos
$sql = "INSERT INTO `seguridad.tblobjetos` (Objeto, TipoObjeto, Descripcion, FechaCreacion, IdUsuario) VALUES (:Objeto, :TipoObjeto, :Descripcion, :FechaCreacion, :IdUsuario)";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':Objeto', $Objeto);
$stmt->bindParam(':TipoObjeto', $TipoObjeto);
$stmt->bindParam(':Descripcion', $Descripcion);
$stmt->bindParam(':FechaCreacion', $FechaCreacion);
$stmt->bindParam(':IdUsuario', $IdUsuario);

if ($stmt->execute()) {
    echo "Nuevo Objeto añadido correctamente";
} else {
    $errorInfo = $stmt->errorInfo();
    echo "Error: " . $sql . "<br>" . $errorInfo[2];
}

// Cerrar la conexión
$conn = null;

// Redirigir de vuelta a la página principal
header("Location: ../../Seguridad/Objetos.php");

exit();

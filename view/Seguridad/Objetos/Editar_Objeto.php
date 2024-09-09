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

if (isset($_POST['IdObjeto'])) {
    $IdObjeto = $_POST['IdObjeto'];
    $Objeto = $_POST['Objeto'];
    $TipoObjeto = $_POST['TipoObjeto'];
    $Descripcion = $_POST['Descripcion'];
    $IdUsuario = $_POST['IdUsuario']; // ID del usuario que está editando

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Consulta SQL para actualizar el Objeto
    $sql = "UPDATE `seguridad.tblobjetos` 
            SET Objeto = :Objeto, TipoObjeto = :TipoObjeto, Descripcion = :Descripcion, ModificadoPor = :ModificadoPor, FechaModificacion = NOW() 
            WHERE IdObjeto = :IdObjeto";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':Objeto', $Objeto);
    $stmt->bindParam(':TipoObjeto', $TipoObjeto);
    $stmt->bindParam(':Descripcion', $Descripcion);
    $stmt->bindParam(':ModificadoPor', $IdUsuario); // Actualizar con el ID del usuario
    $stmt->bindParam(':IdObjeto', $IdObjeto);

    if ($stmt->execute()) {
        header("Location: ../../Seguridad/Objetos.php");
    } else {
        // Redirigir de vuelta a la página principal
        header("Location: ../../Seguridad/Objetos.php");
    }

    $conn = null;
}

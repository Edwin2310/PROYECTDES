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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $universidad = $_POST["universidad"];
    $estado = $_POST["estado"];
    $correo_cons = $_POST["correo_cons"];
    $categoria_cons = $_POST["categoria_cons"];

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    if (!$conn) {
        die("Conexión fallida: " . $conexion->Conexion()->errorInfo());
    }

    $sql = "UPDATE tbl_consejales SET 
                NOMBRE = :nombre, 
                APELLIDO = :apellido, 
                ID_UNIVERSIDAD = :universidad, 
                ESTADO = :estado, 
                CORREO_CONS = :correo_cons,
                CATEGORIA_CONS = :categoria_cons
            WHERE ID_CONSEJAL = :id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':universidad', $universidad);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':correo_cons', $correo_cons);
    $stmt->bindParam(':categoria_cons', $categoria_cons);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Consejal actualizado exitosamente";
    } else {
        $_SESSION['error_message'] = "Error al actualizar consejal: " . $stmt->errorInfo()[2];
    }

    $conn = null;
    header("Location: ../../Seguridad/Consejales.php");
    exit();
}
?>
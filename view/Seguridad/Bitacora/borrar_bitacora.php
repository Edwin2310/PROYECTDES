<?php
require_once("../../../config/conexion.php");

try {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Consulta para borrar todos los registros
    $sql = "DELETE FROM tbl_ms_bitacora";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        echo "Todos los registros fueron borrados exitosamente";
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Error borrando los registros: " . $errorInfo[2];
    }

    $conn = null;
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>

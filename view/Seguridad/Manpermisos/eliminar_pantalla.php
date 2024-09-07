

<?php
/* require_once("../../../config/conexion.php");

if (isset($_POST['id_rol']) && isset($_POST['id_objeto'])) {
    $id_rol = intval($_POST['id_rol']);
    $id_objeto = intval($_POST['id_objeto']);


    error_log("id_rol: $id_rol, id_objeto: $id_objeto"); // Depuración

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    $sql = "DELETE FROM `seguridad.tblpermisos` WHERE IdRol = :id_rol AND IdObjeto = :id_objeto";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
    $stmt->bindParam(':id_objeto', $id_objeto, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    $conn = null;
} else {
    echo 'error';
} */






require_once("../../../config/conexion.php");

if (isset($_POST['id_rol']) && isset($_POST['id_objeto'])) {
    $id_rol = intval($_POST['id_rol']);
    $id_objeto = intval($_POST['id_objeto']);

  /*   error_log("id_rol: $id_rol, id_objeto: $id_objeto");  */// Depuración

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    $sql = "DELETE FROM `seguridad.tblpermisos` WHERE IdRol = :id_rol AND IdObjeto = :id_objeto";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
    $stmt->bindParam(':id_objeto', $id_objeto, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo 'success';
    } else {
    /*     error_log("SQL Error: " . implode(", ", $stmt->errorInfo()));  */// Depuración
        echo 'error';
    }
    $conn = null;
} else {
    echo 'error';
}


?>

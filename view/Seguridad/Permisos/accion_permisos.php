

<?php
session_start();
require_once("../../../config/conexion.php");

if (isset($_POST['id_rol']) && isset($_POST['id_objeto']) && isset($_POST['permiso'])) {
    $id_rol = intval($_POST['id_rol']);
    $id_objeto = intval($_POST['id_objeto']);
    $permiso = intval($_POST['permiso']);

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    $columna_permiso = '';
    switch ($permiso) {
        case 1:
            $columna_permiso = 'PermisoInsercion';
            break;
        case 2:
            $columna_permiso = 'PermisoEliminacion';
            break;
        case 3:
            $columna_permiso = 'PermisoActualizacion';
            break;
        case 4:
            $columna_permiso = 'PermisoConsultar';
            break;
        default:
            echo json_encode(['tiene_permiso' => false]);
            exit();
    }

    $sql = "SELECT $columna_permiso FROM `seguridad.tblpermisos` WHERE IdRol = :id_rol AND IdObjeto = :id_objeto";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
    $stmt->bindParam(':id_objeto', $id_objeto, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && $row[$columna_permiso] == 1) {
            echo json_encode(['tiene_permiso' => true]);
        } else {
            echo json_encode(['tiene_permiso' => false]);
        }
    } else {
        echo json_encode(['tiene_permiso' => false]);
    }

    $conn = null;
} else {
    echo json_encode(['tiene_permiso' => false]);
}
?>

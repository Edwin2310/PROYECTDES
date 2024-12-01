<?php
// Usa __DIR__ para incluir la conexión correctamente
require_once(__DIR__ . '/../../../config/conexion.php');

function verificarPermiso($id_rol, $id_objeto)
{
    try {
        if (!$id_rol) {
            header("Location: ../Seguridad/Permisos/denegado.php");
            exit();
        }

        // Conectar a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Verificar permiso en la base de datos
        $sql = "SELECT * FROM `seguridad.tblpermisos` WHERE IdRol = :idRol AND IdObjeto = :idObjeto";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':idRol', $id_rol, PDO::PARAM_INT);
        $stmt->bindValue(':idObjeto', $id_objeto, PDO::PARAM_INT);
        $stmt->execute();
/* 
        // Verificar si hay resultados
        if ($stmt->rowCount() > 0) {
            // Usuario tiene permiso
            return true;
        } else {
            // Usuario no tiene permiso
            header("Location: ../Seguridad/Permisos/denegado.php");
            exit();
        } */


        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        // Manejo de errores (puedes ajustar esto según tu necesidad)
        error_log("Error en verificarPermiso: " . $e->getMessage());
        header("Location: ../Seguridad/Permisos/denegado.php");
        exit();
    }
}

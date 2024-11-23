<?php
// Usa __DIR__ para incluir la conexión correctamente
require_once(__DIR__ . '/../../../config/conexion.php');

function registrobitaevent($id_usuario, $id_objeto, $accion)
{
    try {
       
        // Conectar a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Verificar permiso en la base de datos
        $sql = "INSERT INTO `seguridad.tblbitacora` ( IdUsuario, IdObjeto, Accion) VALUES (:id_usuario, :id_objeto, :accion) ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindValue(':id_objeto', $id_objeto, PDO::PARAM_INT);
        $stmt->bindValue(':accion', $accion, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        // Manejo de errores (puedes ajustar esto según tu necesidad)
        error_log("Error en verificarPermiso: " . $e->getMessage());
        header("Location: ../Seguridad/Permisos/denegado.php");
        exit();
    }
}

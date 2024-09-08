<?php
session_start();
require_once("../../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION["IdUsuario"]) && isset($_POST['id_objeto']) && isset($_POST['accion']) && isset($_POST['descripcion'])) {
        $id_usuario = $_SESSION["IdUsuario"];
        $id_objeto = $_POST['id_objeto'];
        $accion = $_POST['accion'];
        $descripcion = $_POST['descripcion'];

        try {
            // Conexión a la base de datos
            $conexion = new Conectar();
            $pdo = $conexion->Conexion();

            // Consulta para insertar en la bitácora
            $sql = 'INSERT INTO `seguridad.tblbitacora` (IdUsuario, IdObjeto, Accion, Descripcion) VALUES (:id_usuario, :id_objeto, :accion, :descripcion)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':id_objeto' => $id_objeto,
                ':accion' => $accion,
                ':descripcion' => $descripcion
            ]);

            echo 'Registro exitoso en la bitácora';
        } catch (PDOException $e) {
            echo 'Error al insertar en la bitácora: '.$e->getMessage();
        }
    } else {
        echo 'Datos incompletos para registrar en la bitácora';
    }
} else {
    echo 'Método de solicitud no válido para insertar en la bitácora';
}



<?php
session_start();
require_once("../../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["id_usuario"]) && isset($_POST['id_objeto']) && isset($_POST['accion'])) {
        $id_usuario = $_POST['id_usuario'];
        $id_objeto = $_POST['id_objeto'];
        $accion = $_POST['accion'];

        try {
            // Conexión a la base de datos
            $conexion = new Conectar();
            $pdo = $conexion->Conexion();

            // Consulta para insertar en la bitácora
            $sql = 'INSERT INTO `seguridad.tblbitacora` (IdUsuario, IdObjeto, Accion) VALUES (:id_usuario, :id_objeto, :accion)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':id_objeto' => $id_objeto,
                ':accion' => $accion,
            ]);

            echo 'Registro exitoso en la bitácora';
        } catch (PDOException $e) {
            echo 'Error al insertar en la bitácora: ' . $e->getMessage();
        }
    } else {
        echo 'Datos incompletos para registrar en la bitácora';
    }
} else {
    echo 'Método de solicitud no válido para insertar en la bitácora';
}
?>

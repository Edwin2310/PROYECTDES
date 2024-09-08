<?php
require_once("../config/conexion.php");

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['contrasena']) && isset($_POST['nueva_contrasena']) && isset($_POST['confirmar_contrasena'])) {
        $contrasena = $_POST['contrasena'];
        $nueva_contrasena = $_POST['nueva_contrasena'];
        $confirmar_contrasena = $_POST['confirmar_contrasena'];

        if ($nueva_contrasena !== $confirmar_contrasena) {
            $response['message'] = 'Las contraseñas no coinciden.';
            echo json_encode($response);
            exit;
        }

        $conn = new Conectar();
        $conexion = $conn->Conexion();

        if (!$conexion) {
            $response['message'] = 'Error al conectar a la base de datos.';
            echo json_encode($response);
            exit;
        }

        $conn->set_names();

        // Verificar que la contraseña temporal sea correcta
        $stmt = $conexion->prepare("SELECT CorreoElectronico FROM `seguridad.tblresetcontraseñas` WHERE ContraseñaTemp = ?");
        $stmt->execute([$contrasena]);
        $temp_password_row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$temp_password_row) {
            $response['message'] = 'Contraseña temporal incorrecta.';
            echo json_encode($response);
            exit;
        }

        // Iniciar transacción
        $conexion->beginTransaction();

        try {
            $correo_electronico = $temp_password_row['CorreoElectronico'];

            // Preparar y ejecutar la actualización de contraseña y estado_usuario
            $stmt_update = $conexion->prepare("UPDATE `seguridad.tblusuarios` SET Contraseña = ?, EstadoUsuario = 1 WHERE CorreoElectronico = ?");
            if (!$stmt_update) {
                throw new Exception('Error al preparar la consulta de actualización.');
            }

            $nueva_contrasena_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            $stmt_update->execute([$nueva_contrasena_hash, $correo_electronico]);

            if ($stmt_update->rowCount() <= 0) {
                throw new Exception('Error al actualizar la contraseña.');
            }

            // Eliminar la entrada de la tabla temporal
            $stmt_delete = $conexion->prepare("DELETE FROM `seguridad.tblresetcontraseñas` WHERE CorreoElectronico = ?");
            $stmt_delete->execute([$correo_electronico]);

            // Confirmar transacción
            $conexion->commit();
            $response['success'] = true;
            $response['message'] = 'Contraseña actualizada con éxito.';
            echo json_encode($response);
            exit();
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $conexion->rollBack();
            $response['message'] = 'Error: ' . $e->getMessage();
        }
    } else {
        $response['message'] = 'Por favor, complete todos los campos.';
    }
} else {
    $response['message'] = 'Método de solicitud no válido.';
}

echo json_encode($response);

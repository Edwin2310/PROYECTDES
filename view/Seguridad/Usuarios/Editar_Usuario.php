<?php
// Iniciar la sesión
session_start();

include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . $conexion->Conexion()->errorInfo());
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar los datos del formulario
    $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_SANITIZE_NUMBER_INT);
    $num_identidad = filter_input(INPUT_POST, 'num_identidad', FILTER_SANITIZE_STRING);
    $direccion_1 = filter_input(INPUT_POST, 'direccion_1', FILTER_SANITIZE_STRING);
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $correo_electronico = filter_input(INPUT_POST, 'correo_electronico', FILTER_SANITIZE_EMAIL);
    $nombre_usuario = filter_input(INPUT_POST, 'nombre_usuario', FILTER_SANITIZE_STRING);
    $estado_usuario = filter_input(INPUT_POST, 'estado_usuario', FILTER_SANITIZE_NUMBER_INT);
    $id_rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_NUMBER_INT);
    $num_empleado = filter_input(INPUT_POST, 'num_empleado', FILTER_SANITIZE_NUMBER_INT);
    $id_universidad = filter_input(INPUT_POST, 'id_universidad', FILTER_SANITIZE_NUMBER_INT);
    $modificado_por = $_SESSION['ID_USUARIO'];  // Usar el ID_USUARIO de la sesión

    // Preparar la consulta SQL para actualizar el usuario
    $sql = "UPDATE tbl_ms_usuario SET 
            num_identidad = :num_identidad, 
            direccion_1 = :direccion_1, 
            usuario = :usuario, 
            correo_electronico = :correo_electronico, 
            nombre_usuario = :nombre_usuario, 
            estado_usuario = :estado_usuario, 
            id_rol = :id_rol, 
            num_empleado = :num_empleado, 
            id_universidad = :id_universidad, 
            modificado_por = :modificado_por
            WHERE id_usuario = :id_usuario";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    // Bind de parámetros
    $stmt->bindParam(':num_identidad', $num_identidad, PDO::PARAM_STR);
    $stmt->bindParam(':direccion_1', $direccion_1, PDO::PARAM_STR);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->bindParam(':correo_electronico', $correo_electronico, PDO::PARAM_STR);
    $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
    $stmt->bindParam(':estado_usuario', $estado_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
    $stmt->bindParam(':num_empleado', $num_empleado, PDO::PARAM_INT);
    $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_universidad', $id_universidad, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Actualizar estado y intentos
        if ($estado_usuario == 1) { // Si el nuevo estado es 'Activo'
            $sql_update_estado = "UPDATE tbl_ms_usuario SET 
                                  ESTADO_USUARIO = :estado_usuario, 
                                  NUM_INTENTOS = 0
                                  WHERE ID_USUARIO = :id_usuario";

            // Preparar la declaración para actualizar el estado
            $stmt_update_estado = $conn->prepare($sql_update_estado);

            // Bind de parámetros
            $stmt_update_estado->bindParam(':estado_usuario', $estado_usuario, PDO::PARAM_INT);
            $stmt_update_estado->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt_update_estado->execute();
        }

        // Redireccionar a una página de éxito o mostrar un mensaje
        $_SESSION['success_message'] = "Usuario actualizado exitosamente";
        header("Location: ../../Seguridad/Usuarios.php");
        exit();
    } else {
        // Manejo de errores
        $_SESSION['error_message'] = "Error al actualizar el usuario: " . $stmt->errorInfo()[2];
    }
}

// Cerrar la conexión
$conn = null;

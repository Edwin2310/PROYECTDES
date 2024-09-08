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
    $IdUsuario = filter_input(INPUT_POST, 'IdUsuario', FILTER_SANITIZE_NUMBER_INT);
    $NumIdentidad = filter_input(INPUT_POST, 'NumIdentidad', FILTER_SANITIZE_STRING);
    $Direccion = filter_input(INPUT_POST, 'Direccion', FILTER_SANITIZE_STRING);
    $Usuario = filter_input(INPUT_POST, 'Usuario', FILTER_SANITIZE_STRING);
    $CorreoElectronico = filter_input(INPUT_POST, 'CorreoElectronico', FILTER_SANITIZE_EMAIL);
    $NombreUsuario = filter_input(INPUT_POST, 'NombreUsuario', FILTER_SANITIZE_STRING);
    $EstadoUsuario = filter_input(INPUT_POST, 'EstadoUsuario', FILTER_SANITIZE_NUMBER_INT);
    $IdRol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_NUMBER_INT);
    $NumEmpleado = filter_input(INPUT_POST, 'NumEmpleado', FILTER_SANITIZE_NUMBER_INT);
    $IdUniversidad = filter_input(INPUT_POST, 'IdUniversidad', FILTER_SANITIZE_NUMBER_INT);
    $ModificadoPor = $_SESSION['IdUsuario'];  // Usar el IdUsuario de la sesión

    // Primera consulta para actualizar la tabla `seguridad.tblusuarios`
    $sql = "UPDATE `seguridad.tblusuarios` SET 
            CorreoElectronico = :CorreoElectronico, 
            EstadoUsuario = :EstadoUsuario, 
            IdRol = :IdRol, 
            IdUniversidad = :IdUniversidad, 
            ModificadoPor = :ModificadoPor
            WHERE IdUsuario = :IdUsuario";

    // Preparar la declaración
    $stmt1 = $conn->prepare($sql);

    // Bind de parámetros para la primera consulta
    $stmt1->bindParam(':CorreoElectronico', $CorreoElectronico, PDO::PARAM_STR);
    $stmt1->bindParam(':EstadoUsuario', $EstadoUsuario, PDO::PARAM_INT);
    $stmt1->bindParam(':IdRol', $IdRol, PDO::PARAM_INT);
    $stmt1->bindParam(':IdUniversidad', $IdUniversidad, PDO::PARAM_INT);
    $stmt1->bindParam(':ModificadoPor', $ModificadoPor, PDO::PARAM_INT);
    $stmt1->bindParam(':IdUsuario', $IdUsuario, PDO::PARAM_INT);

    // Segunda consulta para actualizar la tabla `seguridad.tblusuariospersonal`
    $sql2 = "UPDATE `seguridad.tblusuariospersonal` SET 
        NumIdentidad = :NumIdentidad, 
        Direccion = :Direccion, 
        Usuario = :Usuario, 
        NombreUsuario = :NombreUsuario, 
        NumEmpleado = :NumEmpleado
        WHERE IdUsuario = :IdUsuario";

    // Preparar la declaración
    $stmt2 = $conn->prepare($sql2);

    // Bind de parámetros para la segunda consulta
    $stmt2->bindParam(':NumIdentidad', $NumIdentidad, PDO::PARAM_STR);
    $stmt2->bindParam(':Direccion', $Direccion, PDO::PARAM_STR);
    $stmt2->bindParam(':Usuario', $Usuario, PDO::PARAM_STR);
    $stmt2->bindParam(':NombreUsuario', $NombreUsuario, PDO::PARAM_STR);
    $stmt2->bindParam(':NumEmpleado', $NumEmpleado, PDO::PARAM_INT);
    $stmt2->bindParam(':IdUsuario', $IdUsuario, PDO::PARAM_INT);

    // Ejecutar la primera consulta
    if ($stmt1->execute()) {
        // Ejecutar la segunda consulta
        if ($stmt2->execute()) {
            // Si el nuevo estado es 'Activo', actualizamos el estado y los intentos
            if ($EstadoUsuario == 1) {
                $sql_update_estado = "UPDATE `seguridad.tblusuarios` SET 
                                      EstadoUsuario = :EstadoUsuario, 
                                      NumIntentos = 0
                                      WHERE IdUsuario = :IdUsuario";

                // Preparar la declaración para actualizar el estado
                $stmt_update_estado = $conn->prepare($sql_update_estado);

                // Bind de parámetros
                $stmt_update_estado->bindParam(':EstadoUsuario', $EstadoUsuario, PDO::PARAM_INT);
                $stmt_update_estado->bindParam(':IdUsuario', $IdUsuario, PDO::PARAM_INT);

                // Ejecutar la consulta
                $stmt_update_estado->execute();
            }

            // Redireccionar a una página de éxito o mostrar un mensaje
            $_SESSION['success_message'] = "Usuario actualizado exitosamente";
            header("Location: ../../Seguridad/Usuarios.php");
            exit();
        } else {
            // Manejo de errores en la segunda consulta
            $_SESSION['error_message'] = "Error al actualizar el usuario personal: " . $stmt2->errorInfo()[2];
        }
    } else {
        // Manejo de errores en la primera consulta
        $_SESSION['error_message'] = "Error al actualizar el usuario: " . $stmt1->errorInfo()[2];
    }
}

// Cerrar la conexión
$conn = null;

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
    $id_parametro = filter_input(INPUT_POST, 'id_parametro', FILTER_SANITIZE_NUMBER_INT);
    $parametro = filter_input(INPUT_POST, 'parametro', FILTER_SANITIZE_STRING);
    $valor = filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_STRING);

    // Obtener el ID_USUARIO y CREADO_POR del parámetro antes de la actualización
    $sql_datos_parametro = "SELECT ID_USUARIO, CREADO_POR FROM tbl_ms_parametros WHERE ID_PARAMETRO = :id_parametro";
    $stmt_datos_parametro = $conn->prepare($sql_datos_parametro);
    $stmt_datos_parametro->bindParam(':id_parametro', $id_parametro, PDO::PARAM_INT);
    $stmt_datos_parametro->execute();
    $datos_parametro = $stmt_datos_parametro->fetch(PDO::FETCH_ASSOC);

    if (!$datos_parametro) {
        $_SESSION['error_message'] = "Error: No se encontró el parámetro con ID $id_parametro.";
        header("Location: ../../Seguridad/Parametros.php");
        exit();
    }

    $id_usuario = $datos_parametro['ID_USUARIO'];
    $creado_por = $datos_parametro['CREADO_POR'];

    // Obtener el nombre de usuario actual (simulado para ejemplo)
    $modificado_por = "usuario_actual"; // Aquí deberías implementar la lógica para obtener el nombre del usuario actual

    // Preparar la consulta SQL para actualizar el parámetro
    $sql = "UPDATE tbl_ms_parametros SET 
            PARAMETRO = :parametro, 
            VALOR = :valor, 
            ID_USUARIO = :id_usuario,
            MODIFICADO_POR = :modificado_por
            WHERE ID_PARAMETRO = :id_parametro";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    // Bind de parámetros
    $stmt->bindParam(':parametro', $parametro, PDO::PARAM_STR);
    $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':modificado_por', $creado_por, PDO::PARAM_STR); // Aquí se usa CREADO_POR como MODIFICADO_POR
    $stmt->bindParam(':id_parametro', $id_parametro, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redireccionar a una página de éxito o mostrar un mensaje
        $_SESSION['success_message'] = "Parámetro actualizado correctamente";
        header("Location: ../../Seguridad/Parametros.php");
        exit();
    } else {
        // Manejo de errores
        $_SESSION['error_message'] = "Error al actualizar el parámetro: " . $stmt->errorInfo()[2];
        header("Location: ../../Seguridad/Parametros.php");
        exit();
    }
}

// Cerrar la conexión
$conn = null;
?>

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
    $IdParametro = filter_input(INPUT_POST, 'IdParametro', FILTER_SANITIZE_NUMBER_INT);
    $Parametro = filter_input(INPUT_POST, 'Parametro', FILTER_SANITIZE_STRING);
    $Valor = filter_input(INPUT_POST, 'Valor', FILTER_SANITIZE_STRING);

    // Obtener el IdUsuario y CreadoPor del parámetro antes de la actualización
    $sql_datos_Parametro = "SELECT IdUsuario, CreadoPor FROM `seguridad.tblparametros` WHERE IdParametro = :IdParametro";
    $stmt_datos_Parametro = $conn->prepare($sql_datos_Parametro);
    $stmt_datos_Parametro->bindParam(':IdParametro', $IdParametro, PDO::PARAM_INT);
    $stmt_datos_Parametro->execute();
    $datos_Parametro = $stmt_datos_Parametro->fetch(PDO::FETCH_ASSOC);

    if (!$datos_Parametro) {
        $_SESSION['error_message'] = "Error: No se encontró el parámetro con ID $IdParametro.";
        header("Location: ../../Seguridad/Parametros.php");
        exit();
    }

    $IdUsuario = $datos_Parametro['IdUsuario'];
    $CreadoPor = $datos_Parametro['CreadoPor'];

    // Obtener el nombre de usuario actual (simulado para ejemplo)
    $ModificadoPor = "usuario_actual"; // Aquí deberías implementar la lógica para obtener el nombre del usuario actual

    // Preparar la consulta SQL para actualizar el parámetro
    $sql = "UPDATE `seguridad.tblparametros` SET 
            Parametro = :Parametro, 
            Valor = :Valor, 
            IdUsuario = :IdUsuario,
            ModificadoPor = :ModificadoPor
            WHERE IdParametro = :IdParametro";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    // Bind de parámetros
    $stmt->bindParam(':Parametro', $Parametro, PDO::PARAM_STR);
    $stmt->bindParam(':Valor', $Valor, PDO::PARAM_STR);
    $stmt->bindParam(':IdUsuario', $IdUsuario, PDO::PARAM_INT);
    $stmt->bindParam(':ModificadoPor', $CreadoPor, PDO::PARAM_STR); // Aquí se usa CreadoPor como ModificadoPor
    $stmt->bindParam(':IdParametro', $IdParametro, PDO::PARAM_INT);

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

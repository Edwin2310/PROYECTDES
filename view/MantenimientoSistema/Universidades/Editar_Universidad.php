<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

if (!$conn) {
    die("Conexión fallida: " . implode(" ", $conn->errorInfo()));
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $IdUniversidad = isset($_POST['IdUniversidad']) ? $_POST['IdUniversidad'] : '';
    $NomUniversidad = isset($_POST['NomUniversidad']) ? trim($_POST['NomUniversidad']) : '';

    // Validar campos
    if (empty($IdUniversidad) || empty($NomUniversidad)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios";
        header("Location: ../../MantenimientoSistema/Universidades.php?action=edit-error");
        exit();
    }

    try {
        $sql_update = "UPDATE `mantenimiento.tbluniversidades` SET NomUniversidad = :NomUniversidad WHERE IdUniversidad = :IdUniversidad";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bindParam(':NomUniversidad', $NomUniversidad);
        $stmt_update->bindParam(':IdUniversidad', $IdUniversidad);

        if ($stmt_update->execute()) {
            $_SESSION['success_message'] = "Universidad editada exitosamente";
            header("Location: ../../MantenimientoSistema/Universidades.php?action=edit-success");
        } else {
            $_SESSION['error_message'] = "Error al editar Universidad: " . implode(" ", $stmt_update->errorInfo());
            header("Location: ../../MantenimientoSistema/Universidades.php?action=edit-error");
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error al ejecutar la consulta: " . $e->getMessage();
        header("Location: ../../MantenimientoSistema/Universidades.php?action=edit-error");
    }

    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/Universidades.php?action=edit-error");
    exit();
}
?>

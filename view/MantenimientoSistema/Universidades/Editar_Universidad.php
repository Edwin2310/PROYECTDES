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
    $id_categoria = isset($_POST['id_categoria']) ? $_POST['id_categoria'] : '';
    $cod_arbitrios = isset($_POST['cod_arbitrios']) ? $_POST['cod_arbitrios'] : '';
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
    $id_tipo_solicitud = isset($_POST['id_tipo_solicitud']) ? $_POST['id_tipo_solicitud'] : '';
    $monto = isset($_POST['monto']) ? $_POST['monto'] : '';

    if (empty($id_categoria) || empty($cod_arbitrios) || empty($categoria) || empty($id_tipo_solicitud) || empty($monto)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios";
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=edit-error");
        exit();
    }

    $sql_update = "UPDATE tbl_categoria SET COD_ARBITRIOS = :cod_arbitrios, NOM_CATEGORIA = :categoria, ID_TIPO_SOLICITUD = :id_tipo_solicitud, MONTO = :monto WHERE ID_CATEGORIA = :id_categoria";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':cod_arbitrios', $cod_arbitrios);
    $stmt_update->bindParam(':categoria', $categoria);
    $stmt_update->bindParam(':id_tipo_solicitud', $id_tipo_solicitud);
    $stmt_update->bindParam(':monto', $monto);
    $stmt_update->bindParam(':id_categoria', $id_categoria);

    if ($stmt_update->execute()) {
        $_SESSION['success_message'] = "Categoría editada exitosamente";
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=edit-success");
    } else {
        $_SESSION['error_message'] = "Error al editar categoría: " . implode(" ", $stmt_update->errorInfo());
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=edit-error");
    }

    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=edit-error");
    exit();
}
?>

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
    // Obtener datos del formulario
    $cod_arbitrios = isset($_POST['cod_arbitrios']) ? $_POST['cod_arbitrios'] : '';
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
    $id_tipo_solicitud = isset($_POST['id_tipo_solicitud']) ? $_POST['id_tipo_solicitud'] : '';
    $monto = isset($_POST['monto']) ? $_POST['monto'] : '';

    // Validar datos
    if (empty($cod_arbitrios) || empty($categoria) || empty($id_tipo_solicitud) || empty($monto)) {
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=error&message=Todos los campos son obligatorios");
        exit();
    }

    // Verificar si la categoría ya existe
    $sql_check = "SELECT COUNT(*) FROM tbl_categoria WHERE COD_ARBITRIOS = :cod_arbitrios AND NOM_CATEGORIA = :categoria AND ID_TIPO_SOLICITUD = :id_tipo_solicitud";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':cod_arbitrios', $cod_arbitrios);
    $stmt_check->bindParam(':categoria', $categoria);
    $stmt_check->bindParam(':id_tipo_solicitud', $id_tipo_solicitud);
    $stmt_check->execute();

    if ($stmt_check->fetchColumn() > 0) {
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=duplicate");
        exit();
    }

    // Insertar el nuevo registro en la tabla tbl_categoria
    $sql_insert = "INSERT INTO tbl_categoria (COD_ARBITRIOS, NOM_CATEGORIA, ID_TIPO_SOLICITUD, MONTO) 
                   VALUES (:cod_arbitrios, :categoria, :id_tipo_solicitud, :monto)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':cod_arbitrios', $cod_arbitrios);
    $stmt_insert->bindParam(':categoria', $categoria);
    $stmt_insert->bindParam(':id_tipo_solicitud', $id_tipo_solicitud);
    $stmt_insert->bindParam(':monto', $monto);

    if ($stmt_insert->execute()) {
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=add-success");
    } else {
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=error&message=Error al agregar categoría: " . implode(" ", $stmt_insert->errorInfo()));
    }

    $conn = null;
    exit();
} else {
    header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=error&message=Solicitud no válida");
    exit();
}
?>

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
    $IdCategoria = isset($_POST['IdCategoria']) ? $_POST['IdCategoria'] : '';
    $CodArbitrios = isset($_POST['CodArbitrios']) ? $_POST['CodArbitrios'] : '';
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
    $IdTiposolicitud = isset($_POST['IdTiposolicitud']) ? $_POST['IdTiposolicitud'] : '';
    $Monto = isset($_POST['Monto']) ? $_POST['Monto'] : '';

    if (empty($IdCategoria) || empty($CodArbitrios) || empty($categoria) || empty($IdTiposolicitud) || empty($Monto)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios";
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=edit-error");
        exit();
    }

    $sql_update = "UPDATE `mantenimiento.tblcategorias` SET CodArbitrios = :CodArbitrios, NomCategoria = :categoria, IdTiposolicitud = :IdTiposolicitud, Monto = :Monto WHERE IdCategoria = :IdCategoria";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':CodArbitrios', $CodArbitrios);
    $stmt_update->bindParam(':categoria', $categoria);
    $stmt_update->bindParam(':IdTiposolicitud', $IdTiposolicitud);
    $stmt_update->bindParam(':Monto', $Monto);
    $stmt_update->bindParam(':IdCategoria', $IdCategoria);

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

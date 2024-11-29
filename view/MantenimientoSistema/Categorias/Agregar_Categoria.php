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
    $CodArbitrios = isset($_POST['CodArbitrios']) ? $_POST['CodArbitrios'] : '';
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
    $IdTiposolicitud = isset($_POST['IdTiposolicitud']) ? $_POST['IdTiposolicitud'] : '';
    $Monto = isset($_POST['Monto']) ? $_POST['Monto'] : '';

    // Validar datos
    if (empty($CodArbitrios) || empty($categoria) || empty($IdTiposolicitud) || empty($Monto)) {
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=error&message=Todos los campos son obligatorios");
        exit();
    }

    // Verificar si la categoría ya existe
    $sql_check = "SELECT COUNT(*) FROM `mantenimiento.tblcategorias` WHERE CodArbitrios = :CodArbitrios AND NomCategoria = :categoria AND IdTiposolicitud = :IdTiposolicitud";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':CodArbitrios', $CodArbitrios);
    $stmt_check->bindParam(':categoria', $categoria);
    $stmt_check->bindParam(':IdTiposolicitud', $IdTiposolicitud);
    $stmt_check->execute();

    if ($stmt_check->fetchColumn() > 0) {
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=duplicate");
        exit();
    }

    // Insertar el nuevo registro en la tabla `mantenimiento.tblcategorias`
    $sql_insert = "INSERT INTO `mantenimiento.tblcategorias` (CodArbitrios, NomCategoria, IdTiposolicitud, Monto) 
                   VALUES (:CodArbitrios, :categoria, :IdTiposolicitud, :Monto)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':CodArbitrios', $CodArbitrios);
    $stmt_insert->bindParam(':categoria', $categoria);
    $stmt_insert->bindParam(':IdTiposolicitud', $IdTiposolicitud);
    $stmt_insert->bindParam(':Monto', $Monto);

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

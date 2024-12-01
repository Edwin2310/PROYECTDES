<?php
require_once("../../../config/conexion.php");

if (isset($_POST['IdCategoria'])) {
    $IdCategoria = $_POST['IdCategoria'];

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Actualizar el IdVisibilidad a 1 para habilitar la modalidad
    $sql = "UPDATE `mantenimiento.tblcategorias` 
            SET `IdVisibilidad` = 1 
            WHERE `IdCategoria` = :IdCategoria";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':IdCategoria', $IdCategoria, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirigir a la página principal con un mensaje de éxito
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes_Bloqueadas.php?mensaje=habilitada");
    } else {
        // En caso de error
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes_Bloqueadas.php?mensaje=error");
    }

    exit();
}

<?php
require_once("../../../config/conexion.php");

if (isset($_POST['IdCarrera'])) {
    $IdCarrera = $_POST['IdCarrera'];

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Actualizar el IdVisibilidad a 1 para habilitar la modalidad
    $sql = "UPDATE `mantenimiento.tblcarreras` 
            SET `IdVisibilidad` = 1 
            WHERE `IdCarrera` = :IdCarrera";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':IdCarrera', $IdCarrera, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirigir a la página principal con un mensaje de éxito
        header("Location: ../../MantenimientoSistema/Carreras_Bloqueadas.php?mensaje=habilitada");
    } else {
        // En caso de error
        header("Location: ../../MantenimientoSistema/Carreras_Bloqueadas.php?mensaje=error");
    }

    exit();
}

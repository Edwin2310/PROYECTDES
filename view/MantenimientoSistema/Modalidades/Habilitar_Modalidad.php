<?php
require_once("../../../config/conexion.php");

if (isset($_POST['IdModalidad'])) {
    $IdModalidad = $_POST['IdModalidad'];

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Actualizar el IdVisibilidad a 1 para habilitar la modalidad
    $sql = "UPDATE `mantenimiento.tblmodalidades` 
            SET `IdVisibilidad` = 1 
            WHERE `IdModalidad` = :IdModalidad";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':IdModalidad', $IdModalidad, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirigir a la página principal con un mensaje de éxito
        header("Location: ../../MantenimientoSistema/Modalidades_Bloqueadas.php?mensaje=habilitada");
    } else {
        // En caso de error
        header("Location: ../../MantenimientoSistema/Modalidades_Bloqueadas.php?mensaje=error");
    }

    exit();
}

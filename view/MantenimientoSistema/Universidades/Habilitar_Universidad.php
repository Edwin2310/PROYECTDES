<?php
require_once("../../../config/conexion.php");

if (isset($_POST['IdUniversidad'])) {
    $IdUniversidad = $_POST['IdUniversidad'];

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Actualizar el IdVisibilidad a 1 para habilitar la modalidad
    $sql = "UPDATE `mantenimiento.tbluniversidades`
            SET `IdVisibilidad` = 1 
            WHERE `IdUniversidad` = :IdUniversidad";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':IdUniversidad', $IdUniversidad, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirigir a la página principal con un mensaje de éxito
        header("Location: ../../MantenimientoSistema/Universidades_Bloqueadas.php?mensaje=habilitada");
    } else {
        // En caso de error
        header("Location: ../../MantenimientoSistema/Universidades_Bloqueadas.php?mensaje=error");
    }

    exit();
}

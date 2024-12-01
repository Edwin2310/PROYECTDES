<?php
require_once("../../../config/conexion.php");

if (isset($_POST['IdGrado'])) {
    $IdGrado = $_POST['IdGrado'];

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Actualizar el IdVisibilidad a 1 para habilitar la modalidad
    $sql = "UPDATE `mantenimiento.tblgradosacademicos`
            SET `IdVisibilidad` = 1 
            WHERE `IdGrado` = :IdGrado";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':IdGrado', $IdGrado, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirigir a la página principal con un mensaje de éxito
        header("Location: ../../MantenimientoSistema/GradosAcademicos_Bloqueados.php?mensaje=habilitada");
    } else {
        // En caso de error
        header("Location: ../../MantenimientoSistema/GradosAcademicos_Bloqueados.php?mensaje=error");
    }

    exit();
}

<?php
require_once("../../../config/conexion.php");

if (isset($_POST['IdDepartamento'])) {
    $IdDepartamento = $_POST['IdDepartamento'];

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Actualizar el IdVisibilidad a 1 para habilitar la modalidad
    $sql = "UPDATE `mantenimiento.tbldeptos` 
            SET `IdVisibilidad` = 1 
            WHERE `IdDepartamento` = :IdDepartamento";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':IdDepartamento', $IdDepartamento, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirigir a la página principal con un mensaje de éxito
        header("Location: ../../MantenimientoSistema/Departamentos_Bloqueados.php?mensaje=habilitada");
    } else {
        // En caso de error
        header("Location: ../../MantenimientoSistema/Departamentos_Bloqueados.php?mensaje=error");
    }

    exit();
}

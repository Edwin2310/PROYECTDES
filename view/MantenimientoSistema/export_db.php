<?php
require_once("../../config/conexion.php");

// Conectar a la base de datos
$conexion = new Conectar();
$conn = $conexion->Conexion();


    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 53; // ID del objeto o módulo correspondiente a esta página

    if (!$id_rol) {
        header("Location: ../Seguridad/Permisos/denegado.php");
        exit();
    }

    // Conectar a la base de datos
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Verificar permiso en la base de datos
    $sql = "SELECT * FROM `seguridad.tblpermisos` WHERE IdRol = :idRol AND IdObjeto = :idObjeto";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idRol', $id_rol);
    $stmt->bindParam(':idObjeto', $id_objeto);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        // Usuario tiene permiso, continuar con el contenido de la página
    } else {
        header("Location: ../Seguridad/Permisos/denegado.php");
        exit();
    }


// Nombre del archivo de respaldo
$backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
$handle = fopen($backup_file, 'w+');

if ($handle === false) {
    die('No se pudo crear el archivo de respaldo.');
}

// Obtener todas las tablas de la base de datos
$tables = [];
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch(PDO::FETCH_NUM)) {
    $tables[] = $row[0];
}

foreach ($tables as $table) {
    // Agregar la estructura de la tabla
    $result = $conn->query("SHOW CREATE TABLE $table");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $create_table_sql = $row['Create Table'] . ";\n\n";
    fwrite($handle, $create_table_sql);

    // Agregar los datos de la tabla
    $result = $conn->query("SELECT * FROM $table");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $columns = array_keys($row);
        $values = array_values($row);

        $columns = implode('`, `', $columns);
        $values = array_map([$conn, 'quote'], $values);
        $values = implode(', ', $values);

        $insert_sql = "INSERT INTO `$table` (`$columns`) VALUES ($values);\n";
        fwrite($handle, $insert_sql);
    }
    fwrite($handle, "\n\n");
}

fclose($handle);

// Descargar el archivo de respaldo
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . basename($backup_file));
header('Content-Length: ' . filesize($backup_file));
readfile($backup_file);

// Eliminar el archivo después de la descarga (opcional)
unlink($backup_file);

exit;

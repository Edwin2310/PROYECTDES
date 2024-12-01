<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
require_once(__DIR__ . '/../Seguridad/Bitacora/Funciones_Bitacoras.php');

    // Obtener los valores necesarios para la verificación
    $id_usuario = $_SESSION['IdUsuario'] ?? null;
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 53; // ID del objeto o módulo correspondiente a esta página

    // Obtener la página actual y la última marca de acceso
    $current_page = basename($_SERVER['PHP_SELF']);
    $last_access_time = $_SESSION['last_access_time'][$current_page] ?? 0;

    // Obtener el tiempo actual
    $current_time = time();

    // Verificar si han pasado al menos 10 segundos desde el último registro
    if ($current_time - $last_access_time > 3) {
        // Verificar permisos
        if (verificarPermiso($id_rol, $id_objeto)) {
            $accion = "Accedió al módulo.";

            // Registrar en la bitácora
            registrobitaevent($id_usuario, $id_objeto, $accion);
        } else {
            $accion = "acceso denegado.";

            // Registrar en bitácora antes de redirigir
            registrobitaevent($id_usuario, $id_objeto, $accion);

            // Redirigir a la página de denegación
            header("Location: ../Seguridad/Permisos/denegado.php");
            exit();
        }

        // Actualizar la marca temporal en la sesión
        $_SESSION['last_access_time'][$current_page] = $current_time;
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

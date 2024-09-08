<?php
require_once("../../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtén el contenido del cuerpo de la solicitud y decodifícalo
    $data = json_decode(file_get_contents('php://input'), true);

    // Verifica si los datos están presentes
    if (isset($data['idRol'], $data['idObjeto'], $data['permisoInsercion'], $data['permisoEliminacion'], $data['permisoActualizacion'], $data['permisoConsultar'])) {
        $idRol = $data['idRol'];
        $idObjeto = $data['idObjeto'];
        $permisoInsercion = $data['permisoInsercion'];
        $permisoEliminacion = $data['permisoEliminacion'];
        $permisoActualizacion = $data['permisoActualizacion'];
        $permisoConsultar = $data['permisoConsultar'];

        try {
            // Conectar a la base de datos
            $conexion = new Conectar();
            $conn = $conexion->Conexion();

            // Consulta para actualizar los permisos
            $sql = "UPDATE `seguridad.tblpermisos` p
                    SET p.PERMISO_INSERCION = :permisoInsercion,
                        p.PERMISO_ELIMINACION = :permisoEliminacion,
                        p.PERMISO_ACTUALIZACION = :permisoActualizacion,
                        p.PERMISO_CONSULTAR = :permisoConsultar
                    WHERE IdRol = :idRol AND IdObjeto = :idObjeto";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idRol', $idRol, PDO::PARAM_INT);
            $stmt->bindParam(':idObjeto', $idObjeto, PDO::PARAM_INT);
            $stmt->bindParam(':permisoInsercion', $permisoInsercion, PDO::PARAM_INT);
            $stmt->bindParam(':permisoEliminacion', $permisoEliminacion, PDO::PARAM_INT);
            $stmt->bindParam(':permisoActualizacion', $permisoActualizacion, PDO::PARAM_INT);
            $stmt->bindParam(':permisoConsultar', $permisoConsultar, PDO::PARAM_INT);
           
            

            // Ejecuta la consulta
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Permisos actualizados correctamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar los permisos.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error de base de datos: ' . $e->getMessage()]);
        }

        // Cierra la conexión
        $conn = null;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>

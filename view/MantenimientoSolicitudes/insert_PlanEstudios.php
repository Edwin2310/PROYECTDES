<?php
require_once("../../config/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numeroRegistro = isset($_POST['numeroRegistro']) ? htmlspecialchars($_POST['numeroRegistro']) : '';
    $idSolicitud = isset($_POST['idSolicitud']) ? htmlspecialchars($_POST['idSolicitud']) : '';
    $fileName = isset($_POST['fileName']) ? htmlspecialchars($_POST['fileName']) : '';
    $filePath = isset($_POST['filePath']) ? htmlspecialchars($_POST['filePath']) : '';

    // Obtener el ID de usuario y nombre de usuario de la sesión
    session_start();
    $idUsuario = isset($_SESSION["ID_USUARIO"]) ? $_SESSION["ID_USUARIO"] : null;
    $nombreUsuario = isset($_SESSION["NOMBRE_USUARIO"]) ? $_SESSION["NOMBRE_USUARIO"] : null;

    if ($numeroRegistro && $idSolicitud && $fileName && $filePath) {
        // Conectar a la base de datos
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        try {
            // Iniciar una transacción
            $conn->beginTransaction();

            // Obtener ID_CARRERA e ID_UNIVERSIDAD
            $sqlSelect = "SELECT ID_CARRERA, ID_UNIVERSIDAD
                          FROM tbl_solicitudes
                          WHERE ID_SOLICITUD = :idSolicitud";
            $stmtSelect = $conn->prepare($sqlSelect);
            $stmtSelect->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
            $stmtSelect->execute();
            $result = $stmtSelect->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $idCarrera = $result['ID_CARRERA'];
                $idUniversidad = $result['ID_UNIVERSIDAD'];
            } else {
                throw new Exception('No se encontró la solicitud.');
            }

            // Insertar en la tabla `tbl_plan_estudio`
            $sqlInsert = "INSERT INTO tbl_plan_estudio (NUM_REGISTRO, ADJUNTO_PLAN, ID_SOLICITUD, ID_UNIVERSIDAD, ID_CARRERA) 
                          VALUES (:numeroRegistro, :filePath, :idSolicitud, :idUniversidad, :idCarrera)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bindParam(':numeroRegistro', $numeroRegistro, PDO::PARAM_STR);
            $stmtInsert->bindParam(':filePath', $filePath, PDO::PARAM_STR);
            $stmtInsert->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);
            $stmtInsert->bindParam(':idUniversidad', $idUniversidad, PDO::PARAM_INT);
            $stmtInsert->bindParam(':idCarrera', $idCarrera, PDO::PARAM_INT);

            if ($stmtInsert->execute()) {
                // Actualizar el estado de la solicitud
                $nuevoEstado = 17; // Estado a actualizar
                $sqlUpdate = "UPDATE tbl_solicitudes SET ID_ESTADO = :nuevoEstado WHERE ID_SOLICITUD = :idSolicitud";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':idSolicitud', $idSolicitud, PDO::PARAM_INT);

                if ($stmtUpdate->execute()) {
                    // Confirmar la transacción
                    $conn->commit();
                    echo json_encode(['success' => true, 'message' => 'Número de registro, archivo y solicitud insertados correctamente, y estado actualizado.']);
                } else {
                    throw new Exception('Error al actualizar el estado de la solicitud.');
                }
            } else {
                throw new Exception('Error al insertar los datos en tbl_plan_estudio.');
            }
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conn->rollBack();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos o archivo no válido.']);
    }
}



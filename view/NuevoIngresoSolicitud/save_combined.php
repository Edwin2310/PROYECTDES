<?php
session_start();
include("../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();
if (!$conn) {
    die("Conexión fallida: " . $conn->errorInfo()[2]);
}

// Obtén los datos enviados por AJAX
$step = isset($_POST['step']) ? $_POST['step'] : null;

if ($step === 'step1') {
    // Paso 1 - Guarda los datos del paso 1 en la base de datos
    $nombreCompleto = isset($_POST['NombreCompleto']) ? $_POST['NombreCompleto'] : null;
    $CorreoElectronico = isset($_POST['CorreoElectronico']) ? $_POST['CorreoElectronico'] : null;

    // Inserta los datos en la base de datos y obtiene el ID de la solicitud
    $sql = "INSERT INTO `proceso.tblsolicitudes` (NombreCompleto, CorreoElectronico) VALUES (:nombreCompleto, :CorreoElectronico)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombreCompleto', $nombreCompleto);
    $stmt->bindParam(':CorreoElectronico', $CorreoElectronico);

    try {
        if ($stmt->execute()) {
            // Obtén el ID de la solicitud recién creada
            $idSolicitud = $conn->lastInsertId();
            $_SESSION['IdSolicitud'] = $idSolicitud; // Guarda el ID en la sesión
            echo 'step1_success';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage();
    }
} elseif ($step === 'step2') {
    // Paso 2 - Actualiza los datos del paso 2 en la base de datos
    if (!isset($_SESSION['IdSolicitud'])) {
        echo 'error: missing ID';
        exit;
    }

    $idSolicitud = $_SESSION['IdSolicitud'];
    $codigoPago = isset($_POST['CodigoPago']) ? $_POST['CodigoPago'] : null;
    $idCategoria = isset($_POST['IdCategoria']) ? $_POST['IdCategoria'] : null;
    $idTipoSolicitud = isset($_POST['IdTiposolicitud']) ? $_POST['IdTiposolicitud'] : null;
    $numReferencia = isset($_POST['NumReferencia']) ? $_POST['NumReferencia'] : null;
    $nombreCarrera = isset($_POST['IdCarrera']) ? $_POST['IdCarrera'] : null;
    $idGradoAcad = isset($_POST['IdGrado']) ? $_POST['IdGrado'] : null;
    $idModalidad = isset($_POST['IdModalidad']) ? $_POST['IdModalidad'] : null;
    $universidad = isset($_POST['IdUniversidad']) ? $_POST['IdUniversidad'] : null;
    $departamentos = isset($_POST['IdDepartamento']) ? $_POST['IdDepartamento'] : null;
    $municipios = isset($_POST['IdMunicipio']) ? $_POST['IdMunicipio'] : null;
    $descripciones = isset($_POST['Descripcion']) ? $_POST['Descripcion'] : null;
    $idUsuario = $_SESSION['IdUsuario']; // Obtener el ID del usuario de la sesión
    $idEstado = 1; // Valor fijo para IdEstado

    try {
        // Inserta los datos en la tabla tbl_carreras
        $sqlCarrera = "INSERT INTO `mantenimiento.tblcarreras` (NomCarrera, IdUniversidad, IdModalidad, IdGrado) 
                       VALUES (:nombreCarrera, :universidad, :idModalidad, :idGradoAcad)";
        $stmtCarrera = $conn->prepare($sqlCarrera);
        $stmtCarrera->bindParam(':nombreCarrera', $nombreCarrera);
        $stmtCarrera->bindParam(':universidad', $universidad);
        $stmtCarrera->bindParam(':idModalidad', $idModalidad);
        $stmtCarrera->bindParam(':idGradoAcad', $idGradoAcad);

        if ($stmtCarrera->execute()) {
            // Obtén el IdCarrera recién insertado
            $idCarrera = $conn->lastInsertId();

            // Actualiza la tabla tbl_solicitudes
            $sqlUpdateSolicitud = "UPDATE `proceso.tblsolicitudes` 
                                   SET CodigoPago = :codigoPago,
                                       IdCategoria = :idCategoria,
                                       IdTiposolicitud = :idTipoSolicitud,
                                       NumReferencia = :numReferencia,
                                       IdCarrera = :idCarrera,  
                                       IdGrado = :idGradoAcad,
                                       IdModalidad = :idModalidad,
                                       IdUniversidad = :universidad,
                                       IdDepartamento = :departamentos,
                                       IdMunicipio = :municipios,
                                       Descripcion = :Descripcion,
                                       IdUsuario = :idUsuario,
                                       IdEstado = :idEstado
                                   WHERE IdSolicitud = :idSolicitud";

            $stmtUpdate = $conn->prepare($sqlUpdateSolicitud);
            $stmtUpdate->bindParam(':codigoPago', $codigoPago);
            $stmtUpdate->bindParam(':idCategoria', $idCategoria);
            $stmtUpdate->bindParam(':idTipoSolicitud', $idTipoSolicitud);
            $stmtUpdate->bindParam(':numReferencia', $numReferencia);
            $stmtUpdate->bindParam(':idCarrera', $idCarrera);
            $stmtUpdate->bindParam(':idGradoAcad', $idGradoAcad);
            $stmtUpdate->bindParam(':idModalidad', $idModalidad);
            $stmtUpdate->bindParam(':universidad', $universidad);
            $stmtUpdate->bindParam(':departamentos', $departamentos);
            $stmtUpdate->bindParam(':municipios', $municipios);
            $stmtUpdate->bindParam(':Descripcion', $descripciones); // Asegurando coincidencia
            $stmtUpdate->bindParam(':idUsuario', $idUsuario);
            $stmtUpdate->bindParam(':idEstado', $idEstado);
            $stmtUpdate->bindParam(':idSolicitud', $idSolicitud);

            // Ejecuta la consulta y verifica el resultado
            if ($stmtUpdate->execute()) {
                echo 'success';
            } else {
                echo 'error_solicitud';
            }
        } else {
            echo 'error_carrera';
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage();
    }

    $conn = null;
} else {
    echo 'error: invalid step';
}

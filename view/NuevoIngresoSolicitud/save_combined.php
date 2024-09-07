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
    $nombreCompleto = isset($_POST['NOMBRE_COMPLETO']) ? $_POST['NOMBRE_COMPLETO'] : null;
    $email = isset($_POST['EMAIL']) ? $_POST['EMAIL'] : null;

    // Inserta los datos en la base de datos y obtiene el ID de la solicitud
    $sql = "INSERT INTO tbl_solicitudes (nombre_completo, email) VALUES (:nombreCompleto, :email)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombreCompleto', $nombreCompleto);
    $stmt->bindParam(':email', $email);

    try {
        if ($stmt->execute()) {
            // Obtén el ID de la solicitud recién creada
            $idSolicitud = $conn->lastInsertId();
            $_SESSION['ID_SOLICITUD'] = $idSolicitud; // Guarda el ID en la sesión
            echo 'step1_success';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage();
    }
} elseif ($step === 'step2') {
    // Paso 2 - Actualiza los datos del paso 2 en la base de datos
    if (!isset($_SESSION['ID_SOLICITUD'])) {
        echo 'error: missing ID';
        exit;
    }

    $idSolicitud = $_SESSION['ID_SOLICITUD'];
    $codigoPago = isset($_POST['codigo-pago']) ? $_POST['codigo-pago'] : null;
    $idCategoria = isset($_POST['id_categoria']) ? $_POST['id_categoria'] : null;
    $idTipoSolicitud = isset($_POST['id_tipo_solicitud']) ? $_POST['id_tipo_solicitud'] : null;
    $numReferencia = isset($_POST['Num_referencia']) ? $_POST['Num_referencia'] : null;
    $nombreCarrera = isset($_POST['id_carrera']) ? $_POST['id_carrera'] : null;
    $idGradoAcad = isset($_POST['id_grado_acad']) ? $_POST['id_grado_acad'] : null;
    $idModalidad = isset($_POST['id_modalidad']) ? $_POST['id_modalidad'] : null;
    $universidad = isset($_POST['Universidad']) ? $_POST['Universidad'] : null;
    $departamentos = isset($_POST['Departamento']) ? implode(',', $_POST['Departamento']) : null;
    $municipios = isset($_POST['Municipio']) ? implode(',', $_POST['Municipio']) : null;
    $descripcionSolicitud = isset($_POST['Descripcion_solicitud']) ? $_POST['Descripcion_solicitud'] : null;
    $idUsuario = $_SESSION['IdUsuario']; // Obtener el ID del usuario de la sesión
    $idEstado = 1; // Valor fijo para ID_ESTADO

    try {
        // Inserta los datos en la tabla tbl_carrera de forma independiente
        $sqlCarrera = "INSERT INTO tbl_carrera (NOM_CARRERA, ID_UNIVERSIDAD, ID_MODALIDAD, ID_GRADO) 
                       VALUES (:nombreCarrera, :universidad, :idModalidad, :idGradoAcad)";
        $stmtCarrera = $conn->prepare($sqlCarrera);
        $stmtCarrera->bindParam(':nombreCarrera', $nombreCarrera);
        $stmtCarrera->bindParam(':universidad', $universidad);
        $stmtCarrera->bindParam(':idModalidad', $idModalidad);
        $stmtCarrera->bindParam(':idGradoAcad', $idGradoAcad);

        if ($stmtCarrera->execute()) {
            // Obtén el ID_CARRERA recién insertado
            $idCarrera = $conn->lastInsertId();

            // Luego, actualiza la tabla tbl_solicitudes con el nuevo ID_CARRERA
            $sqlUpdateSolicitud = "UPDATE tbl_solicitudes 
                                   SET codigo_pago = :codigoPago,
                                       id_categoria = :idCategoria,
                                       id_tipo_solicitud = :idTipoSolicitud,
                                       num_referencia = :numReferencia,
                                       id_carrera = :idCarrera,  /* Usa el nuevo ID_CARRERA */
                                       id_grado = :idGradoAcad,
                                       id_modalidad = :idModalidad,
                                       id_universidad = :universidad,
                                       id_departamento = :departamentos,
                                       id_municipio = :municipios,
                                       descripcion = :descripcionSolicitud,
                                       id_usuario = :idUsuario,
                                       id_estado = :idEstado
                                   WHERE id_solicitud = :idSolicitud";

            $stmtUpdate = $conn->prepare($sqlUpdateSolicitud);
            $stmtUpdate->bindParam(':codigoPago', $codigoPago);
            $stmtUpdate->bindParam(':idCategoria', $idCategoria);
            $stmtUpdate->bindParam(':idTipoSolicitud', $idTipoSolicitud);
            $stmtUpdate->bindParam(':numReferencia', $numReferencia);
            $stmtUpdate->bindParam(':idCarrera', $idCarrera);  // Aquí usas el ID de carrera recién insertado
            $stmtUpdate->bindParam(':idGradoAcad', $idGradoAcad);
            $stmtUpdate->bindParam(':idModalidad', $idModalidad);
            $stmtUpdate->bindParam(':universidad', $universidad);
            $stmtUpdate->bindParam(':departamentos', $departamentos);
            $stmtUpdate->bindParam(':municipios', $municipios);
            $stmtUpdate->bindParam(':descripcionSolicitud', $descripcionSolicitud);
            $stmtUpdate->bindParam(':idUsuario', $idUsuario);
            $stmtUpdate->bindParam(':idEstado', $idEstado);
            $stmtUpdate->bindParam(':idSolicitud', $idSolicitud);

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

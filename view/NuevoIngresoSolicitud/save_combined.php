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

    $idUsuario = $_SESSION['IdUsuario'];
    $idSolicitud = $_SESSION['IdSolicitud'];
    $idEstado = 1; // Valor fijo para ID_ESTADO
    $nombreCompleto = isset($_POST['NombreCompleto']) ? $_POST['NombreCompleto'] : null;
    $correoElectronico = isset($_POST['CorreoElectronico']) ? $_POST['CorreoElectronico'] : null;
    $idTiposolicitud = isset($_POST['IdTiposolicitud']) ? $_POST['IdTiposolicitud'] : null;
    $idCategoria = isset($_POST['IdCategoria']) ? $_POST['IdCategoria'] : null;
    $codigoPago = isset($_POST['CodigoPago']) ? $_POST['CodigoPago'] : null;
    $codigoReferencia = isset($_POST['CodigoReferencia']) ? $_POST['CodigoReferencia'] : null;
    $nombreCentro = isset($_POST['NombreCentro']) ? $_POST['NombreCentro'] : null;
    $idTipoCentro = isset($_POST['IdTipoCentro']) ? $_POST['IdTipoCentro'] : null;
    $idUniversidad = isset($_POST['IdUniversidad']) ? $_POST['IdUniversidad'] : null;
    $idDepartamento = isset($_POST['IdDepartamento']) ? $_POST['IdDepartamento'] : null;
    $idMunicipio = isset($_POST['IdMunicipio']) ? $_POST['IdMunicipio'] : null;
    $descripcionSolicitud = isset($_POST['DescripcionSolicitud']) ? $_POST['DescripcionSolicitud'] : null;

    try {
        $conn->beginTransaction();

        // **Paso 1: Actualizar la solicitud en tblSolicitudes**
        $sqlUpdateSolicitud = "UPDATE `proceso.tblsolicitudes`
            SET CodigoPago = :CodigoPago,
                IdCategoria = :idCategoria,
                CodigoReferencia = :codigoReferencia,
                IdUniversidad = :idUniversidad,
                DescripcionSolicitud = :descripcionSolicitud,
                IdUsuario = :idUsuario,
                IdEstado = :idEstado
            WHERE IdSolicitud = :idSolicitud";

        $stmtUpdateSolicitud = $conn->prepare($sqlUpdateSolicitud);
        $stmtUpdateSolicitud->bindParam(':idCategoria', $idCategoria);
        $stmtUpdateSolicitud->bindParam(':CodigoPago', $codigoPago);
        $stmtUpdateSolicitud->bindParam(':codigoReferencia', $codigoReferencia);
        $stmtUpdateSolicitud->bindParam(':idUniversidad', $idUniversidad);
        $stmtUpdateSolicitud->bindParam(':descripcionSolicitud', $descripcionSolicitud);
        $stmtUpdateSolicitud->bindParam(':idUsuario', $idUsuario);
        $stmtUpdateSolicitud->bindParam(':idEstado', $idEstado);
        $stmtUpdateSolicitud->bindParam(':idSolicitud', $idSolicitud);

        if (!$stmtUpdateSolicitud->execute()) {
            throw new Exception("Error en UPDATE de proceso.tblSolicitudes");
        }

        // **Paso 2: Insertar o actualizar en tblDetallesCentros**
        $sqlCheck = "SELECT COUNT(*) FROM `proceso.tblDetallesCentros` WHERE IdSolicitud = :idSolicitud";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':idSolicitud', $idSolicitud);
        $stmtCheck->execute();
        $exists = $stmtCheck->fetchColumn();

        if ($exists > 0) {
            $sqlDetalles = "UPDATE `proceso.tblDetallesCentros`
                SET IdTiposolicitud = :idTiposolicitud,
                    NombreCentro = :nombreCentro,
                    IdTipoCentro = :idTipoCentro,
                    IdUniversidad = :idUniversidad,
                    IdDepartamento = :idDepartamento,
                    IdMunicipio = :idMunicipio
                WHERE IdSolicitud = :idSolicitud";
        } else {
            $sqlDetalles = "INSERT INTO `proceso.tblDetallesCentros` (
                IdSolicitud, IdTiposolicitud, NombreCentro, IdTipoCentro,
                IdUniversidad, IdDepartamento, IdMunicipio
            ) VALUES (
                :idSolicitud, :idTiposolicitud, :nombreCentro, :idTipoCentro,
                :idUniversidad, :idDepartamento, :idMunicipio
            )";
        }

        $stmtDetalles = $conn->prepare($sqlDetalles);
        $stmtDetalles->bindParam(':idSolicitud', $idSolicitud);
        $stmtDetalles->bindParam(':idTiposolicitud', $idTiposolicitud);
        $stmtDetalles->bindParam(':nombreCentro', $nombreCentro);
        $stmtDetalles->bindParam(':idTipoCentro', $idTipoCentro);
        $stmtDetalles->bindParam(':idUniversidad', $idUniversidad);
        $stmtDetalles->bindParam(':idDepartamento', $idDepartamento);
        $stmtDetalles->bindParam(':idMunicipio', $idMunicipio);

        if (!$stmtDetalles->execute()) {
            throw new Exception("Error en tblDetallesCentros");
        }

        // **Paso 3: Obtener el NombreCentro de proceso.tblDetallesCentros**
        $sqlGetNombreCentro = "SELECT NombreCentro FROM `proceso.tblDetallesCentros` WHERE IdSolicitud = :idSolicitud";
        $stmtGetNombreCentro = $conn->prepare($sqlGetNombreCentro);
        $stmtGetNombreCentro->bindParam(':idSolicitud', $idSolicitud);
        $stmtGetNombreCentro->execute();
        $nombreCentro = $stmtGetNombreCentro->fetchColumn();

        if (!$nombreCentro) {
            throw new Exception("Error: No se encontró NombreCentro para la solicitud proporcionada.");
        }

        // Asignar el valor obtenido a TipoCentro
        $tipoCentro = $nombreCentro;

        // Establecer valor predeterminado para IdVisibilidad
        $idVisibilidad = 2;


        // **Paso 3: Insertar o actualizar en mantenimiento.tbltiposcentros**
        $sqlCheck = "SELECT COUNT(*) FROM `mantenimiento.tbltiposcentros` WHERE IdSolicitud = :idSolicitud";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':idSolicitud', $idSolicitud);
        $stmtCheck->execute();
        $exists = $stmtCheck->fetchColumn();

        if ($exists > 0) {
            $sqlTiposCentros = "UPDATE `mantenimiento.tbltiposcentros`
                SET TipoCentro = :tipoCentro,
                    IdVisibilidad = :idVisibilidad
                WHERE IdSolicitud = :idSolicitud";
        } else {
            $sqlTiposCentros = "INSERT INTO `mantenimiento.tbltiposcentros` (
                TipoCentro, IdVisibilidad, IdSolicitud
            ) VALUES (
                :tipoCentro, :idVisibilidad, :idSolicitud
            )";
        }

        $stmtTiposCentros = $conn->prepare($sqlTiposCentros);
        $stmtTiposCentros->bindParam(':tipoCentro', $tipoCentro);
        $stmtTiposCentros->bindParam(':idVisibilidad', $idVisibilidad);
        $stmtTiposCentros->bindParam(':idSolicitud', $idSolicitud);

        if ($exists > 0) {
            $stmtTiposCentros->bindParam(':idVisibilidad', $idVisibilidad);
        }

        if (!$stmtTiposCentros->execute()) {
            throw new Exception("Error en mantenimiento.tbltiposcentros");
        }

        // Confirmar la transacción
        $conn->commit();
        echo "step2_success";
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error al guardar datos del paso 2: " . $e->getMessage();
    }
}

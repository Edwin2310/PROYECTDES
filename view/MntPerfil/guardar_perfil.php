<?php
require_once("../../config/conexion.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["IdUsuario"])) {
    header("Location: ../index.php");
    exit();
}

$redirect = $_POST['redirect'] ?? $_SERVER['HTTP_REFERER'] ?? '../home/index.php';

// Obtener los datos enviados desde el formulario
$usuarioId = $_SESSION["IdUsuario"];
$nombreUsuario = trim($_POST["nombreUsuario"] ?? '');
$contrasena = trim($_POST["contrasena"] ?? '');
$correoElectronico = trim($_POST["correoElectronico"] ?? '');
$numIdentidad = trim($_POST["numIdentidad"] ?? '');
$Usuario = trim($_POST["Usuario"] ?? '');
$direccion = trim($_POST["direccion"] ?? '');
$fotoPerfil = $_FILES['fotoPerfil'] ?? null;
$eliminarFoto = isset($_POST['eliminarFoto']) && $_POST['eliminarFoto'] === '1';

$conectar = new Conectar();
$conexion = $conectar->Conexion();

try {
    $conexion->beginTransaction();

    // Actualizar datos personales
    $sqlDatosPersonales = "UPDATE `seguridad.tbldatospersonales` 
                           SET NombreUsuario = ?, 
                               NumIdentidad = ?,
                               Usuario = ?, 
                               Direccion = ?
                           WHERE IdUsuario = ?";
    $stmtDatosPersonales = $conexion->prepare($sqlDatosPersonales);
    $stmtDatosPersonales->execute([$nombreUsuario, $numIdentidad, $Usuario,$direccion, $usuarioId]);

    // Actualizar correo electrónico
    $sqlUsuarios = "UPDATE `seguridad.tblusuarios` 
                    SET CorreoElectronico = ? 
                    WHERE IdUsuario = ?";
    $stmtUsuarios = $conexion->prepare($sqlUsuarios);
    $stmtUsuarios->execute([$correoElectronico, $usuarioId]);

    // Eliminar la foto de perfil si está marcado
    if ($eliminarFoto) {
        $sqlEliminarFoto = "UPDATE `seguridad.tbldatospersonales` 
                            SET FotoPerfil = NULL 
                            WHERE IdUsuario = ?";
        $stmtEliminarFoto = $conexion->prepare($sqlEliminarFoto);
        $stmtEliminarFoto->execute([$usuarioId]);
    } elseif ($fotoPerfil && $fotoPerfil['error'] === UPLOAD_ERR_OK) {
        // Procesar la foto de perfil si se ha subido
        $fileTmp = $fotoPerfil['tmp_name'];
        $infoImagen = getimagesize($fileTmp);
        $tipoImagen = $infoImagen['mime'];

        switch ($tipoImagen) {
            case 'image/jpeg':
                $imagenOriginal = imagecreatefromjpeg($fileTmp);
                break;
            case 'image/png':
                $imagenOriginal = imagecreatefrompng($fileTmp);
                break;
            case 'image/gif':
                $imagenOriginal = imagecreatefromgif($fileTmp);
                break;
            default:
                throw new Exception('Formato de imagen no soportado');
        }

        $anchoOriginal = imagesx($imagenOriginal);
        $altoOriginal = imagesy($imagenOriginal);

        $anchoDeseado = 300;
        $altoDeseado = ($altoOriginal / $anchoOriginal) * $anchoDeseado;

        $imagenReducida = imagecreatetruecolor($anchoDeseado, $altoDeseado);
        imagecopyresampled($imagenReducida, $imagenOriginal, 0, 0, 0, 0, $anchoDeseado, $altoDeseado, $anchoOriginal, $altoOriginal);

        ob_start();
        imagejpeg($imagenReducida, null, 75);
        $fotoBinaria = ob_get_clean();

        $sqlFoto = "UPDATE `seguridad.tbldatospersonales` 
                    SET FotoPerfil = ? 
                    WHERE IdUsuario = ?";
        $stmtFoto = $conexion->prepare($sqlFoto);
        $stmtFoto->execute([$fotoBinaria, $usuarioId]);
    }

    $conexion->commit();
    $_SESSION["NombreUsuario"] = $nombreUsuario;
    header("Location: $redirect?success=1");
    exit();
} catch (Exception $e) {
    $conexion->rollBack();
    error_log("Error al actualizar el perfil: " . $e->getMessage());
    header("Location: $redirect?error=2");
    exit();
}

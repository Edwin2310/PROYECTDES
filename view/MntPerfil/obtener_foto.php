<?php
require_once("../../config/conexion.php");

// Validar que se proporcione un ID de usuario
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    exit('ID de usuario no válido.');
}

$idUsuario = intval($_GET['id']);
$conectar = new Conectar();
$conexion = $conectar->Conexion();

// Ruta de la imagen predeterminada
define('DEFAULT_IMAGE_PATH', '../../public/assets/img/avatars/avatar15.jpg');

try {
    // Consultar la foto del usuario
    $sqlFoto = "SELECT FotoPerfil FROM `seguridad.tbldatospersonales` WHERE IdUsuario = ?";
    $stmtFoto = $conexion->prepare($sqlFoto);
    $stmtFoto->execute([$idUsuario]);
    $foto = $stmtFoto->fetchColumn();

    // Si la foto existe, mostrarla
    if ($foto) {
        // Establecer el encabezado correcto
        header("Content-Type: image/jpeg"); // Puedes ajustar dinámicamente según el tipo MIME
        echo $foto;
    } else {
        // Mostrar imagen predeterminada si no hay foto
        header("Content-Type: image/png");
        echo file_get_contents(DEFAULT_IMAGE_PATH);
    }
} catch (Exception $e) {
    // Manejo de errores
    http_response_code(500);
    echo "Error al obtener la foto: " . $e->getMessage();
}

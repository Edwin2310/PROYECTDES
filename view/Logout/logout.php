<?php

require_once("../../config/conexion.php");
session_start();
require_once(__DIR__ . '/../Seguridad/Bitacora/Funciones_Bitacoras.php');

// Obtener los valores necesarios para la verificación
$id_usuario = $_SESSION['IdUsuario'] ?? null;
$id_objeto = 49; // ID del objeto o módulo correspondiente a esta página

// Obtener la página actual y la última marca de acceso
$current_page = basename($_SERVER['PHP_SELF']);
$last_access_time = $_SESSION['last_access_time'][$current_page] ?? 0;

// Obtener el tiempo actual
$current_time = time();

// Verificar si han pasado al menos 10 segundos desde el último registro
if ($current_time - $last_access_time > 3) {
    $accion = "Cerro sesión.";
    // Registrar en la bitácora
    registrobitaevent($id_usuario, $id_objeto, $accion);
}

// Actualizar la marca temporal en la sesión
$_SESSION['last_access_time'][$current_page] = $current_time;

session_destroy();
header("Location:".Conectar::ruta()."index.php");
exit();

<?php
session_start(); // Iniciar la sesión al inicio del script

// Verificar el User-Agent del navegador
$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
if (strpos($user_agent, 'firefox') !== false) {
    // Redirigir a la página de error si el navegador es Firefox
    header("Location: pagina_no_soportada.html");
    exit();
}

// Incluir la conexión a la base de datos y la clase Usuario
require_once("config/conexion.php");
require_once("models/Usuario.php");

$usuario = new Usuario();

// Procesar el formulario de inicio de sesión cuando se envía
if (isset($_POST["enviar"]) && $_POST["enviar"] == "si") {
    $usuario->login();
}
?>
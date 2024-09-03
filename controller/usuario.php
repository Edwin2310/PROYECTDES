<?php

require_once ("../config/conexion.php");
require_once ("../models/Usuario.php");

$usuario = new Usuario();

// Controlador de las operaciones a hacer dependiendo de lo que quiera hacer el usuario de soporte
switch ($_GET["op"]) {
    
    // Nueva operación para verificar si el correo existe
    case "correo":
        $correo = $_POST["correo"];
        $datos = $usuario->get_correo_usuario($correo);

        if (empty($datos)) {
            echo json_encode(["status" => "error", "message" => "Usuario no Registrado"]);
        } else {
            // Verificar el estado del usuario
            if ($datos[0]["ESTADO_USUARIO"] == 2) {
                echo json_encode([
                    "status" => "error", 
                    "message" => "El usuario se encuentra inactivo por lo que no tiene derecho a recuperación de contraseña. Si considera que esto es un error, contacte con soporte."
                ]);
            } else {
                echo json_encode(["status" => "success", "message" => "Usuario encontrado"]);
            }
        }
        break;
}
?>

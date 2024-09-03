<?php
    require_once("../config/conexion.php");
    require_once("../models/Email.php");
    $email = new Email();

    switch($_GET["op"]){

        case "send_recuperar":
            $email->recuperar($_POST["correo"]);
        break;
    }
?>
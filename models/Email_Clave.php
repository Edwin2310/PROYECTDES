<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../phpMailer/Exception.php';
require __DIR__ . '/../phpMailer/PHPMailer.php';
require __DIR__ . '/../phpMailer/SMTP.php';

function enviarCorreo($correo, $nombre_usuario, $contrasena)
{
    $mail = new PHPMailer(true);
    try {
        // Configurar PHPMailer
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'sigaesunah@gmail.com';
        $mail->Password = 'vpuvvwtfkrizepvl';
        $mail->SMTPSecure = 'tls';
        $mail->From = 'sigaesunah@gmail.com';
        $mail->FromName = 'Creación De Contraseña';
        $mail->CharSet = 'UTF-8';
        $mail->addAddress($correo);
        $mail->WordWrap = 50;
        $mail->isHTML(true);
        $mail->Subject = "Confirme Su Cuenta";

        // Adjuntar imágenes
        $mail->addEmbeddedImage(__DIR__ . '/template/images/image-1.gif', 'image_gif'); // ruta 
        $mail->addEmbeddedImage(__DIR__ . '/template/images/image-2.png', 'image_2');
        $mail->addEmbeddedImage(__DIR__ . '/template/images/image-3.png', 'image_3');
        $mail->addEmbeddedImage(__DIR__ . '/template/images/image-4.png', 'image_4');
        $mail->addEmbeddedImage(__DIR__ . '/template/images/image-5.png', 'image_5');
        $mail->addEmbeddedImage(__DIR__ . '/template/images/image-6.png', 'image_6');
        $mail->addEmbeddedImage(__DIR__ . '/template/images/image-7.png', 'image_7');
        $mail->addEmbeddedImage(__DIR__ . '/template/images/image-8.png', 'image_8'); // ruta 

        

        // Leer la plantilla HTML del correo
        $templatePath = __DIR__ . '/template/ConTemporal.html';
        $template = file_get_contents($templatePath); //  ruta 

        // Reemplazar placeholders en la plantilla
        $template = str_replace("{{nombre_usuario}}", $nombre_usuario, $template);
        $template = str_replace("{{contrasena}}", $contrasena, $template);
        $template = str_replace("{{link}}", "http://localhost/PROYECTDES/habilitarUsuario/nuevaClave.php", $template);

        $mail->Body = $template;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

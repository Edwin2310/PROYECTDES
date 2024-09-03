<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '../../../../phpMailer/Exception.php';
require __DIR__ . '../../../../phpMailer/PHPMailer.php';
require __DIR__ . '../../../../phpMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén los valores del formulario
    $nombreCompleto = $_POST['NOMBRE_COMPLETO'];
    $correo = $_POST['EMAIL'];


    function enviarCorreo($correo, $nombre_usuario)
    {
        $mail = new PHPMailer(true);
        try {
            // Configurar PHPMailer
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = 'sigaesunah@gmail.com'; // Tu dirección de correo electrónico
            $mail->Password = 'vpuvvwtfkrizepvl'; // Tu contraseña de correo electrónico
            $mail->SMTPSecure = 'tls';
            $mail->From = 'sigaesunah@gmail.com';
            $mail->FromName = 'Dirección de Educación Superior';
            $mail->CharSet = 'UTF-8';
            $mail->addAddress($correo);
            $mail->WordWrap = 50;
            $mail->isHTML(true);
            $mail->Subject = "Confirmación de Recepción de Solicitud";

            // Establecer la zona horaria
            date_default_timezone_set('America/Tegucigalpa');

            // Fecha y hora actuales
            $fecha = date('d-m-Y');
            $hora = date('H:i');

            // Contenido del correo
            $mail->Body = "<p>Estimado/a <b>$nombre_usuario</b> de Universidad,</p>
                       <p>Hemos recibido su solicitud dirigida a la Dirección de Educación Superior en $fecha a las $hora. Queremos confirmarle que su solicitud está en proceso de revisión y evaluación por nuestro equipo.</p>
                       <p>Nos comprometemos a trabajar diligentemente para darle respuesta en el menor tiempo posible. Le solicitamos estar atento a su correo electrónico para futuras actualizaciones o cualquier notificación adicional que pueda requerir.</p>
                       <p>Agradecemos su paciencia y comprensión.</p>
                       <b><p>Atentamente,<br>
                       Dirección de Educación Superior<br>
                       Sistema de Gestión Académica en la Educación Superior</b></p>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    // Envía el correo
    if (enviarCorreo($correo, $nombreCompleto)) {
        // echo 'Correo enviado con éxito';
    } else {
        //echo 'No se pudo enviar el correo.';
    }
} else {
    echo 'No se recibieron datos.';
}

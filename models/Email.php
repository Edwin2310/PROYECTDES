<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../phpMailer/Exception.php';
require __DIR__ . '/../phpMailer/PHPMailer.php';
require __DIR__ . '/../phpMailer/SMTP.php';
require_once("../config/conexion.php");
require_once("../Models/Usuario.php");

class Email extends PHPMailer {
    
    public function recuperar($correo){
        // Obtener datos del usuario
        $usuario = new Usuario();
        $datos = $usuario->get_correo_usuario($correo);
        
        if (!$datos) {
            return false; // Si no se encuentra el correo en la base de datos
        }
        
        $nom = $datos[0]["NOMBRE_USUARIO"]; // Suponiendo que obtienes el nombre del primer registro

        $token = bin2hex(random_bytes(16)); // Generar un token más seguro y único
        $codigoValidacion = rand(100000, 999999); // Código de 6 dígitos
        
        // Calcular la fecha de vencimiento: fecha actual + 30 minutos
        $fechaVencimiento = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        // Configurar PHPMailer
        $this->isSMTP();
        $this->Host = 'smtp.gmail.com';
        $this->Port = 587;
        $this->SMTPAuth = true;
        $this->Username = 'sigaesunah@gmail.com';
        $this->Password = 'vpuvvwtfkrizepvl';
        $this->SMTPSecure = 'tls';
        $this->setFrom('sigaesunah@gmail.com', 'Sistema de Gestión SIGAES');
        $this->addAddress($correo);
        $this->Subject = "Recuperar Contraseña";
        $this->CharSet = 'UTF-8';
        $this->isHTML(true);

        // Contenido del correo en formato HTML
        $cuerpo = "
        <html>
        <head>
            <title>Recuperar Contraseña</title>
        </head>
        <body>
            <h2>Recuperación de Contraseña</h2>
            <p>Hola $nom,</p>
            <p>Recibiste este correo porque solicitaste restablecer tu contraseña.</p>
            <p>Tu código de validación es: <strong>$codigoValidacion</strong></p>
            <p>Por favor, haz clic en el siguiente enlace para verificar tu código de validación:</p>
            <p><a href='http://localhost/PROYECTDES/recuperarcontraseña/verificarCodigo.php?token=$token'>Verificar Código</a></p>
            <p>Si no solicitaste este cambio, ignora este mensaje.</p>
        </body>
        </html>
        ";

        $this->Body = $cuerpo;

        // Enviar correo electrónico
        if ($this->send()) {
            // Si el correo se envió correctamente, guardar el token, código de validación y fecha de vencimiento en la base de datos
            try {
                $conn = new Conectar();
                $conexion = $conn->Conexion();

                // Preparar y ejecutar la inserción en la tabla tbl_reset_contraseña
                $stmt = $conexion->prepare("INSERT INTO `seguridad.tblresetcontraseñas` (CorreoElectronico, Token, CodigoValidacion, FechaVencimiento) VALUES (:correo, :token, :codigoValidacion, :fechaVencimiento)");
                $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt->bindParam(':codigoValidacion', $codigoValidacion, PDO::PARAM_INT);
                $stmt->bindParam(':fechaVencimiento', $fechaVencimiento, PDO::PARAM_STR);
                
                if ($stmt->execute()) {
                    // Éxito en la inserción
                    return true;
                } else {
                    // Error al insertar en la base de datos
                    error_log("Error al insertar en tbl_reset_contraseña: " . $stmt->errorInfo()[2]);
                    return false;
                }
            } catch (Exception $e) {
                // Error en la conexión o ejecución SQL
                error_log("Error en la conexión o ejecución SQL al insertar en tbl_reset_contraseña: " . $e->getMessage());
                return false;
            } finally {
                // Cerrar conexión y liberar recursos
                $stmt->closeCursor();
                $conexion = null;
            }
        } else {
            // Error al enviar el correo electrónico
            error_log("Error al enviar correo electrónico de recuperación a $correo");
            return false;
        }
    }
}
?>

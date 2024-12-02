
<?php
/* require_once("../../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numeroSesion = $_POST['numeroSesion'];
    $selectedSolicitudes = $_POST['selectedSolicitudes'];

    $solicitudes = explode(',', $selectedSolicitudes);

    $conectar = new Conectar();
    $dbh = $conectar->Conexion();

    try {
        $dbh->beginTransaction();

        foreach ($solicitudes as $idSolicitud) {
            $sql = "INSERT INTO `proceso.tblsesionesctc` (SESION, IdSolicitud) VALUES (:numeroSesion, :idSolicitud)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':numeroSesion', $numeroSesion);
            $stmt->bindParam(':idSolicitud', $idSolicitud);
            $stmt->execute();
        }

        $dbh->commit();
        echo "Sesión asignada correctamente a las solicitudes seleccionadas.";
    } catch (Exception $e) {
        $dbh->rollBack();
        echo "Error al asignar la sesión: " . $e->getMessage();
    }
} */


require_once("../../config/conexion.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../phpMailer/Exception.php';
require __DIR__ . '/../../phpMailer/PHPMailer.php';
require __DIR__ . '/../../phpMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numeroSesion = $_POST['numeroSesion'];
    $selectedSolicitudes = $_POST['selectedSolicitudes'];

    $solicitudes = explode(',', $selectedSolicitudes);

    $conectar = new Conectar();
    $dbh = $conectar->Conexion();

    try {
        $dbh->beginTransaction();

        // Insertar en `proceso.tblsesionesctc`
        foreach ($solicitudes as $idSolicitud) {
            $sql = "INSERT INTO `proceso.tblsesionesctc` (SesionCTC, IdSolicitud) VALUES (:numeroSesion, :idSolicitud)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':numeroSesion', $numeroSesion);
            $stmt->bindParam(':idSolicitud', $idSolicitud);
            $stmt->execute();

            // Actualizar estado en `proceso.tblsolicitudes`
            $updateSql = "UPDATE `proceso.tblsolicitudes` SET IdEstado = 7 WHERE IdSolicitud = :idSolicitud";
            $updateStmt = $dbh->prepare($updateSql);
            $updateStmt->bindParam(':idSolicitud', $idSolicitud);
            $updateStmt->execute();

            // Obtener el correo del usuario que hizo la solicitud
            $userSql = "SELECT CorreoElectronico FROM `proceso.tblsolicitudes` WHERE IdSolicitud = :idSolicitud";
            $userStmt = $dbh->prepare($userSql);
            $userStmt->bindParam(':idSolicitud', $idSolicitud);
            $userStmt->execute();
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            $userEmail = $user['CorreoElectronico '];

            // Enviar correo
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->Username = 'sigaesunah@gmail.com';
            $mail->Password = 'vpuvvwtfkrizepvl';
            $mail->SetFrom('no-reply@example.com', 'Sistema');
            $mail->AddAddress($userEmail);
            $mail->Subject = 'Solicitud Agendada';
            $mail->Body = 'Su solicitud ha sido agendada al CTC.';

            if (!$mail->Send()) {
                echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
            }
        }


        $dbh->commit();
        echo "Sesión asignada correctamente a las solicitudes seleccionadas y el estado actualizado.";
    } catch (Exception $e) {
        $dbh->rollBack();
        echo "Error al asignar la sesión: " . $e->getMessage();
    }
}



?>

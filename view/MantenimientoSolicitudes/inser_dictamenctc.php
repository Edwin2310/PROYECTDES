

<?php
session_start();
include("../../config/conexion.php");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../phpMailer/Exception.php';
require __DIR__ . '/../../phpMailer/PHPMailer.php';
require __DIR__ . '/../../phpMailer/SMTP.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_sesion = $_POST['idSesion'];
    $dictamen = $_POST['dictamen'];
    $estado = $_POST['estado'];
    $centro = $_POST['centro'];
    $observaciones = $_POST['observaciones'];
    $id_usuario = $_POST['id_usuario']; // Campo oculto para el ID del usuario

    // Validar los datos
    if (empty($id_sesion) || empty($dictamen) || empty($estado) || empty($centro) || empty($observaciones)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    // Conectar a la base de datos
    $conexion = new Conectar();
    $conn = $conexion->Conexion();
    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Error en la conexión a la base de datos.']);
        exit;
    }

    // Insertar los datos en la tabla
    $sql = "INSERT INTO `documentos.tbldictamenesctc` (IdSesionCtc , NumDictamenCTC, IdEstadoDictamen, 	ObervacionesDictamen, 	IdUniversidad )
            VALUES (:id_sesion, :dictamen, :estado, :observaciones, :centro)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':id_sesion', $id_sesion);
    $stmt->bindParam(':dictamen', $dictamen);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':observaciones', $observaciones);
    $stmt->bindParam(':centro', $centro);

    if ($stmt->execute()) {
        // Obtener el correo del usuario que hizo la solicitud
        $userSql = "SELECT CorreoElectronico FROM `proceso.tblsolicitudes` WHERE IdSolicitud = (SELECT IdSolicitud FROM `proceso.tblsesionesctc` WHERE IdSesioCtc = :id_sesion)";
        $userStmt = $conn->prepare($userSql);
        $userStmt->bindParam(':id_sesion', $id_sesion);
        $userStmt->execute();
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);
        $userEmail = $user['CorreoElectronico'];

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
        $mail->Subject = 'Dictamen Emitido';
        $mail->Body = 'Su solicitud ha sido emitida con un dictamen CTC.';

        if (!$mail->Send()) {
            echo json_encode(['success' => false, 'message' => 'Error al enviar el correo: ' . $mail->ErrorInfo]);
            exit;
        }
        echo json_encode(['success' => true, 'message' => 'Datos insertados correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al insertar los datos.']);
    }

    $conn = null;
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>

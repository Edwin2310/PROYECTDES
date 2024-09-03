<?php
// Incluir el archivo de conexión a la base de datos
require_once("../config/conexion.php");

// Habilitar la depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el token y el código de validación del formulario
    $token = $_POST['token'];
    $codigo = $_POST['codigo'];

    // Verificar que el campo del código no esté vacío
    if (empty($codigo)) {
        echo json_encode(['status' => 'error', 'message' => 'El campo del código está vacío.']);
        exit();
    }

    try {
        // Crear una conexión a la base de datos
        $conn = new Conectar();
        $conexion = $conn->Conexion();

        // Preparar la consulta para verificar el token y el código de validación
        $stmt = $conexion->prepare("SELECT CORREO_ELECTRONICO, FECHA_VENCIMIENTO FROM tbl_reset_contraseña WHERE TOKEN = :token AND CODIGO_VALIDACION = :codigo");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR); // Asumiendo que CODIGO_VALIDACION es un string
        $stmt->execute();

        // Verificar si se encontró una única fila coincidente
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $correo = $row['CORREO_ELECTRONICO'];
            $fechaExpiracion = $row['FECHA_VENCIMIENTO'];

            // Verificar si el código ha expirado
            $currentDateTime = new DateTime();
            $expirationDateTime = new DateTime($fechaExpiracion);

            if ($currentDateTime > $expirationDateTime) {
                echo json_encode(['status' => 'error', 'message' => 'El código ha expirado.']);
            } else {
                echo json_encode(['status' => 'success', 'message' => 'Código validado correctamente.', 'correo' => $correo]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'El código es incorrecto.']);
        }

        $stmt->closeCursor();
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al intentar verificar el código: ' . $e->getMessage()]);
    }
}
?>

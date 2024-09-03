<?php
session_start();
date_default_timezone_set('America/Tegucigalpa'); // Configurar la zona horaria según tu ubicación
require_once("../config/conexion.php");
require_once("../models/Email_Clave.php"); // Incluir el archivo donde está definida la función enviarCorreo

// Función para generar el código de validación
function generateValidationCode($length = 6)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&';
    $charactersLength = strlen($characters);
    $randomCode = '';
    for ($i = 0; $i < $length; $i++) {
        $randomCode .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomCode;
}

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['codigo_validacion'])) {
        $codigo_validacion = $_POST['codigo_validacion'];

        // Generar una clave temporal utilizando la función
        $clave_temporal = generateValidationCode(6); // Genera una clave de 6 caracteres

        // Establecer conexión con la base de datos
        $conn = new Conectar();
        $conexion = $conn->Conexion();

        if (!$conexion) {
            $response['message'] = 'Error al conectar a la base de datos.';
            echo json_encode($response);
            exit;
        }

        $conn->set_names();

        try {
            // Obtener el valor del parámetro Max_Clave_Validacion
            $sql_param = "SELECT VALOR FROM tbl_ms_parametros WHERE PARAMETRO = 'Max_Clave_Validacion'";
            $stmt_param = $conexion->prepare($sql_param);
            $stmt_param->execute();
            $result_param = $stmt_param->fetch(PDO::FETCH_ASSOC);

            if ($result_param) {
                $dias_validacion = (int) $result_param['VALOR'];
            } else {
                $dias_validacion = 1; // Valor por defecto si no se encuentra el parámetro
            }

            // Calcular la fecha de vencimiento usando el valor del parámetro
            $fecha_vencimiento = (new DateTime())->add(new DateInterval("P{$dias_validacion}D"))->format('Y-m-d H:i:s');

            // Actualizar la tabla reset_contraseña con la clave temporal y la fecha de vencimiento
            $update_stmt = $conexion->prepare("UPDATE tbl_reset_contraseña SET contrasena_temp = ?, FECHA_VENCIMIENTO = ? WHERE codigo_validacion = ?");
            $update_stmt->execute([$clave_temporal, $fecha_vencimiento, $codigo_validacion]);

            if ($update_stmt->rowCount() > 0) {
                // Obtener el correo electrónico del usuario desde la tabla reset_contraseña
                $stmt_correo = $conexion->prepare("SELECT r.correo_electronico, u.nombre_usuario
                                                   FROM tbl_reset_contraseña r
                                                   INNER JOIN tbl_ms_usuario u ON r.correo_electronico = u.correo_electronico
                                                   WHERE r.codigo_validacion = ?");
                $stmt_correo->execute([$codigo_validacion]);
                $resultado_correo = $stmt_correo->fetch(PDO::FETCH_ASSOC);

                if ($resultado_correo && isset($resultado_correo['correo_electronico']) && isset($resultado_correo['nombre_usuario'])) {
                    $correo_usuario = $resultado_correo['correo_electronico'];
                    $nombre_usuario = $resultado_correo['nombre_usuario'];

                    // Envío de correo electrónico con la clave temporal
                    if (enviarCorreo($correo_usuario, $nombre_usuario, $clave_temporal)) {
                        $response['success'] = true;
                        $response['message'] = 'Correo electrónico enviado correctamente.';
                    } else {
                        $response['message'] = 'Error al enviar el correo electrónico.';
                    }
                } else {
                    $response['message'] = 'No se encontró el correo electrónico asociado al código de validación.';
                }
            } else {
                $response['message'] = 'No se encontró el código de validación en la base de datos.';
            }
        } catch (Exception $e) {
            $response['message'] = 'Excepción capturada: ' . $e->getMessage();
        }

        // Finalizar script con respuesta en formato JSON
        echo json_encode($response);
        exit;
    } else {
        $response['message'] = 'Por favor, ingrese el código.';
        echo json_encode($response);
        exit;
    }
} else {
    $response['message'] = 'Método de solicitud no válido.';
    echo json_encode($response);
    exit;
}

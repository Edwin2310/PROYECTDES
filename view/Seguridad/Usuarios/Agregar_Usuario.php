<?php
session_start();
date_default_timezone_set('America/Tegucigalpa');
include("../../../config/conexion.php");
include("../../../models/Email_Codigo.php");

$response = array('success' => false, 'message' => '');

function generateValidationCode($length = 6)
{
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomCode = '';
    for ($i = 0; $length > $i; $i++) {
        $randomCode .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomCode;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $num_identidad = $_POST['num_identidad'];
    $direccion_1 = $_POST['direccion_1'];
    $usuario = $_POST['usuario'];
    $correo_electronico = $_POST['correo_electronico'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $num_empleado = $_POST['num_empleado'];
    $empleado_des = $_POST['empleado_des'];
    $id_rol = $_POST['id_rol'];
    $id_universidad = $_POST['id_universidad'] ?? '1'; // Valor predeterminado
    $creado_por = $_SESSION['IdUsuario'];

    // Validación
    if (strlen($num_identidad) !== 13) {
        $response['message'] = 'El número de identidad debe tener 13 dígitos.';
        echo json_encode($response);
        exit;
    }

    // Verificar que 'Empleado DES' sea 'Sí' y ajustar 'Universidad'
    if ($empleado_des === 'Sí') {
        $id_universidad = '1'; // Ajusta este valor según cómo lo manejas en tu base de datos
    }

    // Conectar a la base de datos
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    try {
        // Verificar si el número de identidad ya existe
        $sql_check_identidad = "SELECT COUNT(*) as count FROM tbl_ms_usuario WHERE num_identidad = :num_identidad";
        $stmt_check_identidad = $conn->prepare($sql_check_identidad);
        $stmt_check_identidad->bindParam(':num_identidad', $num_identidad);
        $stmt_check_identidad->execute();
        $result_check_identidad = $stmt_check_identidad->fetch(PDO::FETCH_ASSOC);

        if ($result_check_identidad['count'] > 0) {
            $response['message'] = 'El número de identidad ya está en uso.';
        } else {
            // Verificar si el usuario ya existe
            $sql_check_usuario = "SELECT COUNT(*) as count FROM tbl_ms_usuario WHERE usuario = :usuario";
            $stmt_check_usuario = $conn->prepare($sql_check_usuario);
            $stmt_check_usuario->bindParam(':usuario', $usuario);
            $stmt_check_usuario->execute();
            $result_check_usuario = $stmt_check_usuario->fetch(PDO::FETCH_ASSOC);

            if ($result_check_usuario['count'] > 0) {
                $response['message'] = 'El nombre de usuario ya está en uso.';
            } else {
                // Verificar si el correo electrónico ya existe
                $sql_check_email = "SELECT COUNT(*) as count FROM tbl_ms_usuario WHERE correo_electronico = :correo_electronico";
                $stmt_check_email = $conn->prepare($sql_check_email);
                $stmt_check_email->bindParam(':correo_electronico', $correo_electronico);
                $stmt_check_email->execute();
                $result_check_email = $stmt_check_email->fetch(PDO::FETCH_ASSOC);

                if ($result_check_email['count'] > 0) {
                    $response['message'] = 'El correo electrónico ya está en uso.';
                } else {
                    // Obtener el valor del parámetro Max_Cod_Validacion
                    $sql_param = "SELECT VALOR FROM tbl_ms_parametros WHERE PARAMETRO = 'Max_Cod_Validacion'";
                    $stmt_param = $conn->prepare($sql_param);
                    $stmt_param->execute();
                    $result_param = $stmt_param->fetch(PDO::FETCH_ASSOC);

                    if ($result_param) {
                        $dias_validacion = (int) $result_param['VALOR'];
                    } else {
                        $dias_validacion = 1; // Valor por defecto
                    }

                    // Generar un código de validación
                    $codigo_validacion = generateValidationCode();

                    // Insertar el nuevo usuario
                    $sql = "INSERT INTO tbl_ms_usuario (num_identidad, direccion_1, usuario, correo_electronico, nombre_usuario, num_empleado, empleado_des, contrasena, id_rol, id_universidad, creado_por, estado_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 4)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$num_identidad, $direccion_1, $usuario, $correo_electronico, $nombre_usuario, $num_empleado, $empleado_des, password_hash($codigo_validacion, PASSWORD_DEFAULT), $id_rol, $id_universidad, $creado_por]);

                    if ($stmt->rowCount() > 0) {
                        // Calcular la fecha de vencimiento
                        $fecha_vencimiento = (new DateTime())->add(new DateInterval("P{$dias_validacion}D"))->format('Y-m-d H:i:s');

                        // Guardar el código de validación
                        $sql_temp = "INSERT INTO tbl_reset_contraseña (correo_electronico, codigo_validacion, token, FECHA_VENCIMIENTO) VALUES (?, ?, ?, ?)";
                        $stmt_temp = $conn->prepare($sql_temp);
                        $token = bin2hex(random_bytes(16));
                        $stmt_temp->execute([$correo_electronico, $codigo_validacion, $token, $fecha_vencimiento]);


                        // Enviar el correo electrónico
                        if (sendEmail($correo_electronico, $nombre_usuario, $codigo_validacion)) {
                            $response['success'] = true;
                            $response['message'] = 'Usuario agregado y correo enviado exitosamente.';
                        } else {
                            $response['message'] = 'Usuario agregado, pero no se pudo enviar el correo.';
                        }
                    } else {
                        $response['message'] = 'Error al agregar el usuario.';
                    }
                }
            }
        }
    } catch (Exception $e) {
        $response['message'] = 'Excepción capturada: ' . $e->getMessage();
    }

    // Cerrar la conexión
    $conn = null;

    echo json_encode($response);
}

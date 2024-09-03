<?php
session_start();
date_default_timezone_set('America/Tegucigalpa');
include("../config/conexion.php");
require_once("../models/Email_Codigo.php");

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

// Función para eliminar registros expirados
function eliminarRegistrosExpirados($conn)
{
    $currentDate = date('Y-m-d H:i:s');
    $delete_stmt = $conn->prepare("DELETE FROM tbl_reset_contraseña WHERE FECHA_VENCIMIENTO < ?");
    $delete_stmt->execute([$currentDate]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_identidad = $_POST['num_identidad'];
    $direccion_1 = $_POST['direccion_1'];
    $usuario = $_POST['usuario'];
    $correo_electronico = $_POST['correo_electronico'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $id_rol = $_POST['id_rol'];
    $id_universidad = $_POST['id_universidad'];
    $creado_por = $_POST['id_rol'];



    // Conectar a la base de datos
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    try {
        // Verificar si el usuario ya existe
        $sql_verificar_usuario = "SELECT usuario FROM tbl_ms_usuario WHERE usuario = ?";
        $stmt_verificar_usuario = $conn->prepare($sql_verificar_usuario);
        $stmt_verificar_usuario->execute([$usuario]);

        if ($stmt_verificar_usuario->rowCount() > 0) {
            // Usuario ya existe, enviar respuesta al frontend
            $response['message'] = 'Error, El usuario ya está registrado.';
        } else {
            // Verificar si el número de identidad ya está en uso
            $sql_verificar_identidad = "SELECT num_identidad FROM tbl_ms_usuario WHERE num_identidad = ?";
            $stmt_verificar_identidad = $conn->prepare($sql_verificar_identidad);
            $stmt_verificar_identidad->execute([$num_identidad]);

            if ($stmt_verificar_identidad->rowCount() > 0) {
                // Número de identidad ya en uso, enviar respuesta al frontend
                $response['message'] = 'Error, El número de identidad ya está registrado.';
            } else {
                // Verificar si el correo electrónico ya está en uso
                $sql_verificar_correo = "SELECT correo_electronico FROM tbl_ms_usuario WHERE correo_electronico = ?";
                $stmt_verificar_correo = $conn->prepare($sql_verificar_correo);
                $stmt_verificar_correo->execute([$correo_electronico]);

                if ($stmt_verificar_correo->rowCount() > 0) {
                    // Correo electrónico ya en uso, enviar respuesta al frontend
                    $response['message'] = 'Error, El correo electrónico ya está registrado.';
                } else {

                    // Obtener el valor del parámetro Max_Cod_Validacion
                    $sql_param = "SELECT VALOR FROM tbl_ms_parametros WHERE PARAMETRO = 'Max_Cod_Validacion'";
                    $stmt_param = $conn->prepare($sql_param);
                    $stmt_param->execute();
                    $result_param = $stmt_param->fetch(PDO::FETCH_ASSOC);

                    if ($result_param) {
                        $dias_validacion = (int) $result_param['VALOR'];
                    } else {
                        $dias_validacion = 1; // Valor por defecto si no se encuentra el parámetro
                    }

                    // Generar una contraseña aleatoria
                    $codigo_validacion = generateValidationCode();

                    // Insertar el nuevo usuario en la base de datos
                    $sql_insertar_usuario = "INSERT INTO tbl_ms_usuario (num_identidad, direccion_1, usuario, correo_electronico, nombre_usuario, contrasena, id_rol, id_universidad, creado_por, estado_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 4)";
                    $stmt_insertar_usuario = $conn->prepare($sql_insertar_usuario);
                    $stmt_insertar_usuario->execute([$num_identidad, $direccion_1, $usuario, $correo_electronico, $nombre_usuario, password_hash($codigo_validacion, PASSWORD_DEFAULT), $id_rol, $id_universidad, $creado_por]);

                    if ($stmt_insertar_usuario->rowCount() > 0) {
                        // Calcular la fecha de vencimiento (3 días a partir de ahora)
                        $fecha_vencimiento = (new DateTime())->add(new DateInterval("P{$dias_validacion}D"))->format('Y-m-d H:i:s');

                        // Guardar la contraseña generada en una tabla temporal
                        $sql_guardar_contrasena = "INSERT INTO tbl_reset_contraseña (correo_electronico, codigo_validacion, token, FECHA_VENCIMIENTO) VALUES (?, ?, ?, ?)";
                        $stmt_guardar_contrasena = $conn->prepare($sql_guardar_contrasena);
                        $token = bin2hex(random_bytes(16)); // Generar un token aleatorio
                        $stmt_guardar_contrasena->execute([$correo_electronico, $codigo_validacion, $token, $fecha_vencimiento]);

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

    // Eliminar registros expirados
    eliminarRegistrosExpirados($conn);

    // Cerrar la conexión
    $conn = null;

    echo json_encode($response);
}

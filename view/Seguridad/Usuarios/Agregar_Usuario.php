<?php
session_start();
date_default_timezone_set('America/Tegucigalpa');
include("../../../config/conexion.php");
include("../../../models/Email_Codigo.php");

$response = array('success' => false, 'message' => '');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $NumIdentidad = $_POST['NumIdentidad'];
    $Direccion = $_POST['Direccion'];
    $Usuario = $_POST['Usuario'];
    $CorreoElectronico = $_POST['CorreoElectronico'];
    $NombreUsuario = $_POST['NombreUsuario'];
    $NumEmpleado = $_POST['NumEmpleado'];
    $EmpleadoDes = $_POST['EmpleadoDes'];
    $IdRol = $_POST['IdRol'];
    $IdUniversidad = $_POST['IdUniversidad'] ?? '1'; // Valor predeterminado
    $creado_por = $_SESSION['IdUsuario'];


    // Validación
    if (strlen($NumIdentidad) !== 13) {
        $response['message'] = 'El número de identidad debe tener 13 dígitos.';
        echo json_encode($response);
        exit;
    }

    // Verificar que 'Empleado DES' sea 'Sí' y ajustar 'Universidad'
    if ($EmpleadoDes === 'Sí') {
        $IdUniversidad = '1'; // Ajusta este Valor según cómo lo manejas en tu base de datos
    }

    // Conectar a la base de datos
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    try {
        // Verificar si el número de identidad ya existe
        $sql_check_identidad = "SELECT COUNT(*) as count FROM `seguridad.tbldatospersonales` WHERE NumIdentidad = :NumIdentidad";
        $stmt_check_identidad = $conn->prepare($sql_check_identidad);
        $stmt_check_identidad->bindParam(':NumIdentidad', $NumIdentidad);
        $stmt_check_identidad->execute();
        $result_check_identidad = $stmt_check_identidad->fetch(PDO::FETCH_ASSOC);

        if ($result_check_identidad['count'] > 0) {
            $response['message'] = 'El número de identidad ya está en uso.';
        } else {
            // Verificar si el usuario ya existe
            $sql_check_usuario = "SELECT COUNT(*) as count FROM `seguridad.tbldatospersonales` WHERE Usuario = :Usuario";
            $stmt_check_usuario = $conn->prepare($sql_check_usuario);
            $stmt_check_usuario->bindParam(':Usuario', $Usuario);
            $stmt_check_usuario->execute();
            $result_check_usuario = $stmt_check_usuario->fetch(PDO::FETCH_ASSOC);

            if ($result_check_usuario['count'] > 0) {
                $response['message'] = 'El nombre de usuario ya está en uso.';
            } else {
                // Verificar si el correo electrónico ya existe
                $sql_check_email = "SELECT COUNT(*) as count FROM `seguridad.tblusuarios` WHERE CorreoElectronico = :CorreoElectronico";
                $stmt_check_email = $conn->prepare($sql_check_email);
                $stmt_check_email->bindParam(':CorreoElectronico', $CorreoElectronico);
                $stmt_check_email->execute();
                $result_check_email = $stmt_check_email->fetch(PDO::FETCH_ASSOC);

                if ($result_check_email['count'] > 0) {
                    $response['message'] = 'El correo electrónico ya está en uso.';
                } else {
                    // Obtener el Valor del parámetro Max_Cod_Validacion
                    $sql_param = "SELECT Valor FROM `seguridad.tblparametros` WHERE Parametro = 'Max_Cod_Validacion'";
                    $stmt_param = $conn->prepare($sql_param);
                    $stmt_param->execute();
                    $result_param = $stmt_param->fetch(PDO::FETCH_ASSOC);

                    if ($result_param) {
                        $dias_validacion = (int) $result_param['Valor'];
                    } else {
                        $dias_validacion = 1; // Valor por defecto
                    }

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


                    // Generar un código de validación
                    $CodigoValidacion = generateValidationCode();

                    // Insertar el nuevo usuario en la tabla seguridad.tblusuarios
                    $sql = "INSERT INTO `seguridad.tblusuarios` (CorreoElectronico, Contraseña, IdRol, IdUniversidad, CreadoPor, IdEstado) VALUES (?, ?, ?, ?, ?, 4)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        $CorreoElectronico,
                        password_hash($CodigoValidacion, PASSWORD_DEFAULT),
                        $IdRol,
                        $IdUniversidad,
                        $creado_por
                    ]);


                    // Obtener el IdUsuario generado
                    $IdUsuario = $conn->lastInsertId();

                    // Insertar el nuevo registro en seguridad.tbldatospersonales
                    $sql_personal = "INSERT INTO `seguridad.tbldatospersonales` (IdUsuario, NombreUsuario, NumIdentidad, NumEmpleado, Usuario, EmpleadoDes, Direccion) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt_personal = $conn->prepare($sql_personal);
                    $stmt_personal->execute([
                        $IdUsuario,
                        $NombreUsuario,
                        $NumIdentidad,
                        $NumEmpleado,
                        $Usuario,
                        $EmpleadoDes,
                        $Direccion
                    ]);


                    if ($stmt->rowCount() > 0) {
                        // Calcular la fecha de vencimiento
                        $fecha_vencimiento = (new DateTime())->add(new DateInterval("P{$dias_validacion}D"))->format('Y-m-d H:i:s');


                        // Obtener el IdUsuario generado
                        $IdUsuario = $conn->lastInsertId();


                        // Guardar el código de validación
                        $sql_temp = "INSERT INTO `seguridad.tblresetcontraseñas`  (CorreoElectronico, CodigoValidacion, Token, FechaVencimiento, IdUsuario) VALUES (?, ?, ?, ?, ?)";
                        $stmt_temp = $conn->prepare($sql_temp);
                        $token = bin2hex(random_bytes(16));
                        $stmt_temp->execute([$CorreoElectronico, $CodigoValidacion, $token, $fecha_vencimiento, $IdUsuario]);


                        // Enviar el correo electrónico
                        if (sendEmail($CorreoElectronico, $NombreUsuario, $CodigoValidacion)) {
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

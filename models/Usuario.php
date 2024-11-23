<?php
class Usuario extends Conectar
{
    public function login()
{
    session_start(); // Asegúrate de que la sesión esté iniciada
    $conectar = parent::Conexion();
    parent::set_names();

    if (isset($_POST["enviar"])) {
        $contrasena = $_POST["contrasena"];
        $correo = $_POST["correo"];

        if (empty($correo) || empty($contrasena)) {
            $_SESSION["error"] = "Los campos están vacíos.";
            header("Location:" . Conectar::ruta() . "index.php");
            exit();
        } else {
            $max_intentos = $this->obtener_parametro('Max_Login_Intentos');
            $num_intentos = $this->obtener_num_intentos($correo);

            if ($num_intentos >= $max_intentos) {
                $this->bloquear_usuario($correo);
                $_SESSION["error"] = "Demasiados intentos. Contacte con soporte para que su usuario sea nuevamente habilitado o restablezca su contraseña.";
                header("Location:" . Conectar::ruta() . "index.php");
                exit();
            } else {
                $sql = "SELECT * FROM `seguridad.tblusuarios` WHERE CorreoElectronico=? AND IdEstado='1'";
                $stmt = $conectar->prepare($sql);
                $stmt->bindValue(1, $correo);
                $stmt->execute();
                $resultado = $stmt->fetch();

                if (is_array($resultado) && count($resultado) > 0) {
                    if (password_verify($contrasena, $resultado["Contraseña"])) {
                        $this->reset_intentos($correo);
                        $this->incrementar_primer_ingreso($correo);
                        $this->actualizar_fecha_ultima_conexion($correo);

                        $_SESSION["IdUsuario"] = $resultado["IdUsuario"];
                        $_SESSION["CorreoElectronico"] = $resultado["CorreoElectronico"];
                        $_SESSION["IdRol"] = $resultado["IdRol"];

                        // Nueva consulta para obtener el NombreUsuario desde la tabla seguridad.tbldatospersonales
                        $sql_personal = "SELECT NombreUsuario FROM `seguridad.tbldatospersonales` WHERE IdUsuario = ?";
                        $stmt_personal = $conectar->prepare($sql_personal);
                        $stmt_personal->bindValue(1, $resultado["IdUsuario"]);
                        $stmt_personal->execute();
                        $resultado_personal = $stmt_personal->fetch();

                        if (is_array($resultado_personal) && count($resultado_personal) > 0) {
                            // Guardar el NombreUsuario en la sesión
                            $_SESSION["IdUsuarioPersonal"] = $resultado["IdUsuarioPersonal"];
                            $_SESSION["NombreUsuario"] = $resultado_personal["NombreUsuario"];
                        }

                       //________________________________________________________________________________________________________
                        //NO TOCAR ES DE BITACORA
                        require_once(__DIR__ . '/../view/Seguridad/Bitacora/Funciones_Bitacoras.php');
                        
                            // Obtener los valores necesarios para la verificación
                            $id_usuario = $_SESSION['IdUsuario'] ?? null;
                            $id_objeto = 1; // ID del objeto o módulo correspondiente a esta página
                        
                            // Obtener la página actual y la última marca de acceso
                            $current_page = basename($_SERVER['PHP_SELF']);
                            $last_access_time = $_SESSION['last_access_time'][$current_page] ?? 0;
                        
                            // Obtener el tiempo actual
                            $current_time = time();
                        
                            // Verificar si han pasado al menos 10 segundos desde el último registro
                            if ($current_time - $last_access_time > 3) {
                                    $accion = "Inicio sesión";
                                    // Registrar en la bitácora
                                    registrobitaevent($id_usuario, $id_objeto, $accion);
                                } 
                                // Actualizar la marca temporal en la sesión
                                $_SESSION['last_access_time'][$current_page] = $current_time;
                            //_______________________________________________________________________________________________________
                        

                        header("Location:" . Conectar::ruta() . "view/home/");
                        exit();
                    } else {
                        $this->incrementar_intentos($correo);
                        $num_intentos_actualizados = $this->obtener_num_intentos($correo);
                        $intentos_restantes = $max_intentos - $num_intentos_actualizados;
                        $_SESSION["error"] = "El Usuario y/o Contraseña son incorrectos. Intentos restantes: $intentos_restantes de $max_intentos";
                        header("Location:" . Conectar::ruta() . "index.php");
                        exit();
                    }
                } else {
                    $_SESSION["error"] = "El Usuario y/o Contraseña son incorrectos.";
                    header("Location:" . Conectar::ruta() . "index.php");
                    exit();
                }
            }
        }
    }
}




    public function incrementar_primer_ingreso($correo)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "UPDATE `seguridad.tblusuarios` SET PrimerIngreso = PrimerIngreso + 1 WHERE CorreoElectronico = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $correo);
        $stmt->execute();
    }

    public function actualizar_fecha_ultima_conexion($correo)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "UPDATE `seguridad.tblusuarios` SET FechaUltimaConexion = NOW() WHERE CorreoElectronico = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $correo);
        $stmt->execute();
    }

    public function get_correo_usuario($correo)
    {
        $conectar = parent::Conexion();
        parent::set_names();
        $sql = "SELECT IdUsuario, IdEstado FROM `seguridad.tblusuarios` WHERE CorreoElectronico=?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $correo);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizar_contraseña($correo, $nueva_contrasena)
    {
        $conn = new Conectar();
        $conexion = $conn->Conexion();

        try {
            // Iniciar la transacción
            $conexion->beginTransaction();

            // Obtener el IdUsuario asociado al correo electrónico
            $stmt_id = $conexion->prepare("SELECT IdUsuario FROM `seguridad.tblusuarios` WHERE CorreoElectronico = :correo");
            $stmt_id->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt_id->execute();
            $id_usuario = $stmt_id->fetchColumn(); // Obtener el IdUsuario
            $stmt_id->closeCursor();

            if ($id_usuario) {
                // Actualizar la contraseña en la tabla principal de usuarios
                $stmt_update = $conexion->prepare("UPDATE `seguridad.tblusuarios` SET Contraseña = :nueva_contrasena, IdEstado = 1, NumIntentos = 0 WHERE CorreoElectronico = :correo");
                $stmt_update->bindParam(':nueva_contrasena', $nueva_contrasena, PDO::PARAM_STR);
                $stmt_update->bindParam(':correo', $correo, PDO::PARAM_STR);
                $stmt_update->execute();
                $stmt_update->closeCursor();

                // Insertar la nueva contraseña en el historial de contraseñas
                $stmt_hist = $conexion->prepare("INSERT INTO `seguridad.tblhistcontraseñas` (IdUsuario, Contraseña, FechaCreacion) VALUES (:id_usuario, :nueva_contrasena, current_timestamp())");
                $stmt_hist->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt_hist->bindParam(':nueva_contrasena', $nueva_contrasena, PDO::PARAM_STR);
                $stmt_hist->execute();
                $stmt_hist->closeCursor();

                // Confirmar la transacción
                $conexion->commit();

                return true; // Indicar éxito
            } else {
                // IdUsuario no encontrado
                $conexion->rollBack();
                return false;
            }
        } catch (Exception $e) {
            // Manejar errores
            $conexion->rollBack();
            error_log("Error al actualizar la contraseña: " . $e->getMessage());
            return false;
        }
    }


    // Función para obtener parámetro de la tabla tbl_ms_parametros
    public function obtener_parametro($parametro)
    {
        $conn = new Conectar();
        $conexion = $conn->Conexion();

        $sql = "SELECT Valor FROM `seguridad.tblparametros` WHERE Parametro = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $parametro);
        $stmt->execute();
        $valor = $stmt->fetchColumn();
        $stmt->closeCursor();

        return $valor;
    }

    // Función para obtener el número de intentos de login actual
    private function obtener_num_intentos($correo)
    {
        $conn = new Conectar();
        $conexion = $conn->Conexion();

        $sql = "SELECT NumIntentos FROM `seguridad.tblusuarios` WHERE CorreoElectronico = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $correo);
        $stmt->execute();
        $num_intentos = $stmt->fetchColumn();
        $stmt->closeCursor();

        return $num_intentos;
    }

    // Función para incrementar el contador de intentos fallidos
    private function incrementar_intentos($correo)
    {
        $conn = new Conectar();
        $conexion = $conn->Conexion();

        $sql = "UPDATE `seguridad.tblusuarios` SET NumIntentos = NumIntentos + 1 WHERE CorreoElectronico = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $correo);
        $stmt->execute();
        $stmt->closeCursor();
    }

    // Función para resetear el contador de intentos fallidos
    private function reset_intentos($correo)
    {
        $conn = new Conectar();
        $conexion = $conn->Conexion();

        $sql = "UPDATE `seguridad.tblusuarios` SET NumIntentos = 0 WHERE CorreoElectronico = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $correo);
        $stmt->execute();
        $stmt->closeCursor();
    }

    // Función para bloquear al usuario cambiando IdEstado a 3
    private function bloquear_usuario($correo)
    {
        $conn = new Conectar();
        $conexion = $conn->Conexion();

        $sql = "UPDATE `seguridad.tblusuarios` SET IdEstado = 3 WHERE CorreoElectronico = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $correo);
        $stmt->execute();
        $stmt->closeCursor();
    }
}

<?php
class Usuario extends Conectar
{
    public function login()
    {
        session_start();
        $conectar = parent::Conexion();
        parent::set_names();

        if (isset($_POST["enviar"])) {
            $contrasena = $_POST["contrasena"];
            $identificador = $_POST["correo"]; // Este campo puede ser correo o usuario.

            if (empty($identificador) || empty($contrasena)) {
                $_SESSION["error"] = "Los campos están vacíos.";
                header("Location:" . Conectar::ruta() . "index.php");
                exit();
            } else {
                $max_intentos = $this->obtener_parametro('Max_Login_Intentos');
                $num_intentos = $this->obtener_num_intentos($identificador);

                if ($num_intentos >= $max_intentos) {
                    $this->bloquear_usuario($identificador);
                    $_SESSION["error"] = "Demasiados intentos. Contacte con soporte para que su usuario sea nuevamente habilitado o restablezca su contraseña.";

                    //________________________________________________________________________________________________________
                    //NO TOCAR ES DE BITACORA
                    require_once(__DIR__ . '/../view/Seguridad/Bitacora/Funciones_Bitacoras.php');

                    // Obtener los valores necesarios para la verificación
                    $id_usuario = null;
                    $id_objeto = 1; // ID del objeto o módulo correspondiente a esta página

                    // Obtener la página actual y la última marca de acceso
                    $current_page = basename($_SERVER['PHP_SELF']);
                    $last_access_time = $_SESSION['last_access_time'][$current_page] ?? 0;

                    // Obtener el tiempo actual
                    $current_time = time();

                    // Verificar si han pasado al menos 10 segundos desde el último registro
                    if ($current_time - $last_access_time > 3) {
                        $accion = "Usuario bloqueado";
                        // Registrar en la bitácora
                        registrobitaevent($id_usuario, $id_objeto, $accion);
                    }
                    // Actualizar la marca temporal en la sesión
                    $_SESSION['last_access_time'][$current_page] = $current_time;
                    //_______________________________________________________________________________________________________

                    header("Location:" . Conectar::ruta() . "index.php");
                    exit();
                } else {
                    // Modificar la consulta para buscar por correo electrónico o usuario.
                    $sql = "
                    SELECT u.*, dp.Usuario 
                    FROM `seguridad.tblusuarios` u
                    LEFT JOIN `seguridad.tbldatospersonales` dp ON u.IdUsuario = dp.IdUsuario
                    WHERE (u.CorreoElectronico = :identificador OR dp.Usuario = :identificador)
                    AND u.IdEstado = '1'";
                    $stmt = $conectar->prepare($sql);
                    $stmt->bindValue(':identificador', $identificador, PDO::PARAM_STR);
                    $stmt->execute();
                    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($resultado) {
                        if (password_verify($contrasena, $resultado["Contraseña"])) {
                            $this->reset_intentos($identificador);
                            $this->incrementar_primer_ingreso($resultado["IdUsuario"]);
                            $this->actualizar_fecha_ultima_conexion($resultado["IdUsuario"]);

                            $_SESSION["IdUsuario"] = $resultado["IdUsuario"];
                            $_SESSION["CorreoElectronico"] = $resultado["CorreoElectronico"];
                            $_SESSION["IdRol"] = $resultado["IdRol"];
                            $_SESSION["Usuario"] = $resultado["Usuario"]; // Guardar Usuario en sesión

                            // Obtener el NombreUsuario desde seguridad.tbldatospersonales
                            $sql_personal = "SELECT NombreUsuario FROM `seguridad.tbldatospersonales` WHERE IdUsuario = :idUsuario";
                            $stmt_personal = $conectar->prepare($sql_personal);
                            $stmt_personal->bindValue(':idUsuario', $resultado["IdUsuario"], PDO::PARAM_INT);
                            $stmt_personal->execute();
                            $resultado_personal = $stmt_personal->fetch(PDO::FETCH_ASSOC);

                            if ($resultado_personal) {
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
                            $this->incrementar_intentos($identificador);
                            $num_intentos_actualizados = $this->obtener_num_intentos($identificador);
                            $intentos_restantes = $max_intentos - $num_intentos_actualizados;

                            if ($intentos_restantes == 1) {
                                $_SESSION["error"] = "El Usuario y/o Contraseña son incorrectos. Intentos restantes 1.";

                                //________________________________________________________________________________________________________
                                //NO TOCAR ES DE BITACORA
                                require_once(__DIR__ . '/../view/Seguridad/Bitacora/Funciones_Bitacoras.php');

                                // Obtener los valores necesarios para la verificación
                                $id_usuario = null;
                                $id_objeto = 1; // ID del objeto o módulo correspondiente a esta página

                                // Obtener la página actual y la última marca de acceso
                                $current_page = basename($_SERVER['PHP_SELF']);
                                $last_access_time = $_SESSION['last_access_time'][$current_page] ?? 0;

                                // Obtener el tiempo actual
                                $current_time = time();

                                // Verificar si han pasado al menos 10 segundos desde el último registro
                                if ($current_time - $last_access_time > 3) {
                                    $accion = "Inicio de sesión fallida sospecho";
                                    // Registrar en la bitácora
                                    registrobitaevent($id_usuario, $id_objeto, $accion);
                                }
                                // Actualizar la marca temporal en la sesión
                                $_SESSION['last_access_time'][$current_page] = $current_time;
                                //_______________________________________________________________________________________________________

                            } elseif ($intentos_restantes == 0) {
                                $_SESSION["error"] = "El Usuario y/o Contraseña son incorrectos. Ya no le quedan más intentos.";

                                //________________________________________________________________________________________________________
                                //NO TOCAR ES DE BITACORA
                                require_once(__DIR__ . '/../view/Seguridad/Bitacora/Funciones_Bitacoras.php');

                                // Obtener los valores necesarios para la verificación
                                $id_usuario = null;
                                $id_objeto = 1; // ID del objeto o módulo correspondiente a esta página

                                // Obtener la página actual y la última marca de acceso
                                $current_page = basename($_SERVER['PHP_SELF']);
                                $last_access_time = $_SESSION['last_access_time'][$current_page] ?? 0;

                                // Obtener el tiempo actual
                                $current_time = time();

                                // Verificar si han pasado al menos 10 segundos desde el último registro
                                if ($current_time - $last_access_time > 3) {
                                    $accion = "Inicio de sesión fallida sospecho";
                                    // Registrar en la bitácora
                                    registrobitaevent($id_usuario, $id_objeto, $accion);
                                }
                                // Actualizar la marca temporal en la sesión
                                $_SESSION['last_access_time'][$current_page] = $current_time;
                                //_______________________________________________________________________________________________________

                            } else {
                                $_SESSION["error"] = "El Usuario y/o Contraseña son incorrectos.";


                                //________________________________________________________________________________________________________
                                //NO TOCAR ES DE BITACORA
                                require_once(__DIR__ . '/../view/Seguridad/Bitacora/Funciones_Bitacoras.php');

                                // Obtener los valores necesarios para la verificación
                                $id_usuario = null;
                                $id_objeto = 1; // ID del objeto o módulo correspondiente a esta página

                                // Obtener la página actual y la última marca de acceso
                                $current_page = basename($_SERVER['PHP_SELF']);
                                $last_access_time = $_SESSION['last_access_time'][$current_page] ?? 0;

                                // Obtener el tiempo actual
                                $current_time = time();

                                // Verificar si han pasado al menos 10 segundos desde el último registro
                                if ($current_time - $last_access_time > 3) {
                                    $accion = "Inicio de sesión fallida sospecho";
                                    // Registrar en la bitácora
                                    registrobitaevent($id_usuario, $id_objeto, $accion);
                                }
                                // Actualizar la marca temporal en la sesión
                                $_SESSION['last_access_time'][$current_page] = $current_time;
                                //___________________________________________________________cambiar siempre en la tabla bitacora que el id usuario pueda ser null.
                            }

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

    // Obtener número de intentos fallidos para Correo o Usuario
    private function obtener_num_intentos($identificador)
    {
        $conn = new Conectar();
        $conexion = $conn->Conexion();

        $sql = "
        SELECT NumIntentos 
        FROM `seguridad.tblusuarios` u
        LEFT JOIN `seguridad.tbldatospersonales` dp ON u.IdUsuario = dp.IdUsuario
        WHERE u.CorreoElectronico = ? OR dp.Usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $identificador);
        $stmt->bindValue(2, $identificador);
        $stmt->execute();
        $num_intentos = $stmt->fetchColumn();
        $stmt->closeCursor();

        return $num_intentos;
    }

    // Incrementar intentos fallidos
    private function incrementar_intentos($identificador)
    {
        $conn = new Conectar();
        $conexion = $conn->Conexion();

        $sql = "
        UPDATE `seguridad.tblusuarios` u
        LEFT JOIN `seguridad.tbldatospersonales` dp ON u.IdUsuario = dp.IdUsuario
        SET NumIntentos = NumIntentos + 1 
        WHERE u.CorreoElectronico = ? OR dp.Usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $identificador);
        $stmt->bindValue(2, $identificador);
        $stmt->execute();
        $stmt->closeCursor();
    }

    // Resetear intentos fallidos
    private function reset_intentos($identificador)
    {
        $conn = new Conectar();
        $conexion = $conn->Conexion();

        $sql = "
        UPDATE `seguridad.tblusuarios` u
        LEFT JOIN `seguridad.tbldatospersonales` dp ON u.IdUsuario = dp.IdUsuario
        SET NumIntentos = 0 
        WHERE u.CorreoElectronico = ? OR dp.Usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $identificador);
        $stmt->bindValue(2, $identificador);
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

<?php
class Usuario extends Conectar
{
    public function login()
    {
        $conectar = parent::Conexion();
        parent::set_names();
        if (isset($_POST["enviar"])) {
            $contrasena = $_POST["contrasena"];
            $correo = $_POST["correo"];

            if (empty($correo) or empty($contrasena)) {
                $_SESSION["error"] = "Los campos están vacíos.";
                header("Location:" . Conectar::ruta() . "index.php");
                exit();
            } else {
                // Obtener el parámetro Max_Login_Intentos
                $max_intentos = $this->obtener_parametro('Max_Login_Intentos');

                // Verificar número de intentos actuales
                $num_intentos = $this->obtener_num_intentos($correo);

                if ($num_intentos >= $max_intentos) {
                    // Bloquear usuario (cambiar ESTADO_USUARIO a 3)
                    $this->bloquear_usuario($correo);
                    $_SESSION["error"] = "Demasiados intentos. Contacte con soporte para que su usuario sea nuevamente habilitado o restablezca su contraseña.";
                    header("Location:" . Conectar::ruta() . "index.php");
                    exit();
                } else {
                    $sql = "SELECT * FROM tbl_ms_usuario WHERE CORREO_ELECTRONICO=? AND ESTADO_USUARIO='1'";
                    $stmt = $conectar->prepare($sql);
                    $stmt->bindValue(1, $correo);
                    $stmt->execute();
                    $resultado = $stmt->fetch();

                    if (is_array($resultado) && count($resultado) > 0) {
                        // Si usas contraseñas encriptadas
                        if (password_verify($contrasena, $resultado["CONTRASENA"])) {
                            // Restablecer contador de intentos
                            $this->reset_intentos($correo);

                            // Incrementar PRIMER_INGRESO
                            $this->incrementar_primer_ingreso($correo);

                            // Actualizar FECHA_ULTIMA_CONEXION
                            $this->actualizar_fecha_ultima_conexion($correo);

                            $_SESSION["ID_USUARIO"] = $resultado["ID_USUARIO"];
                            $_SESSION["NOMBRE_USUARIO"] = $resultado["NOMBRE_USUARIO"];
                            $_SESSION["CORREO_ELECTRONICO"] = $resultado["CORREO_ELECTRONICO"];
                            $_SESSION["ID_ROL"] = $resultado["ID_ROL"];
                            // Redirigir a la vista principal
                            header("Location:" . Conectar::ruta() . "view/home/");
                            exit();
                        } else {
                            // Incrementar contador de intentos fallidos
                            $this->incrementar_intentos($correo);
                            // Obtener número de intentos actuales después de incrementar
                            $num_intentos_actualizados = $this->obtener_num_intentos($correo);
                            // Calcular intentos restantes
                            $intentos_restantes = $max_intentos - $num_intentos_actualizados;
                            $_SESSION["error"] = "El Usuario y/o Contraseña son incorrectos. Intentos restantes: $intentos_restantes de $max_intentos";
                            header("Location:" . Conectar::ruta() . "index.php");
                            exit();
                        }
                    } else {
                        // El correo electrónico no existe en la base de datos
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

        $sql = "UPDATE tbl_ms_usuario SET PRIMER_INGRESO = PRIMER_INGRESO + 1 WHERE CORREO_ELECTRONICO = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $correo);
        $stmt->execute();
    }

    public function actualizar_fecha_ultima_conexion($correo)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "UPDATE tbl_ms_usuario SET FECHA_ULTIMA_CONEXION = NOW() WHERE CORREO_ELECTRONICO = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $correo);
        $stmt->execute();
    }

    public function get_correo_usuario($correo)
    {
        $conectar = parent::Conexion();
        parent::set_names();
        $sql = "SELECT NOMBRE_USUARIO, ESTADO_USUARIO FROM tbl_ms_usuario WHERE CORREO_ELECTRONICO=?";
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

            // Obtener el ID_USUARIO asociado al correo electrónico
            $stmt_id = $conexion->prepare("SELECT ID_USUARIO FROM tbl_ms_usuario WHERE CORREO_ELECTRONICO = :correo");
            $stmt_id->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt_id->execute();
            $id_usuario = $stmt_id->fetchColumn(); // Obtener el ID_USUARIO
            $stmt_id->closeCursor();

            if ($id_usuario) {
                // Actualizar la contraseña en la tabla principal de usuarios
                $stmt_update = $conexion->prepare("UPDATE tbl_ms_usuario SET contrasena = :nueva_contrasena, ESTADO_USUARIO = 1, NUM_INTENTOS = 0 WHERE CORREO_ELECTRONICO = :correo");
                $stmt_update->bindParam(':nueva_contrasena', $nueva_contrasena, PDO::PARAM_STR);
                $stmt_update->bindParam(':correo', $correo, PDO::PARAM_STR);
                $stmt_update->execute();
                $stmt_update->closeCursor();

                // Insertar la nueva contraseña en el historial de contraseñas
                $stmt_hist = $conexion->prepare("INSERT INTO tbl_ms_hist_contraseña (ID_USUARIO, CONTRASEÑA, FECHA_CREACION) VALUES (:id_usuario, :nueva_contrasena, current_timestamp())");
                $stmt_hist->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt_hist->bindParam(':nueva_contrasena', $nueva_contrasena, PDO::PARAM_STR);
                $stmt_hist->execute();
                $stmt_hist->closeCursor();

                // Confirmar la transacción
                $conexion->commit();

                return true; // Indicar éxito
            } else {
                // ID_USUARIO no encontrado
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

        $sql = "SELECT VALOR FROM tbl_ms_parametros WHERE PARAMETRO = ?";
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

        $sql = "SELECT NUM_INTENTOS FROM tbl_ms_usuario WHERE CORREO_ELECTRONICO = ?";
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

        $sql = "UPDATE tbl_ms_usuario SET NUM_INTENTOS = NUM_INTENTOS + 1 WHERE CORREO_ELECTRONICO = ?";
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

        $sql = "UPDATE tbl_ms_usuario SET NUM_INTENTOS = 0 WHERE CORREO_ELECTRONICO = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $correo);
        $stmt->execute();
        $stmt->closeCursor();
    }

    // Función para bloquear al usuario cambiando ESTADO_USUARIO a 3
    private function bloquear_usuario($correo)
    {
        $conn = new Conectar();
        $conexion = $conn->Conexion();

        $sql = "UPDATE tbl_ms_usuario SET ESTADO_USUARIO = 3 WHERE CORREO_ELECTRONICO = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $correo);
        $stmt->execute();
        $stmt->closeCursor();
    }
}

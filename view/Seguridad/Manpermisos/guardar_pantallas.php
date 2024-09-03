<?php
/* session_start();
require_once("../../../config/conexion.php");

if (isset($_SESSION["ID_USUARIO"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_rol = $_POST['roleList'];
        $id_objetos = $_POST['objectSelect'];
        
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        foreach ($id_objetos as $id_objeto) {
            $sql = "INSERT INTO tbl_permisos (ID_ROL, ID_OBJETO, PERMISO_INSERCION, PERMISO_ELIMINACION, PERMISO_ACTUALIZACION, PERMISO_CONSULTAR) 
                    VALUES (:id_rol, :id_objeto, 0, 0, 0, 0)"; // Puedes ajustar los permisos según sea necesario
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_rol', $id_rol);
            $stmt->bindParam(':id_objeto', $id_objeto);
            $stmt->execute();
        }
        
        $conn = null;
        header("Location: ../../Seguridad/man_permisos.php");// Redirige de nuevo a la misma página
        exit();
    }
} else {
    header("Location: " . Conectar::ruta() . "index.php");
    exit();
} */

/* 
session_start();
require_once("../../../config/conexion.php");

if (isset($_SESSION["ID_USUARIO"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_rol = $_POST['roleList'];
        $id_objetos = $_POST['objectSelect'];
        
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        $objetos_existentes = [];
        $objetos_insertados = [];

        foreach ($id_objetos as $id_objeto) {
            // Verificar si la combinación de ID_ROL y ID_OBJETO ya existe
            $sql = "SELECT COUNT(*) FROM tbl_permisos WHERE ID_ROL = :id_rol AND ID_OBJETO = :id_objeto";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_rol', $id_rol);
            $stmt->bindParam(':id_objeto', $id_objeto);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count == 0) {
                // Insertar el nuevo registro si no existe
                $sql = "INSERT INTO tbl_permisos (ID_ROL, ID_OBJETO, PERMISO_INSERCION, PERMISO_ELIMINACION, PERMISO_ACTUALIZACION, PERMISO_CONSULTAR) 
                        VALUES (:id_rol, :id_objeto, 0, 0, 0, 0)"; // Ajusta los permisos según sea necesario
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id_rol', $id_rol);
                $stmt->bindParam(':id_objeto', $id_objeto);
                $stmt->execute();
                $objetos_insertados[] = $id_objeto;
            } else {
                // Añadir al array de objetos existentes
                $objetos_existentes[] = $id_objeto;
            }
        }
        
        $conn = null;

        if (!empty($objetos_existentes)) {
            $_SESSION['permiso_msg'] = "Algunos pantallas ya existen para este rol: " . implode(", ", $objetos_existentes);
            $_SESSION['permiso_msg_type'] = "warning";
        } else {
            $_SESSION['permiso_msg'] = "Pantallas asignados correctamente.";
            $_SESSION['permiso_msg_type'] = "success";
        }
        
        header("Location: ../../Seguridad/man_permisos.php"); // Redirige a la misma página
        exit();
    }
} else {
    header("Location: " . Conectar::ruta() . "index.php");
    exit();
}
 */



 session_start();
 require_once("../../../config/conexion.php");
 
 if (isset($_SESSION["ID_USUARIO"])) {
     if ($_SERVER["REQUEST_METHOD"] == "POST") {
         $id_rol = $_POST['roleList'];
         $id_objetos = isset($_POST['objectSelect']) ? $_POST['objectSelect'] : [];
 
         if (empty($id_objetos)) {
             // Si no se seleccionó ninguna pantalla, establecer un mensaje de advertencia
             $_SESSION['permiso_msg'] = "Por favor, selecciona al menos una pantalla antes de guardar.";
             $_SESSION['permiso_msg_type'] = "warning";
         } else {
             $conexion = new Conectar();
             $conn = $conexion->Conexion();
 
             $objetos_existentes = [];
             $objetos_insertados = [];
 
             // Obtener nombres de objetos para mensajes
             $nombres_objetos = [];
             $sql = "SELECT ID_OBJETO, OBJETO FROM tbl_ms_objetos";
             $stmt = $conn->prepare($sql);
             $stmt->execute();
             $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
             foreach ($result as $row) {
                 $nombres_objetos[$row['ID_OBJETO']] = $row['OBJETO'];
             }
 
             foreach ($id_objetos as $id_objeto) {
                 // Verificar si la combinación de ID_ROL y ID_OBJETO ya existe
                 $sql = "SELECT COUNT(*) FROM tbl_permisos WHERE ID_ROL = :id_rol AND ID_OBJETO = :id_objeto";
                 $stmt = $conn->prepare($sql);
                 $stmt->bindParam(':id_rol', $id_rol);
                 $stmt->bindParam(':id_objeto', $id_objeto);
                 $stmt->execute();
                 $count = $stmt->fetchColumn();
 
                 if ($count == 0) {
                     // Insertar el nuevo registro si no existe
                     $sql = "INSERT INTO tbl_permisos (ID_ROL, ID_OBJETO, PERMISO_INSERCION, PERMISO_ELIMINACION, PERMISO_ACTUALIZACION, PERMISO_CONSULTAR) 
                             VALUES (:id_rol, :id_objeto, 0, 0, 0, 0)"; // Ajusta los permisos según sea necesario
                     $stmt = $conn->prepare($sql);
                     $stmt->bindParam(':id_rol', $id_rol);
                     $stmt->bindParam(':id_objeto', $id_objeto);
                     $stmt->execute();
                     $objetos_insertados[] = $nombres_objetos[$id_objeto];
                 } else {
                     // Añadir al array de objetos existentes
                     $objetos_existentes[] = $nombres_objetos[$id_objeto];
                 }
             }
 
             $conn = null;
 
             if (!empty($objetos_existentes)) {
                 $_SESSION['permiso_msg'] = "Algunas pantallas ya existen para este rol: " . implode(", ", $objetos_existentes);
                 $_SESSION['permiso_msg_type'] = "warning";
             } elseif (!empty($objetos_insertados)) {
                 $_SESSION['permiso_msg'] = "Pantallas asignadas correctamente.";
                 $_SESSION['permiso_msg_type'] = "success";
             } else {
                 $_SESSION['permiso_msg'] = "No se han asignado nuevas pantallas.";
                 $_SESSION['permiso_msg_type'] = "info";
             }
         }
 
         header("Location:../../Seguridad/man_permisos.php"); // Redirige a la misma página
         exit();
     }
 } else {
     header("Location: " . Conectar::ruta() . "index.php");
     exit();
 }
  

?>

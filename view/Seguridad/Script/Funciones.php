<?php
// Usa __DIR__ para construir la ruta correcta a conexion.php
require_once(__DIR__ . '/../../../config/conexion.php');

function obtenerRoles($usuario)
{
    try {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Llamada al procedimiento almacenado con parámetro
        $sql = "CALL `seguridad.splRolesMostrar`(:usuario)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener los resultados
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Generar opciones HTML para el <select>
        $opciones = "";
        foreach ($roles as $rol) {
            $idRol = htmlspecialchars($rol['IdRol'], ENT_QUOTES, 'UTF-8');
            $nombreRol = htmlspecialchars($rol['NombreRol'], ENT_QUOTES, 'UTF-8');
            $opciones .= "<option value='$idRol'>$nombreRol</option>";
        }
        return $opciones;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function obtenerUniversidades($usuario)
{
    try {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Llamada al procedimiento almacenado con parámetro
        $sql = "CALL `proceso.splUniversidadesMostrar`(:usuario)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener los resultados
        $universidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Generar opciones HTML para el <select>
        $opciones = "";
        foreach ($universidades as $universidad) {
            $idUniversidad = htmlspecialchars($universidad['IdUniversidad'], ENT_QUOTES, 'UTF-8');
            $nombreUniversidad = htmlspecialchars($universidad['NomUniversidad'], ENT_QUOTES, 'UTF-8');
            $opciones .= "<option value='$idUniversidad'>$nombreUniversidad</option>";
        }
        return $opciones;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function editarEstados($usuario)
{
    try {
        // Crear una nueva instancia de Conectar y obtener la conexión
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Llamada al procedimiento almacenado con parámetro
        $sql = "CALL `seguridad.splEstadosEditar`(:usuario)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener los resultados
        $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Generar opciones HTML para el <select>
        $opciones = "";
        foreach ($estados as $estado) {
            $idEstado = htmlspecialchars($estado['IdEstado'], ENT_QUOTES, 'UTF-8');
            $nombreEstado = htmlspecialchars($estado['EstadoUsuario'], ENT_QUOTES, 'UTF-8');
            $opciones .= "<option value='$idEstado'>$nombreEstado</option>";
        }
        return $opciones;
    } catch (PDOException $e) {
        // Manejo de errores
        return "Error: " . $e->getMessage();
    }
}

function editarEstadosInactivos($usuario)
{
    try {
        // Crear una nueva instancia de Conectar y obtener la conexión
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Llamada al procedimiento almacenado con parámetro
        $sql = "CALL `seguridad.splEstadosEditarInactivos`(:usuario)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener los resultados
        $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Generar opciones HTML para el <select>
        $opciones = "";
        foreach ($estados as $estado) {
            $idEstado = htmlspecialchars($estado['IdEstado'], ENT_QUOTES, 'UTF-8');
            $nombreEstado = htmlspecialchars($estado['EstadoUsuario'], ENT_QUOTES, 'UTF-8');
            $opciones .= "<option value='$idEstado'>$nombreEstado</option>";
        }
        return $opciones;
    } catch (PDOException $e) {
        // Manejo de errores
        return "Error: " . $e->getMessage();
    }
}

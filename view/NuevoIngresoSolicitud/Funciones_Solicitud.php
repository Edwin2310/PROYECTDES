<?php
// Usa __DIR__ para construir la ruta correcta a conexion.php
require_once(__DIR__ . '/../../config/conexion.php');

function obtenerCodigos($usuario)
{
    try {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Llamada al procedimiento almacenado con parámetro
        $sql = "CALL `proceso.splCodigoMostrar`(:usuario)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener los resultados
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Generar opciones HTML para el <select>
        $opciones = "";
        foreach ($categorias as $categoria) {
            $idCategoria = htmlspecialchars($categoria['ID_CATEGORIA'], ENT_QUOTES, 'UTF-8');
            $codigoArbitrios = htmlspecialchars($categoria['COD_ARBITRIOS'], ENT_QUOTES, 'UTF-8');
            $opciones .= "<option value='$idCategoria'>$codigoArbitrios</option>";
        }
        return $opciones;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}


function obtenerGrados($usuario)
{
    try {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Llamada al procedimiento almacenado con parámetro
        $sql = "CALL `proceso.splGradoMostrar`(:usuario)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener los resultados
        $grados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Generar opciones HTML para el <select>
        $opciones = "";
        foreach ($grados as $grado) {
            $idGrado = htmlspecialchars($grado['ID_GRADO'], ENT_QUOTES, 'UTF-8');
            $nombreGrado = htmlspecialchars($grado['NOM_GRADO'], ENT_QUOTES, 'UTF-8');
            $opciones .= "<option value='$idGrado'>$nombreGrado</option>";
        }

        return $opciones;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}


function obtenerModalidades($usuario)
{
    try {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Llamada al procedimiento almacenado con parámetro
        $sql = "CALL `proceso.splModalidadMostrar`(:usuario)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener los resultados
        $modalidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Generar opciones HTML para el <select>
        $opciones = "";
        foreach ($modalidades as $modalidad) {
            $idModalidad = htmlspecialchars($modalidad['ID_MODALIDAD'], ENT_QUOTES, 'UTF-8');
            $nombreModalidad = htmlspecialchars($modalidad['NOM_MODALIDAD'], ENT_QUOTES, 'UTF-8');
            $opciones .= "<option value='$idModalidad'>$nombreModalidad</option>";
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
            $idUniversidad = htmlspecialchars($universidad['ID_UNIVERSIDAD'], ENT_QUOTES, 'UTF-8');
            $nombreUniversidad = htmlspecialchars($universidad['NOM_UNIVERSIDAD'], ENT_QUOTES, 'UTF-8');
            $opciones .= "<option value='$idUniversidad'>$nombreUniversidad</option>";
        }
        return $opciones;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function obtenerDepartamentos($usuario)
{
    try {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Llamada al procedimiento almacenado con parámetro
        $sql = "CALL `proceso.splDepartamentoMostrar`(:usuario)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener los resultados
        $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Generar opciones HTML para el <select>
        $opciones = "";
        foreach ($departamentos as $departamento) {
            $idDepartamento = htmlspecialchars($departamento['ID_DEPARTAMENTO'], ENT_QUOTES, 'UTF-8');
            $nombreDepartamento = htmlspecialchars($departamento['NOM_DEPTO'], ENT_QUOTES, 'UTF-8');
            $opciones .= "<option value='$idDepartamento'>$nombreDepartamento</option>";
        }

        return $opciones;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

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
            $idCategoria = htmlspecialchars($categoria['IdCategoria'], ENT_QUOTES, 'UTF-8');
            $codigoArbitrios = htmlspecialchars($categoria['CodArbitrios'], ENT_QUOTES, 'UTF-8');
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
            $idGrado = htmlspecialchars($grado['IdGrado'], ENT_QUOTES, 'UTF-8');
            $nombreGrado = htmlspecialchars($grado['NomGrado'], ENT_QUOTES, 'UTF-8');
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
            $idModalidad = htmlspecialchars($modalidad['IdModalidad'], ENT_QUOTES, 'UTF-8');
            $nombreModalidad = htmlspecialchars($modalidad['NomModalidad'], ENT_QUOTES, 'UTF-8');
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
            $idUniversidad = htmlspecialchars($universidad['IdUniversidad'], ENT_QUOTES, 'UTF-8');
            $nombreUniversidad = htmlspecialchars($universidad['NomUniversidad'], ENT_QUOTES, 'UTF-8');
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
            $idDepartamento = htmlspecialchars($departamento['IdDepartamento'], ENT_QUOTES, 'UTF-8');
            $nombreDepartamento = htmlspecialchars($departamento['NomDepto'], ENT_QUOTES, 'UTF-8');
            $opciones .= "<option value='$idDepartamento'>$nombreDepartamento</option>";
        }

        return $opciones;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function obtenerCarreras($usuario)
{
    try {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Llamada al procedimiento almacenado con parámetro
        $sql = "CALL `proceso.splCarrerasMostrar`(:usuario)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener los resultados
        $carreras = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Generar opciones HTML para el <select>
        $opciones = "";
        foreach ($carreras as $carrera) {
            $idCarrera = htmlspecialchars($carrera['IdCarrera'], ENT_QUOTES, 'UTF-8');
            $nombreCarrerra = htmlspecialchars($carrera['NomCarrera'], ENT_QUOTES, 'UTF-8');
            $opciones .= "<option value='$idCarrera'>$nombreCarrerra</option>";
        }
        return $opciones;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

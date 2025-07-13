<?php 
require_once "global.php";

$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

mysqli_query($conexion, 'SET NAMES "'.DB_ENCODE.'"');

// Si tenemos un posible error en la conexión lo mostramos
if (mysqli_connect_errno()) {
    error_log("Error de conexión BD: " . mysqli_connect_error());
    die("Error de conexión a la base de datos");
}

if (!function_exists('ejecutarConsulta')) {
    function ejecutarConsulta($sql) {
        global $conexion;
        $query = $conexion->query($sql);
        if (!$query) {
            error_log("Error SQL: " . $conexion->error . " - Query: " . $sql);
        }
        return $query;
    }

    function ejecutarConsultaSimpleFila($sql) {
        global $conexion;
        $query = $conexion->query($sql);
        if (!$query) {
            error_log("Error SQL: " . $conexion->error . " - Query: " . $sql);
            return false;
        }
        $row = $query->fetch_assoc();
        return $row;
    }

    function ejecutarConsulta_retornarID($sql) {
        global $conexion;
        $query = $conexion->query($sql);
        if (!$query) {
            error_log("Error SQL: " . $conexion->error . " - Query: " . $sql);
            return false;
        }
        return $conexion->insert_id;
    }

    // NUEVAS FUNCIONES SEGURAS CON PREPARED STATEMENTS
    function ejecutarConsultaPreparada($sql, $tipos = "", $valores = []) {
        global $conexion;
        
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparando consulta: " . $conexion->error);
            return false;
        }
        
        if (!empty($tipos) && !empty($valores)) {
            $stmt->bind_param($tipos, ...$valores);
        }
        
        $resultado = $stmt->execute();
        if (!$resultado) {
            error_log("Error ejecutando consulta: " . $stmt->error);
            return false;
        }
        
        return $stmt;
    }

    function ejecutarConsultaPreparadaSelect($sql, $tipos = "", $valores = []) {
        global $conexion;
        
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparando consulta: " . $conexion->error);
            return false;
        }
        
        if (!empty($tipos) && !empty($valores)) {
            $stmt->bind_param($tipos, ...$valores);
        }
        
        if (!$stmt->execute()) {
            error_log("Error ejecutando consulta: " . $stmt->error);
            return false;
        }
        
        return $stmt->get_result();
    }

    function ejecutarConsultaPreparadaFila($sql, $tipos = "", $valores = []) {
        $result = ejecutarConsultaPreparadaSelect($sql, $tipos, $valores);
        if (!$result) {
            return false;
        }
        return $result->fetch_assoc();
    }

    function limpiarCadena($str) {
        global $conexion;
        // Eliminar espacios al inicio y final
        $str = trim($str);
        // Escapar caracteres especiales
        $str = mysqli_real_escape_string($conexion, $str);
        // Convertir caracteres especiales a entidades HTML
        $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        return $str;
    }

    // FUNCIÓN PARA VALIDAR ENTRADAS
    function validarEntrada($valor, $tipo, $longitud_max = null) {
        $valor = trim($valor);
        
        if (empty($valor)) {
            return false;
        }
        
        switch ($tipo) {
            case 'texto':
                if ($longitud_max && strlen($valor) > $longitud_max) {
                    return false;
                }
                return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $valor);
                
            case 'email':
                return filter_var($valor, FILTER_VALIDATE_EMAIL);
                
            case 'numero':
                return is_numeric($valor) && $valor > 0;
                
            case 'entero':
                return filter_var($valor, FILTER_VALIDATE_INT) !== false;
                
            case 'telefono':
                return preg_match('/^[0-9\-\+\s\(\)]+$/', $valor);
                
            default:
                return false;
        }
    }
}
?>
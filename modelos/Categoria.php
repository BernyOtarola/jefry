<?php 
require "../config/Conexion.php";

class Categoria {
    public function __construct() {
        // Constructor
    }

    // INSERTAR CON PREPARED STATEMENTS
    public function insertar($nombre) {
        try {
            // Validar entrada
            if (!validarEntrada($nombre, 'texto', 50)) {
                return ['success' => false, 'message' => 'Nombre inválido'];
            }
            
            $sql = "INSERT INTO categoria (nombre) VALUES (?)";
            $stmt = ejecutarConsultaPreparada($sql, "s", [$nombre]);
            
            if ($stmt) {
                return ['success' => true, 'message' => 'Categoría agregada correctamente'];
            } else {
                return ['success' => false, 'message' => 'Error al agregar categoría'];
            }
            
        } catch (Exception $e) {
            error_log("Error en insertar categoria: " . $e->getMessage());
            
            // Manejar error de duplicado
            if ($e->getCode() == 1062) {
                return ['success' => false, 'message' => 'La categoría ya existe'];
            }
            
            return ['success' => false, 'message' => 'Error interno del servidor'];
        }
    }

    // EDITAR CON PREPARED STATEMENTS
    public function editar($id, $nombre) {
        try {
            // Validar entradas
            if (!validarEntrada($id, 'entero')) {
                return ['success' => false, 'message' => 'ID inválido'];
            }
            
            if (!validarEntrada($nombre, 'texto', 50)) {
                return ['success' => false, 'message' => 'Nombre inválido'];
            }
            
            $sql = "UPDATE categoria SET nombre = ? WHERE id = ?";
            $stmt = ejecutarConsultaPreparada($sql, "si", [$nombre, $id]);
            
            if ($stmt && $stmt->affected_rows > 0) {
                return ['success' => true, 'message' => 'Categoría actualizada correctamente'];
            } else {
                return ['success' => false, 'message' => 'No se pudo actualizar la categoría'];
            }
            
        } catch (Exception $e) {
            error_log("Error en editar categoria: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error interno del servidor'];
        }
    }

    // ELIMINAR CON PREPARED STATEMENTS
    public function eliminar($id) {
        try {
            // Validar entrada
            if (!validarEntrada($id, 'entero')) {
                return ['success' => false, 'message' => 'ID inválido'];
            }
            
            $sql = "DELETE FROM categoria WHERE id = ?";
            $stmt = ejecutarConsultaPreparada($sql, "i", [$id]);
            
            if ($stmt && $stmt->affected_rows > 0) {
                return ['success' => true, 'message' => 'Categoría eliminada correctamente'];
            } else {
                return ['success' => false, 'message' => 'No se pudo eliminar la categoría'];
            }
            
        } catch (Exception $e) {
            error_log("Error en eliminar categoria: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error interno del servidor'];
        }
    }

    // MOSTRAR CON PREPARED STATEMENTS
    public function mostrar($id) {
        try {
            // Validar entrada
            if (!validarEntrada($id, 'entero')) {
                return false;
            }
            
            $sql = "SELECT * FROM categoria WHERE id = ?";
            return ejecutarConsultaPreparadaFila($sql, "i", [$id]);
            
        } catch (Exception $e) {
            error_log("Error en mostrar categoria: " . $e->getMessage());
            return false;
        }
    }

    // LISTAR CON PREPARED STATEMENTS
    public function listar() {
        try {
            $sql = "SELECT * FROM categoria ORDER BY nombre ASC";
            return ejecutarConsultaPreparadaSelect($sql);
            
        } catch (Exception $e) {
            error_log("Error en listar categorias: " . $e->getMessage());
            return false;
        }
    }

    // FUNCIÓN PARA SELECT EN FORMULARIOS
    public function obtenerParaSelect() {
        try {
            $sql = "SELECT id, nombre FROM categoria ORDER BY nombre ASC";
            return ejecutarConsultaPreparadaSelect($sql);
            
        } catch (Exception $e) {
            error_log("Error en obtenerParaSelect: " . $e->getMessage());
            return false;
        }
    }
}
?>
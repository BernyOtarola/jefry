<?php
// Iniciar sesión y validar autenticación
session_start();

// DESCOMENTAR CUANDO IMPLEMENTES AUTENTICACIÓN
/*
if (!isset($_SESSION["id"])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}
*/

// Headers de seguridad
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Verificar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

require_once "../modelos/Categoria.php";

$categoria = new Categoria();

// Validar y limpiar entradas
$id = isset($_POST["id"]) ? filter_var($_POST["id"], FILTER_VALIDATE_INT) : null;
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";

// Validar operación
$operacion = isset($_GET["op"]) ? $_GET["op"] : "";
$operaciones_validas = ['guardar', 'editar', 'eliminar', 'mostrar', 'listar', 'select'];

if (!in_array($operacion, $operaciones_validas)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Operación inválida']);
    exit;
}

try {
    switch ($operacion) {
        case 'guardar':
            if (empty($nombre)) {
                echo json_encode(['success' => false, 'message' => 'El nombre es requerido']);
                break;
            }
            
            $resultado = $categoria->insertar($nombre);
            echo json_encode($resultado);
            break;

        case 'editar':
            if (!$id || empty($nombre)) {
                echo json_encode(['success' => false, 'message' => 'ID y nombre son requeridos']);
                break;
            }
            
            $resultado = $categoria->editar($id, $nombre);
            echo json_encode($resultado);
            break;

        case 'eliminar':
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'ID es requerido']);
                break;
            }
            
            $resultado = $categoria->eliminar($id);
            echo json_encode($resultado);
            break;

        case 'mostrar':
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'ID es requerido']);
                break;
            }
            
            $resultado = $categoria->mostrar($id);
            if ($resultado) {
                echo json_encode($resultado);
            } else {
                echo json_encode(['success' => false, 'message' => 'Categoría no encontrada']);
            }
            break;

        case 'listar':
            $resultado = $categoria->listar();
            if (!$resultado) {
                echo json_encode([
                    "sEcho" => 1,
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "aaData" => []
                ]);
                break;
            }
            
            $data = array();
            while ($reg = $resultado->fetch_object()) {
                $data[] = array(
                    "0" => htmlspecialchars($reg->id),
                    "1" => htmlspecialchars($reg->nombre),
                    "2" => '<button class="btn btn-warning btn-sm" onclick="editar(' . $reg->id . ')">
                               <i class="bx bx-pencil"></i>&nbsp;Editar
                           </button>
                           <button class="btn btn-danger btn-sm ml-2" onclick="showModal(' . $reg->id . ')">
                               <i class="bx bx-trash"></i>&nbsp;Eliminar
                           </button>'
                );
            }
            
            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            echo json_encode($results);
            break;

        case 'select':
            $resultado = $categoria->obtenerParaSelect();
            if (!$resultado) {
                echo '<option value="">Error al cargar categorías</option>';
                break;
            }
            
            echo '<option value="">Seleccione una categoría</option>';
            while ($reg = $resultado->fetch_object()) {
                echo '<option value="' . $reg->id . '">' . htmlspecialchars($reg->nombre) . '</option>';
            }
            break;
    }
    
} catch (Exception $e) {
    error_log("Error en categoria.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
}
?>
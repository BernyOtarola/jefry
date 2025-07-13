<?php

require_once "../modelos/Categoria.php";

$categoria = new Categoria();

$id = isset($_POST["id"]) ? $_POST["id"] : "";
$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";

switch ($_GET["op"]) {
	case 'guardar':
		$rspta = $categoria->insertar($nombre);
		if (intval($rspta) == 1) {
			echo "Categoria Agregada";
		}
		if (intval($rspta) == 1062) {
			echo "Codigo de Categoria Repetida";
		}
		break;

	case 'editar':
		$rspta = $categoria->editar($id, $nombre);
		echo $rspta ? "Categoría actualizada" : "Categoría no se pudo actualizar";

		break;

	case 'eliminar':
		$rspta = $categoria->eliminar($id);
		echo $rspta ? "Categoría eliminada" : "Categoría no se pudo eliminar";

		break;

	case 'mostrar':
		$rspta = $categoria->mostrar($id);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		break;

	case 'listar':
		$rspta = $categoria->listar();
		//Vamos a declarar un array
		$data = array();

		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => $reg->id,
				"1" => $reg->nombre,
				"2" => '<button class="btn btn-warning" onclick="editar(\'' . $reg->id . '\')"><i class="bx bx-pencil"></i>&nbsp;Editar</button><button class="btn btn-danger ml-2" onclick="showModal(\'' . $reg->id . '\')"><i class="bx bx-trash"></i>&nbsp;Eliminar</button>'
			);
		}
		$results = array(
			"sEcho" => 1, //Información para el datatables
			"iTotalRecords" => count($data), //enviamos el total registros al datatable
			"iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);

		break;
}

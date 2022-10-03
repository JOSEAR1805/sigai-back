<?php
include "cabecera.php";
include "lib/funciones_bd.php";
$datos = array();

$arg = getallheaders();
$json_data = json_decode(file_get_contents('php://input'));

$token = $arg["authorization"] ?? $arg["Authorization"] ?? '';
$iduser = sesionactiva($token);

if ($iduser > 0) 
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		header("HTTP/1.1 200 OK");		
		$Resultado=categoriasindicadores($json_data);
		if($Resultado)
		{
			echo $Resultado;		
			exit();
		} else {
			header("HTTP/1.1 200 OK");		
			$Estatus=3;
			$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
			$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "categoria"=>null);
			echo json_encode($json);
			exit();
		}
	}else{
		if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
			header("HTTP/1.1 401 Bad Request");
		}
		}
} else {
	if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
		header("HTTP/1.1 401 Bad Request");
	}
}

?>

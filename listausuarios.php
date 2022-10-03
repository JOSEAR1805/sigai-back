<?php
include "cabecera.php";
include "lib/funciones_bd.php";

$json=array();

$arg = getallheaders();
$json_data = json_decode(file_get_contents('php://input'));

$json_data = (object)$json;

$token = $arg["authorization"] ?? $arg["Authorization"] ?? '';
$iduser = sesionactiva($token);
$vistas = $arg["Cookie"] ?? '';

if ($iduser > 0) 
{
	$json_data->idUsuario = $iduser;
	$json_data->vista = $vistas;

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		header("HTTP/1.1 200 OK");		
		$Resultado=listausuarios($json_data);
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
		    header("HTTP/1.1 200 OK");		
			$Estatus=4;
			$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";			
			$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "categoria"=>null);
			echo json_encode($json);
			exit();
		}
} else {
    header("HTTP/1.1 200 OK");		
    $Estatus=5;
    $Mensaje="No tiene sesion activa";			
    $json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "categoria"=>null);
    echo json_encode($json);
    exit();
}

?>
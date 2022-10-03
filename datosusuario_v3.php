<?php
include "cabecera.php";
include "lib/funciones_bd.php";

$arg = getallheaders();
//$json_data = json_decode(file_get_contents('php://input'));

$token = $arg["Authorization"] ?? '';
$iduser = sesionactiva($token);

if ($iduser > 0) 
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		header("HTTP/1.1 200 OK");		
		$Resultado=datosusuario($iduser);
		if($Resultado)
		{
			header("HTTP/1.1 200 OK");
			echo $Resultado;		
			exit();
		} else {
			header("HTTP/1.1 200 OK");
			$Estatus=3;
			$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
			$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "DatosUsuario"=>[]);
			echo json_encode($json);
			exit();
		}
	}else{
		if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
			header("HTTP/1.1 401 Unauthorized");
		}
	}
} else {
	if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
		header("HTTP/1.1 401 Unauthorized");
	}
}

?>

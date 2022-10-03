<?php
include "cabecera.php";
include "lib/funciones_bd.php";

$arg = getallheaders();
$json_data = json_decode(file_get_contents('php://input'));

if (!isset($json_data)) {	
	$json=array();
	$json_data = (object)$json;
}

$token = $arg["authorization"] ?? $arg["Authorization"] ?? '';
$iduser = sesionactiva($token);

$json_data->idUsuario = $iduser;
/*Nuevo validar vista*/
if (isset($json_data->vista)) {	
	$rvista = vistas($json_data);	
}else{
	$rvista = false;		
}
/*Fin Nuevo*/

if ($iduser > 0 && $rvista) 
{	
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		header("HTTP/1.1 200 OK");		
		$Resultado=guardarmenuperfil($json_data);
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
	if ($iduser < 0) {
		$Mensaje="No tiene sesion activa";			
	} elseif($iduser > 0 && !$rvista){
		$Mensaje="No tiene acceso a la vista";			
	}else {
		$Mensaje="Verificar sesion o permiso a la vista";			
	}
    $json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "categoria"=>null);
    echo json_encode($json);
    exit();
}

?>

<?php

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

include "lib/funciones_bd.php";

$method = $_SERVER["REQUEST_METHOD"];

if ($method == 'POST')
{
	$datos = json_decode(file_get_contents("php://input"));
	
	$idUser = isset($datos->id_usuario) ? $datos->id_usuario : "";
    $idPerfil = isset($datos->id_perfil) ? $datos->id_perfil : "";
    $idGcia = isset($datos->id_gerencia) ? $datos->id_gerencia : "";
    
    $Resultado=listagrafpgcia($idUser, $idPerfil,$idGcia);
	
	if($Resultado)
	{
		header("HTTP/1.1 200 OK");
		echo $Resultado;		
		exit();
	} else {
		header("HTTP/1.1 204 No Content");
		//echo json_encode($Consulta);
		$Estatus=4;
		$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
		$ArrayData3[] = array('id_indicador'=> "", 'nb_indicador'=> "", 'posicion'=>""); 	
		$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "ListaIndicadores"=>$ArrayData1, "ListaIndicadoresMostrar"=>$ArrayData2,"ListadoXgcia"=>$ArrayData3);
		echo json_encode($json);
		exit();
	}
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
// header("HTTP/1.1 400 Bad Request");
?>
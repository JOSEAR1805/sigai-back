<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
header('content-type: application/json; charset=utf-8');

include "lib/funciones_bd.php";

$method = $_SERVER["REQUEST_METHOD"];

if ($method == 'POST'){
//if ($_SERVER['REQUEST_METHOD'] == 'GET')

    $datos = json_decode(file_get_contents("php://input"));
	
    $idUser = isset($datos->id_usuario) ? $datos->id_usuario : "";
    $idPerfil = isset($datos->id_perfil) ? $datos->id_perfil : "";

	$Resultado=listavpgmetaresul($idUser, $idPerfil);
	
	if($Resultado)
	{
		header("HTTP/1.1 200 OK");
		echo $Resultado;		
		exit();
	} else {
		header("HTTP/1.1 204 No Content");
		//echo json_encode($Consulta);
		$Estatus=3;
		$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
		//$Url="";
			$fila=["id_indicador"=>"", "nb_indicador"=>"", "año"=>"" ];
			$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "DatosUsuario"=>$fila);
		echo json_encode($json);
		exit();
	}
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>

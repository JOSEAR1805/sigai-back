<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

include "lib/funciones_bd.php";

$datos = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	header("HTTP/1.1 200 OK");
	
	$idUsuario = isset($datos->idUsuario) ? $datos->idUsuario : "";
    $idPerfil = isset($datos->idPerfil) ? $datos->idPerfil : "";
	
    $Resultado=listavpgmetaresul($idUsuario, $idPerfil);

    if($Resultado)
	{
		header("HTTP/1.1 200 OK");
		echo $Resultado;		
		exit();
	} else {
		header("HTTP/1.1 204 No Content");
		$Estatus=3;
		$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
		$ArrayData=["id_vice_presidencia"=>"", "nb_vicepresidencia"=>"" ];
		$ArrayData2=["id_gerencia"=>"", "nb_gerencia"=>"", "id_vice_presidencia"=>"" ];
		$indicadores=["id_indicador"=>"", "nb_indicador"=>"", "id_gerencia"=>"", "permiso"=>""];
		$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "listavicepresidencias"=>$ArrayData,"listagerencias"=>$ArrayData2, "indicadores"=>$indicadores);
		echo json_encode($json);
		exit();
	}

//    print_r($json_data);
}else{
    // header("HTTP/1.1 400 Bas Request");
}

?>
<?php
include "cabecera.php";
include "lib/funciones_bd.php";

$arg = getallheaders();
$json_data = json_decode(file_get_contents('php://input'));
$token=$arg["Authorization"];
$temporal=array("token"=>$token);
//archivo(json_encode($arg), "token.txt");
//$datos= array("encabezado"=>$token, "data"=>$json_data);
//echo json_encode($temporal);
//exit();



if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	header("HTTP/1.1 200 OK");
    $Resultado=datosusuario($temporal);

    if($Resultado)
	{
		header("HTTP/1.1 200 OK");
		echo $Resultado;		
		//echo json_encode('Hay datos');
		exit();
	} else {
		header("HTTP/1.1 204 No Content");
		//echo json_encode($Consulta);
		$Estatus=3;
		$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
		//$Url="";
			$fila=["idindica"=>"", "nb_indicador"=>"", "año"=>"" ];
			$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "DatosUsuario"=>$fila);
		echo json_encode($json);
		exit();
	}

//    print_r($json_data);
}else{
    //header("HTTP/1.1 400 Bas Request");
}

?>

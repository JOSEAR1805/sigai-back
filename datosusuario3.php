<?php
include "headres.php";
/*
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
*/
include "lib/funciones_bd.php";
//var_dump("Algo");
$json_data = json_decode(file_get_contents('php://input'));
//$token=$_POST['token'];
$token=json_decode(get_headers());
//$token2=$token["token"];
//$token=json_decode(getallheaders(file_get_contents('php://input')));
//$token="El token".$_SERVER['tok'];
$arg = getallheaders();
$token=$arg["Token"];
//$token = $_SERVER['argc'];
//$token = "El token".$_REQUEST['tok'];
//console (print_r("El token: ".$token['tok']));
//archivo ("El token: ".print_r($token),"token.txt");
archivo(json_encode($arg), "token.txt");
echo $token;
//echo $token;
exit();



if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	header("HTTP/1.1 200 OK");
    $Resultado=datosusuario($json_data);

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

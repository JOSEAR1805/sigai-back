<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

include "lib/funciones_bd.php";

$json_data = json_decode(file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

	/* Esto lo puse para que el programador pudiera trabajar mientras que se corrige la funcion */
	
	$datosGenerales=["idVicePresidencia"=>5, "vicePesidencia"=>"PRESTACI?N DE SERVICIOS","idGerencia"=>20, "gerencia"=>"MERCADOS MASIVOS", "nombreIndicador"=>"CANTIDAD CLIENTES VOZ", "unidadIndicador"=>"CLIENTES", "fechaMaxIngresoDatos"=>"2021-02-20", "fechaModDatos"=>"2021-11-29", "permiso"=>2];

	$listaMetas[0]=["idmes"=>1,"nbMes"=>"enero","valor"=>19144.00,"observacion"=>null];
	$listaMetas[1]=["idmes"=>2,"nbMes"=>"febrero","valor"=>19137.00,"observacion"=>null];
	$listaMetas[2]=["idmes"=>3,"nbMes"=>"marzo","valor"=>17873.00,"observacion"=>null];
	$listaMetas[3]=["idmes"=>4,"nbMes"=>"abril","valor"=>18461.00,"observacion"=>null];
	$listaMetas[4]=["idmes"=>5,"nbMes"=>"mayo","valor"=>20000.00,"observacion"=>null];
	$listaMetas[5]=["idmes"=>6,"nbMes"=>"junio","valor"=>0.00,"observacion"=>null];
	$listaMetas[6]=["idmes"=>7,"nbMes"=>"julio","valor"=>0.00,"observacion"=>null];
	$listaMetas[7]=["idmes"=>8,"nbMes"=>"agosto","valor"=>0.00,"observacion"=>null];
	$listaMetas[8]=["idmes"=>9,"nbMes"=>"septiembre","valor"=>0.00,"observacion"=>null];
	$listaMetas[9]=["idmes"=>10,"nbMes"=>"octubre","valor"=>0.00,"observacion"=>null];
	$listaMetas[10]=["idmes"=>11,"nbMes"=>"noviembre","valor"=>0.00,"observacion"=>null];
	$listaMetas[11]=["idmes"=>12,"nbMes"=>"diciembre","valor"=>0.00,"observacion"=>null];
	
	$datos=["estatus"=>1, "mensaje"=>"Datos Indicador", "datosGenerales"=>$datosGenerales, "listaMetas"=>$listaMetas];
	
	echo json_encode($datos);
	exit();
	
	
	
	
	
	header("HTTP/1.1 200 OK");
    $Resultado=datosmetasindicador($json_data);

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
    header("HTTP/1.1 400 Bas Request");
}

?>

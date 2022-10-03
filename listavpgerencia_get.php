<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

include "lib/funciones_bd.php";
//  listar todos los posts o solo uno
Archivo("Llega aqui","verificar1.txt");
// Inicio POST
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	header("HTTP/1.1 200 OK");

    //Param IN
    //(Id_Indicador, Año)

    if (isset($_GET['id_Usuario'])) $idUser = $_GET['id_Usuario']; else $idUser = '';
	if (isset($_GET['id_Perfil'])) $idPerfil = $_GET['id_Perfil']; else $idPerfil= '';
        
	Archivo("Id_Indicador: ".$idUser.", Año: ".$idPerfil, "verificar1.txt");
	
	$Resultado=listavpgcia($idUser, $idPerfil);
	Archivo($Resultado, "verificar2.txt");

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
			$fila=["id_indicador"=>"", "nb_indicador"=>"", "año"=>"" ];
			$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "DatosUsuario"=>$fila);
		echo json_encode($json);
		exit();
	}
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
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

    if (isset($_GET['id_Indicador'])) $id_indicador = $_GET['id_Indicador']; else $id_indicador = '';
	if (isset($_GET['anio'])) $year = $_GET['anio']; else $year= date("Y");
        
	Archivo("id_indicador: ".$id_indicador.", Año: ".$year, "verificar1.txt");
	
	$Resultado=infoindicagra($id_indicador, $year);
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
			$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Pagina"=>$Url, "DatosUsuario"=>$fila);
		echo json_encode($json);
		exit();
	}
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
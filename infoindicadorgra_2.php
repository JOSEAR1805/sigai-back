<?php

include "lib/funciones_bd.php";
//  listar todos los posts o solo uno
Archivo("Llega aqui","verificar1.txt");
// Inicio POST
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	header("HTTP/1.1 200 OK");

    //Param IN
    //(Id_Indicador, Año)

    if (isset($_GET['id_indicador'])) $id_indicador = $_GET['id_indicador']; else $id_indicador = '';
	if (isset($_GET['año'])) $year = $_GET['año']; else $year= '';
        
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
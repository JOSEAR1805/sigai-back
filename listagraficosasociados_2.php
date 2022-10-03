<?php

include "lib/funciones_bd.php";
//  listar todos los posts o solo uno
Archivo("Llega aqui","ListaGraficos0.txt");
// Inicio POST
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	header("HTTP/1.1 200 OK");

    //Param IN
    //(Id_Usuario, Id_Perfil)

    if (isset($_GET['id_Usuario'])) $idUsuario = $_GET['id_Usuario']; else $idUsuario = '';
	if (isset($_GET['id_Perfil'])) $idPerfil = $_GET['id_Perfil']; else $idPerfil= '';
        
	Archivo("Id_Usuario: ".$idUsuario.", Id_Perfil: ".$idPerfil, "ListaGraficos1.txt");
	
	$Estatus=3;
	$Mensaje="prueba";
	$Url="";
	$fila1[0]=array("id_Indicador"=>"1", "Nombre_Indicador"=>"Indicador 1");
	$fila1[1]=array("id_Indicador"=>"2", "Nombre_Indicador"=>"Indicador 2");
	$fila1[2]=array("id_Indicador"=>"3", "Nombre_Indicador"=>"Indicador 3");
	$fila1[3]=array("id_Indicador"=>"4", "Nombre_Indicador"=>"Indicador 4");
	$fila1[4]=array("id_Indicador"=>"5", "Nombre_Indicador"=>"Indicador 5");
	$fila1[5]=array("id_Indicador"=>"6", "Nombre_Indicador"=>"Indicador 6");
	$fila1[6]=array("id_Indicador"=>"7", "Nombre_Indicador"=>"Indicador 7");
	$fila1[7]=array("id_Indicador"=>"8", "Nombre_Indicador"=>"Indicador 8");
	$fila2[0]=["Id_Indicador"=>"1","Nombre_Indicador"=>"Indicador 1"];
	$fila2[1]=["Id_Indicador"=>"3","Nombre_Indicador"=>"Indicador 3"];
	$fila2[2]=["Id_Indicador"=>"4","Nombre_Indicador"=>"Indicador 4"];
	$fila2[3]=["Id_Indicador"=>"7","Nombre_Indicador"=>"Indicador 7"];
	$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "ListaIndicadores"=>$fila1, "ListaIndicadoresMostrar"=>$fila2);
	echo json_encode($json);
	exit();
	
	
	$Resultado=listarindicadores($idUsuario, $idPerfil);
	Archivo($Resultado, "verificarusuario2.txt");
	// $fila1=["id_Indicador"=>"", "Nombre_Indicador"=>""];
		// $fila2=["ListaIndicadoresMostrar",["Id_Indicador"=>"","Nombre_Indicador"=>""]];
		$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Resultado"=>$Resultado);
		echo json_encode($json);
		exit();
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
		$Url="";
			//$fila=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "nb_gerencia gerencia"=>"", "id_unid_admin"=>"", "nombe unidad_administrativa"=>"", "id_tipo_doc_ident"=>"", "nemonico_doc tipo_doc_identidad"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "nombre_jerarquia"=>"",  "clave"=>"", "nb_usuario"=>""];
			$fila1=["id_Indicador"=>"", "Nombre_Indicador"=>""];
			$fila2=["ListaIndicadoresMostrar",["Id_Indicador"=>"","Nombre_Indicador"=>""]];
			$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "ListaIndicadores"=>$fila1, "ListaIndicadoresMostrar"=>$fila2);
		echo json_encode($json);
		exit();
	}
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
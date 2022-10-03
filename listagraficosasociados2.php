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
	
	$Resultado=listarindicadores2($idUsuario, $idPerfil);
	Archivo($Resultado, "verificarusuario2.txt");

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
			$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "ListaIndicadores"=>$fila, "ListaIndicadoresMostrar"=>$fila2);
		echo json_encode($json);
		exit();
	}
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
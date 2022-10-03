<?php
include "cabecera.php";
include "lib/funciones_bd.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$datos = json_decode(file_get_contents("php://input"));

    $Usuario = isset($datos->usuario) ? $datos->usuario : "";
    $Clave = isset($datos->clave) ? $datos->clave : "";
	
	$Resultado=usuariovalido($Usuario, $Clave);
	
	if($Resultado)
	{
		header("HTTP/1.1 200 OK");
		echo $Resultado;
		//echo json_encode('Hay datos');
		exit();
	} else {
		header("HTTP/1.1 204 No Content");
		$Estatus=4;
		$Mensaje="Error al tratar de consumir el recurso, por favor consulte con al administrador";
		$Url="";
		$fila=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "nb_gerencia gerencia"=>"", "id_unid_admin"=>"", "nombe unidad_administrativa"=>"", "id_tipo_doc_ident"=>"", "nemonico_doc tipo_doc_identidad"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "nombre_jerarquia"=>"",  "clave"=>"", "nb_usuario"=>""];
		$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Token"=>"", "Pagina"=>$Url, "DatosUsuario"=>$fila);
		echo json_encode($json);
		exit();
	}
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
// header("HTTP/1.1 400 Bad Request");
?>
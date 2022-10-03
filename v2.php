<?php
// include "db.php";
include "lib/funciones_bd.php";
// $dbConn =  connect($db);
/*
  listar todos los posts o solo uno
 */
// Consultar si el usuario es valido

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	header("HTTP/1.1 200 OK");
	if (isset($_POST['Usuario'])) $Usuario = $_POST['Usuario']; else $Usuario = '';

	if (isset($_POST['Clave'])) $Clave = $_POST['Clave']; else $Clave= '';
	$Resultado=usuariovalido($Usuario, $Clave);
/*	if($Resultado){
		header("HTTP/1.1 207 multi Status");
	}else{
		header("HTTP/1.1 410 Gone");
}*/
}elseif($_SERVER['REQUEST_METHOD'] == 'PUT'){
	header("HTTP/1.1 206 Partial Content");
}
else{
	header("HTTP/1.1 401 Bad Request");
}
/*if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	//$array=array('uno'=>1,'dos'=>2,'tres'=>3,'cuatro'=>4);
	header("HTTP/1.1 200 OK");
	if (isset($_POST['Usuario'])) $Usuario = $_POST['Usuario']; else $Usuario = '';
	if (isset($_POST['Clave'])) $Clave = $_POST['Clave']; else $Clave= '';
	//echo json_encode($Clave);
	//exit();
	Archivo("Usuario: ".$Usuario.", Clave: ".$Clave, "usuariovalido1.txt");
	$Resultado=usuariovalido($Usuario, $Clave);
	Archivo($Resultado, "usuariovalido2.txt");
	// $dbConn= new AccesoDB;
	// $query = "SELECT Id_Usuario, Usuario FROM usuarios WHERE Usuario='".$Usuario."' and Clave=MD5('".$Clave."')";
	// $Consulta=$dbConn->DB_Consultar($query);
	// $resp=mysqli_fetch_row($Consulta);
	// Archivo($Resultado,'us_valido.txt');
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
		$fila=["id_usuario"=>"", "id_perfil"=>"", "id_gerencia"=>"", "id_unid_admin"=>"", "id_tipo_doc_ident"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "clave"=>"", "nb_usuario"=>""];
		$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Pagina"=>$Url, "DatosUsuario"=>$fila);
		echo $json;
		exit();
	}
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 401 Bad Request");
?>*/

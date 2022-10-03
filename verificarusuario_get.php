<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Origin: https://sigai.vercel.app');
// header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
// header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];

include "lib/funciones_bd.php";
Archivo("Metodo: ".$method, "verificarusuario0.txt");
	
// Consultar si el usuario es valido
//$Prueba=$_GET;
	//var_dump($Prueba);
	//exit;
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
	// Archivo("Metodo: ".$_GET, "verificarusuario01.txt");
	
	//$array=array('uno'=>1,'dos'=>2,'tres'=>3,'cuatro'=>4);
	header("HTTP/1.1 200 OK");
	if (isset($_GET['Usuario'])) $Usuario = $_GET['Usuario']; else $Usuario = '';
	if (isset($_GET['Clave'])) $Clave = $_GET['Clave']; else $Clave= '';
	//echo json_encode($Clave);
	//exit();
	
	Archivo("Usuario: ".$Usuario.", Clave: ".$Clave, "verificarusuario1.txt");
	
	// $Estatus=4;
	// $Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	// $Url="";
	// $fila=["id_usuario"=>"", "id_perfil"=>"", "id_gerencia"=>"", "id_unid_admin"=>"", "id_tipo_doc_ident"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "clave"=>"", "nb_usuario"=>""];
	// $json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Pagina"=>$Url, "DatosUsuario"=>$fila);
	// echo json_encode($json);
	// exit();
	
	
	$Resultado=usuariovalido($Usuario, $Clave);
	Archivo($Resultado, "verificarusuario2.txt");
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
		$fila=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "nb_gerencia gerencia"=>"", "id_unid_admin"=>"", "nombe unidad_administrativa"=>"", "id_tipo_doc_ident"=>"", "nemonico_doc tipo_doc_identidad"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "nombre_jerarquia"=>"",  "clave"=>"", "nb_usuario"=>""];
		$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Pagina"=>$Url, "DatosUsuario"=>$fila);
		echo json_encode($json);
		exit();
	}
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
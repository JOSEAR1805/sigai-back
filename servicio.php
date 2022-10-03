<?php
// include "db.php";
// include "lib/funciones_bd.php";
// $dbConn =  connect($db);
/*
  listar todos los posts o solo uno
 */
 // Archivo("Llega aqui","Servicio.txt");
// Consultar si el usuario es valido
// if ($_SERVER['REQUEST_METHOD'] == 'POST')
// {
	//$array=array('uno'=>1,'dos'=>2,'tres'=>3,'cuatro'=>4);
	header("HTTP/1.1 200 OK");
	// if (isset($_POST['Usuario'])) $Usuario = $_POST['Usuario']; else $Usuario = '';
	// if (isset($_POST['Clave'])) $Clave = $_POST['Clave']; else $Clave= '';
	//echo json_encode($Clave);
	//exit();
	
	// Archivo("Usuario: ".$Usuario.", Clave: ".$Clave, "verificarusuario1.txt");
	
	$Estatus=1;
	$Mensaje="Esto es un ejemplo para ver si funciona el servicio";
	// $Estatus=3;
	// $Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$Url="";
	$fila=["id_usuario"=>"", "id_perfil"=>"", "id_gerencia"=>"", "id_unid_admin"=>"", "id_tipo_doc_ident"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "clave"=>"", "nb_usuario"=>""];
	$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Pagina"=>$Url, "DatosUsuario"=>$fila);
	echo json_encode($json);
	exit();
	
	
	//$Resultado=usuariovalido($Usuario, $Clave);
	// Archivo($Resultado, "verificarusuario2.txt");
	// $dbConn= new AccesoDB;
	// $query = "SELECT Id_Usuario, Usuario FROM usuarios WHERE Usuario='".$Usuario."' and Clave=MD5('".$Clave."')";
	// $Consulta=$dbConn->DB_Consultar($query);
	// $resp=mysqli_fetch_row($Consulta);
	// Archivo($Resultado,'us_valido.txt');
	/* if($Resultado)
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
		echo json_encode($json);
		exit();
	} */
// }
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
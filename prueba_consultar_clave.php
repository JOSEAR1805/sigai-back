<?php
// include 'lib/db.php';
include "lib/funciones_bd.php";
include 'lib/clas_usu_logueo_clave.php';
echo "Comienza la prueba. <br>";
$dbConn= new AccesoDB;

$tabla='cantv."Usuarios"';
$id_usuario=1;

$clave= new ClaveUsuario();
$enviada="123";




$query="SELECT id_usuario, id_perfil, id_gerencia, id_unid_admin, id_tipo_doc_ident, doc_identidad, nombres, apellidos, f_nac, f_creacion, f_modif, id_us_mod, id_jerarquia, clave, nb_usuario
	FROM $tabla WHERE id_usuario= $id_usuario";
	
	
 
$Consulta=$dbConn->db_Consultar($query);

echo $query."<br>";
echo 'Filas: ';
// print_r ($Consulta -> rowCount());
echo $dbConn->db_Num_Rows($Consulta);
 echo "<br>";
 
 if ($dbConn->db_Num_Rows($Consulta)){
	//Comparo las claves
	
	$ClaveUsuariobd=$dbConn->fetch_associativo($Consulta);
	echo "<br>";
	print_r($ClaveUsuariobd);
	echo "<br>";
	echo "Usuario: ".$ClaveUsuariobd["nb_usuario"];
	echo "<br>";
	echo "Clave: ".$ClaveUsuariobd["clave"];
	echo "<br>";
	echo $clave->verificarClave($enviada,$ClaveUsuariobd["clave"]);
	
	if ($clave->verificarClave($enviada,$ClaveUsuariobd["clave"])==1){
		echo "<br>";
		echo "Clave Correcta";
	}else{
		echo "<br>";
		echo "Clave Incorrecta";
	}
	
 }else{
	 
	 echo "No trae datos";
	 
 }


?>
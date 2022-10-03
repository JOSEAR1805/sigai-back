<?php
include 'db.php';
$dbConn= new AccesoDB;

$tabla='cantv."Usuarios"';
$id_perfil=1 ;
$id_gerencia=1 ;
$id_unid_admin=1 ;
$id_tipo_doc_ident=1 ;
$doc_identidad='12345678' ;
$nombres='Ususrio de' ;
$apellidos='Prueba 1' ;
$f_nac='2021/08/01 17:00:00' ;
$f_creacion='2021/08/01 17:00:00' ;
$f_modif='2021/08/01 17:00:00' ;
$id_us_mod=1 ;
$id_jerarquia=1 ;
	
$query="INSERT INTO $tabla (id_gerencia, id_unid_admin, id_tipo_doc_ident, doc_identidad, nombres, apellidos, f_nac, f_creacion, f_modif, id_us_mod, id_jerarquia)
	VALUES ('$id_gerencia', '$id_unid_admin', '$id_tipo_doc_ident', '$doc_identidad', '$nombres', '$apellidos', '$f_nac', '$f_creacion', '$f_modif', '$id_us_mod', '$id_jerarquia')";

 /* $tabla='cantv."Perfiles"';
$nombre_perfil="Vice-Presidencia";
$descripcion="Perfil de Vice-Presidencia";
$query="INSERT INTO $tabla (nombre_perfil, descripcion)
	VALUES ('$nombre_perfil', '$descripcion')";
 */
 
$Consulta=$dbConn->db_Insertar($query);

echo $query."<br>";
echo 'Filas: '.$Consulta;
// echo $Consulta->rowCount();
?>
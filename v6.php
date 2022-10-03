<?php 
$host="66.23.226.204";
$puerto="5432";
$usuario="des4";         
$clave  ="1nd1c4";    
$db     ="indicadores";
#$con_str = "host=$host port=$puerto dbname=$db user=$usuario password=$clave";

$con=pg_connect("host=localhost dbname=indicadores user=des4 password=1nd1c4");
#$con = pg_connect($con_str);
if ($con){
	echo 'Conectado';
}else{
	echo 'ERROR';
}
#pg_connect("host=localhost dbname=indicadores user=des4 password=1nd1c4") or die("ERROR BD: ".pg_last_error());
#try{
#$bd= pg_connect("host=$host port=$puerto dbname=$db user=$usuario password=$clave")
#	or die('No hay conexion: ' . pg_last_error());
	#$bd1->setAttribute(PDO::ATTR_ERRMDODE, PDO::ERRMODE_EXCEPTION);
#} catch (Exception $e) {
#	echo "ERROR de conexion: " . $e->getMessage();
#}


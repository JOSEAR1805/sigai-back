<?php 
#$host="66.23.226.204";
$host="localhost";
$puerto="5432";
$usuario="des4";         
$clave  ="1nd1c4";    
$db     ="indicadores";

$str = "host=$host port=$puerto dbname=$db user=$usuario password=$clave";
#$str = "host=localhost port=5432 dbname=indicadores user=des4 password=1nd1c4";
$con = pg_connect($str) or die("Error de conexion");
/*if(!$con){
	echo "ERROR: Sin conexion a a la BD\n";
}else{
	echo "Conexion EXITOSA";
}
*/
#pg_close($con);

?>

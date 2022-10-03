<?php 
$host="66.23.226.204";
$puerto="5432";
$usuario="des4";         
$clave  ="1nd1c4";    
$db     ="indicadores";
try{
	$bd1 = new PDO("pgsql:host=$host;port=$puerto;dbname=$db", $usuario, $clave);
	$bd1->setAttribute(PDO::ATTR_ERRMDODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
	echo "ERROR de conexion: " . $e->getMessage();
}


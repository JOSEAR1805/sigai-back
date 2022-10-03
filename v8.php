<?php

require 'config.php';

#$con =new PDO('pgsql:host=localhost;dbname=indicadores','des4','1nd1c4');
#$con =new PDO("pgsql:host=localhost;dbname=indicadores','des4','1nd1c4');

$str_con="pgsl:host=$host;dbname=$db,$user,$clave";
#var_dump($str_con);
$con =new PDO($str_con);

if($con){
	echo 'Conectado';
}else{
	echo 'ERROR';
}


?>

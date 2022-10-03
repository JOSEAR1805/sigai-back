<?php
include "lib/funciones_bd.php";
// var_dump (usuariovalido("prueba1", "123"));
// Archivo("Esto es una prueba","Prueba.txt");
$v1=2;
$v2=3;
//$v3=5;

$parametros['id_Usuario']=$v1;
$parametros['id_Perfil']=$v2;
//$parametros['id_Gerencia']=$v3;
var_dump($parametros);
//exit();

$ch = curl_init();
// echo REST_COPA."verificarusuario.php"; exit();
// curl_setopt($ch, CURLOPT_URL, "http://localhost/indican/verificarusuario.php");
curl_setopt($ch, CURLOPT_URL, "http://66.23.226.204/indican/listagraficosasociados.php");
// curl_setopt($ch, CURLOPT_URL, REST_COPA."verificarusuario.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//establecemos el verbo http que queremos utilizar para la petición
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//Defino los parametros a transferir al rest
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parametros));
$res = curl_exec($ch);
$StatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo $StatusCode.'<br>';
// exit();
if($StatusCode!='200')
{
    echo "FAULT: <p>Code: (".$StatusCode.")</p>";
    // echo "String: ".$uspost->faultstring;
	echo "Problema al llamar al servicio. Codigo: ".$StatusCode;
    exit();
}

// $Resultado=mysqli_fetch_assoc($res);
// var_dump ($res); exit;
//Codigo 200 se hizo la consulta y trajo datos, Usuario y clave correctos
////echo 'Usuario y clave correctos'.$StatusCode.'<br>';
$Resultado=json_decode($res);
echo "Respuesta: <br>";
var_dump($res); 
// echo "<br>Respuesta2: <br>";
// print_r($res); 
// exit();
$Estatus=$Resultado->Estatus; //0
$Mensaje=$Resultado->Mensaje; //1
$Pagina_Inicio=$Resultado->Pagina; //2

// echo '<br>Estatus: ';
// echo $Estatus;
// echo '<br>Mensaje: ';
// echo $Mensaje;
// echo '<br> Id_Usuario: ';
// echo $Id_Usuario;
// echo '<br> Pagina Inicio: ';
// echo $Pagina_Inicio;
// exit();


?>
<?php
include "cabecera.php";
include "lib/funciones_bd.php";

$method = $_SERVER["REQUEST_METHOD"];

// Consultar si el usuario es valido
if ($method == "POST") {
    $datos = json_decode(file_get_contents("php://input"));

    $Usuario = isset($datos->usuario) ? $datos->usuario : "";
    $Clave = isset($datos->clave) ? $datos->clave : "";

    $Resultado = usuariovalido($Usuario, $Clave);

    if ($Resultado) {
        header("HTTP/1.1 200 OK");
        echo $Resultado;
        exit();
    } else {
        header("HTTP/1.1 204 No Content");
        exit();
    }
}

// En caso de que ninguna de las opciones anteriores se haya ejecutado
// header("HTTP/1.1 400 Bad Request");

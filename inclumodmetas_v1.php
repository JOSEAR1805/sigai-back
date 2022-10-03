<?php

include "cabecera.php";
include "lib/funciones_bd.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $json_data = json_decode(file_get_contents('php://input'));
    $Resultado = inclumodmetas($json_data);

    if ($Resultado) {
        header("HTTP/1.1 200 OK");
        echo $Resultado;
        //echo json_encode('Hay datos');
        exit();
    } else {
        header("HTTP/1.1 204 No Content");
        //echo json_encode($Consulta);
        $Estatus = 3;
        $Mensaje = "Error al tratar de consumir el recurso, por favor consulte con el administrador";
        //$Url="";
        $fila = ["idindica" => "", "nb_indicador" => "", "año" => ""];
        $json = array("Estatus" => $Estatus, "Mensaje" => $Mensaje, "DatosUsuario" => $fila);
        echo json_encode($json);
        exit();
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
        header("HTTP/1.1 400 Bas Request");
    }
}

?>

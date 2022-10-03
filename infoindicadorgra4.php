<?php
include "cabecera.php";
include "lib/funciones_bd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $datos = json_decode(file_get_contents("php://input"));

    $Resultado = infoindicagra($datos);

    if ($Resultado) {
        header("HTTP/1.1 200 OK");
        echo $Resultado;
        exit();
    } else {
        header("HTTP/1.1 200 OK");
        //echo json_encode($Consulta);
        $Estatus = 3;
        $Mensaje = "Error al tratar de consumir el recurso, por favor consulte con el administrador";
        //$Url="";
        //$fila = ["id_indicador" => "", "nb_indicador" => "", "año" => ""];
        $json = array("Estatus" => $Estatus, "Mensaje" => $Mensaje, "Pagina" => $Url, "DatosUsuario" => []);
        echo json_encode($json);
        exit();
    }
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
// header("HTTP/1.1 400 Bad Request");
?>

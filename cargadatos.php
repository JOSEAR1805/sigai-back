<?php
include "cabecera.php";
include "funciones_bd.php";
//  listar todos los posts o solo uno
//Archivo("Llega aqui","verificar1.txt");
// Inicio POST
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	header("HTTP/1.1 200 OK");

    //Param IN
    //(Id_Indicador, Año)

    if (isset($_POST['idindica'])) $idindica = $_POST['idindica']; else $idindica = '';
	if (isset($_POST['mes'])) $mes = $_POST['mes']; else $mes= '';
    if (isset($_POST['anio'])) $anio = $_POST['anio']; else $anio= '';
    if (isset($_POST['tipo'])) $tipo = $_POST['tipo']; else $tipo= '';
    if (isset($_POST['idusuaut'])) $idusuaut = $_POST['idusuaut']; else $idusuaut= '';
    if (isset($_POST['idusu'])) $idusu = $_POST['idusu']; else $idusu= '';
    if (isset($_POST['cant'])) $cant = $_POST['cant']; else $cant= '';
        
	//Archivo("Id_Usuario: ".$idUser.", Perfil: ".$idPerfil, "verificar1.txt");
	$Resultado=cargadatos($idindica, $mes, $anio, $tipo, $idusuaut, $idusu, $cant);
//	Archivo($Resultado, "verificar2.txt");

	if($Resultado)
	{
		header("HTTP/1.1 200 OK");
		echo $Resultado;		
		//echo json_encode('Hay datos');
		exit();
	} else {
		header("HTTP/1.1 204 No Content");
		//echo json_encode($Consulta);
		$Estatus=3;
		$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
		//$Url="";
			$fila=["idindica"=>"", "nb_indicador"=>"", "año"=>"" ];
			$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "DatosUsuario"=>$fila);
		echo json_encode($json);
		exit();
	}
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>

<?php
include 'config.php';
//Funciones de acceso a datos

class AccesoDB{
    private $host=HOST;
	private $puerto=PUERTO;
    private $usuario=USUARIO;         
    private $clave  =CLAVE;    
    private $db     =DB;
    public $conexion;
    public function __construct(){
		$this->conexion = pg_connect("host=$this->host port=$this->puerto dbname=$this->db user=$this->usuario password=$this->clave");
        //or die('No se ha podido conectar: ' . pg_last_error());
        // $this->conexion->set_charset("utf8");

    }
    //INSERTAR
    public function db_Insertar($query,$Id=0){
		try {
			$resultado = $this->conexion->prepare($query);
  
			if ($resultado->execute()){
				
				$ultimoInsertId = $this->conexion->lastInsertId();
				return $ultimoInsertId;
			} else {
				
				 $ultimoInsertId = 0;
				//echo $resultado->errorInfo()[2];
				return $ultimoInsertId;
			}
			
		} catch (PDOException $e){
			//Aqui dbe ir a grabar en aoritoria el error que se genero
			$error= "Error insertando: ".$e->getMessage();
			return $error;
		}
    } 
    //BORRAR
    public function db_Borrar($query){    
		try {
			$resultado = $this->conexion->prepare($query);
			$resultado->execute();
			if ($resultado->rowCount() > 0){
				
				$afectados = $resultado->rowCount();
				return $afectados;
			} else {
				
				 $afectados = 0;
				// echo $resultado->errorInfo()[2];
				return $afectados;
				
			}
		} catch (PDOException $e){
			//Aqui dbe ir a grabar en aoritoria el error que se genero
			$error= "Error insertando: ".$e->getMessage();
		}
    }
    //ACTUALIZAR
    public function db_Actualizar($query){    
        try {
			$resultado = $this->conexion->prepare($query);
			$resultado->execute();
			if ($resultado->rowCount() > 0){
				
				$afectados = $resultado->rowCount();
				return $afectados;
			} else {
				
				 $afectados = 0;
				// echo $resultado->errorInfo()[2];
				return $afectados;
				
			}
		} catch (PDOException $e){
			//Aqui dbe ir a grabar en aoritoria el error que se genero
			$error= "Error insertando: ".$e->getMessage();
		}
    }
    //BUSCAR
    public function db_Consultar($query){
		try{
			$resultado = pg_query($this->conexion,$query) or die('La consulta fallo: ' . pg_last_error());
			// $resp=mysqli_fetch_row($resultado);
			// Archivo($resp[1],'Consuta.txt');
			if ($resultado)
				return $resultado;
				// return $resultado->fetch_all(MYSQLI_ASSOC);
				//return $resultado;
			return false;
		} catch (PDOException $e){
			//Aqui dbe ir a grabar en aoritoria el error que se genero
			$error= "Error consultando: ".$e->getMessage();
			return false;
		}
    }
	// NUM_ROWS o numero de filas de una consulta
	function db_Num_Rows($rs_conn="") {
            
			if($rs_conn && $rs_conn!="") $num_rows = pg_num_rows($rs_conn);
            else $num_rows = 0;
            return $num_rows; 
	}
	
	function fetch_associativo($rs_conn=""){
		if($rs_conn && $rs_conn!="") $num_rows = pg_fetch_assoc($rs_conn);
        else $num_rows = 0;
        return $num_rows; 
		
		
	}
}


function usuariovalido($Usuario, $Clave){
	include 'clas_usu_logueo_clave.php';
	// include 'config.php';
	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$Url="";
	$Pagina="";
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>"", "id_unid_admin"=>"", "unidad_administrativa"=>"", "id_tipo_doc_ident"=>"", "tipo_doc_identidad"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "nombre_jerarquia"=>"", "clave"=>"", "nb_usuario"=>""];
	
	$token=mt_rand(1000,10000);  // Se emite un numero aleatorio de manera temporal, de manera que se sustituya por el numero de sesion de la tabla de sesiones
	
	if ($Usuario<>"" and  $Clave<>""){
		
		$dbConn= new AccesoDB;
		$tabla='cantv."Usuarios"';
		$esquema=ESQUEMA;
		$query='SELECT us.id_usuario id_usuario, us.id_perfil id_perfil, p.nombre_perfil nombre_perfil, us.id_gerencia id_gerencia, ger.nb_gerencia gerencia, us.id_unid_admin id_unid_admin, ua.nombe unidad_administrativa, us.id_tipo_doc_ident id_tipo_doc_ident, tdi.nemonico_doc tipo_doc_identidad, us.doc_identidad doc_identidad, us.nombres nombres, us.apellidos apellidos, us.f_nac f_nac, us.f_creacion f_creacion, us.f_modif f_modif, us.id_us_mod id_us_mod, us.id_jerarquia id_jerarquia, jq.nombre_jerarquia nombre_jerarquia,  us.clave clave, us.nb_usuario nb_usuario 
		FROM cantv."Usuarios" us
		INNER JOIN cantv."Perfiles" p ON us.id_perfil=p.id_perfil
		INNER JOIN cantv."gerencia" ger ON us.id_gerencia=ger.id_gerencia
		INNER JOIN cantv."Unidad_Administrativa" ua ON us.id_unid_admin=ua.id_unid_admin
		INNER JOIN cantv."Tipo_Doc_Identidad" tdi ON us.id_tipo_doc_ident=tdi.id_tipo_doc_ident
		INNER JOIN cantv."Jerarquias" jq ON us.id_jerarquia=jq.id_jerarquia
		WHERE us.nb_usuario='.chr(39).$Usuario.chr(39);
		Archivo($query, "usuariovalido1.txt");
		$Consulta=$dbConn->db_Consultar($query);
		$Numfilas=$dbConn->db_Num_Rows($Consulta);
		Archivo($Numfilas, "usuariovalido2.txt");
		if ($Numfilas>0){
			//Si hay registros es porque es un usuario existente
			// se verifica la clave
			
			$fila=$dbConn->fetch_associativo($Consulta);
				
			$ClaveUsuariobd=$fila["clave"];
			Archivo($ClaveUsuariobd, "usuariovalido3.txt");
			$clave= new ClaveUsuario();				//Se instancio para comparar la clave
			
			if ($clave->verificarClave($Clave,$fila["clave"])==1){
				// Si la clave es correcta se envia el mensaje de confirmacion y los datos del usuario
				$Estatus=1;
				$Mensaje="Usuario correcto";
				$fila["clave"]="";
				
				//Me traigo la pagina de inicio de este perfil
				$query="SELECT p1.id_pagina, p1.".chr(34)."nombre_pagina".chr(34).", p1.".chr(34)."descripcion".chr(34).", p1.".chr(34)."url".chr(34).", p1.".chr(34)."activo".chr(34)."
				FROM cantv.".chr(34)."paginas".chr(34)." p1 inner join cantv.".chr(34)."pagina_perfil".chr(34)." p2
				on p1.id_pagina=p2.id_pagina
				WHERE p2.".chr(34)."id_perfil".chr(34)."=".$fila["id_perfil"]." AND p2.inicio=1";
				Archivo($query, "usuariovalido4.txt");
				$Consulta=$dbConn->db_Consultar($query);
				$Numfilas=$dbConn->db_Num_Rows($Consulta);
				if ($Numfilas>0){
					// Si tiene pagina de inicio, la copio
					$FilaUrl=$dbConn->fetch_associativo($Consulta);
					$Url=$FilaUrl["Url"];
					Archivo("Url: ".$Url, "usuariovalido5.txt");
				}else{
					// Si no tiena pagina de inicio, paso el dato vacio
					$Url="";
					Archivo("No hay Url", "usuariovalido5.txt");
				}
			}else{
				$Estatus=3;
				$Mensaje="Usuario o clave incorrectos";
				$fila=$fila2;
				$token="";
			}
		}else{
			//Si no hay registros es porque no es un usuario del punto
			//envio mensaje de usuario incorrecto
			$Estatus=3;
			$Mensaje="Usuario o clave incorrectos";
			$fila=$fila2;
			$token="";
		}
		$fila["clave"]="";
		$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Token"=>$token, "Pagina"=>$Url, "DatosUsuario"=>$fila);
		Archivo("Estatus: ".$Estatus.", Mensaje: ". $Mensaje, "usuariovalido6.txt");
		return json_encode($json);
	}else{  // En caso de que alguno de los parametros vienen sin datos
		$Estatus=4;
		$Mensaje="El Usuario y la clave deben contener datos";
		$token="";
		$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Token"=>$token, "Pagina"=>$Url, "DatosUsuario"=>$fila2);
		Archivo("Estatus: ".$Estatus.", Mensaje: ". $Mensaje, "usuariovalido6.txt");
		return json_encode($json);

	}
	
}

//---------------Cesar --------------//

function listarindicadores($json_data){

	$Estatus=3;
	$Estatus1=0;
	$Estatus2=0;
	$Mensaje1='';

	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>""];
	$ArrayData=array();	

	$idPerfil = $json_data->idperfil;
	$idUsuario = $json_data->idusuario;	
	
	$dbConn= new AccesoDB;
	//Buscar indicadores por perfil
	$query='SELECT ind.id_indicador as idi, ind.nb_indicador as nbi, ac.valor as permiso, gc.id_gerencia
	FROM cantv."indicaxperfil" as ip, cantv."indicadores" as ind, cantv."acciones" as ac,
	cantv."gerencia" as gc,	cantv."vice_presidencia" as vp
	WHERE ip.id_indica=ind.id_indicador
	AND ip.id_accion=ac.id_accion
	AND ind.id_gerencia=gc.id_gerencia
	AND ip.id_perfil='.chr(39).$idPerfil.chr(39). '
	GROUP BY ind.id_indicador, ind.nb_indicador,gc.id_gerencia,ac.valor';	

	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);
	
	if ($Numfilas>0){
		//Listar indicdores
		$i=0;
		while ($fila = pg_fetch_assoc($Consulta)) {
		
		$id = $fila['idi'];
		$nb = utf8_decode($fila['nbi']);		
		$ArrayData[$i] = array('id_indicador'=> $id, 'nb_indicador'=> $nb); 
		$i++;
	  	}

        $Estatus=1;
		$Mensaje="Listado de Indicadores";	
		//Extraer los 4 favoritos
		$query2='SELECT ind.id_indicador,it.posicion,ind.nb_indicador
		FROM cantv."indicatop" it, cantv."indicadores" as ind
		WHERE it.id_indica=ind.id_indicador
		AND it.id_user='.chr(39).$idUsuario.chr(39);
	
		$Consulta2=$dbConn->db_Consultar($query2);
		$Numfilas2=$dbConn->db_Num_Rows($Consulta2);			
	
		if ($Numfilas2>0){
			$ArrayData2=array();
			$i=0;
			while ($fila = pg_fetch_assoc($Consulta2)) {
			
			$id = $fila['id_indicador'];
			$nb = utf8_decode($fila['nb_indicador']);			
			$po = $fila['posicion'];
			$ArrayData2[$i] = array('id_indicador'=> $id, 'nb_indicador'=> $nb, "Posicion"=> $po); 
			$i++;
			  }
		}else{
			$fila2=["ListaIndicadoresMostrar",["Id_Indicador"=>"","Nombre_Indicador"=>""]];
			$ArrayData2[0] = $fila2;		
		}		
	}else{
		$Estatus=2;
		$Mensaje="Sin Indicadores para el Perfil ";	
		$fila=["Id_Indicador"=>"","Nombre_Indicador"=>""];
		$ArrayData[0] = $fila;		
		$fila2=["Id_Indicador"=>"","Nombre_Indicador"=>""];
		$ArrayData2[0] = $fila2;		
	}	
	$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "ListaIndicadores"=>$ArrayData, "ListaIndicadoresMostrar"=>$ArrayData2);
	return json_encode($json);
}

function infoindicagra($json_data){

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$fila2=[];
	$v1 = 0;
	$v2 = 0;
	$v3 = 0;
	$v4 = 0;
	$v5 = 0;
	$v6 = 0;
	$nin = "";
	$uop = "";
	$ufi = "";
	$ArrayData=array();
	$ArrayData2=array();
	
	$idIndica = $json_data->idIndicador;	
	$iduser = $json_data->idUsuario;
	$anio = $json_data->anio;	
	
	//Acciones x definir con idTipoGrafxInd
	if (isset($json_data->idTipoGrafxInd)) {
		$idtgraf = $json_data->idTipoGrafxInd;
	} else {
		$idtgraf = 1;
	}
	
	$dbConn= new AccesoDB;
	//Validar si tiene acceso a la inormacion del indicador
	$query3='SELECT ip.id_indica, id_accion
	FROM cantv.indicaxperfil as ip, cantv."Usuarios" as us
	WHERE ip.id_perfil=us.id_perfil
	AND us.id_usuario='.$iduser.'
	AND ip.id_indica='.$idIndica;

	$Consulta3=$dbConn->db_Consultar($query3);
	$Numfilas3=$dbConn->db_Num_Rows($Consulta3);
	//Si tiene permiso entro a buscr informacion
	if($Numfilas3 > 0){		
		//obtener los tipos validos de datos
		$query1="SELECT id_tipo, trim(lower(substring(tipo,1,1))||substring(replace(initcap(tipo),' ',''),2,length(tipo))) as tipos
		FROM cantv.tipos_datos";
	
		$Consulta1=$dbConn->db_Consultar($query1);
		$Numfilas1=$dbConn->db_Num_Rows($Consulta1);
	
		if($Numfilas1 > 0){				
			$i = 1;
			while ($fila1 = pg_fetch_array($Consulta1)) {						
				${"nom".$i} = 'totalAnual'.ucfirst($fila1['tipos']);
				$i++;
			}
		}
		
		//Consulta los datos x indicador 
		$query="SELECT ind.id_indicador,ind.nb_indicador, di.cantidad as valor, di.anio, 
		di.mes as mes_n, m1.nb_mes as mes, di.id_tipo, tt.tipos, um1.id_unidad_medicion, 
		um1.unidad_medicion as und_med_oper,um2.id_unidad_medicion, um2.unidad_medicion as und_med_fin
		FROM cantv.datos_indicador as di, cantv.meses as m1,
		(SELECT id_tipo, trim(lower(substring(tipo,1,1))||substring(replace(initcap(tipo),' ',''),2,length(tipo))) as tipos
			FROM cantv.tipos_datos) as tt, cantv.indicadores as ind 
			LEFT JOIN cantv.unidades_medicion as um1
			ON ind.id_unidad_medicion=um1.id_unidad_medicion
			LEFT JOIN cantv.unidades_medicion as um2
			ON ind.id_unidad_med_financiera=um2.id_unidad_medicion
		WHERE di.id_indicador=ind.id_indicador
		AND di.mes=m1.id_mes
		AND di.id_tipo=tt.id_tipo
		AND di.anio=".chr(39).$anio.chr(39)."
		AND ind.id_indicador=".chr(39).$idIndica.chr(39)."
		ORDER BY di.mes";

		$Consulta=$dbConn->db_Consultar($query);
		$Numfilas=$dbConn->db_Num_Rows($Consulta);
	
		if($Numfilas > 0){				
			while ($fila = pg_fetch_array($Consulta)) {			
				$ime = ($fila['mes_n']);
				$mes = ($fila['mes']);
				$tip = ($fila['tipos']);
				$uop = ($fila['und_med_oper']);
				$ufi = ($fila['und_med_fin']);
				$val = floatval($fila['valor']);
				$nin = ($fila['nb_indicador']);
				$itp = intval($fila['id_tipo']);
				
				if(strpos($tip,'Financiera') ){
					${"array".$ime}=array("nombre"=>$tip, "mes"=>$mes, "valor"=>$val , "unidadMedidaFinanciera"=>$ufi);			
				}else{
					${"array".$ime}=array("nombre"=>$tip, "mes"=>$mes, "valor"=>$val , "unidadMedidaOperativa"=>$uop);			
				}	
				//Ciclo para armar totales por tipo 						
				switch ($itp) {
					case 1:
						$v1 = round((floatval($v1) + floatval($val)),2); 						
						break;
					case 2:
						$v2 = round((floatval($v2) + floatval($val)),2); 
						break;
					case 3:
						$v3 = round((floatval($v3) + floatval($val)),2); 
						break;
					case 4:
						$v4 = round((floatval($v4) + floatval($val)),2); 
						break;
					case 5:
						$v5 = round((floatval($v5) + floatval($val)),2); 
						break;									
					default:
						$v6 = round((floatval($v6) + floatval($val)),2); 
						$nom6 = "No definido";
						break;
				}
	
				$ArrayData[] = ${"array".$ime};
			}	
			//Ciclo para obtener los graficos establecidos por indicador
			$query2 ='SELECT ind.id_indicador, tg3.id_tipo_grafico, tg3.nombre  
			FROM cantv."indicadores" as ind LEFT JOIN 
			(SELECT tg1.id_indica,tg2.id_tipo_grafico, tg2.nombre  
			FROM cantv."tipo_grafxindica" as tg1,cantv.tipos_graficos tg2
			WHERE tg1.id_tipo_grafico=tg2.id_tipo_grafico) as tg3
			ON ind.id_indicador=tg3.id_indica
			WHERE ind.id_indicador='.chr(39).$idIndica.chr(39);
	
			$Consulta2=$dbConn->db_Consultar($query2);
			$Numfilas2=$dbConn->db_Num_Rows($Consulta2);		
					
			if ($Numfilas2>0){						
				while ($fila = pg_fetch_array($Consulta2)) {							
					$id2 = $fila['id_tipo_grafico'];
					$dtg = ($fila['nombre']);
					$ArrayData2[] = array('idTipoGrafxInd'=> $id2, 'Descripcion'=>$dtg); 				
				}	
			}else{			
				$ArrayData2[] = array('idTipoGrafxInd'=> "", 'Descripcion'=>""); 
			}				
			$Estatus = 1;
			$Mensaje = "Datos de Indicadores";
		}else{
			$Estatus=2;
			$Mensaje="Sin Datos";	
			$ArrayData = []; 
		}	
	}else {
		$Estatus = 3;
		$Mensaje = "No tiene permiso para ver informacion de este indicador";
		$nom1 = "";
		$nom2 = "";
		$nom3 = "";
		$nom4 = "";
		$nom5 = "";
	}
	
	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "idIndicador"=>$idIndica, "nbIndicador"=>$nin, 
	"unidadMedidaOperativa"=>$uop,"unidadMedidaFinanciera"=>$ufi, $nom1=>$v1,$nom2=>$v2,$nom3=>$v3,$nom4=>$v4,$nom5=>$v5,
	"datosIndicador"=>$ArrayData, "tiposGraficosSoport"=>$ArrayData2);
	
	return json_encode($json);  
}

function listavpgcia($json_data){		

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";	
	$fila = array('idVicePresidencia'=> null, 'nbVicePresidencia'=> null); 			
	$fila2= array('idGerencia'=> null, 'nbGerencia'=> null,'idVicePresidencia'=> null); 			
	$fila3= array('idGerencia'=> null, 'idIndicador'=>null, 'nombreInd'=>null, 'permiso'=>null);
	$dbConn= new AccesoDB;
		
	$idusu = $json_data->idUsuario;
	
	$res = json_decode(configusuario($idusu));

	$icu = $res->idCategoria;

	if ($icu == 0) {
		$query11 = '';
	}else{
		$query11 = ' AND ind.id_categoria='.$icu.' ';
	}

	//Buscar VP, Gcias e indicadores por perfil
	$query='SELECT vp.id_vice_presidencia, vp.nb_vicepresidencia, gc.id_gerencia, gc.nb_gerencia, 
	ac.valor as permiso, ind.id_indicador, ind.nb_indicador, ind.id_categoria
	FROM cantv."indicaxperfil" as ip, cantv."Usuarios" as us,
	cantv."indicadores" as ind, cantv."gerencia" as gc,
	cantv."vice_presidencia" as vp, cantv."acciones" as ac
	WHERE ip.id_perfil=us.id_perfil
	AND ip.id_indica=ind.id_indicador
	AND ind.id_gerencia=gc.id_gerencia
	AND gc.id_vice_presidencia=vp.id_vice_presidencia
	AND ip.id_accion=ac.id_accion
	AND us.id_usuario='.$idusu.$query11.' 
	GROUP BY gc.id_gerencia, gc.nb_gerencia,vp.id_vice_presidencia, ac.valor, ind.id_indicador, ind.nb_indicador';

	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);
	$ArrayData=array();	
	$ArrayData1=array();	
	$ArrayData2=array();
	$ArrayData3=array();
	if ($Numfilas>0){				
		//Listar los datos encontrados de VP, Gcia e indicadores x perfil
		while ($fila = pg_fetch_array($Consulta)) {			
			$ivp = intval($fila['id_vice_presidencia']);
			$nvp = utf8_decode($fila['nb_vicepresidencia']);			
			$igc = intval($fila['id_gerencia']);
			$ngc = utf8_decode($fila['nb_gerencia']);			
			$ind = intval($fila['id_indicador']);
			$per = intval($fila['permiso']);
			$nin = utf8_decode($fila['nb_indicador']);			
			$ica = intval($fila['id_categoria']);		
			
			$ArrayData[] = array('idVicePresidencia'=> $ivp, 'nbVicePresidencia'=> $nvp,'idCategoria'=>$ica); 			
			$ArrayData2[] = array('idGerencia'=> $igc, 'nbGerencia'=> $ngc,'idVicePresidencia'=> $ivp,'idCategoria'=>$ica); 			
			$ArrayData3[] = array('idGerencia'=> $igc, 'idIndicador'=>$ind, 'nombreInd'=>$nin, 'permiso'=>$per, 'idCategoria'=>$ica);				

		}	
		//Llamo la funcion array_unico para evitar datos repetidos en cada arreglo
		$ArrayData = array_unico($ArrayData,'idVicePresidencia');		
		$ArrayData2 = array_unico($ArrayData2,'idGerencia');		

		$Estatus=1;
		$Mensaje="datosAdministrativos";		
	}else{
		//En caso de no encontrar datos por perfil
		$Estatus=2;
		$Mensaje="Perfil o Usuario No tiene Unidad Administrativa asociada";	
		$ArrayData=$fila;
		$ArrayData2=$fila2;
		$ArrayData3=$fila3;
	}					
	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "listaVicePresidencias"=>$ArrayData,'listaGerencias'=>$ArrayData2, "indicadores"=>$ArrayData3);
	return json_encode($json);		  
}

function listafichaindicador($json_data){		

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";	
	$fila = array('idVicePresidencia'=> null, 'nbVicePresidencia'=> null); 			
	$fila2= array('idGerencia'=> null, 'nbGerencia'=> null,'idVicePresidencia'=> null); 			
	$fila3= array('idIndicador'=>null, 'nombreInd'=>null,'idGerencia'=> null,'permiso'=>null);
	$dbConn= new AccesoDB;
		
	$idUser = $json_data->idUsuario;
	
	if (empty($json_data->idCategoria) || is_null($json_data->idCategoria)) {
		$subquery = '';
		$Mensaje1 = '';
		$cat = 'Todas';		
	} else {
		$query1='SELECT id_categoria, nb_categoria 
		FROM cantv.categoria_indicador 
		WHERE id_categoria='.$json_data->idCategoria;
		$Consulta1=$dbConn->db_Consultar($query1);
		$Numfilas1=$dbConn->db_Num_Rows($Consulta1);

		if ($Numfilas1 > 0) {
			$fila1 = pg_fetch_array($Consulta1);
			$cat = utf8_decode($fila1['nb_categoria']);
			$subquery = ' AND ind.id_categoria='.$json_data->idCategoria;
			$Mensaje1 = '';
		}else {
			$subquery = '';
			$cat = 'Todas';		
			$sw = 1;
			$Mensaje1 = ' - No ha seleccionado una Categoria especifica';
		}	
	}

	//Buscar VP, Gcias e indicadores por perfil
	$query='SELECT vp.id_vice_presidencia, vp.nb_vicepresidencia, gc.id_gerencia, gc.nb_gerencia, 
	ac.valor as permiso, ind.id_indicador, ind.nb_indicador, ind.id_categoria
	FROM cantv."indicaxperfil" as ip, cantv."Usuarios" as us,
	cantv."indicadores" as ind, cantv."gerencia" as gc,
	cantv."vice_presidencia" as vp, cantv."acciones" as ac
	WHERE ip.id_perfil=us.id_perfil
	AND ip.id_indica=ind.id_indicador
	AND ind.id_gerencia=gc.id_gerencia
	AND gc.id_vice_presidencia=vp.id_vice_presidencia
	AND ip.id_accion=ac.id_accion	
	AND us.id_usuario='.chr(39).$idUser.chr(39). ' 
	GROUP BY gc.id_gerencia, gc.nb_gerencia,vp.id_vice_presidencia, ac.valor, ind.id_indicador, ind.nb_indicador';

	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);
	$ArrayData=array();	
	$ArrayData1=array();	
	$ArrayData2=array();
	$ArrayData3=array();
	if ($Numfilas>0){				
		//Listar los datos encontrados de VP, Gcia e indicadores x perfil
		while ($fila = pg_fetch_array($Consulta)) {			
			$ivp = intval($fila['id_vice_presidencia']);
			$nvp = utf8_decode($fila['nb_vicepresidencia']);			
			$igc = intval($fila['id_gerencia']);
			$ngc = utf8_decode($fila['nb_gerencia']);			
			$ind = intval($fila['id_indicador']);
			$per = intval($fila['permiso']);
			$nin = utf8_decode($fila['nb_indicador']);			
			$ica = intval($fila['id_categoria']);		

			$ArrayData[] = array('idVicePresidencia'=> $ivp, 'nbVicePresidencia'=> $nvp,'categoria'=>$cat); 			
			$ArrayData2[] = array('idGerencia'=> $igc, 'nbGerencia'=> $ngc,'idVicePresidencia'=> $ivp,'categoria'=>$cat); 			
			$ArrayData3[] = array('idIndicador'=>$ind, 'nombreInd'=>$nin,'idGerencia'=> $igc,'permiso'=>$per, 'idCategoria'=>$ica);			
		}	
		//Llamo la funcion array_unico para evitar datos repetidos en cada arreglo
		$ArrayData = array_unico($ArrayData,'idVicePresidencia');		
		$ArrayData2 = array_unico($ArrayData2,'idGerencia');		

		$Estatus=1;
		$Mensaje="datosAdministrativos";		
	}else{
		//En caso de no encontrar datos por perfil
		$Estatus=2;
		$Mensaje="Perfil o Usuario No tiene Unidad Administrativa asociada";	
		$ArrayData=$fila;
		$ArrayData2=$fila2;
		$ArrayData3=$fila3;
	}					
	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje.$Mensaje1, "listaVicePresidencias"=>$ArrayData,'listaGerencias'=>$ArrayData2, "indicadores"=>$ArrayData3);
	return json_encode($json);		  
}

function listavpgmetaresul($json_data){		

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>""];
	$sw=0;
	$dbConn= new AccesoDB;
	
	$idPerfil = $json_data->idPerfil;
	$idUser = $json_data->idUsuario;	

	if (empty($json_data->categoria) || is_null($json_data->categoria)) {
		$subquery = '';
		$Mensaje1 = '';
		$cat = 'Todas';		
	} else {
		$query1='SELECT id_categoria, nb_categoria 
		FROM cantv.categoria_indicador 
		WHERE id_categoria='.$json_data->categoria;
		$Consulta1=$dbConn->db_Consultar($query1);
		$Numfilas1=$dbConn->db_Num_Rows($Consulta1);

		if ($Numfilas1 > 0) {
			$fila1 = pg_fetch_array($Consulta1);
			$cat = utf8_decode($fila1['nb_categoria']);
			$subquery = ' AND ind.id_categoria='.$json_data->categoria;
			$Mensaje1 = '';
		}else {
			$subquery = '';
			$cat = 'Todas';		
			$sw = 1;
			$Mensaje1 = ' -Categoria seleccionada NO existe';
		}	
	}

	//Buscar los datos en BD por parametros de entrada
	$query='SELECT vp.id_vice_presidencia, vp.nb_vicepresidencia, gc.id_gerencia, gc.nb_gerencia, 
	ac.valor as permiso, ind.id_indicador, ind.nb_indicador, ind.id_categoria
	FROM cantv."indicaxperfil" as ip, cantv."Usuarios" as us,
	cantv."indicadores" as ind, cantv."gerencia" as gc,
	cantv."vice_presidencia" as vp, cantv."acciones" as ac
	WHERE ip.id_perfil=us.id_perfil
	AND ip.id_indica=ind.id_indicador
	AND ind.id_gerencia=gc.id_gerencia
	AND gc.id_vice_presidencia=vp.id_vice_presidencia
	AND ip.id_accion=ac.id_accion
	AND ip.id_perfil='.chr(39).$idPerfil.chr(39). ' AND us.id_usuario='.chr(39).$idUser.chr(39). $subquery.'  
	GROUP BY gc.id_gerencia, gc.nb_gerencia,vp.id_vice_presidencia, ac.valor, ind.id_indicador, ind.nb_indicador';

	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);
	$ArrayData=array();	
	$ArrayData2=array();
	$ArrayData3=array();
	//validar que existan datos para este criterio	
	if ($Numfilas>0){				
		//recorrer el RS con los datos y guardar en los arrays
		while ($fila = pg_fetch_array($Consulta)) {
			
			$ivp = intval($fila['id_vice_presidencia']);
			$igc = intval($fila['id_gerencia']);
			$ind = intval($fila['id_indicador']);
			$ica = intval($fila['id_categoria']);
			$per = ($fila['permiso']);
			$nvp = utf8_decode($fila['nb_vicepresidencia']);
			$ngc = utf8_decode($fila['nb_gerencia']);
			$nin = utf8_decode($fila['nb_indicador']);

			if (!array_search($ivp, array_column($ArrayData,'id_vice_presidencia'))) {			
				$ArrayData[] = array('id_vice_presidencia'=> $ivp, 'nb_vicepresidencia'=> $nvp, 'categoria'=>$cat); 			
			}			
			if (!array_search($igc, array_column($ArrayData2,'id_gerencia'))) {
				$ArrayData2[] = array('id_gerencia'=> $igc, 'nb_gerencia'=> $ngc,'id_vice_presidencia'=> $ivp,'categoria'=>$cat); 			
			}			
			$ArrayData3[] = array('id_indicador'=>$ind, 'nb_indicador'=>$nin,'id_gerencia'=> $igc, 'permiso'=>$per, 'idCategoria'=>$ica);
		}	
		$ArrayData = array_unico($ArrayData,'id_vice_presidencia');		
		$ArrayData2 = array_unico($ArrayData2,'id_gerencia');								
		if ($sw==1) {
			$Estatus=2;
		} else {
			$Estatus=1;
		}				
		$Mensaje="Datos Administrativos";		
	}else{
		//Si no hay datos
		$Estatus=3;
		$Mensaje="Perfil o Usuario No tiene ";	
		$Mensaje1="Unidad Administrativa asociada";
		$ArrayData=["id_vice_presidencia"=>"", "nb_vicepresidencia"=>"" ];
		$ArrayData2=["id_gerencia"=>"", "nb_gerencia"=>"", "id_vice_presidencia"=>"" ];
		$ArrayData3=['id_gerencia'=>"", 'id_indicador'=>"", 'nombre_ind'=>"", 'Permiso'=>""];
	}					
	$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje.$Mensaje1, "ListaVicePresidencias"=>$ArrayData,'ListaGerencias'=>$ArrayData2, "Indicadores"=>$ArrayData3);	
	return json_encode($json);		  
}

function listagrafpgcia($json_data){

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$fila1= array('idIndicador'=> null, 'nbIndicador'=> null, 'idGcia'=> null,'permiso'=> null, 'posicion'=>null);
	$fila2= array('idIndicador'=> null, 'nbIndicador'=> null, "posicion"=> null); 
	
	$ArrayData1=array();
	$ArrayData2=array();
	$ArrayData3=array();
	$ArrayData4=array();
	$k=1;

	$idPerfil = $json_data->idPerfil;
	$idUser = $json_data->idUsuario;	
	$idGcia = $json_data->idGerencia;	

	if (empty($json_data->idPerfil) || empty($json_data->idUsuario) || empty($json_data->idGerencia)) {
		$Estatus=3;
		$Mensaje="Error en el ingreo de los datos";
		$ArrayData1=$fila1;				
	}else{
		$dbConn= new AccesoDB;

		$query='SELECT a1.id_indicador,a1.nb_indicador,a1.id_gerencia,
		a1.permiso,a2.posgral,a3.posgcia, a1.id_usuario FROM
		(SELECT ind.id_indicador, ind.nb_indicador, ind.id_gerencia, 
		ac.valor as permiso, ip.id_perfil, us1.id_usuario
		FROM cantv."indicaxperfil" as ip, cantv."indicadores" as ind, 
		cantv."acciones" as ac, cantv."Usuarios" as us1
		WHERE ip.id_indica=ind.id_indicador	
		AND ip.id_perfil=us1.id_perfil
		AND ip.id_accion=ac.id_accion
		AND ind.id_gerencia='.chr(39).$idGcia.chr(39).' 
		AND ip.id_perfil='.chr(39).$idPerfil.chr(39).
		' AND us1.id_usuario='.chr(39).$idUser.chr(39).') as a1
		LEFT JOIN 
		(SELECT it.id_indica,it.posicion as posgral,u2.id_perfil , u2.id_usuario
		FROM cantv."indicatop" as it, cantv."Usuarios" as u2
		WHERE it.id_user=u2.id_usuario) as a2
		ON a1.id_indicador=a2.id_indica
		AND a1.id_usuario=a2.id_usuario
		LEFT JOIN
		(SELECT gu.id_usuario,gu.id_indica, gu.posicion as posgcia,u2.id_perfil 
		FROM cantv."ind_gcia_usu" as gu, cantv."Usuarios" as u2
		WHERE gu.id_usuario=u2.id_usuario) as a3
		ON a1.id_indicador=a3.id_indica
		AND a1.id_usuario=a3.id_usuario';	
		
		$Consulta=$dbConn->db_Consultar($query);
		$Numfilas=$dbConn->db_Num_Rows($Consulta);

		if ($Numfilas>0){				
			while ($fila = pg_fetch_array($Consulta)) {
				
				$idi = intval($fila['id_indicador']);
				$nbi = utf8_decode($fila['nb_indicador']);
				$idg = intval($fila['id_gerencia']);
				$pgr = intval($fila['posgral']);	
				$pgc = intval($fila['posgcia']);	
				$per = intval($fila['permiso']);

				//Listado por Perfil - Usuario - Gerencia
				$ArrayData1[] = array('idIndicador'=> $idi, 'nbIndicador'=> $nbi); 	
				if ($k < 3) {
					//Si no tiene inticaTOP especificos por gerecia tomo 2 de la Gcia
					$ArrayData4[] = array('idIndicador'=> $idi, 'nbIndicador'=> $nbi, "posicion"=> $k); 
					$k++;	
				}				
				if ($pgc >0 && $pgc <5) {
					$ArrayData2[] = array('idIndicador'=> $idi, 'nbIndicador'=> $nbi, "posicion"=> $pgc); 								
				} 									
				if ($pgr >0 && $pgr <5) {
					$ArrayData3[] = array('idIndicador'=> $idi, 'nbIndicador'=> $nbi, "posicion"=> $pgr); 
				} 													
			}	
			$v1 = sizeof($ArrayData1);
			$v2 = sizeof($ArrayData2);
			$v3 = sizeof($ArrayData3);

			if ($v1 == 0) {
				$Mensaje1='-Sin Datos para estas entradas';
				$ArrayData1=$fila1; 			
			}else{
				$Mensaje1='';
			}

			$j = $v3;

			if ($v2 == 0 && $v3>0)  {
				//Si no tiene inticaTOP especificos y si tiene almenos uno en el TOP General=>Tomo lo que tenga en TOP general
				$ArrayData2 = $ArrayData3;
				$Mensaje1='-SIN Indicadores TOP pero SI con TOP General';								
			} elseif ($v2 == 0 && $v3 ==0) {
				//Si no tiene inticaTOP especificos=>Tomo 2 cualquiera de la Gcia
				$ArrayData2 = $ArrayData4;
				$Mensaje1='-SIN Indicadores TOP';								
			}

			$Estatus=1;
			$Mensaje="Indicadores x Perfil".$Mensaje1;					
		}else{
			$Estatus=2;
			$Mensaje="Sin Indicadores x Perfil-Usuario-Gerencia";		
			$ArrayData1=$fila1;				
		}	
	}	
	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "listaIndicadores"=>$ArrayData1, "listaIndicadoresMostrar"=>$ArrayData2);
	return json_encode($json);
}

function datosmetasindicador($json_data){

	$Estatus=3;	
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$ArrayData00 = array("idVicePresidencia"=>null, "vicePresidencia"=>null,"idGerencia"=>null,"gerencia"=>null,"idIndicador"=>null,"nombreIndicador"=>null, "unidadIndicador"=>null,"fechaMaxIngresoDatos"=>null, "fechaModDatos"=>null, "permiso"=>null);
	$ArrayData01 = array("idMes"=>null, "nbMes"=>null,"valor"=>null, "observacion"=>null);

	$ArrayData=array();
	$ArrayData1=array();
	$i=0;
	$total=0;
	$hoy = date("Y-m-d");
	$meshoy=date("m");

	$idindica = $json_data->idIndicador;
	$idusu = $json_data->idUsuario;	
	$dbConn= new AccesoDB;

	if (empty($json_data->idTipo) || is_null($json_data->idTipo)) {
		$tipos = 1; //1 es para Metas Operativas Planificadas
		$nombre = 'metaOperativaPlanificada';
	} else {
		$tipos = $json_data->idTipo;	
		$query1="select id_tipo, trim(lower(substring(tipo,1,1))||substring(replace(initcap(tipo),' ',''),2,length(tipo))) as tipos
		from cantv.tipos_datos where id_tipo=".$tipos;		
		$Consulta1=$dbConn->db_Consultar($query1);
		$fila1 = pg_fetch_array($Consulta1);
		$nombre = $fila1['tipos'];
		if (empty($nombre)) {
			$nombre = 'Tipo NO Definido';
			$Estatus=3;
			$Mensaje='Tipo NO esta bien definido en los parametros de entrada';
			$ArrayData1=$fila1;
			$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "datosMetas"=>$ArrayData1);		
			return json_encode($json);			
		}
	}	

	if (empty($json_data->anio) || is_null($json_data->anio)) {
		$anio = date('Y');
	} else {
		$anio = $json_data->anio;
	}	
	
	if (empty($json_data->categoria) || is_null($json_data->categoria)) {
		$subquery = '';
		$Mensaje1 = '';
	} else {
		$query1='SELECT id_categoria, nb_categoria 
		FROM cantv.categoria_indicador 
		WHERE id_categoria='.$json_data->categoria;
		$Consulta1=$dbConn->db_Consultar($query1);
		$Numfilas1=$dbConn->db_Num_Rows($Consulta1);

		if ($Numfilas1 > 0) {
			$subquery = ' AND i1.id_categoria='.$json_data->categoria;
			$Mensaje1 = '';
		}else {
			$subquery = '';
			$Mensaje1 = '- Categoria seleccionada NO es valida';	
		}	

	}	

	//obtener Parametros de referencia x indicador
	$query='SELECT i1.id_indicador,i1.nb_indicador, i1.id_gerencia,	i1.fecha_max_meta, i1.dias_inc_result, 
	um.unidad_medicion as umed,	gc.nb_gerencia, vp.nb_vicepresidencia, vp.id_vice_presidencia, 
	bd.fecha_sol, bd.idsol, ip.id_accion, i1.id_categoria, bd.fecha_aprob
	FROM cantv.gerencia as gc, cantv.vice_presidencia as vp, cantv.unidades_medicion as um,
	cantv."Usuarios" as us, cantv.indicaxperfil as ip, cantv.indicadores as i1
	LEFT JOIN 
	(SELECT id_bandeja as idsol,id_status, fecha_sol, idusu_sol, id_indica, observacion, fecha_aprob
	FROM cantv.bandeja
	WHERE id_status=2 AND id_indica='.chr(39).$idindica.chr(39).
	' AND id_tipo_sol='.$tipos.' 
	ORDER BY id_bandeja desc
	limit 1) as bd
	ON i1.id_indicador=bd.id_indica
	WHERE i1.id_unidad_medicion=um.id_unidad_medicion
	AND gc.id_gerencia=i1.id_gerencia
	AND gc.id_vice_presidencia=vp.id_vice_presidencia
	AND i1.id_indicador=ip.id_indica
	AND us.id_usuario='.chr(39).$idusu.chr(39).' 
	AND i1.id_indicador='.chr(39).$idindica.chr(39).' 
	AND us.id_perfil=ip.id_perfil';	

	$query= $query.$subquery;

	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);

	if ($Numfilas > 0) {
		$fila = pg_fetch_array($Consulta);
		$perm = intval($fila['id_accion']);
		$dias = intval($fila['dias_inc_result']);
		$fmax = $fila['fecha_max_meta'];
		$gcia = utf8_decode($fila['nb_gerencia']);
		$nvpr = utf8_decode($fila['nb_vicepresidencia']);
		$umed = utf8_decode($fila['umed']);
		$nind = utf8_decode($fila['nb_indicador']);
		$fsol = $fila['fecha_sol'];
		$fapr = $fila['fecha_aprob'];
		$idgc = intval($fila['id_gerencia']);
		$idvp = intval($fila['id_vice_presidencia']);
		$idin = intval($fila['id_indicador']);
		$idca = intval($fila['id_categoria']);

		if (is_null($fsol)) {
			$fviva=false;
		} else {
			$fviva=true;
		}		

		$ArrayData=["idVicePresidencia"=>$idvp, "vicePresidencia"=>$nvpr,"idGerencia"=>$idgc,"gerencia"=>$gcia,"idIndicador"=>$idin,"nombreIndicador"=>$nind, "unidadIndicador"=>$umed,"fechaMaxIngresoDatos"=>$fmax, "fechaModViva"=>$fviva, "fechaModDatos"=>$fsol, "permiso"=>$perm, "categoriaIndicador"=>$idca];

		//Trae los datos del indicador para el año solicitado		
		$query3='SELECT di.mes as id_mes,m1.nb_mes,di.anio,di.fecha_inclusion, di.fecha_modificacion,di.cantidad, di.observacion  
		FROM cantv.datos_indicador di, cantv.meses m1
		WHERE di.mes = m1.id_mes
		AND id_indicador='.chr(39).$idindica.chr(39).'
		AND id_tipo='.$tipos.'
		AND anio='.chr(39).$anio.chr(39) . '
		ORDER BY di.anio,di.mes';

		$Consulta3=$dbConn->db_Consultar($query3);
		$Numfilas3=$dbConn->db_Num_Rows($Consulta3);

		if ($Numfilas3 > 0 ) {
			//Arma el arreglo con los datos de las metas
			while ($fila = pg_fetch_array($Consulta3)) {
				$anu = $fila['anio'];
				$idm = intval($fila['id_mes']);
				$mes = utf8_decode($fila['nb_mes']);
				$obs = utf8_decode($fila['observacion']);
				$can = floatval($fila['cantidad']);
				$total = $total + $can;		
				
				if ($hoy <= $fmax) {
					# ...permito
					$sw = true;
				} elseif ($hoy > $fmax && !is_null($fapr) && $idm >=$meshoy) {
					# ...permito
					$sw = true;
				}else {
					# ...NO permito
					$sw = false;
				}			

				$ArrayData1[] = array('idMes'=>$idm,'nbMes'=>$mes,'valor'=>$can,'observacion'=>$obs,'acumulado'=>$total,'actualizar'=>$sw); 	
				$Mensaje = 'Datos Indicador '.$nombre;
				$Estatus=1;
			}
			$ArrayData['total'] = $total;
		} else {
			//Si no hay ningun dato del indicador
			$ArrayData1=$ArrayData01;
			$Mensaje = 'Sin Datos para este Indicador en '. $nombre;
			$Estatus=2;
		}
	} else {
		//$perm = 0;
		$ArrayData=$ArrayData00;
		$ArrayData1=$ArrayData01;
		$Mensaje = 'Usuario o Indicador NO existen o el Usuario No tiene permiso de ver Indicador';
		$Estatus=3;
	}
	$json = array("estatus"=>$Estatus,"mensaje"=>$Mensaje.$Mensaje1,'datosGenerales'=>$ArrayData,"listaMetas"=>$ArrayData1);
	return json_encode($json);
}

function inclumodmetas($json_data){

	$ArrayData = array();
	$ArrayData = array();

	$dbConn= new AccesoDB;
	
	$idind = $json_data->idIndicador;
	$idusu = $json_data->idUsuario;
	$idtip = $json_data->idTipo;
	$anio  = $json_data->anio;
	$datos = $json_data->datos;	

	$json = json_decode(datosindicador($json_data));

	foreach ($json as $key=>$valor) {
		if($key == 'listaMetas'){
			$ArrayData = $valor;					
		}	
		if($key == 'datosGenerales'){
			$ArrayData1 = $valor;	
		}					
	}

	$usua = ($ArrayData1->idUsuarioAprob) ?? $idusu;	
	$iban = $ArrayData1->idBandeja;	

	foreach ($ArrayData as $dato) {
		//iterar entre los datos registrados originales		
		$mes1 = $dato->idMes;
		$can1 = $dato->valor;				
		$obs1 = $dato->observacion;
		$sw   = $dato->actualizar;			

		if($sw === true){
			foreach ($datos as $key) {				
				$mes2 = $key->idMes;
				$can2 = $key->valor;				
				$obs2 = $key->observacion;					

				if ($mes1 == $mes2) {
					//Identifica si el valor existe->Update; No existe->Insert
					$query2='SELECT id_indicador
					FROM cantv.datos_indicador
					WHERE id_indicador='.chr(39).$idind.chr(39). 
					' AND id_tipo='.$idtip.
					' AND anio='.chr(39).$anio.chr(39).' AND mes='.$mes1;

					$Consulta2=$dbConn->db_Consultar($query2);
					$Numfilas2=$dbConn->db_Num_Rows($Consulta2);

					if ($Numfilas2 > 0) {
						$query4='UPDATE cantv.datos_indicador
						SET fecha_modificacion=current_timestamp, 
						id_usuario_aprob_mod='.$usua.' , 
						cantidad='.$can2.', observacion='.chr(39).$obs2.chr(39).' 
						WHERE id_indicador='.$idind.
						' AND id_tipo='.$idtip.
						' AND anio='.chr(39).$anio.chr(39).' AND mes='.$mes2;						

						$Consulta4=$dbConn->db_Consultar($query4);	
						
						if ($Consulta4) {
							$res = cierraSolicitud($iban, $idusu);
						}
					}else{
						$query5='INSERT INTO cantv.datos_indicador (
						id_indicador,id_tipo, anio, mes, fecha_inclusion, cantidad, id_usuario_inclusion,id_usuario_aprob_mod, observacion)
						VALUES ('.$idindica.','.$tipos.','.$anio.','.$mes2.', current_date,'.$can2.','.$idusu.','.$usua.','.chr(39).$obs2.chr(39).')';
						
						$Consulta5=$dbConn->db_Consultar($query5);

						if ($Consulta4) {
							$res = cierraSolicitud($iban, $idusu);
						}
					}				
				}
			}								
		}
	}	
	
	$json = json_decode(datosindicador($json_data));

	return json_encode($json);
}

function datosindicador($json_data){

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$total=0;
	$hoy = date("Y-m-d");
	$meshoy=date("m");

	$idind = $json_data->idIndicador;
	$idtip = $json_data->idTipo;	
	$idusu = $json_data->idUsuario;	

	$dbConn= new AccesoDB;

	switch ($idtip) {
		case '1':
			$idsol = 1;
			$nombre = "metasOperativasPlanificadas";
			break;		
		case '2':
			$idsol = 2;
			$nombre = "metasOperativasEjecutadas";
			break;		
		case '3':
			$idsol = 1;
			$nombre = "metasFinancierasPlanificadas";
			break;				
		case '4':
			$idsol = 2;
			$nombre = "metasFinancierasEjecutadas";
			break;				
		case '5':
			$idsol = 3;
			$nombre = "valorReal";
			break;					
		default:
			$idsol = 0;
			$nombre = "tipoNoDefinido ";
			break;
	}

	$query1='SELECT * 
	FROM cantv.indicaxperfil as ip, cantv."Usuarios" as us
	WHERE ip.id_perfil=us.id_perfil
	AND ip.id_indica='.$idind. '
	AND us.id_usuario='.$idusu;

	$Consulta1=$dbConn->db_Consultar($query1);
	$Numfilas1=$dbConn->db_Num_Rows($Consulta1);

	if ($Numfilas1 > 0) {

		if (empty($json_data->anio) || is_null($json_data->anio)) {
			$anio = date('Y');
		} else {
			$anio = $json_data->anio;
		}			

		//obtener Parametros de referencia x indicador
		$query='SELECT i1.id_indicador,i1.nb_indicador, i1.id_gerencia,	i1.fecha_max_meta, i1.dias_inc_result, 
		um.unidad_medicion as umed,	gc.nb_gerencia, vp.nb_vicepresidencia, vp.id_vice_presidencia, 
		bd.fecha_sol, bd.idsol, ip.id_accion, i1.id_categoria, bd.fecha_aprob, bd.idusu_apr
		FROM cantv.gerencia as gc, cantv.vice_presidencia as vp, cantv.unidades_medicion as um,
		cantv."Usuarios" as us, cantv.indicaxperfil as ip, cantv.indicadores as i1
		LEFT JOIN 
		(SELECT id_bandeja as idsol,id_status, fecha_sol, idusu_sol, id_indica, observacion, fecha_aprob, idusu_apr
		FROM cantv.bandeja
		WHERE id_status=2 AND id_indica='.chr(39).$idind.chr(39).
		' AND id_tipo_sol='.$idsol.' 
		ORDER BY id_bandeja desc
		limit 1) as bd
		ON i1.id_indicador=bd.id_indica
		WHERE i1.id_unidad_medicion=um.id_unidad_medicion
		AND gc.id_gerencia=i1.id_gerencia
		AND gc.id_vice_presidencia=vp.id_vice_presidencia
		AND i1.id_indicador=ip.id_indica
		AND us.id_usuario='.chr(39).$idusu.chr(39).' 
		AND i1.id_indicador='.chr(39).$idind.chr(39).' 
		AND us.id_perfil=ip.id_perfil';	

		$Consulta=$dbConn->db_Consultar($query);
		$Numfilas=$dbConn->db_Num_Rows($Consulta);

		if ($Numfilas > 0) {
			$fila = pg_fetch_array($Consulta);
			$perm = intval($fila['id_accion']);
			$dias = intval($fila['dias_inc_result']);
			$fmax = $fila['fecha_max_meta'];
			$gcia = utf8_decode($fila['nb_gerencia']);
			$nvpr = utf8_decode($fila['nb_vicepresidencia']);
			$umed = utf8_decode($fila['umed']);
			$nind = utf8_decode($fila['nb_indicador']);
			$fsol = $fila['fecha_sol'];
			$fapr = $fila['fecha_aprob'];
			$iapr = $fila['idusu_apr'];
			$idgc = intval($fila['id_gerencia']);
			$idvp = intval($fila['id_vice_presidencia']);
			$idin = intval($fila['id_indicador']);
			$idca = intval($fila['id_categoria']);
			$isol = intval($fila['idsol']);

			if (is_null($fapr)) {
				$fviva=false;
			} else {
				$fviva=true;
			}		

			$ArrayData=["idVicePresidencia"=>$idvp, "vicePresidencia"=>$nvpr,"idGerencia"=>$idgc,"gerencia"=>$gcia,"idIndicador"=>$idin,
			"nombreIndicador"=>$nind, "unidadIndicador"=>$umed,"fechaMaxIngresoDatos"=>$fmax, "fechaModViva"=>$fviva, "fechaModDatos"=>$fapr, 
			"idBandeja"=>$isol,"diasIncResult"=>$dias,"permiso"=>$perm, "categoriaIndicador"=>$idca,"idUsuarioAprob"=>$iapr];
			$Estatus = 1;

			//Trae los datos del indicador para el año solicitado		
			$query3='SELECT di.mes as id_mes,m1.nb_mes,di.anio,di.fecha_inclusion, di.fecha_modificacion,di.cantidad, di.observacion  
			FROM cantv.datos_indicador di, cantv.meses m1
			WHERE di.mes = m1.id_mes
			AND id_indicador='.chr(39).$idind.chr(39).'
			AND id_tipo='.$idtip.'
			AND anio='.chr(39).$anio.chr(39) . '
			ORDER BY di.anio,di.mes';

			$Consulta3=$dbConn->db_Consultar($query3);
			$Numfilas3=$dbConn->db_Num_Rows($Consulta3);

			if ($Numfilas3 > 0 ) {
				//Arma el arreglo con los datos de las metas
				while ($fila = pg_fetch_array($Consulta3)) {
					$anu = $fila['anio'];
					$idm = intval($fila['id_mes']);
					$mes = utf8_decode($fila['nb_mes']);
					$obs = utf8_decode($fila['observacion']);
					$can = floatval($fila['cantidad']);
					$total = $total + $can;		

					if($idtip == 1 || $idtip == 3){
						if ($hoy <= $fmax) {
							# ...permito
							$sw = true;
						} elseif ($hoy > $fmax && !is_null($fapr) && $idm >=$meshoy) {
							# ...permito
							$sw = true;
						}else {
							# ...NO permito
							$sw = false;
						}		
					}elseif ($idtip == 2 || $idtip == 4 || $idtip == 5) {
						$ftop= $anio.'-'.str_pad($meshoy,2,'0',STR_PAD_LEFT).'-'.str_pad($dias,2,'0',STR_PAD_LEFT);
						if($hoy <= $ftop && (intval($meshoy)-1) == $idm){
							$sw = true;
						}elseif($hoy > $ftop && !is_null($fapr) && (intval($meshoy)-1) == $idm) {
							$sw = true;
						}else{
							$sw = false;
						}
					}else {				
						$sw = false;
					}
					$ArrayData1[] = array('idMes'=>$idm,'nbMes'=>$mes,'valor'=>$can,'observacion'=>$obs,'acumulado'=>$total,'actualizar'=>$sw); 	
				}
				$ArrayData["total"]=$total;
				$Mensaje = "Datos de ". $nombre;
			}else {
				$ArrayData1[] = array('idMes'=>null,'nbMes'=>null,'valor'=>null,'observacion'=>null,'acumulado'=>null,'actualizar'=>null);
				$Mensaje = "Sin Datos para ". $nombre;
				$Estatus = $Estatus + 1;
			}	
		}else{
			$ArrayData=["idVicePresidencia"=>null, "vicePresidencia"=>null,"idGerencia"=>null,"gerencia"=>null,"idIndicador"=>null,
			"nombreIndicador"=>null, "unidadIndicador"=>null,"fechaMaxIngresoDatos"=>null, "fechaModViva"=>null, "fechaModDatos"=>null, 
			"diasIncResult"=>null,"permiso"=>null, "categoriaIndicador"=>null];
			$ArrayData1[] = array('idMes'=>null,'nbMes'=>null,'valor'=>null,'observacion'=>null,'acumulado'=>null,'actualizar'=>null);
			$Estatus = 2;
		}	
	}else {
		$Mensaje = "Perfil de Usuario No tiene acceso a este indicador";
		$Estatus = 4;
		$ArrayData=["idVicePresidencia"=>null, "vicePresidencia"=>null,"idGerencia"=>null,"gerencia"=>null,"idIndicador"=>null,
			"nombreIndicador"=>null, "unidadIndicador"=>null,"fechaMaxIngresoDatos"=>null, "fechaModViva"=>null, "fechaModDatos"=>null, 
			"diasIncResult"=>null,"permiso"=>null, "categoriaIndicador"=>null];
		$ArrayData1[] = array('idMes'=>null,'nbMes'=>null,'valor'=>null,'observacion'=>null,'acumulado'=>null,'actualizar'=>null);		
		$Estatus = $Estatus + 1;
	}	
	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje,"datosGenerales"=>$ArrayData,"listaMetas"=>$ArrayData1);

	return json_encode($json);
}

function inclumodresulreal($json_data){

	$Estatus=3;	
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$vacio=['mes'=>"", 'accion'=>'Insert-Update', 'Resultado'=>'No Aprobado', 'Fecha Max'=>""];
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>""];
	$ArrayData=array();
	$ArrayData1=array();
	$ametas=array(2,4,5);
	$meses=array();
	$i=0;
	$j=0;
	$sw=0;
	$sw2=0;
	
	$idindica = $json_data->idIndicador;
	$idusu = $json_data->idUsuario;		
	$tipos = $json_data->idTipo;	
	$datos = $json_data->datos;	
	
	if(empty($json_data->anio) || is_null($json_data->anio)){
		$anio = date("Y");
	}else {
		$anio = $json_data->anio;
	}	

	$dbConn= new AccesoDB;

	$query3 = 'SELECT valor as dias	FROM cantv.configuracion WHERE id_conf=3';

	$Consulta3=$dbConn->db_Consultar($query3);
	$fila3 = pg_fetch_array($Consulta3);

	$diasaprobados = ($fila3['dias']);		//Cantidad de dias de que dispone el usaurio una vez aprobada una solicitud

	$query1="SELECT id_tipo, trim(lower(substring(tipo,1,1))||substring(replace(initcap(tipo),' ',''),2,length(tipo))) as tipos
	FROM cantv.tipos_datos WHERE id_tipo=".$tipos;
	$Consulta1=$dbConn->db_Consultar($query1);
	$fila1 = pg_fetch_array($Consulta1);
	$Numfilas1=$dbConn->db_Num_Rows($Consulta1);
	$nombre = $fila1['tipos'];
	$idt = $fila1['id_tipo'];

	if ($Numfilas1 > 0 && in_array($idt,$ametas)) {
		# Han solicitado por las metas validas [2,4,5]
		$hoy = date("Y-m-d");
		$meshoy = date("m");
		$diauno = date("Y-m-")."01";
		$diafin = date("Y-m-t");
		
		$dbConn= new AccesoDB;

		//obtener Parametros de referencia x indicador
		$query2='SELECT id1.id_accion,bd.fecha_sol, bd.fecha_aprob,bd.idusu_apr,
		coalesce(bd.id_bandeja,0) as id_bandeja , id1.dias_inc_result, bd.id_status, bd.id_tipo_sol
		FROM
		(SELECT ip.id_indica, ip.id_accion, us.id_usuario,ip.id_perfil, ind.dias_inc_result
			FROM cantv."Usuarios" as us, cantv.indicaxperfil as ip, cantv.indicadores as ind
			WHERE us.id_perfil=ip.id_perfil
				AND ind.id_indicador=ip.id_indica
			AND us.id_usuario='.$idusu.') as id1
		LEFT JOIN 
			(SELECT id_bandeja,fecha_sol, idusu_sol,idusu_apr, fecha_aprob, 
			id_indica, id_status,id_tipo_sol
			FROM cantv.bandeja
			WHERE id_status=2 AND id_tipo_sol='.$tipos. ' 
			ORDER BY id_bandeja desc limit 1) as bd
		ON id1.id_indica=bd.id_indica
		WHERE id1.id_indica='.chr(39).$idindica.chr(39);

		$Consulta2=$dbConn->db_Consultar($query2);
		$Numfilas2=$dbConn->db_Num_Rows($Consulta2);
		$fila2 = pg_fetch_array($Consulta2);		
		$dias = $fila2['dias_inc_result'];				
		$solc = intval($fila2['id_bandeja']);				
		$stat = intval($fila2['id_status']);
		$fsol = $fila2['fecha_sol'];								
		$fapr = date("Y-m-d", strtotime($fila2['fecha_aprob']));					

		if ($solc > 0)  {
			# Tiene una solicitud
			$ftop2 = date("Y-m-d", strtotime($fapr . $diasaprobados));	
			$j++;					
		}else {
			# Sin solicitud, solo evalua dias_inc_resul
			$ftop2 = $diauno;	
		}

		$ftop= date("Y-m-").str_pad($dias,2,'0',STR_PAD_LEFT);

		if ( $ftop >  $hoy) {
			# Condicion Normal de actualizar datos de Metas
			$sw = 1; //Switche que habilita la inserion/actualizacion de datos
			$usua = $idusu;
			$Mensaje = 'Incluir o Modificar Metas';
			$Estatus = 1;
		} elseif ( $ftop <  $hoy  &&  $hoy <=  $ftop2 && $j > 0) {
			# Hay una solicitud aprobada
			$sw = 2; //Switche que habilita la inserion/actualizacion de datos			
			$usua = intval($fila2['idusu_apr']);
			$ucerrar = $idusu;
			$Mensaje = 'Incluir o Modificar Metas fuera de tiempo con solicitud aprobada';
			$Estatus = 1;
		}elseif ( $ftop < $hoy  &&  $hoy >  $ftop2  && $j > 0) {			
			$sw = 0; //Sin solicitud valida
			$query4 = 'SELECT cast(valor as smallint) as usuadmin
			FROM cantv.configuracion WHERE id_conf=4';

			$Consulta4=$dbConn->db_Consultar($query4);
			$fila4 = pg_fetch_array($Consulta4);

			$ucerrar = intval($fila4['usuadmin']);		//usuario Administrador para que cierre la solicitud extemporanea
			$Mensaje = 'No Aprobado -> Solicitud Pendiente Vencida';
			$Estatus = 2;
		}else {
			# code...
			$sw = 0; //Sin opcion general de actualizar informacion
			$Mensaje = 'Incluir o Modificar Metas No Aprobada';
			$Estatus = 3;
		}
		
		if ($sw > 0) {
			# Verificar valores por actualizar
			//Parametros configurable de meses atras a permitir modficicar -> por defecto en un(1) mes
			$query3 = 'select cast(valor as smallint) as nmes
			from cantv.configuracion where id_conf=2';

			$Consulta3=$dbConn->db_Consultar($query3);
			$fila3 = pg_fetch_array($Consulta3);

			$nmes = intval($fila3['nmes']);		//numero de meses. Uno(1) por defecto
			$meses = array();			
			$fecha = $diauno;

			for ($x = 1; $x <= $nmes; $x++) {
				$fecha = date('Y-m-d', strtotime($fecha. ' - 1 days'));
				$m1 = date("m",strtotime($fecha));
				$y1 = date("Y",strtotime($fecha));
				$meses[] = $y1.$m1;  	//mes(es) permitido(s) de insertar/actualizar
				$fecha = date($y1."-".$m1."-01");
			}

			//$i=0;			
			foreach ($datos as $dato) {
				//iterar entre los datos suministrados de entrada
				$mes = $dato->idMes;
				$can = $dato->valor;				
				$obs = $dato->observacion;								
					
				if (in_array($anio.str_pad($mes,2,'0',STR_PAD_LEFT), $meses)) {

					//Identifica si el valor existe->Update; No existe->Insert
					$query2='SELECT id_indicador
					FROM cantv.datos_indicador
					WHERE id_indicador='.chr(39).$idindica.chr(39).
					' AND id_tipo='.$tipos. ' AND anio='.chr(39).$anio.chr(39).' AND mes='.$mes;
							
					$Consulta2=$dbConn->db_Consultar($query2);
					$Numfilas2=$dbConn->db_Num_Rows($Consulta2);
					if ($Numfilas2 > 0) {
						$query4='UPDATE cantv.datos_indicador
						SET fecha_modificacion=current_date, 
						id_usuario_aprob_mod='.$usua.', 
						cantidad='.$can.', observacion='.chr(39).$obs.chr(39).' 
						WHERE id_indicador='.$idindica.' AND id_tipo='.$tipos. 
						' AND anio='.chr(39).$anio.chr(39).' AND mes='.$mes;
						$accion='Actualizar';																
					} else {
						$query4='INSERT INTO cantv.datos_indicador (
						id_indicador, d_tipo, anio, mes, fecha_inclusion, cantidad, id_usuario_inclusion, observacion)
						VALUES ('.$idindica.','.$tipos.','.$anio.','.$mes.', current_date,'.$can.','.$idusu.','.chr(39).$obs.chr(39).')';
						$accion='Insertar';
					} 
					$Consulta4=$dbConn->db_Consultar($query4);
					$ArrayData1[] = array('mes'=>$mes, 'accion'=>$accion, 'Resultado'=>'Aprobado', 'Fecha Max'=>$ftop);
				}else {
					# ...No esta abierta la opción de modificar/incluir datos para este mes
					$ArrayData1[] = array('mes'=>$mes, 'accion'=>'Insert-Update', 'Resultado'=>'No Aprobado', 'Fecha Max'=>$ftop);
				}	
			}	
		} else {
			# ...No esta abierta la opción de modificar/incluir datos
			$ArrayData1 = $vacio;
		}
	} else {
		# No definidos
		$Estatus=2;				
		$Mensaje='Solo para Metas Ejecutadas o Valores Reales con idTipo=>[2,4,5]';
		$ArrayData1 = $vacio;
	}
	if ($j > 0) {			
		$result = cierraSolicitud($solc, $ucerrar);
	}	

	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje,"datos"=>$ArrayData1);
	return json_encode($json);
}

function abreSolicitud($json_data){

	$Estatus=7;	
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$json=["estatus"=>$Estatus, "mensaje"=>$Mensaje];	

	$idusu = $json_data->idUsuario;	
	$idind = $json_data->idIndicador;	
	$tipos = $json_data->tipo;
	$obser = $json_data->observacion;

	$dbConn= new AccesoDB;
	
	//Consulta las opciones validas de tipo de datos
	$query1="select id_tipo, trim(lower(substring(tipo,1,1))||substring(replace(initcap(tipo),' ',''),2,length(tipo))) as tipos
	from cantv.tipos_datos";
	$Consulta1=$dbConn->db_Consultar($query1);
	while ($fila = pg_fetch_array($Consulta1)) {			
		$idt = $fila['id_tipo'];
		$tip = utf8_decode($fila['tipos']);
		//Arreglo que contiene las opciones validas, para:
			//1.- extraer el datos positivo
			//2.- Informar las validas en caso de ERROR en el parametro de entrada
		$ArrayData1[$idt] = $tip;
	}	

	if (array_key_exists($tipos, $ArrayData1)) {
		# Tiene una opcion valida
		$tabla = $ArrayData1[$tipos];

		$query='SELECT usu.id_usuario, usu.id_perfil, usu.id_gerencia,ip2.id_indica,ip2.id_accion as permiso, 
		ip2.idsol, ip2.id_status, ip2.fecha_sol, ip2.observacion
		FROM
		(SELECT id_usuario, id_perfil, id_gerencia
		FROM cantv."Usuarios"
		WHERE id_usuario='.$idusu.') as usu 
		LEFT JOIN
		(SELECT ip.id_indica, ip.id_perfil, ip.id_accion, 
			sol.idsol,sol.fecha_sol, sol.id_status, sol.observacion
		FROM cantv.indicaxperfil as ip	LEFT JOIN
			(SELECT id_bandeja as idsol,id_status, fecha_sol, idusu_sol, 
			id_indica, observacion
			FROM cantv.bandeja
			WHERE id_tipo_sol='.$tipos.'
			order by id_bandeja desc
			limit 1) as sol
		ON ip.id_indica=sol.id_indica	
			WHERE ip.id_indica='.$idind.') as ip2
		ON usu.id_perfil=ip2.id_perfil';
		
		$Consulta=$dbConn->db_Consultar($query);
		$Numfilas=$dbConn->db_Num_Rows($Consulta);
		$fila = pg_fetch_array($Consulta);		
		$ind = intval($fila['id_indica']);				

		if ($Numfilas > 0 && !is_null($ind) && !empty($ind)) {
			# Con Datos
			$perm = intval($fila['permiso']);				
			$solc = intval($fila['idsol']);
			$stat = intval($fila['id_status']);		

			if (is_null($stat) || empty($stat) || $stat == 3) {
				# No tiene solicitud previa -> preguntar si se puede crear
				if ($perm > 1) {
					# Tiene permiso de crear
					$query2='INSERT INTO cantv.bandeja(id_indica, idusu_sol, fecha_sol, id_status, id_tipo_sol, observacion)
						VALUES ('.$idind.','.$idusu.',current_timestamp, 1,'.$tipos.','.chr(39).$obser.chr(39).');';
					$Consulta2=$dbConn->db_Consultar($query2);
					if ($Consulta2) {
						# Sin Errores de BD
						$Estatus = 1;
						$Mensaje='Se ha creado una Nueva solicitud de modificar '.$tabla. ' para el indicador '.$idind;
					} else {
						# Con Errores de BD
						$Estatus = 4;
						$Mensaje='ERROR al modificar '.$tabla;
					}			
				} else {
					# no tiene permiso de crear
					$Estatus = 2;
					$Mensaje='Sin permiso para modificar '.$tabla. ' del indicador '.$idind;
				}			
			} elseif (!is_null($stat) && $stat == 2) {
				# Solicitud en Proceso - Aprobada -> NO Crear nueva
				$Estatus = 2;
				$Mensaje='Ya tiene Solicitud Aprobada, No se puede crear Nueva para '.$tabla. ' del indicador '.$idind;
			} elseif (!is_null($stat) && $stat == 1) {
				# Solicitud en Proceso -> NO Crear nueva
				$Estatus = 2;
				$Mensaje='Ya tiene una Solicitud Pendiente por Aprobar, No se puede crear una nueva para '.$tabla. ' del indicador '.$idind;		
			} else {
				# Error de Procesamiento
				$Estatus = 4;
				$Mensaje='ERROR de BD al modificar status: '.$stat;		
			}		
		}elseif ($Numfilas > 0 && (is_null($ind) || empty($ind))) {
			# SIN Datos del Indicador
			$Estatus = 5;
			$Mensaje='Indicador NO Existe';							
		} else {
			# SIN Datos de Usuario
			$Estatus = 3;
			$Mensaje='Usuario NO Existe o No tiene permiso de ver este indicador';							
		}
	} else {
		# code...
		$Estatus=6;
		$Mensaje=array("ERROR: opciones validas para Tipo"=>$ArrayData1);
	}
	
	$json = array('estatus'=>$Estatus, 'mensaje'=>$Mensaje);
	return json_encode($json);
}

function cierraSolicitud($idBandeja, $idusu){

	$dbConn= new AccesoDB;
	$query = 'UPDATE cantv."bandeja" 
	SET id_status=3, fecha_cierre=current_timestamp, idusu_cierre='.$idusu. '
	WHERE id_bandeja='.$idBandeja;
	$Consulta=$dbConn->db_Consultar($query);
	if ($Consulta) {
		$sw=1;
	} else {
		$sw=0;
	}
	return $sw;
}

function datosusuario($iduser){

	$Estatus=3;	
	$Url="";
	$Pagina="";
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";	
	$fila=["idUsuario"=>"","idPerfil"=>"","nombrePerfil"=>"","idGerencia"=>"","gerencia"=>"","idUnidAdmin"=>"","unidadAdministrativa"=>"","idtipoDocIdent"=>"","tipoDocIdentidad"=>"","docIdentidad"=>"","nombres"=>"","apellidos"=>"","fNac"=>"","fCreacion"=>"","fModif"=>"","idUsMod"=>"","idJerarquia"=>"","nombreJerarquia"=>"","nbUsuario"=>""];
	$ArrayData=array();
	$ArrayData1=array();
	$i=0;
		
	$dbConn= new AccesoDB;
	$query='SELECT us.id_usuario id_usuario, us.id_perfil id_perfil, p.nombre_perfil nombre_perfil, 
	us.id_gerencia id_gerencia, ger.nb_gerencia gerencia, us.id_unid_admin id_unid_admin, 
	ua.nombe unidad_administrativa, us.id_tipo_doc_ident id_tipo_doc_ident, tdi.nemonico_doc tipo_doc_identidad, 
	us.doc_identidad doc_identidad, us.nombres nombres, us.apellidos apellidos, us.f_nac f_nac, 
	us.f_creacion f_creacion, us.f_modif f_modif, us.id_us_mod id_us_mod, us.id_jerarquia id_jerarquia, 
	jq.nombre_jerarquia nombre_jerarquia,  us.clave clave, us.nb_usuario nb_usuario , cu.id_categoria
	FROM cantv."Usuarios" us LEFT JOIN cantv.configurar_usuario as cu
	ON us.id_usuario=cu.id_usuario
	INNER JOIN cantv."Perfiles" p ON us.id_perfil=p.id_perfil
	INNER JOIN cantv."gerencia" ger ON us.id_gerencia=ger.id_gerencia
	INNER JOIN cantv."Unidad_Administrativa" ua ON us.id_unid_admin=ua.id_unid_admin
	INNER JOIN cantv."Tipo_Doc_Identidad" tdi ON us.id_tipo_doc_ident=tdi.id_tipo_doc_ident
	INNER JOIN cantv."Jerarquias" jq ON us.id_jerarquia=jq.id_jerarquia
	WHERE us.id_usuario='.chr(39).$iduser.chr(39);		
	
	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);
		
	if ($Numfilas>0){
		//Segunda validacion extra 
		$fila=$dbConn->fetch_associativo($Consulta);	
		
		$idus = intval($fila['id_usuario']);
		$iper = intval($fila['id_perfil']);
		$iger = intval($fila['id_gerencia']);
		$iuad = intval($fila['id_unid_admin']);
		$itdi = intval($fila['id_tipo_doc_ident']);
		$iumo = intval($fila['id_us_mod']);
		$ijer = intval($fila['id_jerarquia']);
		$ica = intval($fila['id_categoria']);

		$doc = ($fila['doc_identidad']);
		$nper = utf8_decode($fila['nombre_perfil']);
		$nger = utf8_decode($fila['gerencia']);
		$nuad = utf8_decode($fila['unidad_administrativa']);
		$ntdi = ($fila['tipo_doc_identidad']);
		$nomb = utf8_decode($fila['nombres']);
		$apes = utf8_decode($fila['apellidos']);
		$fnac = ($fila['f_nac']);
		$fcre = ($fila['f_creacion']);
		$fmod = ($fila['f_modif']);
		$nusu = utf8_decode($fila['nb_usuario']);			
		$njer = utf8_decode($fila['nombre_jerarquia']);
		
		$ArrayData =["idUsuario"=>$idus,"idPerfil"=>$iper,"nombrePerfil"=>$nper,"idGerencia"=>$iger,"gerencia"=>$nger,
		"idUnidAdmin"=>$iuad,"unidadAdministrativa"=>$nuad,"idtipoDocIdent"=>$itdi,"tipoDocIdentidad"=>$ntdi,"docIdentidad"=>$doc,
		"nombres"=>$nomb,"apellidos"=>$apes,"fNac"=>$fnac,"fCreacion"=>$fcre,"fModif"=>$fmod,"idUsMod"=>$iumo,"idJerarquia"=>$ijer,
		"nombreJerarquia"=>$njer,"nbUsuario"=>$nusu,"idCategoria"=>$ica];

		//Me traigo la pagina de inicio de este perfil
		$query2="SELECT p1.id_pagina, p1.".chr(34)."nombre_pagina".chr(34).", p1.".chr(34)."descripcion".chr(34).", p1.".chr(34)."url".chr(34).", p1.".chr(34)."activo".chr(34)."
		FROM cantv.".chr(34)."paginas".chr(34)." p1 inner join cantv.".chr(34)."pagina_perfil".chr(34)." p2
		on p1.id_pagina=p2.id_pagina
		WHERE p2.".chr(34)."id_perfil".chr(34)."=".$fila["id_perfil"]." AND p2.inicio=1";
		
		$Consulta2=$dbConn->db_Consultar($query2);
		$Numfilas2=$dbConn->db_Num_Rows($Consulta2);
		if ($Numfilas2>0){
			// Si tiene pagina de inicio, la copio
			$FilaUrl=$dbConn->fetch_associativo($Consulta2);
			$Url=$FilaUrl["url"];				
		}else{
			// Si no tiena pagina de inicio, paso el dato vacio
			$Url="";				
		}
		$Estatus=1;
		$Mensaje="Usuario y sesion Validos";			
	}else{  // No supero la validacion de la sesion
		$Estatus=4;
		$Mensaje="Sin datos para este usuario";
		$ArrayData = $fila;
		$Url="";				
	}
	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "pagina"=>$Url, "datosUsuario"=>$ArrayData);
	return json_encode($json);	
}

function sesionactiva($token){

	$dbConn= new AccesoDB;	
	$idusr = 0;

	$query='SELECT cast(valor as smallint) as toff
	FROM cantv.configuracion
	WHERE id_conf=1'; //1 es el ID de Duracion maxima de sesison sin actividad en minutos

	$Consulta=$dbConn->db_Consultar($query);
	$fila = pg_fetch_array($Consulta); //tomo el valor maximo de tiempo de sesion viva
	$t_limit = $fila['toff'];		

	//Buscar sesiones activas y tiempo	
	$query2='SELECT id_usuario, fecha_ini, fecha_uso, fecha_fin,
	floor(extract(epoch from now()-fecha_uso)/60) as t_com
	FROM cantv.sesiones
	WHERE fecha_fin is NULL	
	AND usr_token='.chr(39).$token.chr(39);
	
	$Consulta2=$dbConn->db_Consultar($query2);
	$Numfilas=$dbConn->db_Num_Rows($Consulta2);
	
	if ($Numfilas > 0) {
		$fila=$dbConn->fetch_associativo($Consulta2);				
		$f_ini = $fila['fecha_ini'];	
		$f_uso = $fila['fecha_uso'];	
		$f_fin = $fila['fecha_fin'];	
		$t_com = $fila['t_com'];
		if ($t_com > $t_limit) {		
			$resultado = sesioncerrar($token);
			if ($resultado) {
				$idusr = -2; //sesion expirada y cerrada
			} else {
				$idusr = -3; //sesion expirada y no se pudo cerrar
			}
		} else {						
			$query3 = 'UPDATE cantv.sesiones
			SET fecha_uso=current_timestamp 
			WHERE usr_token='.chr(39).$token.chr(39);
			$Consulta3=$dbConn->db_Consultar($query3);
			$idusr = $fila['id_usuario'];					
		}	
	} else {
		$idusr = -1; //No Hay una sesion activa para ese token
	}	
	return $idusr;
}

function sesioncerrar($token){

	$dbConn= new AccesoDB;	

	$query='UPDATE cantv."sesiones" SET fecha_fin=current_timestamp
	WHERE fecha_fin is NULL 
	AND usr_token='.chr(39).$token.chr(39);

	$Consulta=$dbConn->db_Consultar($query);						
	return true;		
}

function sesionabrir($idUsuario){

	$dbConn= new AccesoDB;	
	
	//Buscar si quedo sesion sin cerrar debidamente para ese usuario
	$query2='SELECT id_usuario, usr_token
	FROM cantv.sesiones
	WHERE fecha_fin is NULL	
	AND id_usuario='.$idUsuario;

	$Consulta2=$dbConn->db_Consultar($query2);
	$Numfilas2=$dbConn->db_Num_Rows($Consulta2);
	
	if ($Numfilas2 > 0) {
		//Habia una sesion abierta
		$fila2 = pg_fetch_array($Consulta2); 
		$tkn = $fila2['usr_token'];			
		$result = sesioncerrar($tkn);	
	}else {		
		//Debe abrir una nueva sesion
		$result=true;
	}		
	if ($result) {
		$query3='SELECT cantv.nuevasesion('.chr(39).$idUsuario.chr(39).'::smallint)';

		$Consulta3=$dbConn->db_Consultar($query3); 
		$Numfilas3=$dbConn->db_Num_Rows($Consulta3);	
		if ($Numfilas3 > 0) {
			$fila3 = pg_fetch_array($Consulta3); //Aqui tengo el token
			$tkn = $fila3['nuevasesion'];										
			$Estatus=1;			
			$Mensaje='Nueva Sesion abierta';		
		} else {
			$tkn = '';										
			$Mensaje='ERROR al abrir sesion';		
			$Estatus=2;	 //Error asociado a la funcion de BD para abrir sesion
		}					
	}else{
		$Estatus=3; //Error asociado a la funcion en php de cierre de sesion
		$Mensaje='Error al abrir Sesion';
		$tkn='';
	}
	
	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "token"=>$tkn);
	return json_encode($json);		  	
}

function usuariovalido2($json_data){
	include 'clas_usu_logueo_clave.php';	
	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>"", "id_unid_admin"=>"", "unidad_administrativa"=>"", "id_tipo_doc_ident"=>"", "tipo_doc_identidad"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "nombre_jerarquia"=>"", "clave"=>"", "nb_usuario"=>""];

	$Clave = $json_data->clave;
	$Usuario = $json_data->usuario;		

	$dbConn= new AccesoDB;
	$query='SELECT us.id_usuario id_usuario, us.id_perfil id_perfil, p.nombre_perfil nombre_perfil, us.id_gerencia id_gerencia, 
	ger.nb_gerencia gerencia, us.id_unid_admin id_unid_admin, ua.nombe unidad_administrativa, us.id_tipo_doc_ident id_tipo_doc_ident, 
	tdi.nemonico_doc tipo_doc_identidad, us.doc_identidad doc_identidad, us.nombres nombres, us.apellidos apellidos, us.f_nac f_nac, 
	us.f_creacion f_creacion, us.f_modif f_modif, us.id_us_mod id_us_mod, us.id_jerarquia id_jerarquia, 
	jq.nombre_jerarquia nombre_jerarquia,  us.clave clave, us.nb_usuario nb_usuario 
	FROM cantv."Usuarios" us
	INNER JOIN cantv."Perfiles" p ON us.id_perfil=p.id_perfil
	INNER JOIN cantv."gerencia" ger ON us.id_gerencia=ger.id_gerencia
	INNER JOIN cantv."Unidad_Administrativa" ua ON us.id_unid_admin=ua.id_unid_admin
	INNER JOIN cantv."Tipo_Doc_Identidad" tdi ON us.id_tipo_doc_ident=tdi.id_tipo_doc_ident
	INNER JOIN cantv."Jerarquias" jq ON us.id_jerarquia=jq.id_jerarquia
	WHERE us.nb_usuario='.chr(39).$Usuario.chr(39);
	Archivo($query, "usuariovalido1.txt");
	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);
	Archivo($Numfilas, "usuariovalido2.txt");
	if ($Numfilas>0){
		//Si hay registros es porque es un usuario existente
		// se verifica la clave
		// $filas = $Consulta->fetchAll();		//Coloco todo el ds en variable para pasarlo luego en el json
		// $fila=$filas[0];					// en fila se coloca el primer registro para consegior la clave con la que se va a comparar
		
		$fila=$dbConn->fetch_associativo($Consulta);
		$ClaveUsuariobd=$fila["clave"];
		$clave= new ClaveUsuario();				//Se instancio para comparar la clave
				
		if ($clave->verificarClave($Clave,$fila["clave"])==1){
			//al pasar las validaciones se abre una sesion			
			$iduser = $fila["id_usuario"];			
			$result=sesionabrir($iduser);
			$res = json_decode($result);
			//La respuestas de la solicitud de abrir sesion
			$estat = $res->estatus;
			$mensa = $res->mensaje;
			$token = $res->token;
			if ($estat==1) {
				//Me traigo la pagina de inicio de este perfil
				$query1='SELECT p1.id_pagina , p1."nombre_pagina", p1."url",p1."activo", p1."descripcion"
				FROM cantv.paginas as p1 inner join cantv.pagina_perfil as p2
				ON p1.id_pagina=p2.id_pagina
				WHERE p2.id_perfil='.$fila["id_perfil"].' AND p2.inicio=1';

				$Consulta1=$dbConn->db_Consultar($query1);
				$Numfilas1=$dbConn->db_Num_Rows($Consulta1);
				if ($Numfilas1>0){
					// Si tiene pagina de inicio, la copio
					$FilaUrl=$dbConn->fetch_associativo($Consulta1);
					$Url=$FilaUrl["url"];
				}else{
					// Si no tiena pagina de inicio, paso el dato vacio
					$Url="";
				}
				$Estatus=1;
				$Mensaje="Usuario y clave validos - Se abre sesion";
			} else {
				$Estatus=3;
				$Mensaje=$mensa;
				$token='';
			}						
		}else {
			$Estatus=2;
			$Mensaje="Usuario o clave incorrectos";
			$token='';
		}	
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje de usuario incorrecto
		$Estatus=2;
		$Mensaje="Usuario o clave incorrectos";
		$token='';		
	}
	// $datos[0]=$fila;
	// $json = '{"Estatus": "'.$Estatus.'", "Mensaje": "'.$Mensaje.'", "Pagina": "'.$Url.'", "DatosUsuario": '.$datos.' }';
	//$fila["clave"]="";
	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "token"=>$token, "pagina"=>$Url);
	
	return json_encode($json);
}

function categoriasindicadores($json_data){

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$ArrayData = array();
	$falla = ["idCategoria"=>null,"nbCategoria"=>null];
	
	if (!isset($json_data) || $json_data->idCategoria == 0) {
		$query='SELECT id_categoria, nb_categoria 
		FROM cantv.categoria_indicador';	
		$ArrayData[] = ["idCategoria"=>0,"nbCategoria"=>"Todas"];		
	} else {
		$idcat = $json_data->idCategoria;
		$query='SELECT id_categoria, nb_categoria 
		FROM cantv.categoria_indicador 
		WHERE id_categoria='.$idcat;	
	}
	
	$dbConn= new AccesoDB;
		
	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);

	if ($Numfilas>0){						
		while ($fila = pg_fetch_array($Consulta)) {
				$ide = intval($fila['id_categoria']);
				$nom = utf8_decode($fila['nb_categoria']);
				$ArrayData[] = ["idCategoria"=>$ide,"nbCategoria"=>$nom];
		}
		$Estatus=1;
		$Mensaje="Listado de Categorias";		
	}else{
		$Estatus=2;
		$Mensaje="No hay categorias";						
		$ArrayData = $falla;
	}	

	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "categorias"=>$ArrayData);
	return json_encode($json);
}

function modcategoriasindicadores($json_data){

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$sw = 0;

	$cat = $json_data->idCategoria;	
	$idu = $json_data->idUsuario;	
	$dbConn= new AccesoDB;

	if ($cat == 0) {
		$sw = 1;
	} else {
		# Con sesion activa valida
		$query='SELECT id_categoria,nb_categoria 
		FROM cantv.categoria_indicador
		WHERE id_categoria='.$cat;	
				
		$Consulta=$dbConn->db_Consultar($query);
		$Numfilas=$dbConn->db_Num_Rows($Consulta);
	
		if ($Numfilas>0){				
			# Categoria SI existe
			$sw = 1;
		}else {
			# Categoria NO existe
			$sw = 0;
		}	
	}
	
	if ($sw == 1) {		
		$query='UPDATE cantv.configurar_usuario 
		SET id_categoria='.$cat. ' 
		WHERE id_usuario='.$idu;

		$Consulta=$dbConn->db_Consultar($query);
		$Numfilas=$dbConn->db_Num_Rows($Consulta);
		
		$Estatus=1;			
		$Mensaje='Categoria Actualizada';
	} else {
		$Estatus=2;
		$Mensaje="La categoria seleccionada NO es valida";							
	}

	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "categoria"=>$cat);
	return json_encode($json);
}

function ficha($json_data){		

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>""];
	$ArrayData = array();
	$ArrayData1 = array();
	$ArrayData2 = array();
	$vacio = ['idIndicador'=>null,'nbIndicador'=>null,'operacionEstadistica'=>null,'idSubTipoIndicador'=>null,
	'idEscalaMedicion'=>null,'definicionIndicador'=>null,'objetivoIndicador'=>null,'justificacionIndicador'=>null,
	'metodologiaCalculo'=>null,'formula'=>null,'defConceptosInvol'=>null,'idUnidadMedicion'=>null,'interpretacionIndicador'=>null,				
	'idPeriodoRecDatos'=>null,'idPeriodoPubDatos'=>null,'idGerencia'=>null,'idSubTipoCobertura'=>null,
	'defConceptosInvol'=>null,'fechaMaxMeta'=>null,'diasIncResult'=>null,'idCategoria'=>null];
	$vacio2= ['id'=>null, 'nb'=>null];
		
	$sw=0;
	$dbConn= new AccesoDB;
		
	$iduser = $json_data->idUsuario;
	$idindica = $json_data->idIndicador;
	
	if ($idindica == 0) {
		//armar arreglo vacio
		$ArrayData = $vacio;
		$Estatus = 2;
		$Mensaje = 'Nuevo Indicador';
	} else {		
		$query='SELECT id_indicador, nb_indicador, operacion_estadistica, id_sub_tipo_indicador, 
		id_escala_medicion, definicion_indicador, objetivo_indicador, 
		justificacion_indicador, metodologia_calculo, formula, def_conceptos_invol, 
		id_unidad_medicion, interpretacion_indicador, id_periodo_rec_datos, 
		id_periodo_pub_datos, ind.id_gerencia, id_sub_tipo_cobertura, fecha_max_meta, 
		dias_inc_result, id_categoria, ip.id_accion
		FROM cantv.indicadores as ind, cantv.indicaxperfil as ip
		,cantv."Usuarios" as us
		WHERE ind.id_indicador=ip.id_indica
		AND ip.id_perfil=us.id_perfil
		AND ind.id_indicador='.$idindica.' 
		AND us.id_usuario='.$iduser;

		$Consulta=$dbConn->db_Consultar($query);
		$Numfilas=$dbConn->db_Num_Rows($Consulta);
		if ($Numfilas > 0) {
				$fila = pg_fetch_array($Consulta);

				$id1 = intval($fila['id_indicador']);
				$nid = utf8_decode($fila['nb_indicador']);
				$oes = utf8_decode($fila['operacion_estadistica']);
				$ist = intval($fila['id_sub_tipo_indicador']);
				$iem = intval($fila['id_escala_medicion']);
				$din = utf8_decode($fila['definicion_indicador']);
				$oin = utf8_decode($fila['objetivo_indicador']);
				$jin = utf8_decode($fila['justificacion_indicador']);
				$mca = utf8_decode($fila['metodologia_calculo']);
				$for = utf8_decode($fila['formula']);
				$dci = utf8_decode($fila['def_conceptos_invol']);
				$ium = intval($fila['id_unidad_medicion']);
				$iin = utf8_decode($fila['interpretacion_indicador']);				
				$ipr = intval($fila['id_periodo_rec_datos']);
				$ipp = intval($fila['id_periodo_pub_datos']);
				$igc = intval($fila['id_gerencia']);
				$stc = intval($fila['id_sub_tipo_cobertura']);
				$dci = utf8_decode($fila['def_conceptos_invol']);
				$fmx = ($fila['fecha_max_meta']);
				$dir = intval($fila['dias_inc_result']);
				$ica = intval($fila['id_categoria']);
				$iac = intval($fila['id_accion']);
				
				$ArrayData = ['idIndicador'=>$id1,'nbIndicador'=>$nid,'operacionEstadistica'=>$oes,
				'idSubTipoIndicador'=>$ist,'idEscalaMedicion'=>$iem,'definicionIndicador'=>$din,
				'objetivoIndicador'=>$oin,'justificacionIndicador'=>$jin,'metodologiaCalculo'=>$mca,
				'formula'=>$for,'defConceptosInvol'=>$dci,'idUnidadMedicion'=>$ium,'interpretacionIndicador'=>$iin,				
				'idPeriodoRecDatos'=>$ipr,'idPeriodoPubDatos'=>$ipp,'idGerencia'=>$igc,'idSubTipoCobertura'=>$stc,
				'defConceptosInvol'=>$dci,'fechaMaxMeta'=>$fmx,'diasIncResult'=>$dir,'idCategoria'=>$ica,'permiso'=>$iac];				

				$Estatus = 1;
				$Mensaje = 'Ficha del indicador';				
		}else {
			//armar arreglo vacio
			$Estatus = 2;
			$Mensaje = 'Sin Datos para el indicador seleccionado o no tiene permiso';
		}							
	}

	//Estas son las tablas con Id por pasar ordenadas en un arreglo desde 1 (ArryData1) hasta 9 (ArrayData9)
	$tablas = array('sub_tipo_indicador','unidades_medicion','escalas_mediciones',
	'periodo_publicacion_datos','periodo_recoleccion_datos','gerencia','sub_tipo_cobertura',
	'sub_tipo_indicador','categoria_indicador');

	$i=1;
	foreach ($tablas as $key => $value) {
		
		${"ArrayData" . $i} = listatabla($value);	
		${"tabla" . $i} = lcfirst(str_replace('_','',ucwords($value,'_')));		 
		$i++;
	}
	//Fin de armar tablas soporte con camelCase								

	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "ficha"=>$ArrayData, $tabla1=>$ArrayData1, $tabla2=>$ArrayData2, 
	$tabla3=>$ArrayData3, $tabla4=>$ArrayData4, $tabla5=>$ArrayData5, $tabla6=>$ArrayData6, $tabla7=>$ArrayData7,
	$tabla8=>$ArrayData8, $tabla9=>$ArrayData9 );	

	return json_encode($json);		  
}

function inclumodficha($json_data){
	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>""];
	$ArrayData = array();
	$ArrayData1 = array();
	$ArrayData2 = array();
	$vacio = ['idIndicador'=>null,'nbIndicador'=>null,'operacionEstadistica'=>null,'idSubTipoIndicador'=>null,
	'idEscalaMedicion'=>null,'definicionIndicador'=>null,'objetivoIndicador'=>null,'justificacionIndicador'=>null,
	'metodologiaCalculo'=>null,'formula'=>null,'defConceptosInvol'=>null,'idUnidadMedicion'=>null,'interpretacionIndicador'=>null,				
	'idPeriodoRecDatos'=>null,'idPeriodoPubDatos'=>null,'idGerencia'=>null,'idSubTipoCobertura'=>null,
	'fechaMaxMeta'=>null,'diasIncResult'=>null,'idCategoria'=>null];
	$vacio2= ['id'=>null, 'nb'=>null];	

	$dbConn= new AccesoDB;
			
	$idind = intval($json_data->idIndicador);
	$nbind = utf8_decode($json_data->nbIndicador);
	$opest = utf8_decode($json_data->operacionEstadistica);
	$istin = intval($json_data->idSubTipoIndicador);
	$iesme = intval($json_data->idEscalaMedicion);
	$deind = utf8_decode($json_data->definicionIndicador);
	$obind = utf8_decode($json_data->objetivoIndicador);
	$juind = utf8_decode($json_data->justificacionIndicador);
	$mecal = utf8_decode($json_data->metodologiaCalculo);
	$formu = utf8_decode($json_data->formula);
	$dconi = utf8_decode($json_data->defConceptosInvol);
	$iumed = intval($json_data->idUnidadMedicion);
	$inind = intval($json_data->interpretacionIndicador);
	$iprec = intval($json_data->idPeriodoRecDatos);
	$ippub = intval($json_data->idPeriodoPubDatos);	
	$igcia = intval($json_data->idGerencia);
	$istco = intval($json_data->idSubTipoCobertura);
	$fmeta = ($json_data->fechaMaxMeta);
	$dinre = intval($json_data->diasIncResult);	
	$idcat = intval($json_data->idCategoria);
	
	if(empty($idind) || is_null($idind) || $idind == 0){

		$query='INSERT INTO cantv.indicadores(
		nb_indicador, operacion_estadistica, id_sub_tipo_indicador, id_escala_medicion, definicion_indicador, 
		objetivo_indicador, justificacion_indicador, metodologia_calculo, formula, def_conceptos_invol, id_unidad_medicion, 
		interpretacion_indicador, id_periodo_rec_datos, id_periodo_pub_datos, id_gerencia, id_sub_tipo_cobertura, fecha_max_meta, 
		dias_inc_result, id_categoria)
		VALUES ('.$nbind.','. $opest.','. $istin.','. $iesme.','. $deind.','. $obind.','. $juind.','. $mecal.','. $formu.','. 
		$dconi.','.$iumed.','.$inind.','. $iprec.', '.$ippub.','. $igcia.','. $istco.','. chr(39). $fmeta.chr(39).','. $dinre.','.$idcat.')';

		$Consulta=$dbConn->db_Consultar($query);

		if ($Consulta) {
			$Estatus = 1;
			$Mensaje = "Nuevo indicador incluido";
		}else{
			$Estatus = 2;
			$Mensaje = "Fallo al intentar Insertar Nuevo Indicador";
		}
	}else{
		$query = 'UPDATE cantv.indicadores 
		SET nb_indicador='. chr(39).$nbind. chr(39).', operacion_estadistica='.$opest.', id_sub_tipo_indicador='.$istin.', 
		id_escala_medicion='. $iesme.', definicion_indicador='. $deind.', objetivo_indicador='.$obind.', 
		justificacion_indicador='.$juind.', metodologia_calculo='. chr(39).$mecal. chr(39).', formula='. chr(39).$formu. chr(39).', 
		def_conceptos_invol='.$dconi.', id_unidad_medicion='.$iumed.', 	interpretacion_indicador='.$inind.', 
		id_periodo_rec_datos='.$iprec.', id_periodo_pub_datos='.$ippub.', id_gerencia='.$igcia.', 
		id_sub_tipo_cobertura='. $istco.', fecha_max_meta='. chr(39). $fmeta.chr(39).', dias_inc_result='.$dinre.', 
		id_categoria='.$idcat.' 
		WHERE id_indicador='.$idind;

		$Consulta=$dbConn->db_Consultar($query);

		if ($Consulta) {
			$Estatus = 1;
			$Mensaje = "Indicador Actualizado";
		}else{
			$Estatus = 2;
			$Mensaje = "Fallo al intentar Actualizar Indicador";
		}
	}
	

	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje);	

	return json_encode($json);		
}

function listaanios($json_data){

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";

	$idusu = $json_data->idUsuario;	
	$idind = $json_data->idIndicador;	

	$dbConn= new AccesoDB;

	$query='SELECT anio
	FROM cantv.datos_indicador as di, cantv.indicaxperfil as ip,
	cantv."Usuarios" as us
	WHERE di.id_indicador=ip.id_indica
	AND ip.id_perfil=us.id_perfil
	AND us.id_usuario='.$idusu.
	' AND id_indicador='.$idind. ' 
	GROUP BY anio';	
		
	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);

	if ($Numfilas>0){				
		while ($fila = pg_fetch_array($Consulta)) {
			$an = $fila['anio'];			
			$ArrayData[] = $an;
		}
		$Estatus=1;
		$Mensaje="Con Periodos validos para el indicador solicitado";		
	}else{
		$Estatus=2;
		$Mensaje="No hay datos o No tiene acceso a este indicador";		
		$ArrayData = ["anios"=>null];				
	}	

	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "listaAnios"=>$ArrayData);
	return json_encode($json);
}

function tiposgraficos($json_data){

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";

	$idusu = $json_data->idUsuario;	
	$idind = $json_data->idIndicador;	

	$dbConn= new AccesoDB;

	$query='SELECT gi.id_tipgras, gi.descripcion
	FROM cantv.tipo_grafxindica as gi, cantv.indicaxperfil as ip,
	cantv."Usuarios" as us
	WHERE gi.id_indica=ip.id_indica
	AND ip.id_perfil=us.id_perfil
	AND us.id_usuario='.$idusu.
	'AND gi.id_indica='.$idind;
			
	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);
	
	if ($Numfilas>0){				
		while ($fila = pg_fetch_array($Consulta)) {
			$id = intval($fila['id_tipgras']);			
			$nb = utf8_decode($fila['descripcion']);	
			$ArrayData[] = ["idTipoGrafico"=>$id, "nbTipoGrafico"=>$nb];
		}
		$Estatus=1;
		$Mensaje="Tipos de graficos Validos para este indicador";		
	}else{
		$Estatus=2;
		$Mensaje="No hay datos o No tiene acceso a este indicador";		
		$ArrayData = ["idTipoGrafico"=>null, "nbTipoGrafico"=>null];				
	}	

	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "tiposGrafico"=>$ArrayData);
	return json_encode($json);
}

function periodicidadindicador($json_data){

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";

	$idind = $json_data->idIndicador;	
	$idusu = $json_data->idUsuario;	
	
	$respuesta = usuario_indicador($idind,$idusu);

	if($respuesta){
		$dbConn= new AccesoDB;

		$query='SELECT tp.id_periodicidad, tp.nombre, tp.factor
		FROM cantv.indicadores as ind, cantv.periodo_indicador as pin, 
		cantv.tipo_periodicidad tp
		WHERE ind.id_indicador=pin.id_indicador
		AND pin.id_periodicidad=tp.id_periodicidad
		AND ind.id_indicador='.$idind;	
			
		$Consulta=$dbConn->db_Consultar($query);
		$Numfilas=$dbConn->db_Num_Rows($Consulta);

		if ($Numfilas>0){	
			
			while ($fila = pg_fetch_array($Consulta)) {
				$itp = intval($fila['id_periodicidad']);	
				$nom = utf8_decode($fila['nombre']);	
				$fac = intval($fila['factor']);	

				$ArrayData[] = ['idPeriodicidad'=>$itp,'periodicidad'=>$nom,'meses'=>$fac]; 	
			}
			$Estatus=1;
			$Mensaje="Con Datos Validos";		
		}else{
			$Estatus=2;
			$Mensaje="Sin Datos Validos";		
			$ArrayData[] = ['idPeriodicidad'=>0,'periodicidad'=>"",'meses'=>0];  
		}
	}else{
		$Estatus=3;
		$Mensaje="Sin permiso de ver informacion de este indicador";		
		$ArrayData[] = ['idPeriodicidad'=>0,'periodicidad'=>"",'meses'=>0]; 

	}		

	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "periodicidad"=>$ArrayData);
	return json_encode($json);
}

function listamenu($json_data){

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";

	$idusu = $json_data->idUsuario;		

	$dbConn= new AccesoDB;

	$query='select mm.id_menu, nombre_menu, pg.url,padre, orden
	FROM cantv.menu as mm, cantv."Usuarios" as us,
	cantv.paginas as pg
	WHERE mm.id_perfil=us.id_perfil
	AND mm.id_pagina=pg.id_pagina
	and us.id_usuario='.$idusu. ' 
	ORDER BY padre, orden';	
		
	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);

	if ($Numfilas>0){	
		
		while ($fila = pg_fetch_array($Consulta)) {
			$imm = intval($fila['id_menu']);	
			$nom = utf8_decode($fila['nombre_menu']);	
			$url = utf8_decode($fila['url']);	
			$pad = intval($fila['padre']);	
			$ord = intval($fila['orden']);	

			$ArrayData[] = ['idMenu'=>$imm,'nombre'=>$nom,'url'=>$url,'padre'=>$pad,'orden'=>$ord]; 	
		}
		$Estatus=1;
		$Mensaje="Con Datos Validos";		
	}else{
		$Estatus=2;
		$Mensaje="Sin Datos Validos";		
		$ArrayData = ['idMenu'=>0,'nombre'=>"",'url'=>"",'padre'=>0,'orden'=>0]; 	
	}

	$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "listaMenu"=>$ArrayData);
	return json_encode($json);
}

function usuario_indicador($ind, $iduser) {
	//Valida si el perfil del usuario tiene acceso a la informacion del indicador
    $resp = false;

	$dbConn= new AccesoDB;

	$query='SELECT * 
	FROM cantv.indicaxperfil as ip, cantv."Usuarios" as us
	WHERE ip.id_perfil=us.id_perfil
	AND us.id_usuario='.$iduser. '
	AND ip.id_indica='.$ind;

	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);

	if ($Numfilas>0){				
		$resp = true;
	}

    return $resp;
}

function configusuario($iduser){

	$dbConn= new AccesoDB;	

	$query='SELECT id_categoria FROM cantv.configurar_usuario WHERE id_usuario='.$iduser;

	$Consulta = $dbConn->db_Consultar($query);	
	$Numfilas = $dbConn->db_Num_Rows($Consulta);

	if ($Numfilas>0){	
		$fila = pg_fetch_array($Consulta);
		$icu = intval($fila['id_categoria']);
	}else{
		$icu = 0;
	}	

	$json = array("idCategoria"=>$icu);
	return json_encode($json);
}

function listatabla($tabla){
	
	$columnas = array();
	$ArrayTabla = array();
	$dbConn= new AccesoDB;
		
	$query = "SELECT * FROM cantv.".$tabla;

	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);

	if($Numfilas > 0){		
		$columnas = pg_fetch_all($Consulta);
		//Codigo para convertir las variables del arreglo en camelCase
		foreach ($columnas as $indice){				
			foreach ($indice as $key => $valor){
				if(strpos($key,'_') == true){
					$a = lcfirst(str_replace('_','',ucwords($key,'_')));				
					$indice[$a] = utf8_decode($indice[$key]);
					//$indice[$a] = $valor;
					unset($indice[$key]);	
				}else{
					$a = $key;
				}					
			}		
		$ArrayTabla[] = $indice;								
		}		
	}else{	
		$query2 = "SELECT column_name
		FROM information_schema.columns
		WHERE table_schema='cantv'
		AND table_name=".chr(39).$tabla.chr(39). "
		ORDER by ordinal_position";

		$Consulta2=$dbConn->db_Consultar($query2);
		$columna = pg_fetch_all($Consulta2);
		foreach ($columna as $k=>$v){			
			foreach($v as $z){
				$columna2[] = lcfirst(str_replace('_','',ucwords($z,'_')));
			}
		}	
		$ArrayTabla = array_flip($columna2);
		foreach($ArrayTabla as $k2 => $v2){
			$ArrayTabla[$k2] = $v2*0;
		}
	}		
	return $ArrayTabla;
}

function array_unico($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();
   
    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[] = $val; //si coloco $i dentro[] enumera los datos del arreglo
        }
        $i++;
    }
    return $temp_array;
}

////////////Fin Cesar///////////////
///////////////////////////////////


function Archivo ($Mensaje, $Archivo='Archivo.txt'){
	
	if (strtoupper(substr(php_uname(), 0, 3)) === 'WIN') {
		// $Direccion="C:\wamp\www\indican\archivos\P_";
		$Direccion="archivos\P_";
		$file = fopen($Direccion.$Archivo, "w");
	} else {
		$file = fopen("/var/www/html/indican/archivos/".$Archivo, "w");
	}
	
	$algo="Mensaje: ".$Mensaje;
	fwrite($file, $algo. PHP_EOL);
	fclose($file);
}


?>

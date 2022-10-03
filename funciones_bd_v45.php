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
				$query="SELECT p1.id_pagina, p1.".chr(34)."Nombre_Pagina".chr(34).", p1.".chr(34)."Descripcion".chr(34).", p1.".chr(34)."Url".chr(34).", p1.".chr(34)."Activo".chr(34)."
				FROM cantv.".chr(34)."Paginas".chr(34)." p1 inner join cantv.".chr(34)."Pagina_Perfil".chr(34)." p2
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
	AND ip.id_perfil='.chr(39).$idPerfil.chr(39);	

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
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>""];
	$ArrayData=array();
	
	$year = $json_data->idanio;
	$idIndica = $json_data->idindicador;	

	$dbConn= new AccesoDB;

	//Buscar datos por indicdor por año solicitado
	$query='SELECT ind.id_indicador, ind.nb_indicador, ind.valor_real,
	ind.valor_meta, ind.valor_ejecutado, ind.anio, ind.mes	
	FROM cantv."v_indica_valores" as ind	
	WHERE ind.id_indicador='.chr(39).$idIndica.chr(39).' AND ind.anio='.chr(39).$year.chr(39).
	' ORDER BY ind.mes_n';

	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);

	if ($Numfilas>0){
		//Obtener los datos x indicador
		while ($fila = pg_fetch_array($Consulta)) {
			
			$vr = $fila['valor_real'];
			$vm = $fila['valor_meta'];
			$ve = $fila['valor_ejecutado'];
			$mm = $fila['mes'];
			
			$ArrayData[] = ['mes'=>$mm,'Valor Real'=>$vr, 'Valor Meta'=>$vm, 'Valor Ejecutado'=>$ve]; 

			$id = $fila['id_indicador'];
			$nb = $fila['nb_indicador'];
		}	
		$Estatus=1;
		$Mensaje="Datos de Indicadores";

		//Buscar los tipos de graficos x indicador
		$query2 ='SELECT ind.id_indicador, tg.descripcion, tg.id_tipgras
		FROM cantv."indicadores" as ind, cantv."tipo_grafxindica" as tg
		WHERE ind.id_indicador=tg.id_indica	
		AND ind.id_indicador='.chr(39).$idIndica.chr(39);

		$Consulta2=$dbConn->db_Consultar($query2);
		$Numfilas2=$dbConn->db_Num_Rows($Consulta2);		
		
		$ArrayData2=array();
		if ($Numfilas2>0){
						
			while ($fila = pg_fetch_array($Consulta2)) {
						
				$id2 = $fila['id_tipgras'];				
				$des = utf8_decode($fila['descripcion']);
				$ArrayData2[] = array('[id_tipo_grafico'=> $id2, 'nb_tipo_grafico'=>$des); 				
			}	
		}else{			
			$ArrayData2[] = array('id_tipo_grafico'=> "", 'nb_tipo_grafico'=>""); 
		}	
	}else{
		$Estatus=2;
		$Mensaje="Sin Datos";	
		$id='';
		$nb='';
		$ArrayData[] = array('id_indicador'=>"", 'nb_indicador'=> "", 'año'=>""); 
		$ArrayData2[] = array('mes'=>"", 'valor real'=> "", 'valor meta'=>"", 'valor ejecutado'=>""); 
	}				
	$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "idIndicador"=>$id, "nb_indicador"=>$nb, "DatosIndicador"=>$ArrayData,'Tipos_Graficos_Soport'=>$ArrayData2);
	Archivo("Estatus: ".$Estatus.", Mensaje: ". $Mensaje, "verifica6.txt");
	return json_encode($json);  
}

function listavpgcia($json_data){		

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>""];
	
	$idPerfil = $json_data->idperfil;
	$idUser = $json_data->idusuario;	

	$dbConn= new AccesoDB;
	//Buscar VP, Gcias e indicadores por perfil
	$query='SELECT vp.id_vice_presidencia, vp.nb_vicepresidencia, gc.id_gerencia, gc.nb_gerencia, 
	ac.valor as permiso, ind.id_indicador, ind.nb_indicador
	FROM cantv."indicaxperfil" as ip, cantv."Usuarios" as us,
	cantv."indicadores" as ind, cantv."gerencia" as gc,
	cantv."vice_presidencia" as vp, cantv."acciones" as ac
	WHERE ip.id_perfil=us.id_perfil
	AND ip.id_indica=ind.id_indicador
	AND ind.id_gerencia=gc.id_gerencia
	AND gc.id_vice_presidencia=vp.id_vice_presidencia
	AND ip.id_accion=ac.id_accion
	AND ip.id_perfil='.chr(39).$idPerfil.chr(39). ' AND us.id_usuario='.chr(39).$idUser.chr(39). ' 
	GROUP BY gc.id_gerencia, gc.nb_gerencia,vp.id_vice_presidencia, ac.valor, ind.id_indicador, ind.nb_indicador';

	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);
	$ArrayData=array();	
	$ArrayData2=array();
	$ArrayData3=array();
	if ($Numfilas>0){				
		//Listar los datos encontrados de VP, Gcia e indicadores x perfil
		while ($fila = pg_fetch_array($Consulta)) {			
			$ivp = ($fila['id_vice_presidencia']);
			$nvp = utf8_decode($fila['nb_vicepresidencia']);			
			$igc = ($fila['id_gerencia']);
			$ngc = utf8_decode($fila['nb_gerencia']);			
			$ind = ($fila['id_indicador']);
			$per = ($fila['permiso']);
			$nin = utf8_decode($fila['nb_indicador']);			

			if (!array_search($ivp, array_column($ArrayData,'id_vice_presidencia'))) {
				$ArrayData[] = array('id_vice_presidencia'=> $ivp, 'nb_vicepresidencia'=> $nvp); 			
			}			
			if (!array_search($igc, array_column($ArrayData2,'id_gerencia'))) {
				$ArrayData2[] = array('id_gerencia'=> $igc, 'nb_gerencia'=> $ngc,'id_vice_presidencia'=> $ivp); 			
			}			
			$ArrayData3[] = array('id_gerencia'=> $igc, 'id_indicador'=>$ind, 'nombre_ind'=>$nin, 'Permiso'=>$per);
		}			
		$Estatus=1;
		$Mensaje="Datos Administrativos";		
		$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "ListaVicePresidencias"=>$ArrayData,'ListaGerencias'=>$ArrayData2, "Indicadores"=>$ArrayData3);
	}else{
		//En caso de no encontrar datos por perfil
		$Estatus=2;
		$Mensaje="Perfil o Usuario No tiene Unidad Administrativa asociada";	
		$ArrayData=["id_vice_presidencia"=>"", "nb_vicepresidencia"=>"" ];
		$ArrayData2=["id_gerencia"=>"", "nb_gerencia"=>"", "id_vice_presidencia"=>"" ];
		$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "ListaVicePresidencias"=>$ArrayData,'ListaGerencias'=>$ArrayData2);
	}					
	return json_encode($json);		  
}

function listavpgmetaresul($idUser, $idPerfil){	

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>"", "id_unid_admin"=>"", "unidad_administrativa"=>"", "id_tipo_doc_ident"=>"", "tipo_doc_identidad"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "nombre_jerarquia"=>"", "clave"=>"", "nb_usuario"=>""];
	
	$dbConn= new AccesoDB;

	$query='SELECT vp.id_vice_presidencia, vp.nb_vicepresidencia, gc.id_gerencia, gc.nb_gerencia, ac.valor as permiso
	FROM cantv."indicaxperfil" as ip, cantv."Usuarios" as us,
	cantv."indicadores" as ind, cantv."gerencia" as gc,
	cantv."vice_presidencia" as vp, cantv."acciones" as ac
	WHERE ip.id_perfil=us.id_perfil
	AND ip.id_indica=ind.id_indicador
	AND ind.id_gerencia=gc.id_gerencia
	AND gc.id_vice_presidencia=vp.id_vice_presidencia
	AND ip.id_accion=ac.id_accion
	AND us.id_perfil='.$idPerfil.chr(39).'
	AND us.id_usuario='.chr(39).$idUser.chr(39).'  
	GROUP BY gc.id_gerencia, gc.nb_gerencia,vp.id_vice_presidencia, ac.valor';

	Archivo($query, "verificar3.txt");
	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);
	$ArrayData=array();	
	$ArrayData2=array();
	Archivo($Numfilas, "verificar4.txt");	
	if ($Numfilas>0){				
		while ($fila = pg_fetch_array($Consulta)) {
			
			$ivp = ($fila['id_vice_presidencia']);
			$nvp = utf8_decode($fila['nb_vicepresidencia']);
			$igc = ($fila['id_gerencia']);
			$ngc = ($fila['nb_gerencia']);

			if (!array_search($ivp, array_column($ArrayData,'id_vice_presidencia'))) {
				$ArrayData[] = array('id_vice_presidencia'=>$ivp, 'nb_vicepresidencia'=>$nvp);
			}					
			$ArrayData2[] = array('id_gerencia'=> $igc, 'nb_gerencia'=> $ngc,'id_vice_presidencia'=> $ivp); 				
		}
		
		//************************************************************************** */
		//************************************************************************** */
		/* Aqui va la busqueda de los indicadores de las gerencias a las que tiene permiso el perfil*/
		
		$indicadores[0]=["id_indicador"=>"1", "nb_indicador"=>"indicador1", "id_gerencia"=>"1", "permiso"=>"2"];
		$indicadores[1]=["id_indicador"=>"2", "nb_indicador"=>"indicador2", "id_gerencia"=>"2", "permiso"=>"1"];
		$indicadores[2]=["id_indicador"=>"3", "nb_indicador"=>"indicador3", "id_gerencia"=>"2", "permiso"=>"2"];
		$indicadores[3]=["id_indicador"=>"4", "nb_indicador"=>"indicador4", "id_gerencia"=>"2", "permiso"=>"2"];
		$indicadores[4]=["id_indicador"=>"5", "nb_indicador"=>"indicador5", "id_gerencia"=>"2", "permiso"=>"2"];
		
		//************************************************************************** */
		//************************************************************************** */
		/* Hata aqui, esto debe sustituire por la consulta */

		$Estatus=1;
		$Mensaje="Datos Administrativos";		
		$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "listavicepresidencias"=>$ArrayData,"listagerencias"=>$ArrayData2, "indicadores"=>$indicadores);
	}else{
		$Estatus=2;
		$Mensaje="Perfil o Usuario No tiene Unidad Administrativa asociada";	
		$ArrayData=["id_vice_presidencia"=>"", "nb_vicepresidencia"=>"" ];
		$ArrayData2=["id_gerencia"=>"", "nb_gerencia"=>"", "id_vice_presidencia"=>"" ];
		$indicadores=["id_indicador"=>"", "nb_indicador"=>"", "id_gerencia"=>"", "permiso"=>""];
		$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "listavicepresidencias"=>$ArrayData,"listagerencias"=>$ArrayData2, "indicadores"=>$indicadores);
	}					
	return json_encode($json);		  
}

function listagrafpgcia($idUser,$idPerfil,$idGcia){

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>"", "id_unid_admin"=>"", "unidad_administrativa"=>"", "id_tipo_doc_ident"=>"", "tipo_doc_identidad"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "nombre_jerarquia"=>"", "clave"=>"", "nb_usuario"=>""];
	
	$dbConn= new AccesoDB;

	$ArrayData1=array();
	$ArrayData2=array();
	$ArrayData3=array();

	$query='SELECT a1.id_indicador,a1.nb_indicador,a1.id_gerencia,
	a1.permiso,a2.posgral,a3.posgcia, a1.id_usuario FROM
	(SELECT ind.id_indicador, ind.nb_indicador, ind.id_gerencia, 
	 ac.valor as permiso, ip.id_perfil, us1.id_usuario
	FROM cantv."indicaxperfil" as ip, cantv."indicadores" as ind, 
	cantv."acciones" as ac, cantv."Usuarios" as us1
	WHERE ip.id_indica=ind.id_indicador	
	 AND ip.id_perfil=us1.id_perfil
	AND ip.id_accion=ac.id_accion
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
		$i=0;
		while ($fila = pg_fetch_array($Consulta)) {
			
			$idi = $fila['id_indicador'];
			$nbi = utf8_decode($fila['nb_indicador']);
			$idg = $fila['id_gerencia'];
			$pgr = $fila['posgral'];
			$pgc = $fila['posgcia'];

			//Listado por Perfil 
			if (empty($idGcia)) {
				$ArrayData1[] = array('id_indicador'=> $idi, 'nb_indicador'=> $nbi, 'id_gcia'=> $idg);
				$Mensaje2=' -Sin Gcia';
			} else{				
				if ($idg==$idGcia) {
					$ArrayData1[] = array('id_indicador'=> $idi, 'nb_indicador'=> $nbi, 'id_gcia'=> $idg);
					$Mensaje2=' -Con Gcia';
				}else {
					$Mensaje2=' +Gcias';
				}
			}	
			//Listado TOP 4						
			if ($pgr >= 1 && $pgr <=4) {
				$ArrayData2[] = array('id_indicador'=> $idi, 'nb_indicador'=> $nbi, 'posicion'=>$pgr); 							
			}
			//Listado TOP Por Gerencia
			if ($pgc >= 1 && $pgc <=4) {
				$ArrayData3[] = array('id_indicador'=> $idi, 'nb_indicador'=> $nbi, 'posicion'=>$pgc); 				
			}			
		}	

		$v1 = sizeof($ArrayData2);
		$v2 = sizeof($ArrayData1);

		if ($v1 == 0 && $v2 > 0) {
			$Mensaje1='-Sin IndicaTOP';
			$v3 = $ArrayData1[0]['id_indicador'];
			$v4 = $ArrayData1[0]['nb_indicador'];
			$v5 = $ArrayData1[0]['id_gcia'];
			$ArrayData2=array('id_indicador'=> $v3, 'nb_indicador'=> $v4, 'id_gcia'=>$v5); 			
		}else{
			$Mensaje1='';
		}
		$Estatus=1;
		$Mensaje="Indicadores x Perfil".$Mensaje1.$Mensaje2;					
	}else{
		$Estatus=2;
		$Mensaje="Sin Indicadores x Perfil";
		$fila=["id_indicador"=>"", "nb_indicador"=>""];
		$ArrayData1[0]=$fila;				
	}	
	$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "ListaIndicadores"=>$ArrayData1, "ListaIndicadoresMostrar"=>$ArrayData2,"ListadoXgcia"=>$ArrayData3);
	return json_encode($json);

}

function datosmetasindicador($json_data){

	$Estatus=3;	
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>""];
	$ArrayData=array();
	$ArrayData1=array();
	$i=0;

	$idindica = $json_data->Id_Indicador;
	$idusu = $json_data->Id_Usuario;	
	$tabla='metas_indicadores';
	$anio = date('Y');

	$dbConn= new AccesoDB;
	//Validar si el par Indicador-Usuario solicitado
	$query1='SELECT ip.id_indica, ip.id_accion, us.id_usuario,ip.id_perfil
	FROM cantv."Usuarios" as us, cantv.indicaxperfil as ip
	WHERE us.id_perfil=ip.id_perfil
	AND us.id_usuario='.chr(39).$idusu.chr(39).'
	AND ip.id_indica='.chr(39).$idindica.chr(39);

	$Consulta1=$dbConn->db_Consultar($query1);
	$Numfilas1=$dbConn->db_Num_Rows($Consulta1);

	if ($Numfilas1 > 0) {
		$fila1 = pg_fetch_array($Consulta1);
		$perm = $fila1['id_accion'];

		//obtener Parametros de referencia x indicador
		$query2='SELECT i1.id_indicador,i1.nb_indicador, i1.id_gerencia,
		i1.fecha_max_meta, i1.dias_inc_result, um.unidad_medicion as umed,
		gc.nb_gerencia, vp.nb_vicepresidencia, bd.fecha_sol, vp.id_vice_presidencia
		FROM cantv.gerencia as gc, cantv.vice_presidencia as vp,
	   	cantv.unidades_medicion as um,cantv.indicadores as i1
		   LEFT JOIN 
		   (SELECT MAX(id_bandeja) id_bandeja,id_indica,fecha_sol, idusu_sol,idusu_apr
		   FROM cantv.bandeja
		   WHERE id_tipo_sol in (1,6)
		   AND id_status=2
		   GROUP BY id_indica,fecha_sol, idusu_sol,idusu_apr) as bd
		ON i1.id_indicador=bd.id_indica
		WHERE i1.id_unidad_medicion=um.id_unidad_medicion
		AND gc.id_gerencia=i1.id_gerencia
		AND gc.id_vice_presidencia=vp.id_vice_presidencia
		AND i1.id_indicador='.chr(39).$idindica.chr(39);

		$Consulta2=$dbConn->db_Consultar($query2);
		$fila2 = pg_fetch_array($Consulta2);

		$dias = $fila2['dias_inc_result'];
		$fmax = $fila2['fecha_max_meta'];
		$gcia = utf8_decode($fila2['nb_gerencia']);
		$nvpr = utf8_decode($fila2['nb_vicepresidencia']);
		$umed = $fila2['umed'];
		$nind = $fila2['nb_indicador'];
		$fsol = $fila2['fecha_sol'];
		$idgc = $fila2['id_gerencia'];
		$idvp = $fila2['id_vice_presidencia'];

		$ArrayData=["Id_Vice_Presidencia:"=>$idvp, "Vice Pesidencia:"=>$nvpr,"Id_Gerencia:"=>$idgc,"Gerencia:"=>$gcia, "Unidad_Indicador"=>$umed,"Fecha_Max_ingresoDatos: "=>$fmax, "Fecha_Mod_Datos: "=>$fsol, "Permiso: "=>$perm];
		//Extraer datos de las Metas del indicador
		$query3='SELECT t1.id_indicador, t1.anio,t1.mes,t1.fecha_inclusion,
		t1.fecha_modificacion, t1.cantidad,t1.observacion
		FROM cantv.metas_indicadores as t1
		WHERE t1.id_indicador='.chr(39).$idindica.chr(39).' AND t1.anio='.chr(39).$anio.chr(39);

		$Consulta3=$dbConn->db_Consultar($query3);
		$Numfilas3=$dbConn->db_Num_Rows($Consulta3);

		if ($Numfilas3 > 0 ) {
			while ($fila = pg_fetch_array($Consulta3)) {
				$anu = $fila['anio'];
				$mes = $fila['mes'];
				$can = $fila['cantidad'];
				$obs = utf8_decode($fila['observacion']);

				$ArrayData1[] = array('Mes: '=> $mes, 'valor: '=> $can, 'Observacion: '=>$obs); 	
				$Mensaje = 'Datos Indicador';
				$Estatus=1;
			}
		} else {
			$anu = '';
			$mes = '';
			$can = '';
			$obs = '';
			$ArrayData=["Vice Presidencia: "=>"", "Gerencia: "=>""];
			$ArrayData1=["Mes: "=>"", "Valor: "=>"", "Observacion: "=>""];
			$Mensaje = 'Sin Datos para este Indicador';
			$Estatus=2;
		}
	} else {
		$perm = 0;
		$ArrayData=["Vice Presidencia: "=>"", "Gerencia: "=>""];
		$ArrayData1=["Mes: "=>"", "Valor: "=>"", "Observacion: "=>""];
		$Mensaje = 'Usuario o Indicador NO existen o el Usuario No tiene permiso de ver Indicador';
		$Estatus=2;
	}

	$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, ' '=>$ArrayData,"ListaMetas"=>$ArrayData1);
	return json_encode($json);
}

function inclumodmetas($json_data){

	$Estatus=3;	
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>"", "id_unid_admin"=>"", "unidad_administrativa"=>"", "id_tipo_doc_ident"=>"", "tipo_doc_identidad"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "nombre_jerarquia"=>"", "clave"=>"", "nb_usuario"=>""];
	$ArrayData=array();
	$ArrayData1=array();
	$i=0;

	$idindica = $json_data->Id_Indicador;
	$idusu = $json_data->Id_Usuario;	
	$anio = $json_data->anio;

	$tabla='metas_indicadores';
	//Hoy es
	$fecha = date("Y-m-d");

	$dbConn= new AccesoDB;
	//Buscar pareja Usuario - Indicador 
	$query1='SELECT ip.id_indica, ip.id_accion, us.id_usuario,ip.id_perfil
	FROM cantv."Usuarios" as us, cantv.indicaxperfil as ip
	WHERE us.id_perfil=ip.id_perfil
	AND us.id_usuario='.chr(39).$idusu.chr(39).'
	AND ip.id_indica='.chr(39).$idindica.chr(39);

	$Consulta1=$dbConn->db_Consultar($query1);
	$Numfilas1=$dbConn->db_Num_Rows($Consulta1);

	if ($Numfilas1 > 0) {
		//Combinacion Indicador - Usuario Existe 
		$fila1 = pg_fetch_array($Consulta1);
		$perm = $fila1['id_accion'];

		//obtener Parametros de referencia x indicador
		$query2='SELECT i1.id_indicador,i1.nb_indicador, i1.id_gerencia, bd.id_bandeja,
		i1.fecha_max_meta, i1.dias_inc_result, um.unidad_medicion as umed,bd.idusu_apr,
		gc.nb_gerencia, vp.nb_vicepresidencia, bd.fecha_sol, vp.id_vice_presidencia
		FROM cantv.gerencia as gc, cantv.vice_presidencia as vp,
	   	cantv.unidades_medicion as um,cantv.indicadores as i1
		   LEFT JOIN 
		   (SELECT MAX(id_bandeja) id_bandeja,id_indica,fecha_sol, idusu_sol,idusu_apr
		   FROM cantv.bandeja
		   WHERE id_tipo_sol in (1,6)
		   AND id_status=2
		   GROUP BY id_indica,fecha_sol, idusu_sol,idusu_apr) as bd
		ON i1.id_indicador=bd.id_indica
		WHERE i1.id_unidad_medicion=um.id_unidad_medicion
		AND gc.id_gerencia=i1.id_gerencia
		AND gc.id_vice_presidencia=vp.id_vice_presidencia
		AND i1.id_indicador='.chr(39).$idindica.chr(39);

		$Consulta2=$dbConn->db_Consultar($query2);
		$fila2 = pg_fetch_array($Consulta2);

		$dias = $fila2['dias_inc_result'];
		$fmax = $fila2['fecha_max_meta'];
		$fsol = $fila2['fecha_sol'];
		$usua = $fila2['idusu_apr'];
		$bdja = $fila2['id_bandeja'];
		
		foreach ($json_data->Metas as $dato) {
			//Evaluar cada uno de los datos x mes suministrado
			$mes = $dato->Mes;
			if ($mes < 1 || $mes > 12) {
				//Por si acaso me envia un mes fuera de lo normal
				$Estatus = 3;
				$Mensaje = 'ERROR de datos de entrada';
				$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Metas"=>"");
				return json_encode($json);
			} 			

			$obs = utf8_decode($dato->Observaciones);	
			$can = $dato->Valor;							
			$fecha1= date("Y").'-'.str_pad($mes,2,'0',STR_PAD_LEFT).'-'.str_pad($dias,2,'0',STR_PAD_LEFT);					 
			$fecha2= date("Y").'-'.str_pad(strval(intval($mes)+1),2,'0',STR_PAD_LEFT).'-'.str_pad($dias,2,'0',STR_PAD_LEFT);
			$fecha3=date("Y-m-t", strtotime($fecha1));
			
			if ($fecha <= $fmax) {
				//Permito Act e INC todo el año
				$ftop = $fmax;
				$sw = 1;
			} elseif ($fecha > $fmax && !is_null($fsol) && $fecha3 >= $fsol) {
				$ftop = $fsol;
				$sw = 2;
			}else{
				$ftop = $fmax;
				$sw = 0;
			}
			
			if ($sw > 0) {
				//Identifica si el valor existe->Update; No existe->Insert
				$query2='SELECT id_indicador
				FROM cantv.'.$tabla. ' 
				WHERE id_indicador='.chr(39).$idindica.chr(39).
				' AND anio='.$anio.' AND mes='.$mes;

				$Consulta2=$dbConn->db_Consultar($query2);
				$Numfilas2=$dbConn->db_Num_Rows($Consulta2);

				if ($Numfilas2 > 0) {
					$query4='UPDATE cantv.'.chr(34).$tabla.chr(34).
					' SET fecha_modificacion=current_date, 
					id_usuario_aprobacion_mod='.$usua.' , 
					cantidad='.$can.', observacion='.chr(39).$obs.chr(39).' 
					WHERE id_indicador='.$idindica.
					' AND anio='.$anio.' AND mes='.$mes;

					$Consulta4=$dbConn->db_Consultar($query4);
					$ArrayData1[] = array('mes'=>$mes, 'accion'=>'Update', 'Resultado'=>'Aprobado', 'Fecha Max'=>$ftop);
					$i++;
					$Mensaje = 'Aprobado';
				}else{
					$query5='INSERT INTO cantv.'.$tabla.' (
					id_indicador, anio, mes, fecha_inclusion, cantidad, id_usuario_inclusion,id_usuario_aprobacion_mod, observacion)
					VALUES ('.$idindica.','.$anio.','.$mes.', current_date,'.$can.','.$idusu.','.$usua.','.chr(39).$obs.chr(39).')';
					$Consulta5=$dbConn->db_Consultar($query5);
					$ArrayData1[] = array('mes'=>$mes, 'accion'=>'Insert', 'Resultado'=>'Aprobado', 'Fecha Max'=>$ftop);	
					$i++;
					$Estatus=1;
					$Mensaje = 'Aprobado';	
				}
			} else {
				$Estatus=1;
				$Mensaje = '-Sin Aprobacion';
				$ArrayData1[] = array('mes'=>$mes, 'accion'=>'Ins-Upd', 'Resultado'=>'No Aprobado', 'Fecha Max'=>$ftop);
			}
		}	
		if ($i > 0 && !is_null($bdja)) {
			$query6 = 'UPDATE cantv."bandeja" 
			SET id_status=3
			WHERE id_bandeja='.$bdja;
			$Consulta5=$dbConn->db_Consultar($query6);
		} 
	} else {
		$perm = 0;
		$ArrayData=["Vice Presidencia: "=>"", "Gerencia: "=>""];
		$ArrayData1=["Mes: "=>"", "Valor: "=>"", "Observacion: "=>""];
		$Mensaje = 'Usuario o Indicador NO existen o el Usuario No tiene permiso de ver Indicador';
		$Estatus=2;
	}
	$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Metas"=>$ArrayData1);
	return json_encode($json);
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

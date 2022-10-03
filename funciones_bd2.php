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
	// $datos[0]=$fila;
	
	$dbConn= new AccesoDB;
	$tabla='cantv."Usuarios"';
	$esquema=ESQUEMA;
	/* $query="SELECT id_usuario, id_perfil, id_gerencia, id_unid_admin, id_tipo_doc_ident, doc_identidad, nombres, apellidos, f_nac, f_creacion, f_modif, id_us_mod, id_jerarquia, clave, nb_usuario
	FROM $tabla WHERE nb_usuario='$Usuario'"; */
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
		// $filas = $Consulta->fetchAll();		//Coloco todo el ds en variable para pasarlo luego en el json
		// $fila=$filas[0];					// en fila se coloca el primer registro para consegior la clave con la que se va a comparar
		
		$fila=$dbConn->fetch_associativo($Consulta);
			
		$ClaveUsuariobd=$fila["clave"];
		Archivo($ClaveUsuariobd, "usuariovalido3.txt");
		$clave= new ClaveUsuario();				//Se instancio para comparar la clave
		
		if ($clave->verificarClave($Clave,$fila["clave"])==1){
			// Si la clave es correcta se envia el mensaje de confirmacion y los datos del usuario
			// echo "<br>";
			// echo "Clave Correcta";
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
			$Estatus=2;
			$Mensaje="Usuario o clave incorrectos";
		}
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje de usuario incorrecto
		$Estatus=2;
		$Mensaje="Usuario o clave incorrectos";
		
	}
	// $datos[0]=$fila;
	// $json = '{"Estatus": "'.$Estatus.'", "Mensaje": "'.$Mensaje.'", "Pagina": "'.$Url.'", "DatosUsuario": '.$datos.' }';
	$fila["clave"]="";
	$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Pagina"=>$Url, "DatosUsuario"=>$fila);
	Archivo("Estatus: ".$Estatus.", Mensaje: ". $Mensaje, "usuariovalido6.txt");
	return json_encode($json);

}

//---------------Cesar --------------//

function listarindicadores($idUsuario, $idPerfil){

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$Url="";
	$Pagina="";
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>"", "id_unid_admin"=>"", "unidad_administrativa"=>"", "id_tipo_doc_ident"=>"", "tipo_doc_identidad"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "nombre_jerarquia"=>"", "clave"=>"", "nb_usuario"=>""];
	
	$dbConn= new AccesoDB;
	$tabla='cantv."Usuarios"';
	$esquema=ESQUEMA;
	$query='SELECT ip.id_indica , ind.nb_indicador
	FROM cantv."indicaxperfil" as ip, cantv."indicadores" as ind
	WHERE ip.id_indica=ind.id_indicador
	AND ip.id_perfil='.chr(39).$idPerfil.chr(39);

	Archivo($query, "indicaxperfil.txt");
	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);
	$ArrayData=array();
	Archivo($Numfilas, "indicaxperfil2.txt");	
	if ($Numfilas>0){

while ($fila = pg_fetch_array($Consulta)) {

    $id = $fila['id_indica'];
    $nb = utf8_encode($fila['nb_indicador']);

	$ArrayData[] = array('id_indica'=> $id, 'nb_indicador'=> $nb); 
}

	$NombreInd=$fila["nb_indicador"];
	Archivo($NombreInd, "indicaxperfil3.txt");
	}else{

		$Estatus=2;
		$Mensaje="Perfil No Existe";	
	}

	$query2='SELECT it.id_indica,it.posicion,ind.nb_indicador
	FROM cantv."indicatop" it, cantv."indicadores" as ind
	WHERE it.id_indica=ind.id_indicador
	AND it.id_user='.chr(39).$idUsuario.chr(39);

	$Consulta2=$dbConn->db_Consultar($query2);
	$Numfilas2=$dbConn->db_Num_Rows($Consulta2);
	$ArrayData2=array();

	if ($Numfilas2>0){
		$i=0;
		while ($fila2 = pg_fetch_assoc($Consulta2)) {
			$ArrayData2[$i] = $fila2;			
			$i++;
		  }		

		  $Estatus=1;
		  $Mensaje="Listado de Indicadores Listo";
		  $Url="";
		  
		  
		  $json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "ListaIndicadores"=>$ArrayData, "ListaIndicadoresMostrar"=>$ArrayData2);
		  Archivo("Estatus: ".$Estatus.", Mensaje: ". $Mensaje, "indicaxperfil3.txt");
		  return json_encode($json);
	}else{

		$Estatus=2;
		$Mensaje="Usuario o Perfil No Existe";	
	}

}

function infoindicagra($idIndica, $year){

	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$Url="";
	$Pagina="";
	$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>"", "id_unid_admin"=>"", "unidad_administrativa"=>"", "id_tipo_doc_ident"=>"", "tipo_doc_identidad"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "nombre_jerarquia"=>"", "clave"=>"", "nb_usuario"=>""];
	
	$dbConn= new AccesoDB;
	$tabla='cantv."Usuarios"';
	$esquema=ESQUEMA;

	$query='SELECT ind.id_indicador, ind.nb_indicador, rr.valor_real,
	rr.valor_meta, rr.valor_ejecutado,rr.anio,rr.mes
	FROM cantv."indicadores" as ind
	LEFT JOIN
	(SELECT coalesce(mi.id_indicador, rv.id_indicador) id_indicador, 
		mi.cantidad as valor_meta,
		rv.valor_ejecutado, rv.valor_real,
		coalesce(mi.anio, rv.anio,null) anio,
		coalesce(mi.mes, rv.mes,null) mes
		FROM cantv."metas_indicadores" as mi
		 FULL JOIN 
		(SELECT ri.id_indicador,ri.cantidad as valor_ejecutado, vr.cantidad as valor_real,
		 coalesce(ri.anio, vr.anio,null) anio,
		coalesce(ri.mes, vr.mes,null) mes
		 FROM cantv."resultados_indicadores" as ri
		 LEFT JOIN cantv."valor_real" as vr
		 ON ri.id_indicador=vr.id_indicador
		 AND ri.anio=vr.anio AND ri.mes=vr.mes) as rv
		 ON mi.id_indicador=rv.id_indicador 
		 AND mi.anio=rv.anio
		 AND mi.mes=rv.mes) as rr	 	
		ON ind.id_indicador=rr.id_indicador
		WHERE ind.id_indicador='.chr(39).$idIndica.chr(39).'AND rr.anio='.$year;

	Archivo($query, "verificar3.txt");
	$Consulta=$dbConn->db_Consultar($query);
	$Numfilas=$dbConn->db_Num_Rows($Consulta);
	$ArrayData=array();
	Archivo($Numfilas, "verificar4.txt");	
	if ($Numfilas>0){

		while ($fila = pg_fetch_array($Consulta)) {
			
			$vr = $fila['valor_real'];
			$vm = $fila['valor_meta'];
			$ve = $fila['valor_ejecutado'];
			$mm = $fila['mes'];

			$ArrayData[] = array('mes'=> $mm, 'valor_real'=> $vr, 'valor_meta'=>$vm, 'valor_ejecutado'=>$ve); 
			$id = $fila['id_indicador'];
			$nb = $fila['nb_indicador'];
		}	
			//$NombreInd=$fila["nb_indicador"];
			Archivo($nb, "vrifica5.txt");

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
				$des = $fila['descripcion'];

				$ArrayData2[] = array('id_tipoGrafxInd'=> $id2, 'Descripcion'=>$des); 
				$id3 = $fila['id_indicador'];				
			}	

		}	

	}else{

		$Estatus=2;
		$Mensaje="Perfil o AÑo No Existe";	
		$fila=["id_indicador"=>"", "nb_indicador"=>"", "año"=>"" ];
		$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "DatosUsuario"=>$fila);
		return json_encode($json);

	}

		  $Estatus=1;
		  $Mensaje="Datos de Indicadores";
		  $Url="";
		  
		  
		  $json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "idIndicador"=>$id, "nb_indicador"=>$nb, "DatosIndicador"=>$ArrayData,'Tipos_Graficos_Soport'=>$ArrayData2);
		  Archivo("Estatus: ".$Estatus.", Mensaje: ". $Mensaje, "verifica6.txt");
		  return json_encode($json);
	  

	}

	function listavpgcia($idUser, $idPerfil){

		$Estatus=3;
		$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
		$Url="";
		$Pagina="";
		$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>"", "id_unid_admin"=>"", "unidad_administrativa"=>"", "id_tipo_doc_ident"=>"", "tipo_doc_identidad"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "nombre_jerarquia"=>"", "clave"=>"", "nb_usuario"=>""];
		
		$dbConn= new AccesoDB;
		$tabla='cantv."Usuarios"';
		$esquema=ESQUEMA;
	
		$query='SELECT vp.nb_vicepresidencia,vp.id_vice_presidencia
		FROM cantv."indicaxperfil" as ip, cantv."Usuarios" as us,
		cantv."indicadores" as ind, cantv."gerencia" as gc,
		cantv."vice_presidencia" as vp
		WHERE ip.id_perfil=us.id_perfil
		AND ip.id_indica=ind.id_indicador
		AND ind.id_gerencia=gc.id_gerencia
		AND gc.id_vice_presidencia=vp.id_vice_presidencia
		AND us.id_perfil='.chr(39).$idPerfil.chr(39).'AND us.id_usuario='.chr(39).$idUser.chr(39).'GROUP BY vp.nb_vicepresidencia,vp.id_vice_presidencia';

		$query2='SELECT gc.id_gerencia, gc.nb_gerencia,vp.id_vice_presidencia
		FROM cantv."indicaxperfil" as ip, cantv."Usuarios" as us,
		cantv."indicadores" as ind, cantv."gerencia" as gc,
		cantv."vice_presidencia" as vp
		WHERE ip.id_perfil=us.id_perfil
		AND ip.id_indica=ind.id_indicador
		AND ind.id_gerencia=gc.id_gerencia
		AND gc.id_vice_presidencia=vp.id_vice_presidencia
		AND us.id_perfil='.chr(39).$idPerfil.chr(39).'AND us.id_usuario='.chr(39).$idUser.chr(39).'GROUP BY gc.id_gerencia, gc.nb_gerencia,vp.id_vice_presidencia';
		
		Archivo($query, "verificar3.txt");
		$Consulta=$dbConn->db_Consultar($query);
		$Numfilas=$dbConn->db_Num_Rows($Consulta);
		$ArrayData=array();
		$ArrayData2=array();
		Archivo($Numfilas, "verificar4.txt");	
		if ($Numfilas>0){
	
			while ($fila = pg_fetch_array($Consulta)) {
				
				$ivp = $fila['id_vice_presidencia'];
				$nvp = $fila['nb_vicepresidencia'];
				
				$ArrayData[] = array('id_vice_presidencia'=> $ivp, 'nb_vicepresidencia'=> $nvp); 				
			}	
			
			$Consulta2=$dbConn->db_Consultar($query2);
			$Numfilas2=$dbConn->db_Num_Rows($Consulta2);
			if ($Numfilas2>0){
		
				while ($fila = pg_fetch_array($Consulta2)) {
									
					$igc = $fila['id_gerencia'];
					$ngc = $fila['nb_gerencia'];
					$ivp = $fila['id_vice_presidencia'];
					
					$ArrayData2[] = array('id_gerencia'=> $igc, 'nb_gerencia'=> $ngc,'id_vice_presidencia'=> $ivp); 				
				}	
			}
	
		}else{
	
			$Estatus=2;
			$Mensaje="Perfil o Usuario No tiene Unidad Administrativa asociada";	
			$fila=["id_indicador"=>"", "nb_indicador"=>"", "año"=>"" ];
			$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "DatosUsuario"=>$fila);
			return json_encode($json);
	
		}
	
			  $Estatus=1;
			  $Mensaje="Datos Administrativos";
			  $Url="";
			  			  
			  $json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "ListaVicePresidencias"=>$ArrayData,'ListaGerencias'=>$ArrayData2);
			  Archivo("Estatus: ".$Estatus.", Mensaje: ". $Mensaje, "verifica6.txt");
			  return json_encode($json);		  
		}
	
		function listagrafpgcia($idUser,$idPerfil,$idGcia){

			$Estatus=3;
			$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
			$Url="";
			$Pagina="";
			$fila2=["id_usuario"=>"", "id_perfil"=>"", "nombre_perfil"=>"", "id_gerencia"=>"", "gerencia"=>"", "id_unid_admin"=>"", "unidad_administrativa"=>"", "id_tipo_doc_ident"=>"", "tipo_doc_identidad"=>"", "doc_identidad"=>"", "nombres"=>"", "apellidos"=>"", "f_nac"=>"", "f_creacion"=>"", "f_modif"=>"", "id_us_mod"=>"", "id_jerarquia"=>"", "nombre_jerarquia"=>"", "clave"=>"", "nb_usuario"=>""];
			
			$dbConn= new AccesoDB;
			$tabla='cantv."Usuarios"';
			$esquema=ESQUEMA;
		
			$query='SELECT ind.id_indicador,ind.nb_indicador, it.posicion
			FROM cantv.indicatop it, cantv."Usuarios" as us, cantv."indicadores" as ind
			WHERE it.id_user=us.id_usuario
			AND it.id_indica=ind.id_indicador
			AND us.id_perfil='.chr(39).$idPerfil.chr(39).'AND us.id_usuario='.chr(39).$idUser.chr(39);
			
			$query2='SELECT ind.id_indicador,ind.nb_indicador, it.posicion
			FROM cantv.indicatop it, cantv."Usuarios" as us, cantv."indicadores" as ind
			WHERE it.id_user=us.id_usuario
			AND it.id_indica=ind.id_indicador
			AND us.id_perfil='.chr(39).$idPerfil.chr(39).'AND us.id_usuario='.chr(39).$idUser.chr(39);
			
			Archivo($query, "verificar3.txt");
			$Consulta=$dbConn->db_Consultar($query);
			$Numfilas=$dbConn->db_Num_Rows($Consulta);
			$ArrayData=array();
			$ArrayData2=array();
			Archivo($Numfilas, "verificar4.txt");	
			if ($Numfilas>0){
		
				while ($fila = pg_fetch_array($Consulta)) {
					
					$idi = $fila['id_indicador'];
					$nbi = $fila['nb_indicador'];
					
					$ArrayData[] = array('id_indicador'=> $idi, 'nb_indicador'=> $nbi); 				
				}	

				$Consulta2=$dbConn->db_Consultar($query2);
				$Numfilas2=$dbConn->db_Num_Rows($Consulta2);
				if ($Numfilas2>0){
					while ($fila = pg_fetch_array($Consulta2)) {

						$id2 = $fila['id_indicador'];
						$nb2 = $fila['nb_indicador'];
						$pos = $fila['posicion'];
						
						$ArrayData2[] = array('id_indicador'=> $idi, 'nb_indicador'=> $nbi, 'posicion'=>$pos); 				
					}	
	
				}						
			}else{
		
				$Estatus=2;
				$Mensaje="Perfil o Usuario No tiene Unidad Administrativa asociada";	
				$fila=["id_indicador"=>"", "nb_indicador"=>"", "año"=>"" ];
				$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "DatosUsuario"=>$fila);
				return json_encode($json);
		
			}
		
				  $Estatus=1;
				  $Mensaje="Datos Administrativos";
				  $Url="";
								
				  $json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "ListaIndicadores"=>$ArrayData, 'ListaIndicadoresMostrar'=>$ArrayData2);
				  Archivo("Estatus: ".$Estatus.", Mensaje: ". $Mensaje, "verifica6.txt");
				  return json_encode($json);		  
			}
	



















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




///////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////// Estas funciones no se deben utilizar, estan aqui unicamente para referencia//////////////
////////////////////////////// Solo deben ser utilizadas para tomar referencia ////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////



function CambiarClave($Correo, $Codcomp, $ClaveNueva, $ConfClave){
	include 'db.php';
	$json = '{"Estatus": 400, "Mensaje": "Error al tratar de consumir el recurso, por favor consulte con el administrador"}';
	$Estatus=400;
	$Mensaje="Error inesperado, por favor verifique";
	
	// Verifico que la nueva clave tenga el formato correcto
	if (FormatoClaveCorrecto($ClaveNueva)){ //Clave con el formato correcto
	
		// Verifico que la el codigo de confirmacion se
		$dbConn= new AccesoDB;
		
		$query = "select Codigo_Validacion from actualizar_clave where Correo_Usuario='".$Correo."' ORDER BY Fecha_Envio DESC LIMIT 1";        
		$Consulta=$dbConn->DB_Consultar($query);
		$Numfilas=$dbConn->DB_Num_Rows($Consulta);
		
		if ($Numfilas > 0) { // El correo existe en la tebla de envio de codigo
			$Registrio= $Consulta->fetch_assoc();
			$Codigo=$Registrio['Codigo_Validacion'];
			if ($Codcomp==$Codigo){ //Si el codigo de comprobacion es igual que el codigo de validacion en la tabla para el ultimo registro
				if ($ClaveNueva===$ConfClave){ //Compruebo que la clave nueva y la de confirmacion sean iguales
					// Actualizo la clave con la nueva clave
					$query2  = "update usuarios set Clave=md5('".$ClaveNueva. "') where Correo_Usuario='".$Correo."'";
					$Consulta=$dbConn->DB_Actualizar($query2);
					if ($Consulta) {
						$query3  = "delete from actualizar_clave where Correo_Usuario='".$Correo."'";
						$Consulta=$dbConn->borrar($query3);
						// http_response_code(200);
						$Estatus=200;
						$Mensaje="Clave Actualizada correctamente!!";
					} else {
						//echo "No actualizo la clave - Falla en BD";
						// http_response_code(404);
						$Estatus=407;
						$Mensaje="Clave no se pudo actualiza!!";
					}
				}else{ // Si las claves no son iguales
					$Estatus=404;
					$Mensaje="Las Claves no coinciden, por favor verifique!!";
				}
			}else { //Si el codigo de comprobacion es diferente al codigo de validacion en la tabla para el ultimo registro
				$Estatus=405;
				$Mensaje="El Codigo de confirmacion no coincide, por favor verifique o solicite otro!!";
			}
		}else{ // El correo no existe en la tebla de envio de codigo
			$Estatus=403;
			$Mensaje="No existen registros de envios de codigos de confirmacion, por favor verifique o solicite otro!!";
		}
	}else { //Clave con el formato incorrecto
		$Estatus=406;
		$Mensaje="Clave con un formato incorrecto o debil, por favor verifique";
	}
	$json = '{"Estatus": '.$Estatus.', "Mensaje": "'.$Mensaje.'" }';
	return $json;
	
}

function FormatoClaveCorrecto($ClaveNueva){
	// Comprueba si la clave contiene el formato correcto y emite verdadero, si no emite falso
	// LongitudMayor de 6 caracteres
	// Debe tener almenos un numero
	// Debe tener almenos un caracter especial ! " # $ % & / . : ; ,
	$Minimo=5;
	$Maximo=15;
	$resultado=true;
	
	if (strlen($ClaveNueva)<=$Minimo){ //Si es menor
		$resultado=false;
	}
	if (strlen($ClaveNueva)>=$Maximo){ //Si es mayor
		$resultado=false;
	}
	if (!preg_match('`[0-9]`',$ClaveNueva)){ // Si no tiene al menos un numero
      $resultado=false;
   }
    if (!preg_match('`[a-z]`',$ClaveNueva)){ // Si no tiene al menos una letra minuscula
      $resultado=false;
   }
   if (!preg_match('`[A-Z]`',$ClaveNueva)){ // Si no tiene al menos una letra mayuscula
      $resultado=false;
   } 
   if (!preg_match('`[!"#$%&/.:;,]`',$ClaveNueva)){ // Si no tiene al menos una letra mayuscula
      $resultado=false;
   } 
   
   // if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{'.$Minimo.','.$Maximo.'}$/', $ClaveNueva)){
	   // $resultado=false;
   // }
   // if (!preg_match('/^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%]){'.$Minimo.','.$Maximo.'}$/', $ClaveNueva)){
	   // $resultado=false;
   // }
   return $resultado;
}

function Pagina_Inicio($Id_Usuario){

}


function DatosPuntoRecarga($Id_Usuario){
	include 'db.php';
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	$query=$sql = "SELECT us.Id_Usuario, tdi.Nemonico_Tipo_Doc_Identidad, us.Doc_Identidad, us.Nombre, us.Apellido, us.Id_Perfil, us.Correo_Usuario, us.Celular
					FROM usuarios us
					INNER JOIN tipos_doc_identidad tdi ON us.Id_Tipo_Doc_Identidad=tdi.Id_Tipo_Doc_Identidad
					WHERE us.Id_Usuario=".$Id_Usuario."  LIMIT 1";
	$Consulta=$dbConn->DB_Consultar($query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	if ($Numfilas>0){
		//Si hay registros es porque es un usuario del punto
		//envio mensaje de usuario correcto
		$fila = $Consulta->fetch_row();
		//$Id_Usuario=$fila[0];
		$Nemonico_Tipo_Doc_Identidad=$fila[1];
		$Doc_Identidad=$fila[2];
		$Nombre=$fila[3];
		$Apellido=$fila[4];
		$Id_Perfil=$fila[5];
		$Correo_Usuario=$fila[6];
		$Celular=$fila[7];
		$Estatus=1;
		$Mensaje="Usuario correcto";
		// $mensaje = '1-,-Usuario correcto-,-'.$Usuario.'-,-'.$Id_Usuario.'-,-'.$Id_Punto.'-,-'.$Usuario_Bdv.'-,-'.$Clave_Bdv;
		// $json = '{"Estatus": 1, "Mensaje": "Usuario correcto", "Id_Usuario": '.$Id_Usuario.', "Nemonico_Tipo_Doc_Identidad": "'.$Nemonico_Tipo_Doc_Identidad.'", "Doc_Identidad": '.$Doc_Identidad.', "Nombre": "'.$Nombre.'", "Apellido": "'.$Apellido.'", "Id_Perfil": '.$Id_Perfil.', "Correo_Usuario": "'.$Correo_Usuario.'", "Id_Linea": '.$Id_Linea.', "Razon_Social": "'.$Razon_Social.'"  }';
		// Archivo($json,'datos_usuarios2.txt');
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje de usuario incorrecto
		// $mensaje = '3-,-Usuario o clave incorrectos o no es usuario de un punto de venta-,- -,- -,- -,- -,- -';
		$Nemonico_Tipo_Doc_Identidad="";
		$Doc_Identidad=0;
		$Nombre="";
		$Apellido="";
		$Id_Perfil=0;
		$Correo_Usuario="";
		$Celular="";
		$Estatus=2;
		$Mensaje="Usuario no existe";
		// $json = '{"Estatus": 2, "Mensaje": "Usuario no existe", "Id_Usuario": '.$Id_Usuario.', "Nemonico_Tipo_Doc_Identidad": "", "Doc_Identidad": 0, "Nombre": "", "Apellido": "", "Id_Perfil": 0, "Correo_Usuario": "", "Id_Linea": 0, "Razon_Social": ""  }';
		// $json = '{"Estatus": 3, "Mensaje": "Usuario o clave incorrectos o no es usuario de un punto de venta", "Usuario": "'.$Usuario.'","Id_Usuario": 0,"Id_sesion_us": 0,"Id_Punto": 0,"Id_Usuario_Bdv": "","Clave_Bdv": "", "Nombre_Punto": "", "Correo_Punto": "" }';
	}
	$json = '{"Estatus": '.$Estatus.', "Mensaje": "'.$Mensaje.'", "Id_Usuario": '.$Id_Usuario.', "Nemonico_Tipo_Doc_Identidad": "'.$Nemonico_Tipo_Doc_Identidad.'", "Doc_Identidad": '.$Doc_Identidad.', "Nombre": "'.$Nombre.'", "Apellido": "'.$Apellido.'", "Id_Perfil": '.$Id_Perfil.', "Correo_Usuario": "'.$Correo_Usuario.'", "Celular": "'.$Celular.'" }';
	
	return $json;

}
function DatosPunto($Id_Usuario){
	include 'db.php';
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	$query=$sql = "SELECT us.Id_Usuario, tdi.Nemonico_Tipo_Doc_Identidad, us.Doc_Identidad, us.Nombre, us.Apellido, us.Id_Perfil, us.Correo_Usuario, li.Id_Linea, li.Razon_Social
					FROM usuarios us
					INNER JOIN tipos_doc_identidad tdi ON us.Id_Tipo_Doc_Identidad=tdi.Id_Tipo_Doc_Identidad
					INNER JOIN usuarios_lineas usl ON us.Id_Usuario=usl.Id_Usuario
					INNER JOIN lineas li ON usl.Id_Linea=li.Id_Linea
					INNER JOIN lineas_rutas lir ON li.Id_Linea=lir.Id_Linea
					INNER JOIN rutas ru ON lir.Id_Ruta=ru.Id_Ruta
					INNER JOIN rutas_tramos rt ON ru.Id_Ruta=rt.Id_Ruta
					INNER JOIN tramos tr ON rt.Id_Tramo=tr.Id_Tramo
					WHERE us.Id_Usuario=".$Id_Usuario."  LIMIT 1";
	$Consulta=$dbConn->DB_Consultar($query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	if ($Numfilas>0){
		//Si hay registros es porque es un usuario del punto
		//envio mensaje de usuario correcto
		$fila = $Consulta->fetch_row();
		//$Id_Usuario=$fila[0];
		$Nemonico_Tipo_Doc_Identidad=$fila[1];
		$Doc_Identidad=$fila[2];
		$Nombre=$fila[3];
		$Apellido=$fila[4];
		$Id_Perfil=$fila[5];
		$Correo_Usuario=$fila[6];
		$Id_Linea=$fila[7];
		$Razon_Social=$fila[8];
		$Estatus=1;
		$Mensaje="Usuario correcto";
		// $mensaje = '1-,-Usuario correcto-,-'.$Usuario.'-,-'.$Id_Usuario.'-,-'.$Id_Punto.'-,-'.$Usuario_Bdv.'-,-'.$Clave_Bdv;
		// $json = '{"Estatus": 1, "Mensaje": "Usuario correcto", "Id_Usuario": '.$Id_Usuario.', "Nemonico_Tipo_Doc_Identidad": "'.$Nemonico_Tipo_Doc_Identidad.'", "Doc_Identidad": '.$Doc_Identidad.', "Nombre": "'.$Nombre.'", "Apellido": "'.$Apellido.'", "Id_Perfil": '.$Id_Perfil.', "Correo_Usuario": "'.$Correo_Usuario.'", "Id_Linea": '.$Id_Linea.', "Razon_Social": "'.$Razon_Social.'"  }';
		// Archivo($json,'datos_usuarios2.txt');
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje de usuario incorrecto
		// $mensaje = '3-,-Usuario o clave incorrectos o no es usuario de un punto de venta-,- -,- -,- -,- -,- -';
		$Nemonico_Tipo_Doc_Identidad="";
		$Doc_Identidad=0;
		$Nombre="";
		$Apellido="";
		$Id_Perfil=0;
		$Correo_Usuario="";
		$Id_Linea=0;
		$Razon_Social="";
		$Estatus=2;
		$Mensaje="Usuario no existe";
		// $json = '{"Estatus": 2, "Mensaje": "Usuario no existe", "Id_Usuario": '.$Id_Usuario.', "Nemonico_Tipo_Doc_Identidad": "", "Doc_Identidad": 0, "Nombre": "", "Apellido": "", "Id_Perfil": 0, "Correo_Usuario": "", "Id_Linea": 0, "Razon_Social": ""  }';
		// $json = '{"Estatus": 3, "Mensaje": "Usuario o clave incorrectos o no es usuario de un punto de venta", "Usuario": "'.$Usuario.'","Id_Usuario": 0,"Id_sesion_us": 0,"Id_Punto": 0,"Id_Usuario_Bdv": "","Clave_Bdv": "", "Nombre_Punto": "", "Correo_Punto": "" }';
	}
	$json = '{"Estatus": '.$Estatus.', "Mensaje": "'.$Mensaje.'", "Id_Usuario": '.$Id_Usuario.', "Nemonico_Tipo_Doc_Identidad": "'.$Nemonico_Tipo_Doc_Identidad.'", "Doc_Identidad": '.$Doc_Identidad.', "Nombre": "'.$Nombre.'", "Apellido": "'.$Apellido.'", "Id_Perfil": '.$Id_Perfil.', "Correo_Usuario": "'.$Correo_Usuario.'", "Id_Linea": '.$Id_Linea.', "Razon_Social": "'.$Razon_Social.'"  }';
		
	return $json;

}

function DatosPuntoLib($Id_Usuario){
	include 'db.php';
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	$query=$sql = "SELECT us.Id_Usuario, tdi.Nemonico_Tipo_Doc_Identidad, us.Doc_Identidad, us.Nombre, us.Apellido, us.Id_Perfil, us.Correo_Usuario, us.Id_Perfil, pf.Nombre_Perfil, qr.Id_Qr
FROM usuarios us
INNER JOIN tipos_doc_identidad tdi ON us.Id_Tipo_Doc_Identidad=tdi.Id_Tipo_Doc_Identidad
INNER JOIN perfiles pf ON us.Id_Perfil=pf.Id_Perfil
INNER JOIN qr on us.Id_Usuario=qr.Id_Usuario
WHERE qr.Principal=1 AND qr.Bloqueado=0 AND us.Id_Usuario=".$Id_Usuario."  LIMIT 1";
	$Consulta=$dbConn->DB_Consultar($query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	if ($Numfilas>0){
		//Si hay registros es porque es un usuario del punto
		//envio mensaje de usuario correcto
		$fila = $Consulta->fetch_assoc();
		//$Id_Usuario=$fila[0];
		$Nemonico_Tipo_Doc_Identidad=$fila['Nemonico_Tipo_Doc_Identidad'];
		$Doc_Identidad=$fila['Doc_Identidad'];
		$Nombre=$fila['Nombre'];
		$Apellido=$fila['Apellido'];
		$Correo_Usuario=$fila['Correo_Usuario'];
		$Id_Perfil=$fila['Id_Perfil'];
		$Nombre_Perfil=$fila['Nombre_Perfil'];
		$Id_Qr_Principal=$fila['Id_Qr'];
		$Estatus=1;
		$Mensaje="Usuario correcto";
		// $mensaje = '1-,-Usuario correcto-,-'.$Usuario.'-,-'.$Id_Usuario.'-,-'.$Id_Punto.'-,-'.$Usuario_Bdv.'-,-'.$Clave_Bdv;
		// $json = '{"Estatus": 1, "Mensaje": "Usuario correcto", "Id_Usuario": '.$Id_Usuario.', "Nemonico_Tipo_Doc_Identidad": "'.$Nemonico_Tipo_Doc_Identidad.'", "Doc_Identidad": '.$Doc_Identidad.', "Nombre": "'.$Nombre.'", "Apellido": "'.$Apellido.'", "Id_Perfil": '.$Id_Perfil.', "Correo_Usuario": "'.$Correo_Usuario.'", "Id_Linea": '.$Id_Linea.', "Razon_Social": "'.$Razon_Social.'"  }';
		// Archivo($json,'datos_usuarios2.txt');
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje de usuario incorrecto
		// $mensaje = '3-,-Usuario o clave incorrectos o no es usuario de un punto de venta-,- -,- -,- -,- -,- -';
		$Nemonico_Tipo_Doc_Identidad="";
		$Doc_Identidad=0;
		$Nombre="";
		$Apellido="";
		$Correo_Usuario="";
		$Id_Perfil=0;
		$Nombre_Perfil="";
		$Id_Qr_Principal="";
		$Estatus=2;
		$Mensaje="Usuario no existe, no tiene qr principal de trabajo o esta bloqueado";
		// $json = '{"Estatus": 2, "Mensaje": "Usuario no existe", "Id_Usuario": '.$Id_Usuario.', "Nemonico_Tipo_Doc_Identidad": "", "Doc_Identidad": 0, "Nombre": "", "Apellido": "", "Id_Perfil": 0, "Correo_Usuario": "", "Id_Linea": 0, "Razon_Social": ""  }';
		// $json = '{"Estatus": 3, "Mensaje": "Usuario o clave incorrectos o no es usuario de un punto de venta", "Usuario": "'.$Usuario.'","Id_Usuario": 0,"Id_sesion_us": 0,"Id_Punto": 0,"Id_Usuario_Bdv": "","Clave_Bdv": "", "Nombre_Punto": "", "Correo_Punto": "" }';
	}
	// $json = '{"Estatus": '.$Estatus.', "Mensaje": "'.$Mensaje.'", "Id_Usuario": '.$Id_Usuario.', "Nemonico_Tipo_Doc_Identidad": "'.$Nemonico_Tipo_Doc_Identidad.'", "Doc_Identidad": '.$Doc_Identidad.', "Nombre": "'.$Nombre.'", "Apellido": "'.$Apellido.'", "Id_Perfil": '.$Id_Perfil.', "Nombre_Perfil": "'.$Nombre_Perfil.'", "Correo_Usuario": "'.$Correo_Usuario.'", "Id_Qr_Principal": "'.$Id_Qr_Principal.'"  }';
	$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Id_Usuario"=>$Id_Usuario, "Nemonico_Tipo_Doc_Identidad"=>$Nemonico_Tipo_Doc_Identidad, "Doc_Identidad"=>$Doc_Identidad, "Nombre"=>$Nombre, "Apellido"=>$Apellido, "Id_Perfil"=>$Id_Perfil, "Nombre_Perfil"=>$Nombre_Perfil, "Correo_Usuario"=>$Correo_Usuario, "Id_Qr_Principal"=>$Id_Qr_Principal);
	
	return json_encode($Datos);

}

function DatosPuntoNeg($Id_Usuario){
	include 'db.php';
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	$query=$sql = "SELECT us.Id_Usuario, tdi.Nemonico_Tipo_Doc_Identidad, us.Doc_Identidad, us.Nombre, us.Apellido, us.Id_Perfil, us.Correo_Usuario, us.Id_Perfil, pf.Nombre_Perfil, qr.Id_Qr
FROM usuarios us
INNER JOIN tipos_doc_identidad tdi ON us.Id_Tipo_Doc_Identidad=tdi.Id_Tipo_Doc_Identidad
INNER JOIN perfiles pf ON us.Id_Perfil=pf.Id_Perfil
INNER JOIN qr on us.Id_Usuario=qr.Id_Usuario
WHERE qr.Principal=1 AND qr.Bloqueado=0 AND us.Id_Usuario=".$Id_Usuario."  LIMIT 1";
	$Consulta=$dbConn->DB_Consultar($query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	if ($Numfilas>0){
		//Si hay registros es porque es un usuario del punto
		//envio mensaje de usuario correcto
		$fila = $Consulta->fetch_assoc();
		//$Id_Usuario=$fila[0];
		$Nemonico_Tipo_Doc_Identidad=$fila['Nemonico_Tipo_Doc_Identidad'];
		$Doc_Identidad=$fila['Doc_Identidad'];
		$Nombre=$fila['Nombre'];
		$Apellido=$fila['Apellido'];
		$Correo_Usuario=$fila['Correo_Usuario'];
		$Id_Perfil=$fila['Id_Perfil'];
		$Nombre_Perfil=$fila['Nombre_Perfil'];
		$Id_Qr_Principal=$fila['Id_Qr'];
		$Estatus=1;
		$Mensaje="Usuario correcto";
		// $mensaje = '1-,-Usuario correcto-,-'.$Usuario.'-,-'.$Id_Usuario.'-,-'.$Id_Punto.'-,-'.$Usuario_Bdv.'-,-'.$Clave_Bdv;
		// $json = '{"Estatus": 1, "Mensaje": "Usuario correcto", "Id_Usuario": '.$Id_Usuario.', "Nemonico_Tipo_Doc_Identidad": "'.$Nemonico_Tipo_Doc_Identidad.'", "Doc_Identidad": '.$Doc_Identidad.', "Nombre": "'.$Nombre.'", "Apellido": "'.$Apellido.'", "Id_Perfil": '.$Id_Perfil.', "Correo_Usuario": "'.$Correo_Usuario.'", "Id_Linea": '.$Id_Linea.', "Razon_Social": "'.$Razon_Social.'"  }';
		// Archivo($json,'datos_usuarios2.txt');
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje de usuario incorrecto
		// $mensaje = '3-,-Usuario o clave incorrectos o no es usuario de un punto de venta-,- -,- -,- -,- -,- -';
		$Nemonico_Tipo_Doc_Identidad="";
		$Doc_Identidad=0;
		$Nombre="";
		$Apellido="";
		$Correo_Usuario="";
		$Id_Perfil=0;
		$Nombre_Perfil="";
		$Id_Qr_Principal="";
		$Estatus=2;
		$Mensaje="Usuario no existe, no tiene qr principal de trabajo o esta bloqueado";
		// $json = '{"Estatus": 2, "Mensaje": "Usuario no existe", "Id_Usuario": '.$Id_Usuario.', "Nemonico_Tipo_Doc_Identidad": "", "Doc_Identidad": 0, "Nombre": "", "Apellido": "", "Id_Perfil": 0, "Correo_Usuario": "", "Id_Linea": 0, "Razon_Social": ""  }';
		// $json = '{"Estatus": 3, "Mensaje": "Usuario o clave incorrectos o no es usuario de un punto de venta", "Usuario": "'.$Usuario.'","Id_Usuario": 0,"Id_sesion_us": 0,"Id_Punto": 0,"Id_Usuario_Bdv": "","Clave_Bdv": "", "Nombre_Punto": "", "Correo_Punto": "" }';
	}
	// $json = '{"Estatus": '.$Estatus.', "Mensaje": "'.$Mensaje.'", "Id_Usuario": '.$Id_Usuario.', "Nemonico_Tipo_Doc_Identidad": "'.$Nemonico_Tipo_Doc_Identidad.'", "Doc_Identidad": '.$Doc_Identidad.', "Nombre": "'.$Nombre.'", "Apellido": "'.$Apellido.'", "Id_Perfil": '.$Id_Perfil.', "Nombre_Perfil": "'.$Nombre_Perfil.'", "Correo_Usuario": "'.$Correo_Usuario.'", "Id_Qr_Principal": "'.$Id_Qr_Principal.'"  }';
	$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Id_Usuario"=>$Id_Usuario, "Nemonico_Tipo_Doc_Identidad"=>$Nemonico_Tipo_Doc_Identidad, "Doc_Identidad"=>$Doc_Identidad, "Nombre"=>$Nombre, "Apellido"=>$Apellido, "Id_Perfil"=>$Id_Perfil, "Nombre_Perfil"=>$Nombre_Perfil, "Correo_Usuario"=>$Correo_Usuario, "Id_Qr_Principal"=>$Id_Qr_Principal);
	
	return json_encode($Datos);

}


function ListadoRutas($Id_Usuario){
	include 'db.php';
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	$query=$sql = "SELECT ru.Id_Ruta, ru.Nombre_Ruta 
					FROM usuarios us 
					INNER JOIN usuarios_lineas usl ON us.Id_Usuario=usl.Id_Usuario 
					INNER JOIN lineas li ON usl.Id_Linea=li.Id_Linea 
					INNER JOIN lineas_rutas lir ON li.Id_Linea=lir.Id_Linea 
					INNER JOIN rutas ru ON lir.Id_Ruta=ru.Id_Ruta 
					WHERE us.Id_Usuario=".$Id_Usuario;
					
	$Consulta=$dbConn->DB_Consultar($query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	$ArrayData=array();
	if ($Numfilas>0){
		//Si hay registros es porque es un usuario del punto y tiene rutas asociadas
		//envio el listado de rutas
		$Estatus=1;
		$Mensaje="Listado de Rutas";
		$i=0;
		while ($fila=$Consulta->fetch_assoc()){
			$ArrayData[$i]=$fila;
			$i++;
		}
		
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje sin ruta
		$Estatus=2;
		$Mensaje="No Existen Rutas";
		$fila2=array("Id_Ruta"=>0,"Nombre_Ruta"=>"Sin Ruta",);
		$ArrayData[0]=$fila2;
		
	}
	$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Rutas"=>$ArrayData);
	
	return json_encode($Datos);

}

function ListadoTramos($Id_Usuario, $Ruta){
	include 'db.php';
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	$query=$sql = "SELECT tr.Id_Tramo, tr.Nombre_Tramo
					FROM usuarios us 
					INNER JOIN usuarios_lineas usl ON us.Id_Usuario=usl.Id_Usuario 
					INNER JOIN lineas li ON usl.Id_Linea=li.Id_Linea 
					INNER JOIN lineas_rutas lir ON li.Id_Linea=lir.Id_Linea 
					INNER JOIN rutas ru ON lir.Id_Ruta=ru.Id_Ruta 
                    INNER JOIN rutas_tramos rutr ON ru.Id_Ruta=rutr.Id_Ruta
                    INNER JOIN tramos tr ON rutr.Id_Tramo=tr.Id_Tramo
					WHERE us.Id_Usuario=".$Id_Usuario." AND ru.Id_Ruta=".$Ruta;
					
	$Consulta=$dbConn->DB_Consultar($query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	$ArrayData=array();
	if ($Numfilas>0){
		//Si hay registros es porque es un usuario del punto y tiene rutas asociadas
		//envio el listado de rutas
		$Estatus=1;
		$Mensaje="Listado de Tramos";
		$i=0;
		while ($fila=$Consulta->fetch_assoc()){
			$ArrayData[$i]=$fila;
			$i++;
		}
		
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje sin ruta
		$Estatus=2;
		$Mensaje="No Existen Tramos";
		$fila2=array("Id_Tramo"=>0,"Nombre_Tramo"=>"Sin Tramos",);
		$ArrayData[0]=$fila2;
		
	}
	$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Tramos"=>$ArrayData);
	
	return json_encode($Datos);

}

function ListadoCarga(){
	include 'db.php';
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	$query= "SELECT Id_Carga, Nombre_Carga FROM carga ORDER BY Orden ASC";
					
	$Consulta=$dbConn->DB_Consultar($query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	$ArrayData=array();
	if ($Numfilas>0){
		//Si hay registros es porque es un usuario del punto y tiene rutas asociadas
		//envio el listado de rutas
		$Estatus=1;
		$Mensaje="Listado de Carga Adicional";
		$i=0;
		while ($fila=$Consulta->fetch_assoc()){
			$ArrayData[$i]=$fila;
			$i++;
		}
		
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje sin ruta
		$Estatus=2;
		$Mensaje="No Existen Tramos";
		$fila2=array("Id_Carga"=>0,"Nombre_Carga"=>"Sin Carga Adicional",);
		$ArrayData[0]=$fila2;
		
	}
	$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Carga"=>$ArrayData);
	
	return json_encode($Datos);

}


function ListadoTPago(){
	include 'db.php';
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	$query= "SELECT Id_Tipo_Pago, Nombre_Tipo_Pago FROM tipos_pagos ORDER BY Orden  ASC";
					
	$Consulta=$dbConn->DB_Consultar($query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	$ArrayData=array();
	if ($Numfilas>0){
		//Si hay registros es porque es un usuario del punto y tiene rutas asociadas
		//envio el listado de rutas
		$Estatus=1;
		$Mensaje="Listado de Carga Adicional";
		$i=0;
		while ($fila=$Consulta->fetch_assoc()){
			$ArrayData[$i]=$fila;
			$i++;
		}
		
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje sin ruta
		$Estatus=2;
		$Mensaje="No Existen Tramos";
		$fila2=array("Id_Tipo_Pago"=>0,"Nombre_Tipo_Pago"=>"Sin Tipos de Pago",);
		$ArrayData[0]=$fila2;
		
	}
	$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "TPago"=>$ArrayData);
	
	return json_encode($Datos);

}

function MostrarMontoBus($Id_Usuario, $Ruta, $Tramo){
	include 'db.php';
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	$query= "SELECT Monto FROM tarifas WHERE Id_Tramo=".$Tramo;
	
	$Consulta=$dbConn->DB_Consultar($query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	$ArrayData=array();
	$Monto=0;
	if ($Numfilas>0){
		//Si hay registros es porque es un usuario del punto y tiene rutas asociadas
		//envio el listado de rutas
		$Estatus=1;
		$Mensaje="Monto del Tramo";
		// $i=0;
		// while ($fila=$Consulta->fetch_assoc()){
			// $ArrayData[$i]=$fila;
			// $i++;
		// }
		$fila=$Consulta->fetch_assoc();
		$Monto=$fila['Monto'];
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje sin ruta
		$Estatus=2;
		$Mensaje="No Existen Montos";
		// $fila2=array("Id_Tramo"=>0,"Nombre_Tramo"=>"Sin Tramos",);
		// $ArrayData[0]=$fila2;
		$Monto=0;
		
	}
	$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
	Archivo($query,'MostrarMontoBus.txt');
	return json_encode($Datos);

}

function Id_Qr($Qr){
	
	if (!class_exists('AccesoDB')){
	include 'db.php';
	}
	
	/* if (!class_exists('AccesoDB')){
	include 'configura.php';
	} */
	
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	
	
	Archivo ("Qr Origen: ".$Qr,"Id_Qr1.txt");
	$QrDecode=Decodificar64($Qr); // optengo el decodificado
	Archivo ("Qr Origen: ".$QrDecode,"Id_Qr2.txt");
	// Busco la palabra de control
	$Id_Qr=0;
	$Estatus=2;
	$Mensaje="Este Qr no existe en base de datos";
	$PalabraaBuscar=PALABRA_CONTROL;
	$LongitudPalabra=strlen($PalabraaBuscar);
	$PalabraaComparar=substr($QrDecode,0,$LongitudPalabra);
	Archivo ("Longitud de la palabra: ".$LongitudPalabra.", Palabra a buscar: ".$PalabraaBuscar.", La Otra Palabra: ".$PalabraaComparar,"Id_Qr3.txt");
	if ($PalabraaBuscar==$PalabraaComparar){ //Si la palabra a buscar es igual a la palabra al comienzo
		// Quito la palabra y me quedo con el numero que esta hasta el ;, que ese es el id del qr que esta pagando
		$Cadena=str_replace($PalabraaBuscar,"",$QrDecode);
		Archivo ("La Palabra quera: ".$Cadena,"Id_Qr4.txt");
		$StringDatos = explode(";", $Cadena);  // En la posicion 0 tengo el Id_Qr_In, En la posicion 1 tengo la Llave
		Archivo ("El Id: ".$StringDatos[0].", La Llave: ".$StringDatos[1],"Id_Qr5.txt");
		// Compruebo el Id del QR
		$Query="SELECT Id_Qr FROM qr WHERE Id_Qr=".$StringDatos[0]." AND Llave='".$StringDatos[1]."'";
		$Con_Qr=$dbConn->DB_Consultar($Query);
		$Numfilas=$dbConn->DB_Num_Rows($Con_Qr);
		if ($Numfilas>0){ //Existe el egisto
			$Qr_Id=$Con_Qr->fetch_assoc();
			$Id_Qr=$Qr_Id['Id_Qr'];
			$Estatus=1;
			$Mensaje="Qr existe";
			
		}else { //No existen egistros, por lo que el Qr no coincide o no existe o esta alteado
			$Id_Qr=0;
			$Estatus=2;
			$Mensaje="Este Qr no existe en base de datos";
		}
		Archivo ("El Query: ".$Query.", El Id: ".$Id_Qr,"Id_Qr6.txt");
		
	} else { //Si las palabras no coinciden es porque es un qr que no fue generado con nosotros
		Archivo ("las palabras no coinciden","Id_Qr7.txt");
		// Si las palabras no coinciden es porque es un qr que no fue generado por la aplicacion,
		//por lo tanto busco su id directamente en la tabla.
		$Query="SELECT Id_Qr FROM qr WHERE Qr='".$Qr."'";
		$Con_Qr=$dbConn->DB_Consultar($Query);
		$Numfilas=$dbConn->DB_Num_Rows($Con_Qr);
		if ($Numfilas>0){ //Existe el egisto
			$Qr_Id=$Con_Qr->fetch_assoc();
			$Id_Qr=$Qr_Id['Id_Qr'];
			$Estatus=1;
			$Mensaje="Qr existe";
		}else { //No existen egistros, por lo que el Qr ni coincide o no existe o esta alteado
			$Id_Qr=0;
			$Estatus=2;
			$Mensaje="Este Qr no existe en base de datoss";
		}
	}   // Hasta aqui ya tengo el id del qr que esta pagando.
	Archivo ("Estatus: ".$Estatus.", Mensaje: ".$Mensaje.", El Id_Qr_In es : ".$Id_Qr,"Id_Qr8.txt");
	$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Id_Qr"=>$Id_Qr);
	// return json_encode($Datos);
	return $Id_Qr;
}


function SaldoQr($Qr){
	if (!class_exists('AccesoDB')){
	include 'db.php';
	}
	
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	
	
	Archivo ("Qr Origen: ".$Qr,"SalsoQr1.txt");
	// Busco la palabra de control
	$Id_Qr=Id_Qr($Qr);
	Archivo ("Ir Qr Origen: ".$Qr,"SalsoQr2.txt");
	if ($Id_Qr>0){
		$query= "SELECT * FROM qr WHERE Id_Qr=".$Id_Qr;
		$Consulta=$dbConn->DB_Consultar($query);
		$Numfilas=$dbConn->DB_Num_Rows($Consulta);
		$ArrayData=array();
		$Monto=0;
		if ($Numfilas>0){
			//Si hay registros es porque es un usuario del punto y tiene rutas asociadas
			//envio el listado de rutas
			$Estatus=1;
			$Mensaje="Saldo del Recargador";
			$fila=$Consulta->fetch_assoc();
			$Saldo=$fila['Saldo'];
		}else{
			//Si no hay registros es porque no es un usuario del punto
			//envio mensaje sin ruta
			$Estatus=2;
			$Mensaje="Registro no existe, Usuario asociado: ".$Id_Usuario;
			$Saldo=0;
		}
		$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Saldo"=>$Saldo);
	} else {
		
		$Estatus=3;
		$Mensaje="Este Qr no existe en base de datos, por lo que no es posible cobrar";
		$Monto=$Monto;
		$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
		
	}
	return json_encode($Datos);
}

function SaldoMiQr($Id_Usuario='', $Id_Qr=''){
	if (!class_exists('AccesoDB')){
	include 'db.php';
	}
	
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	
	if ($Id_Qr<>''){
		$query= "SELECT * FROM qr WHERE Id_Qr=".$Id_Qr;
	} else {
		if ($Id_Usuario<>'') {
			$query= "SELECT * FROM qr WHERE Id_Usuario=".$Id_Usuario." AND Principal=1";
		} else {
			$query= "SELECT * FROM qr WHERE Id_Usuario=0 AND Principal=1";
		}
	}
	$Consulta=$dbConn->DB_Consultar($query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	$ArrayData=array();
	$Monto=0;
	if ($Numfilas>0){
		//Si hay registros es porque es un usuario del punto y tiene rutas asociadas
		//envio el listado de rutas
		$Estatus=1;
		$Mensaje="Saldo";
		$fila=$Consulta->fetch_assoc();
		$Saldo=$fila['Saldo'];
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje sin ruta
		$Estatus=2;
		$Mensaje="Registro no existe";
		$Saldo=0;
	}
	$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Saldo);
	// Archivo($query,'MostrarMontoBus.txt');
	return json_encode($Datos);

}


function Datos_Usuario($Id_Usuario){
	include 'db.php';
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	$query=$sql = "SELECT us.Id_Usuario, us.Id_Tipo_Doc_Identidad, tdoc.Nemonico_Tipo_Doc_Identidad, us.Doc_Identidad, us.Nombre, us.Apellido, 
	                      us.Fecha_Nacimiento, us.Id_Pais, pa.Nombre_Pais, us.Usuario, us.Correo, us.Fecha_Creacion, us.Id_Idioma, idi.Nombre_Idioma, 
						  us.Billetera, us.Saldo 
	               FROM usuarios us 
				   INNER JOIN tipos_doc_identidad tdoc ON us.ID_Tipo_Doc_Identidad=tdoc.Id_Tipo_Doc_Identidad 
				   INNER JOIN paises pa ON us.Id_Pais=pa.Id_Pais INNER JOIN idiomas idi ON us.Id_Idioma=idi.Id_Idioma 
				   WHERE Id_Usuario=".$Id_Usuario;
	$Consulta=$dbConn->DB_Consultar($query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	if ($Numfilas>0){
		//Si hay registros es porque es un usuario del punto
		//envio mensaje de usuario correcto
		$fila = $Consulta->fetch_row();
		//$Id_Usuario=$fila[0];
		$Id_Tipo_Doc_Identidad=$fila[1];
		$Nemonico_Tipo_Doc_Identidad=$fila[2];
		$Doc_Identidad=$fila[3];
		$Nombre=$fila[4];
		$Apellido=$fila[5];
		$Fecha_Nacimiento=$fila[6];
		$Id_Pais=$fila[7];
		$Nombre_Pais=$fila[8];
		$Usuario=$fila[9];
		$Correo=$fila[10];
		$Fecha_Creacion=$fila[11];
		$Id_Idioma=$fila[12];
		$Nombre_Idioma=$fila[13];
		$Billetera=$fila[14];
		$Saldo=$fila[15];
		
		// $mensaje = '1-,-Usuario correcto-,-'.$Usuario.'-,-'.$Id_Usuario.'-,-'.$Id_Punto.'-,-'.$Usuario_Bdv.'-,-'.$Clave_Bdv;
		$json = '{"Id_Usuario": '.$Id_Usuario.', "Id_Tipo_Doc_Identidad": '.$Id_Tipo_Doc_Identidad.', "Nemonico_Tipo_Doc_Identidad": "'.$Nemonico_Tipo_Doc_Identidad.'","Doc_Identidad": '.$Doc_Identidad.', "Nombre": "'.$Nombre.'", "Apellido": "'.$Apellido.'", "Fecha_Nacimiento": "'.$Fecha_Nacimiento.'", "Id_Pais": '.$Id_Pais.', "Nombre_Pais": "'.$Nombre_Pais.'", "Usuario": "'.$Usuario.'", "Correo": "'.$Correo.'", "Fecha_Creacion": "'.$Fecha_Creacion.'", "Id_Idioma": '.$Id_Idioma.', "Nombre_Idioma": "'.$Nombre_Idioma.'", "Billetera": "'.$Billetera.'", "Saldo": '.$Saldo.' }';
		// Archivo($json,'datos_usuarios2.txt');
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje de usuario incorrecto
		// $mensaje = '3-,-Usuario o clave incorrectos o no es usuario de un punto de venta-,- -,- -,- -,- -,- -';
		$json = '{"Estatus": 3, "Mensaje": "Usuario Usuario no existe, por fvor comuniquese con el administrador", "Id_Usuario": '.$Id_Usuario.' }';
		// $json = '{"Estatus": 3, "Mensaje": "Usuario o clave incorrectos o no es usuario de un punto de venta", "Usuario": "'.$Usuario.'","Id_Usuario": 0,"Id_sesion_us": 0,"Id_Punto": 0,"Id_Usuario_Bdv": "","Clave_Bdv": "", "Nombre_Punto": "", "Correo_Punto": "" }';
	}
	return $json;

}

function GenerarCodigo($Longitud, $Tipo=1){
	/* Funcion que genera un string aleatorio de una longitud determinada
		Parametros:
			$Longitud: Longitud del string que se va a generar
			$tipo: Tipo del string, actualmente no se usa ya que no se genera una clave criptografica, solo se utiliza cuando el string se genera como una llave criptografica
		Devuelve:
			Devuelve un string aleatorio con la longitud solicitada
	*/
    //Creamos la variable codigo
    $Codigo = "";
    //Caracteres a ser utilizados para construir el string aleatorio
    $Caracteres="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    //el maximo de caracteres a usar
    $max=strlen($Caracteres)-1;
    //creamos un for para generar el codigo aleatorio utilizando parametros min y max
    for($i=0;$i < $Longitud;$i++)
    {
        $Codigo.=$Caracteres[rand(0,$max)];
    }
    //regresamos codigo como valor  
	// $Codigo=openssl_random_pseudo_bytes($Longitud, $Tipo);
    return $Codigo;
}

function GenerarQr($Cantidad){
	/* Funcion que genera registros en la tabla qr y enntrega la informacion para que pueda ser utilizado o reproducido
		Parametros:
			$Cantidad: Cantidad de registros Qr que la funcion va a crear
		Devuelve:
			Devuelve un JSON con elemento de estado y una matriz con la informacion de los registros creados
	*/
	$DatosQr=array();
	$Qr=array();
	if ($Cantidad>0){ // Si la cantidad de registros a crear es mayor a cero
		//Creo el o los registos
		include 'db.php';
		$dbConn= new AccesoDB;
		$CodigoIdent=GenerarCodigo(40, 1); // Codigo con el que voy a identificar los registros que voy actualizar despues de crear los qr
		for ($i=0;$i<$Cantidad;$i++){
			$Llave=GenerarCodigo(20, 1);
			$query = "INSERT INTO qr(Llave, Qr, Saldo) VALUES ('".$Llave."','".$CodigoIdent."',0.00)";
			// echo $query.'<br>';
			$DbInsert=$dbConn->DB_Insertar($query);
			
		}
		$query="SELECT * FROM qr WHERE Qr='".$CodigoIdent."'";
		$Consulta=$dbConn->DB_Consultar($query);
		$Numfilas=$dbConn->DB_Num_Rows($Consulta);
		if ($Numfilas>0){
			$i=0;
			$Estatus=1;
			$Mensaje="Creacion de Qr correcta, se crearon ".$Cantidad." codigo(s) Qr";
			foreach($Consulta as $Fila){
				$Valor="Chocopas".$Fila['Id_Qr'].";".$Fila['Llave'];
				$Valor=Codifica64($Valor);
				$query="UPDATE qr SET Qr='".$Valor."' WHERE Id_Qr=".$Fila['Id_Qr'];
				$Actualizar=$dbConn->DB_Actualizar($query);  // Actualizo el campo de qr
				$Qr["Qr"]=$Valor;
				$DatosQr[$i]=$Qr;
				$i++;
			}
		}else { //No hubieron registros en la consulta, probablemente se debio a que no se insertaron
			$Estatus=3;
			$Mensaje="No se obtuvieron registros para actualizar";
			$Qr["Qr"]='Sin Registros';
			$DatosQr[0]=$Qr;
		}
		
	} else { // Si la cantidad de registros a crear es cero o menor
			$Estatus=2;
			$Mensaje="La cantidad de registros a crear debe ser mayor a cero";
			$Qr["Qr"]='Sin Registros';
			$DatosQr[0]=$Qr;		
	}
	
	$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Qr"=>$DatosQr);
	
	return json_encode($Datos);
	
}

function Codifica64($string)
{
	/*
	    Funcion que codifica un string con una palabra de control con base64
	*/
	$string = utf8_encode($string);
	$control = "choco%pago#pasaje@"; //defino la llave para encriptar la cadena
	$string = $control.$string.$control; //concateno la llave para encriptar la cadena
	$string = base64_encode($string);//codifico la cadena
	return($string);
}

function Decodificar64($string)
{
	/*
	    Funcion que decodifica un string con una palabra de control con base64
	*/
	$string = base64_decode($string); //decodifico la cadena
	$control = "choco%pago#pasaje@"; //defino la llave con la que fue encriptada la cadena,, cambiarla por la que deseamos usar
	$string = str_replace($control, "", $string); //quito la llave de la cadena
	return $string;
}

function  CobrarconQr($Id_Usuario, $Qr, $Id_Linea, $Id_Ruta, $Id_Tramo, $Cantidad, $Carga, $Monto){
	/* Funcion que se encarga de cobrar los montos pasados en la lectura qr
	Devuelve un json con la respuesta de la operacion 
	$json = '{"Estatus": 1, "Mensaje": "Exitosa", "Monto": '.9999.99.' }';
		Estatus: codigo de estatus de la transaccion
		Mensaje: Mensaje explicativo del codigo de la respuesta
		Monto: Monto de la transaccion, la cual corresponde a lo que pago el usuario en caso de ser exitosa o el saldo del usuario en caso de que este no tenga saldo suficiente para pagar
		Codigos de respuesta:
			1, Transaccion Exitosa
			2, 
	*/
	
	include 'db.php';
	$dbConn= new AccesoDB;
	// Debo buscar ei id del qr, para esto busco decodifico el qu y si existe la palabra de control, entonces 
	// es un qu de chocopas, por lo que busco por el id, si no busco por el campo qr
	$QrDecode=Decodificar64($Qr); // optengo el decodificado
	Archivo ("Id_Usuario: ".$Id_Usuario.", QR: ".$QrDecode.", Linea: ".$Id_Linea.", Ruta: ".$Id_Ruta.", Tramo: ".$Id_Tramo.", Cantidad: ".$Cantidad.", Carga: ".$Carga.", Monto: ".$Monto,"CobrarconQr10.txt");
	Archivo ("El decodificado es: ".$QrDecode,"CobrarconQr.txt");
	// Busco la palabra de control
	$Id_Qr_In='';
	$Id_Qr_Out='';
	$PalabraaBuscar="Chocopas";
	$PalabraaComparar=substr($QrDecode,0,8);
	Archivo ("Palabra a buscar: ".$PalabraaBuscar.", La Otra Palabra: ".$PalabraaComparar,"CobrarconQr2.txt");
	if ($PalabraaBuscar==$PalabraaComparar){ //Si la palabra a buscar es igual a la palabra al comienzo
		// Quito la palabra y me quedo con el numero que esta hasta el ;, que ese es el id del qr que esta pagando
		$Cadena=str_replace($PalabraaBuscar,"",$QrDecode);
		Archivo ("La Palabra quera: ".$Cadena,"CobrarconQr3.txt");
		$StringDatos = explode(";", $Cadena);  // En la posicion 0 tengo el Id_Qr_In, En la posicion 1 tengo la Llave
		Archivo ("El Id: ".$StringDatos[0].", La Llave: ".$StringDatos[1],"CobrarconQr5.txt");
		// Compruebo el Id del QR
		$Query="SELECT Id_Qr FROM qr WHERE Id_Qr=".$StringDatos[0]." AND Llave='".$StringDatos[1]."'";
		$Con_Qr=$dbConn->DB_Consultar($Query);
		$Numfilas=$dbConn->DB_Num_Rows($Con_Qr);
		if ($Numfilas>0){ //Existe el egisto
			$Qr_Id=$Con_Qr->fetch_assoc();
			$Id_Qr_In=$Qr_Id['Id_Qr'];
		}else { //No existen egistros, por lo que el Qr no coincide o no existe o esta alteado

			$Estatus=4;
			$Mensaje="Este Qr no existe en base de datos, por lo que no es posible cobrar";
			$Monto=$Monto;
			$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
			return json_encode($Datos);
			
		}
	Archivo ("El Query: ".$Query.", El Id: ".$Id_Qr_In,"CobrarconQr6.txt");
		
	} else { //Si las palabras no coinciden es porque es un qr que no fue generado con nosotros
		Archivo ("las palabras no coinciden","CobrarconQr4.txt");
		// Si las palabras no coinciden es porque es un qr que no fue generado por la aplicacion,
		//por lo tanto busco su id directamente en la tabla.
		$Query="SELECT Id_Qr FROM qr WHERE Qr='".$Qr."'";
		$Con_Qr=$dbConn->DB_Consultar($Query);
		$Numfilas=$dbConn->DB_Num_Rows($Con_Qr);
		if ($Numfilas>0){ //Existe el egisto
			$Qr_Id=$Con_Qr->fetch_assoc();
			$Id_Qr_In=$Qr_Id['Id_Qr'];
		}else { //No existen egistros, por lo que el Qr ni coincide o no existe o esta alteado

			$Estatus=4;
			$Mensaje="Este Qr no existe en base de datos, por lo que no es posible cobrar";
			$Monto=$Monto;
			$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
			return json_encode($Datos);
			
		}
		
		
	}   // Hasta aqui ya tengo el id del qr que esta pagando.
	Archivo ("El Id_Qr_In es : ".$Id_Qr_In,"CobrarconQr7.txt");
	// Debo buscar el qr de quien esta cobrando
	$Query="SELECT * FROM qr INNER JOIN usuarios us ON qr.Id_Usuario=us.Id_Usuario WHERE us.Id_Usuario=".$Id_Usuario." and Principal=1";
	$Con_Qr_Cob=$dbConn->DB_Consultar($Query);
	$Numfilas=$dbConn->DB_Num_Rows($Con_Qr_Cob);
	if ($Numfilas>0){ //Existe el egisto
		$Qr_Id_Cob=$Con_Qr_Cob->fetch_assoc();
		$Id_Qr_Out=$Qr_Id_Cob['Id_Qr'];
	}else { //No existen egistros, por lo que el Qr ni coincide o no existe o esta alteado

		$Estatus=4;
		$Mensaje="Aliado no existe en base de datos, por favor verifique";
		$Monto=$Monto;
		$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
		return json_encode($Datos);
		
	}
	Archivo ("El Id_Qr_Out es : ".$Id_Qr_Out,"CobrarconQr8.txt");
	// Calculo el monto a cobrar
	// Si la ruta, el tramo y la cantidad, contienen valor, es porque el cobro proviene de ina linea
	if ($Id_Linea!=0 && $Id_Ruta!=0 && $Id_Tramo!=0 && $Cantidad!=0 && $Monto==0){ // Si estos tres valores contienen datos y el monto es cero, hay que calcular el monto en funcion del tramo y los demas valores y no tomo en cuenta el valor que pueda traer el monto
		// Calculo el monto en funcion de la ruta, que es el monto base
		
		// Antes debo calcular el tipo de tarifa, ya que pueden existir varias tarifas para un mismo tramo
		$Id_Tipo_Tarifa=1;
		
		// Verifico si es horario nocturno
		// Se condidera Horario Nocturno de 6:00 pm a 6:00 am
		date_default_timezone_set("America/La_Paz");
		$Hora_Actual=date("H");
		if ($Hora_Actual<6 || $Hora_Actual>=18){
			$Id_Tipo_Tarifa=2; //Horario Nocturno
		}
		
		// Derermino si es feriado
		// Para efecto del calendario, al principio se toma solo domingos, hay que hacer la programacion para que sea por 
		// base de datos donde se agreguen los dias para el calendario
		date_default_timezone_set("America/La_Paz");
		$Fecha_Actual=date("Y-m-d");
		$query= "SELECT Id_Tipo_Tarifa FROM calendario WHERE Id_Linea=".$Id_Linea." and Fecha_Feriada='".$Fecha_Actual."'";
		$Dia_Fer=$dbConn->DB_Consultar($query);
		$Numfilas=$dbConn->DB_Num_Rows($Dia_Fer);
		if ($Numfilas>0){
			$Fer=$Dia_Fer->fetch_assoc();
			$Id_Tipo_Tarifa=$Fer['Id_Tipo_Tarifa'];
		}
		
		
		
		// me traigo el monto de acuerdo a la ruta y el tipo de tarifa
		
		$query= "SELECT Monto FROM tarifas WHERE Id_Tramo=".$Id_Tramo." AND Id_Tipo_Tarifa=".$Id_Tipo_Tarifa;
		
		$Consulta=$dbConn->DB_Consultar($query);
		$Numfilas=$dbConn->DB_Num_Rows($Consulta);
		//$Monto=0;
		if ($Numfilas>0){
			//Si hay registros es porque es un usuario del punto y tiene rutas asociadas
			//envio el listado de rutas
			$fila=$Consulta->fetch_assoc();
			$Monto=$fila['Monto'];
		}
		
		// Me traigo el factor multiplicador de la carga adicionales
		
		$query= "SELECT * FROM carga WHERE Id_Carga=".$Carga;
		
		$Consulta=$dbConn->DB_Consultar($query);
		$Numfilas=$dbConn->DB_Num_Rows($Consulta);
		$Factor=0;
		if ($Numfilas>0){
			//Si hay registros es porque es un usuario del punto y tiene rutas asociadas
			//envio el listado de rutas
			$fila=$Consulta->fetch_assoc();
			$Factor=$fila['Factor'];
		}
		
		// Hasta aqui tengo el monto base, ahora calculo el monto segun la cantidad de personas
		$Monto_Base=$Monto;
		$Monto=($Monto_Base*$Cantidad);
		
		// Le agrego las cargas adicionales
		$Monto=$Monto+($Monto_Base*$Factor);
		
		$Mensaje="El Id_Qr_Out es : ".$Id_Qr_In."El Id_Qr_Out es : ".$Id_Qr_Out.", Tipo de Tarifa: ".$Id_Tipo_Tarifa.", Monto a transferir: ".$Monto;

	} else{ //  Si la ruta o el tramo o la cantidad no contienen datos y el monto si tiene, es porque es un cobro libre, es decir que el valor de cobro viene dado por el monto pasado
			
		$Mensaje="El Id_Qr_Out es : ".$Id_Qr_In."El Id_Qr_Out es : ".$Id_Qr_Out." Monto a transferir: ".$Monto;
	}
	Archivo ($Mensaje,"CobrarconQr9.txt");
	//Verifico que el qr que paga tenga Saldo suficiente
	$Query="SELECT * FROM qr WHERE Id_Qr=".$Id_Qr_In." and Saldo>=".$Monto;
	$Con_Saldo=$dbConn->DB_Consultar($Query);
	$Numfilas=$dbConn->DB_Num_Rows($Con_Saldo);
	if ($Numfilas>0){ //Existe el egisto y tiene Saldo suficiente
		
		//// Calculo las comisiones
		$Comision_Choco=COMISION_CHOCO;
		$Comision_Banco=COMISION_BANCO;
		$Comision_Aliado=COMISION_ALIADO;
		// Me Traigo el Qr de la empresa Chocopas
		$Id_Qr_Choco=1;
		
		// Procedo a inseta los egistros correspondientes en las tablas de operaciones y transacciones
		// Inserto Operaciones
		$Tipo_Operacion=2;     // Tipo de Operacion Pago
		$Query="INSERT INTO operaciones(Id_Tipo_Operacion, Fecha_Opeacion, Opeacion_Pocesada) VALUES (".$Tipo_Operacion.", NOW(), 0)";
		$Id_Operacion=$dbConn->DB_Insertar($Query);
		
		$Monto_Pago=$Monto - ($Monto*$Comision_Aliado/100) - ($Monto*$Comision_Banco/100) - ($Monto*$Comision_Choco/100);
		// Inseto la transaccion que demuestra el pago, con el estatus no transferida, el cual va a cambiar luego de que se actualicen los saldos tanto en el qr que esta pagando, como en el qr que esta cobrando
		// En $Id_Operacion tengo el id de la operacion que acabo de insertar
		$Tipo_Transaccion=1;     // Transaccion tipo Pago
		$Estado_Transaccion=1;   // Estado de la transaccion No transferida (1 No Transferido, 2 Transferido)
		$Query="INSERT INTO transacciones(Id_Operacion, Id_Tipo_Transaccion, Id_Estado_Transaccion, Id_Qr_In, Id_Qr_Out, F_Transaccion, Monto_Transaccion) VALUES (".$Id_Operacion.", ".$Tipo_Transaccion.", ".$Estado_Transaccion.", ".$Id_Qr_In.", ".$Id_Qr_Out.", NOW(), ".$Monto_Pago.")";
		$Id_Transaccion1=$dbConn->DB_Insertar($Query);
		
		// Actualizo los saldos
		// Actualizo el saldo del pagador,
		// Resto el monto del Saldo
		$Saldos_Correctos=true;
		
		$query="UPDATE qr SET Saldo=Saldo-".$Monto." WHERE Id_Qr=".$Id_Qr_In;
		//echo $query; exit();
		$res=0;
		$res=$dbConn->DB_Actualizar($query);
		if ($res){ //Si hizo bien la actualizacion
			// Actualiza la billetera del cobrador
			$query="UPDATE qr SET Saldo=Saldo+".$Monto_Pago." WHERE Id_Qr=".$Id_Qr_Out;
			//echo $query; exit();
			$res=0;
			$res=$dbConn->DB_Actualizar($query);
			if ($res){ //Si hizo bien la actualizacion
				// Actualizo el campo de la transaccion a Transferido
				$Estado_Transaccion=2;   // Estado de la transaccion transferida (1 No Transferido, 2 Transferido)
				$query="UPDATE transacciones SET Id_Estado_Transaccion=".$Estado_Transaccion." WHERE Id_Transaccion=".$Id_Transaccion1;
				//echo $query; exit();
				$res=0;
				$res=$dbConn->DB_Actualizar($query);
			}else { // Si hubo un error en la actualizacion
				$Saldos_Correctos=false;
			}
		}else { // Si hubo un error en la actualizacion
			$Saldos_Correctos=false;
		}
		
		// Inserto en transacciones el registro correspondiente a la comision de la recarga,
		// Este se descuenta aqui, pero no se coloca al aliado porque ya fue colocado en una 
		// transaccion previa cuando el usuario in recargo, se coloca para efecto de compensar 
		// la transaccion
		$Tipo_Transaccion=4;     // Transaccion tipo Comision Recarga
		$Estado_Transaccion=2;   // Estado de la transaccion transferida (1 No Transferido, 2 Transferido)
		$Monto_Comision=($Monto * $Comision_Aliado / 100);
		$Query="INSERT INTO transacciones(Id_Operacion, Id_Tipo_Transaccion, Id_Estado_Transaccion, Id_Qr_In, Id_Qr_Out, F_Transaccion, Monto_Transaccion) VALUES (".$Id_Operacion.", ".$Tipo_Transaccion.", ".$Estado_Transaccion.", ".$Id_Qr_Out.", ".$Id_Qr_Choco.", NOW(), ".$Monto_Comision.")";
		$Id_Transaccion1=$dbConn->DB_Insertar($Query);
		
		// Actualizo el qr de B&M con el valor de la recarga
		$query="UPDATE qr SET Saldo=Saldo+".$Monto_Comision." WHERE Id_Qr=".$Id_Qr_Choco;
		//echo $query; exit();
		$res=0;
		$res=$dbConn->DB_Actualizar($query);
		if (!$res){    // Si no se hizo la actualizacion
			
		}
		
		
		
		// Inserto en transacciones el registro correspondiente a la comision de bancaria que nos cobraron previamente,
		// Este se descuenta aqui, pero no se actualizan saldos porque ya fue cobrado previamente 
		// cuando el usuario in recargo, se coloca para efecto de compensar la transaccion
		$Tipo_Transaccion=5;     // Transaccion tipo Comision Aliado
		$Estado_Transaccion=2;   // Estado de la transaccion transferida (1 No Transferido, 2 Transferido)
		$Monto_Comision=($Monto * $Comision_Banco / 100);
		$Query="INSERT INTO transacciones(Id_Operacion, Id_Tipo_Transaccion, Id_Estado_Transaccion, Id_Qr_In, Id_Qr_Out, F_Transaccion, Monto_Transaccion) VALUES (".$Id_Operacion.", ".$Tipo_Transaccion.", ".$Estado_Transaccion.", ".$Id_Qr_Out.", ".$Id_Qr_Choco.", NOW(), ".$Monto_Comision.")";
		$Id_Transaccion1=$dbConn->DB_Insertar($Query);
		
		// Inserto en transacciones el registro correspondiente a la comision de la empresa,
		// Este se descuenta aqui, y luego se debe actualizar el saldo en el de la empresa 
		// ya que este saldo no ha sido transferido y debera hacerse en el proximo corte
		$Tipo_Transaccion=6;     // Transaccion tipo Comision Aliado
		$Estado_Transaccion=1;   // Estado de la transaccion No transferida (1 No Transferido, 2 Transferido)
		$Monto_Comision=($Monto * $Comision_Choco / 100);
		$Query="INSERT INTO transacciones(Id_Operacion, Id_Tipo_Transaccion, Id_Estado_Transaccion, Id_Qr_In, Id_Qr_Out, F_Transaccion, Monto_Transaccion) VALUES (".$Id_Operacion.", ".$Tipo_Transaccion.", ".$Estado_Transaccion.", ".$Id_Qr_Out.", ".$Id_Qr_Choco.", NOW(), ".$Monto_Comision.")";
		$Id_Transaccion2=$dbConn->DB_Insertar($Query);
		
		//Actualizo el saldo del Qr de Choco
		$query="UPDATE qr SET Saldo=Saldo+".$Monto_Comision." WHERE Id_Qr=".$Id_Qr_Choco;
		//echo $query; exit();
		$res=0;
		$res=$dbConn->DB_Actualizar($query);
		if ($res){ // Si se actualiza bien, actualizo el estatus de la transaccion
			// $Estado_Transaccion=2;   // Estado de la transaccion transferida (1 No Transferido, 2 Transferido)
			// $query="UPDATE transacciones SET Id_Estado_Transaccion=".$Estado_Transaccion." WHERE Id_Transaccion=".$Id_Transaccion2;
			//echo $query; exit();
			// $res=0;
			// $res=$dbConn->DB_Actualizar($query);
		}
		$Estatus=1;
		$Mensaje="Transaccion Exitosa";
		$Monto=$Monto;
		$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
		// $json = '{"Estatus": 1, "Mensaje": "Transaccion Exitosa", "Monto": '.$Monto.' }';
		Archivo ("Transaccion Exitosa","CobrarconQr11.txt");
		return json_encode($Datos);
	}else { //No existen egistros, por lo que el Qr no coincide o no existe o esta alteado
		
		/********************************************************
		*********************************************************
		** Qr no tiene saldo suficiente para efectuar el pago *** 
		*********************************************************
		********************************************************/
		$Estatus=2;
		$Mensaje="Saldo insuficiente";
		$Monto=$Monto;
		$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
		// $json = '{"Estatus": 2, "Mensaje": "Saldo insuficiente", "Monto": '.$Monto.' }';
		Archivo ("Usuario no tiene Saldo","CobrarconQr12.txt");
		return json_encode($Datos);
	}
	
} //Fin de la funcion CobrarconQr

function VerificarPago($Pagos, $CantidadPagos, $Id_Usuario){
	//Funcion que verifica un pago de un listado enviado
	include 'db.php';
	$dbConn= new AccesoDB;
	
	$Id_Qr_Empresa=IDQREMP;
	$Monto_Acumulado=0;
	$Id_Operacion="";
	$QuerysMensaje="";
	Archivo ("Llega a VerificarPago, Qr Empresa: ".$Id_Qr_Empresa.", Cantidad Pagos: ".$CantidadPagos.", Id Usuario: ".$Id_Usuario,"VerificarPago1.txt");
	
	//Busco el Usuario para obtener el id del qr que se va a arecargar
	$Query="SELECT * FROM qr INNER JOIN usuarios us ON qr.Id_Usuario=us.Id_Usuario WHERE us.Id_Usuario=".$Id_Usuario." and qr.Principal=1 AND qr.Bloqueado=0";
	$Con_Qr_Rec=$dbConn->DB_Consultar($Query);
	$Numfilas=$dbConn->DB_Num_Rows($Con_Qr_Rec);
	if ($Numfilas>0){ // Si el registro existe
		$Qr_Id_Rec=$Con_Qr_Rec->fetch_assoc();
		$Id_Qr_Usuario=$Qr_Id_Rec['Id_Qr'];
		Archivo ("Resultado de usuario y Qr, Qr: ".$Id_Qr_Usuario.", Cantidad de pagos: ".$CantidadPagos,"VerificarPago4.txt");
		
		// Verifico si hay datos en la matriz del banco
		if ($CantidadPagos>0){ // Si hay pagos en el banco
			// Debo recorrer la matriz del banco para verificar cada pago
			$i=0;
			for ($i=0; $i<$CantidadPagos; $i++){
				
				$Query="SELECT * FROM pagomovilplaza WHERE referencia='".$Pagos[$i]->referencia."'";
				Archivo ("Query: ".$Query,"VerificarPago2".$i.".txt");
				$Con_Pg=$dbConn->DB_Consultar($Query);
				$Numfilas=$dbConn->DB_Num_Rows($Con_Pg);
				if ($Numfilas<=0){ //No existe el egisto
					// Hago los insert en Operaciones si no existe y en transacciones del registro de pgo y de la transaccion
					
					if($Id_Operacion==""){  // Inserto una Opercion si no hay ninguna
						
						// Creo la Operacion
						$Tipo_Operacion=4;     // Tipo de Operacion Recarga
						$Query="INSERT INTO operaciones(Id_Tipo_Operacion, Fecha_Opeacion, Opeacion_Pocesada) VALUES (".$Tipo_Operacion.", NOW(), 1)";
						$Id_Operacion=$dbConn->DB_Insertar($Query);
						$QuerysMensaje.="Insert operaciones: ".$Query;
					}
					
					// Inserto la transaccion y sumo el monto para regresarlo posteriormente
					// Creo la transaccion
					$Tipo_Transaccion=8;     // Transaccion tipo Auto Recarga Billetera
					$Estado_Transaccion=2;   // Estado de la transaccion No transferida (1 No Transferido, 2 Transferido)
					$Query="INSERT INTO transacciones(Id_Operacion, Id_Tipo_Transaccion, Id_Estado_Transaccion, Id_Qr_In, Id_Qr_Out, F_Transaccion, Monto_Transaccion) VALUES (".$Id_Operacion.", ".$Tipo_Transaccion.", ".$Estado_Transaccion.", ".$Id_Qr_Empresa.", ".$Id_Qr_Usuario.", NOW(), ".$Pagos[$i]->monto.")";
					$Id_Transaccion=$dbConn->DB_Insertar($Query);
					$QuerysMensaje.=", Insert transacciones: ".$Query;
					
					// Agrego el registro en la tabla de pagomovil, agrego un registro en la tabla de transacciones y actualizo el saldo del aliado
					// Inserto en Pagomovil
					$Query="INSERT INTO pagomovilplaza(Id_Transaccion, accion, banco, concepto, fecha, hora, monto, origen, referencia, telefonoCliente, telefonoAfiliado) VALUES (".$Id_Transaccion.", '".$Pagos[$i]->accion."','".$Pagos[$i]->banco."','".$Pagos[$i]->concepto."','".$Pagos[$i]->fecha."','".$Pagos[$i]->hora."',".$Pagos[$i]->monto.",'".$Pagos[$i]->origen."','".$Pagos[$i]->referencia."','".$Pagos[$i]->telefonoAfiliado."','".$Pagos[$i]->telefonoCliente."')";
					$Id_pagomovil=$dbConn->DB_Insertar($Query);
					$QuerysMensaje=", Insert pagomovilplaza: ".$Query;
						
					$Monto_Acumulado=$Monto_Acumulado + $Pagos[$i]->monto;
					Archivo($Query,"VerificarPago5".$i.".txt");
				} // Fin del Si no existe el pagomovil registrado
			}  // Fin del for
			// Una vez que recorro todos los pagos y creo todas las transacciones que no existen, actualizo el saldo del aliado
			if ($Monto_Acumulado<>0.00){
				// Actualizo el saldo del qr que se  autorecarga
				$Query="UPDATE qr SET Saldo=Saldo + ".$Monto_Acumulado." WHERE Id_Qr=".$Id_Qr_Usuario;
				Archivo($Query,"VerificarPago5.txt");
				//echo $query; exit();
				$res=0;
				$res=$dbConn->DB_Actualizar($Query);
				$QuerysMensaje.=", updte qr: ".$Query;
				
				$Monto=Acomodar_Monto($Monto_Acumulado, '.', ',', '.');
				
				$Estatus=1;
				$Mensaje="Su qr se atualizo de manera exitosa, Monto de la Actualizaci&oacute;n: ".$Monto;
				// $Monto=$Monto_Acumulado;
				$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
				
			}else{  // Si no hay monto acumulado es porque las transacciones todas habian sido registradas anteriormente y no existe pago sin registrar
				
				$Estatus=2;
				$Mensaje="No existen datos de pagos por registrar en el banco para este tel&eacute;fono";
				$Monto=0.00;
				$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
				
			}
				
		}else { // Si no hay pagos en el banco
		
			$Estatus=3;
			$Mensaje="No existen datos de pagos en el banco para este tel&eacute;fono";
			$Monto=0.00;
			$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
			
		}
		
	}else { // Si no hay usuario o qr
		
		
		$Estatus=4;
		$Mensaje="Usuario no tiene un Qr principal asociado o esta bloqueado";
		$Monto=0.00;
		$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
		Archivo ("Error: ".$Estatus.", ".$Mensaje,"VerificarPago7.txt");
		
	}
	
	
	return json_encode($Datos);
}


function RecagarQrQr($Id_Usuario, $Qr, $Monto){
	/* Funcion que se encarga de recargar los montos pasados en la lectura qr a los suarios finales
	Devuelve un json con la respuesta de la operacion 
	$json = '{"Estatus": 1, "Mensaje": "Exitosa", "Monto": '.9999.99.' }';
		Estatus: codigo de estatus de la transaccion
		Mensaje: Mensaje explicativo del codigo de la respuesta
		Monto: Monto de la transaccion, la cual corresponde a lo que pago el usuario en caso de ser exitosa o el saldo del usuario en caso de que este no tenga saldo suficiente para pagar
		Codigos de respuesta:
			1, Transaccion Exitosa
			2, 
	*/
	Archivo ("LLega hasta ecargar un Qr con un Qr","RecargarQrQr.txt");
	
	include 'db.php';
	$dbConn= new AccesoDB;
	// Debo buscar el id del qr, para esto busco decodifico el qr y si existe la palabra de control, entonces 
	// es un qr de chocopas, por lo que busco por el id, si no busco por el campo qr
	$QrDecode=Decodificar64($Qr); // optengo el decodificado
	Archivo ("Id_Usuario: ".$Id_Usuario.", QR: ".$QrDecode.", Monto: ".$Monto,"RecargarQrQr1.txt");
	// Busco la palabra de control
	$Id_Qr_In='';
	$Id_Qr_Out='';
	$PalabraaBuscar="Chocopas";
	$PalabraaComparar=substr($QrDecode,0,8);
	Archivo ("Palabra a buscar: ".$PalabraaBuscar.", La Otra Palabra: ".$PalabraaComparar,"RecargarQrQr2.txt");
	if ($PalabraaBuscar==$PalabraaComparar){ //Si la palabra a buscar es igual a la palabra al comienzo
		// Quito la palabra y me quedo con el numero que esta hasta el ;, que ese es el id del qr que esta pagando
		$Cadena=str_replace($PalabraaBuscar,"",$QrDecode);
		Archivo ("La Palabra queda: ".$Cadena,"RecargarQrQr3.txt");
		$StringDatos = explode(";", $Cadena);  // En la posicion 0 tengo el Id_Qr_Out, En la posicion 1 tengo la Llave
		Archivo ("El Id del qr a recargar: ".$StringDatos[0].", La Llave: ".$StringDatos[1],"RecargarQrQr4.txt");
		// Compruebo el Id del QR
		$Query="SELECT Id_Qr FROM qr WHERE Id_Qr=".$StringDatos[0]." AND Llave='".$StringDatos[1]."'";
		$Con_Qr=$dbConn->DB_Consultar($Query);
		$Numfilas=$dbConn->DB_Num_Rows($Con_Qr);
		if ($Numfilas>0){ //Existe el egisto
			$Qr_Id=$Con_Qr->fetch_assoc();
			$Id_Qr_Out=$Qr_Id['Id_Qr'];
		}else { //No existen egistros, por lo que el Qr no coincide o no existe o esta alteado

			$Estatus=3;
			$Mensaje="Usuario no existe en base de datos, por favor verifique";
			$Monto=$Monto;
			$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
			return json_encode($Datos);
			
		}
	Archivo ("El Query: ".$Query.", El Id del qr a recargar: ".$Id_Qr_Out,"RecargarQrQr5.txt");
		
	} else { //Si las palabras no coinciden es porque es un qr que no fue generado con nosotros
		Archivo ("las palabras no coinciden","RecargarQrQr6.txt");
		// Si las palabras no coinciden es porque es un qr que no fue generado por la aplicacion,
		//por lo tanto busco su id directamente en la tabla.
		$Query="SELECT Id_Qr FROM qr WHERE Qr='".$Qr."'";
		$Con_Qr=$dbConn->DB_Consultar($Query);
		$Numfilas=$dbConn->DB_Num_Rows($Con_Qr);
		if ($Numfilas>0){ //Existe el egisto
			$Qr_Id=$Con_Qr->fetch_assoc();
			$Id_Qr_Out=$Qr_Id['Id_Qr'];
		}else { //No existen egistros, por lo que el Qr ni coincide o no existe o esta alteado

			$Estatus=3;
			$Mensaje="Usuario no existe en base de datos, por favor verifique";
			$Monto=$Monto;
			$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
			return json_encode($Datos);
			
		}
		
		
	}   // Hasta aqui ya tengo el id del qr que se va a ecargar.
	// Debo buscar el qr que esta recargando $Id_Qr_In
	$Query="SELECT * FROM qr INNER JOIN usuarios us ON qr.Id_Usuario=us.Id_Usuario WHERE us.Id_Usuario=".$Id_Usuario." and Principal=1";
	Archivo ("El Id_Qr_Out es el id que se recarga: ".$Id_Qr_Out.", El query: ".$Query,"RecargarQrQr7.txt");
	$Con_Qr_Cob=$dbConn->DB_Consultar($Query);
	$Numfilas=$dbConn->DB_Num_Rows($Con_Qr_Cob);
	if ($Numfilas>0){ //Existe el egisto
		$Qr_Id_Cob=$Con_Qr_Cob->fetch_assoc();
		$Id_Qr_In=$Qr_Id_Cob['Id_Qr'];
	}else { //No existen egistros, por lo que el Qr ni coincide o no existe o esta alteado

		$Estatus=4;
		$Mensaje="Aliado no existe en base de datos, por favor verifique";
		$Monto=$Monto;
		$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
		return json_encode($Datos);
		
	}
	
	// Calculo el monto a ecaga es el qe se paso en el argmento
	
	
	//Verifico que el qr del recagador tenga el saldo suficiente para recargag
	$Query="SELECT * FROM qr WHERE Id_Qr=".$Id_Qr_In." and Saldo>=".$Monto;
	Archivo ("El Id_Qr_In es el id del recargador: ".$Id_Qr_In.", Query del recargador: ".$Query,"RecargarQrQr8.txt");
	$Con_Saldo=$dbConn->DB_Consultar($Query);
	$Numfilas=$dbConn->DB_Num_Rows($Con_Saldo);
	if ($Numfilas>0){ //Existe el egisto y tiene Saldo suficiente
		
		//// Calculo las comisiones
		$Comision_Choco=COMISION_CHOCO;
		$Comision_Banco=COMISION_BANCO;
		$Comision_Aliado=COMISION_ALIADO;
		// Me Traigo el Qr de la empresa Chocopas
		$Id_Qr_Choco=1;
		Archivo ("Comision_Choco: ".$Comision_Choco.', Comision_Banco: '.$Comision_Banco.', Comision_Aliado: '.$Comision_Aliado.', Monto: '.$Monto,"RecargarQrQr80.txt");
		// Procedo a inseta los egistros correspondientes en las tablas de operaciones y transacciones
		// Inserto Operaciones
		$Tipo_Operacion=1;     // Tipo de Operacion Recarga
		$Query="INSERT INTO operaciones(Id_Tipo_Operacion, Fecha_Opeacion, Opeacion_Pocesada) VALUES (".$Tipo_Operacion.", NOW(), 0)";
		Archivo ("Query: ".$Query,"RecargarQrQr81.txt");
		$Id_Operacion=$dbConn->DB_Insertar($Query);
		
		$Monto_Pago=$Monto;
		// Inseto la transaccion que demuestra el pago, con el estatus no transferida, el cual va a cambiar luego de que se actualicen los saldos tanto en el qr que esta pagando, como en el qr que esta cobrando
		// En $Id_Operacion tengo el id de la operacion que acabo de insertar
		$Tipo_Transaccion=2;     // Transaccion tipo Recarga Billetera
		$Estado_Transaccion=1;   // Estado de la transaccion No transferida (1 No Transferido, 2 Transferido)
		$Query="INSERT INTO transacciones(Id_Operacion, Id_Tipo_Transaccion, Id_Estado_Transaccion, Id_Qr_In, Id_Qr_Out, F_Transaccion, Monto_Transaccion) VALUES (".$Id_Operacion.", ".$Tipo_Transaccion.", ".$Estado_Transaccion.", ".$Id_Qr_In.", ".$Id_Qr_Out.", NOW(), ".$Monto_Pago.")";
		Archivo ("Query: ".$Query,"RecargarQrQr82.txt");
		$Id_Transaccion1=$dbConn->DB_Insertar($Query);
		
		// Actualizo los saldos
		// Actualizo el saldo del Aliado,
		// Resto el monto del Saldo
		$Saldos_Correctos=true;
		// $Id_Qr_In es el aliado
		$Query="UPDATE qr SET Saldo=Saldo-".$Monto." WHERE Id_Qr=".$Id_Qr_In;
		Archivo ("Query: ".$Query,"RecargarQrQr83.txt");
		//echo $query; exit();
		$res=0;
		$res=$dbConn->DB_Actualizar($Query);
		if ($res){ //Si hizo bien la actualizacion
			// Actualiza la billetera del usuario que esta recargando
			$Query="UPDATE qr SET Saldo=Saldo+".$Monto." WHERE Id_Qr=".$Id_Qr_Out;
			Archivo ("Query: ".$Query,"RecargarQrQr84.txt");
			//echo $query; exit();
			$res=0;
			$res=$dbConn->DB_Actualizar($Query);
			if ($res){ //Si hizo bien la actualizacion
				// Actualizo el campo de la transaccion a Transferido
				$Estado_Transaccion=2;   // Estado de la transaccion transferida (1 No Transferido, 2 Transferido)
				$Query="UPDATE transacciones SET Id_Estado_Transaccion=".$Estado_Transaccion." WHERE Id_Transaccion=".$Id_Transaccion1;
				Archivo ("Query: ".$Query,"RecargarQrQr85.txt");
				//echo $query; exit();
				$res=0;
				$res=$dbConn->DB_Actualizar($Query);
			}else { // Si hubo un error en la actualizacion
				$Saldos_Correctos=false;
			}
		}else { // Si hubo un error en la actualizacion
			$Saldos_Correctos=false;
		}
		
		// Inserto en transacciones el registro correspondiente a la comision del Aliado,
		// Este se descuenta aqui, pero no se coloca al aliado porque ya fue colocado en una 
		// transaccion previa cuando el usuario in recargo, se coloca para efecto de compensar 
		// la transaccion
		$Tipo_Transaccion=7;     // Transaccion tipo Comision Aliado
		$Estado_Transaccion=2;   // Estado de la transaccion transferida (1 No Transferido, 2 Transferido)
		$Monto_Comision=($Monto * $Comision_Aliado / 100);
		$Query="INSERT INTO transacciones(Id_Operacion, Id_Tipo_Transaccion, Id_Estado_Transaccion, Id_Qr_In, Id_Qr_Out, F_Transaccion, Monto_Transaccion) VALUES (".$Id_Operacion.", ".$Tipo_Transaccion.", ".$Estado_Transaccion.", ".$Id_Qr_Choco.", ".$Id_Qr_In.", NOW(), ".$Monto_Comision.")";
		Archivo ("Query: ".$Query,"RecargarQrQr86.txt");
		$Id_Transaccion1=$dbConn->DB_Insertar($Query);
		
		
		//Actualizo el saldo del Qr del aliado
		$Query="UPDATE qr SET Saldo=Saldo+".$Monto_Comision." WHERE Id_Qr=".$Id_Qr_In;
		Archivo ("Query: ".$Query,"RecargarQrQr87.txt");
		//echo $query; exit();
		$res=0;
		$res=$dbConn->DB_Actualizar($Query);
		if (!$res){ // Si no se actualiza bien, actualizo el estatus de la transaccion
			
		}
		
		// Actualizo el qr de B&M con el valor de la recarga
		$Query="UPDATE qr SET Saldo=Saldo-".$Monto_Comision." WHERE Id_Qr=".$Id_Qr_Choco;
		Archivo ("Query: ".$Query,"RecargarQrQr88.txt");
		//echo $query; exit();
		$res=0;
		$res=$dbConn->DB_Actualizar($Query);
		if (!$res){ // Si no se actualiza bien, actualizo el estatus de la transaccion
			
		}
		
		$Estatus=1;
		$Mensaje="Transaccion Exitosa";
		$Monto=$Monto;
		$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
		// $json = '{"Estatus": 1, "Mensaje": "Transaccion Exitosa", "Monto": '.$Monto.' }';
		// Archivo ($Datos,"RecargarQrQr9.txt");
		return json_encode($Datos);
	}else { //No existen egistros, por lo que el Qr no coincide o no existe o esta alteado
		
		/********************************************************
		*********************************************************
		** Qr no tiene saldo suficiente para efectuar el pago *** 
		*********************************************************
		********************************************************/
		$Estatus=2;
		$Mensaje="Saldo insuficiente";
		$Monto=$Monto;
		$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Monto"=>$Monto);
		// $json = '{"Estatus": 2, "Mensaje": "Saldo insuficiente", "Monto": '.$Monto.' }';
		Archivo ("Usuario no tiene Saldo","CobrarconQr10.txt");
		return json_encode($Datos);
	}
	
	
	
	
} //Fin de la funcion RecagarQrQrQr
 
function Txt_Pagos_Banco($Fecha_Inicio, $Fecha_Fin){
	// Funcion que sustrae de bd los registros generados durante los procesos de pagos y recargas para generar el txt que va para el banco
	// include 'configura.php';
	include 'db.php';
	$dbConn= new AccesoDB;
	
	$Concepto="Transferencia_";   // Este concepto debe prepararse mejor
	$DirTxt=DIRTXTPLAZA;    // Direccion y nombre del txt del banco
	$NombreTxt="txt".date("Ymd").".txt";    //Nombre del txt del banco
	$DirTxt.=$NombreTxt;
	$Concepto.=date("Ymd");
	// Archivo($DirTxt,"Txt_Pagos_Banco3.txt");
	$Id_Usuario_Choco=USUARIO_CHOCO;
	$Rif=RIF;    // Rif de la empresa, debe sustituirse por una funcion que busque el rif en la bd, de momento esta en configura.php
	// $Fecha_Inicio="2020-12-04 00:00:00";    // Tanto Fecha_Inicio, como Fecha_Fin deben quitarse ya que estan aqui para hacer las pruebas
	// $Fecha_Fin="2020-12-04 23:59:59";
	// $Fecha_Inicio="2021-03-02 00:00:00";    // Tanto Fecha_Inicio, como Fecha_Fin deben quitarse ya que estan aqui para hacer las pruebas
	// $Fecha_Fin="2021-03-02 23:59:59";
	
	// Construyo el query para el pago
	$Id_Tipo_Transaccion=1;    //Transaccion tipo pago 
	// Query de pago para los choferes
	$Query="SELECT us.Id_Usuario, qr.Id_Qr, cb.Cuenta_Bancaria, cb.Beneficiario, CONCAT(tdi.Nemonico_Tipo_Doc_Identidad,cb.Doc_Identidad) Doc_Identidad, SUM(tr.Monto_Transaccion) AS Monto, '".$Concepto."' AS Concepto, cb.Email_Cuenta, cb.Telefono_Cuenta 
			FROM transacciones tr 
			INNER JOIN qr ON tr.Id_Qr_Out=qr.Id_Qr 
			INNER JOIN usuarios us ON qr.Id_Usuario=us.Id_Usuario 
			INNER JOIN tipos_doc_identidad tdi ON us.Id_Tipo_Doc_Identidad=tdi.Id_Tipo_Doc_Identidad 
			INNER JOIN cuentas_bancarias cb ON us.Id_Usuario=cb.Id_Usuario 
			WHERE tr.F_Transaccion>='".$Fecha_Inicio."' AND tr.F_Transaccion<='".$Fecha_Fin."' 
			AND tr.Id_Tipo_Transaccion=".$Id_Tipo_Transaccion." AND tr.Id_Estado_Transaccion!=3 
			GROUP BY us.Id_Usuario";
	Archivo("Query: ".$Query,"Txt_Pagos_Banco.txt");
	//Query de pago para chocopago
	
	$Con_Txt=$dbConn->DB_Consultar($Query);
	$Numfilas=$dbConn->DB_Num_Rows($Con_Txt);
	$Txt="";
	$Control="";
	// Construyo el contenido del txt
	if ($Numfilas>0){    //Existen registos Lleno la variable del txt
		$file = fopen($DirTxt, "w");
		
		foreach ($Con_Txt as $Fila){
			// Recorro todos los registros que tienen un cobro para verificarlo y calcular el monto a transferir
			$Id_Usuario=$Fila['Id_Usuario'];          //Usuario al que se le va a hacer el calculo
			$Id_Qr=$Fila['Id_Qr'];
			$Monto=0;
			$Monto_Transacciones=$Fila['Monto'];        // Monto bruto de las transacciones de pago
			$Poscentaje_Transferir=Porcentaje_Transferir($Id_Usuario);       // Porcentaje para calcular el monto a transferir, Hay que corregir la funcion
			$Monto_Transferir=($Monto_Transacciones * $Poscentaje_Transferir)/100 ;      // Obtengo el monto a transferir
			$Saldo_Qr= json_decode(SaldoMiQr('',$Id_Qr),true);
			$Saldo=$Saldo_Qr['Saldo'];
			if ($Saldo>=$Monto_Transferir){
				// Si el saldo es mayor al monto a transferir, 
				$Monto=$Monto_Transferir;
			} else {
				$Monto=$Saldo;
			}
			$Saldo=$Saldo - $Monto;
			
			$Monto=Acomodar_Monto($Monto, '.', '', '');
			fwrite($file,$Control.$Rif.";".$Fila['Cuenta_Bancaria'].";".$Fila['Beneficiario'].";".$Fila['Doc_Identidad'].";".$Monto.";".$Fila['Concepto'].";".$Fila['Email_Cuenta'].";".$Fila['Telefono_Cuenta'].";");
			$Control=PHP_EOL;
			
			// Actualizo el qr del registro que meti en el txt
			$Query="UPDATE qr SET Saldo=".$Saldo." WHERE Id_Qr=".$Id_Qr;
			Archivo("Query: ".$Query,"Txt_Pagos_Banco2.txt");
			$res=0;
			$res=$dbConn->DB_Actualizar($Query);
					
		}
		
		// Actualizo el estatus de las transacciones que se procesaron
		$Query="UPDATE transacciones tr 
				INNER JOIN qr ON tr.Id_Qr_Out=qr.Id_Qr 
				INNER JOIN usuarios us ON qr.Id_Usuario=us.Id_Usuario 
				INNER JOIN tipos_doc_identidad tdi ON us.Id_Tipo_Doc_Identidad=tdi.Id_Tipo_Doc_Identidad 
				INNER JOIN cuentas_bancarias cb ON us.Id_Usuario=cb.Id_Usuario 
				SET tr.Id_Estado_Transaccion=3
				WHERE tr.F_Transaccion>='".$Fecha_Inicio."' AND tr.F_Transaccion<='".$Fecha_Fin."' 
				AND tr.Id_Tipo_Transaccion=".$Id_Tipo_Transaccion." AND tr.Id_Estado_Transaccion!=3";
		Archivo("Query: ".$Query,"Txt_Pagos_Banco3.txt");
		$res=0;
		$res=$dbConn->DB_Actualizar($Query);
		
		// Guardo la comision de Chocopago
		$Id_Tipo_Transaccion=6;    //Transaccion tipo Comision Chocopas 
		$Query="SELECT us.Id_Usuario, qr.Id_Qr, cb.Cuenta_Bancaria, cb.Beneficiario, CONCAT(tdi.Nemonico_Tipo_Doc_Identidad,cb.Doc_Identidad) Doc_Identidad, SUM(tr.Monto_Transaccion) AS Monto, '".$Concepto."' AS Concepto, cb.Email_Cuenta, cb.Telefono_Cuenta 
				FROM transacciones tr 
				INNER JOIN qr ON tr.Id_Qr_Out=qr.Id_Qr 
				INNER JOIN usuarios us ON qr.Id_Usuario=us.Id_Usuario 
				INNER JOIN tipos_doc_identidad tdi ON us.Id_Tipo_Doc_Identidad=tdi.Id_Tipo_Doc_Identidad 
				INNER JOIN cuentas_bancarias cb ON us.Id_Usuario=cb.Id_Usuario 
				WHERE tr.F_Transaccion>='".$Fecha_Inicio."' AND tr.F_Transaccion<='".$Fecha_Fin."' 
				AND us.Id_Usuario=".$Id_Usuario_Choco." AND tr.Id_Tipo_Transaccion in (".$Id_Tipo_Transaccion.") AND tr.Id_Estado_Transaccion!=3 
				GROUP BY us.Id_Usuario";
		Archivo("Query: ".$Query,"Txt_Pagos_Banco4.txt");	
		$Con_Ch=$dbConn->DB_Consultar($Query);
		$Numfilas=$dbConn->DB_Num_Rows($Con_Ch);
		if ($Numfilas>0) {
			$Fila=$Con_Ch->fetch_assoc();
			
			$Id_Usuario=$Fila['Id_Usuario'];          //Usuario al que se le va a hacer el calculo
			$Id_Qr=$Fila['Id_Qr'];
			$Monto=0;
			$Monto_Transacciones=$Fila['Monto'];        // Monto bruto de las transacciones de pago
			$Poscentaje_Transferir=Porcentaje_Transferir($Id_Usuario);       // Porcentaje para calcular el monto a transferir, Hay que corregir la funcion
			$Monto_Transferir=($Monto_Transacciones * $Poscentaje_Transferir)/100 ;      // Obtengo el monto a transferir
			$Saldo_Qr= json_decode(SaldoMiQr('',$Id_Qr),true);
			$Saldo=$Saldo_Qr['Saldo'];
			/* if ($Saldo>=$Monto_Transferir){
				// Si el saldo es mayor al monto a transferir, 
				$Monto=$Monto_Transferir;
			} else {
				$Monto=$Saldo;
			} */
			$Monto=$Monto_Transferir;
			$Saldo=$Saldo - $Monto;
			
			$Monto=Acomodar_Monto($Monto, '.', '', '');
			fwrite($file,$Control.$Rif.";".$Fila['Cuenta_Bancaria'].";".$Fila['Beneficiario'].";".$Fila['Doc_Identidad'].";".$Monto.";".$Fila['Concepto'].";".$Fila['Email_Cuenta'].";".$Fila['Telefono_Cuenta'].";");
			
		}
		
		
		//Actualizo el estatus de las transacciones comision de choco%pago#pasaje
		$Query="UPDATE transacciones tr 
				INNER JOIN qr ON tr.Id_Qr_Out=qr.Id_Qr 
				INNER JOIN usuarios us ON qr.Id_Usuario=us.Id_Usuario 
				INNER JOIN tipos_doc_identidad tdi ON us.Id_Tipo_Doc_Identidad=tdi.Id_Tipo_Doc_Identidad 
				INNER JOIN cuentas_bancarias cb ON us.Id_Usuario=cb.Id_Usuario 
				SET tr.Id_Estado_Transaccion=3
				WHERE tr.F_Transaccion>='".$Fecha_Inicio."' AND tr.F_Transaccion<='".$Fecha_Fin."' 
				AND us.Id_Usuario=".$Id_Usuario_Choco." AND tr.Id_Tipo_Transaccion in (".$Id_Tipo_Transaccion.") AND tr.Id_Estado_Transaccion!=3";
		Archivo("Query: ".$Query,"Txt_Pagos_Banco5.txt");
		$res=0;
		$res=$dbConn->DB_Actualizar($Query);
		
		
		// fwrite($file, $Txt);
		fclose($file);
	}
	
	
	Archivo("Query: ".$Query,"Txt_Pagos_Banco6.txt");
	
}

function Txt_Pagos_Banco_tmp($Fecha_Inicio, $Fecha_Fin){
	// Funcion que sustrae de bd los registros generados durante los procesos de pagos y recargas para generar el txt que va para el banco
	// include 'configura.php';
	include 'db.php';
	$dbConn= new AccesoDB;
	
	$Concepto="Transferencia_";   // Este concepto debe prepararse mejor
	$DirTxt=DIRTXTPLAZA;    // Direccion y nombre del txt del banco
	$NombreTxt="txt".date("Ymd").".txt";    //Nombre del txt del banco
	$DirTxt.="/".$NombreTxt;
	$Concepto.=date("Ymd").".txt";
	// Archivo($DirTxt,"Txt_Pagos_Banco3.txt");
	
	$Rif=RIF;    // Rif de la empresa, debe sustituirse por una funcion que busque el rif en la bd, de momento esta en configura.php
	$Fecha_Inicio="2020-12-04 00:00:00";    // Tanto Fecha_Inicio, como Fecha_Fin deben quitarse ya que estan aqui para hacer las pruebas
	$Fecha_Fin="2020-12-04 23:59:59";
	
	
	
	// Construyo el query para el pago
	$Id_Tipo_Transaccion=1;    //Transaccion tipo pago 
	// Query de pago para los choferes
	$Query="SELECT us.Id_Usuario, qr.Id_Qr, cb.Cuenta_Bancaria, CONCAT(us.Nombre,' ',us.Apellido) AS Beneficiario, CONCAT(tdi.Nemonico_Tipo_Doc_Identidad,us.Doc_Identidad) Doc_Identidad, SUM(tr.Monto_Transaccion) AS Monto, '".$Concepto."' AS Concepto, cb.Email_Cuenta, cb.Telefono_Cuenta FROM transacciones tr INNER JOIN qr ON tr.Id_Qr_Out=qr.Id_Qr INNER JOIN usuarios us ON qr.Id_Usuario=us.Id_Usuario INNER JOIN tipos_doc_identidad tdi ON us.Id_Tipo_Doc_Identidad=tdi.Id_Tipo_Doc_Identidad INNER JOIN cuentas_bancarias cb ON us.Id_Usuario=cb.Id_Usuario WHERE tr.F_Transaccion>='".$Fecha_Inicio."' AND tr.F_Transaccion<='".$Fecha_Fin."' AND tr.Id_Tipo_Transaccion=".$Id_Tipo_Transaccion." GROUP BY us.Id_Usuario";

	$Id_Tipo_Transaccion=6;    //Transaccion tipo Comision Chocopas 
	//Query de pago para chocopago
	$Query.=" UNION SELECT us.Id_Usuario, qr.Id_Qr, cb.Cuenta_Bancaria, CONCAT(us.Nombre,' ',us.Apellido) AS Beneficiario, CONCAT(tdi.Nemonico_Tipo_Doc_Identidad,us.Doc_Identidad) Doc_Identidad, SUM(tr.Monto_Transaccion) AS Monto, '".$Concepto."' AS Concepto, cb.Email_Cuenta, cb.Telefono_Cuenta FROM transacciones tr INNER JOIN qr ON tr.Id_Qr_Out=qr.Id_Qr INNER JOIN usuarios us ON qr.Id_Usuario=us.Id_Usuario INNER JOIN tipos_doc_identidad tdi ON us.Id_Tipo_Doc_Identidad=tdi.Id_Tipo_Doc_Identidad INNER JOIN cuentas_bancarias cb ON us.Id_Usuario=cb.Id_Usuario WHERE tr.F_Transaccion>='".$Fecha_Inicio."' AND tr.F_Transaccion<='".$Fecha_Fin."' AND tr.Id_Tipo_Transaccion=".$Id_Tipo_Transaccion." GROUP BY us.Id_Usuario";
	
	$Con_Txt=$dbConn->DB_Consultar($Query);
	$Numfilas=$dbConn->DB_Num_Rows($Con_Txt);
	$Txt="";
	$Control="";
	// Construyo el contenido del txt
	if ($Numfilas>0){    //Existen registos Lleno la variable del txt
		$file = fopen($DirTxt, "w");
		foreach ($Con_Txt as $Fila){
			$Monto=Acomodar_Monto($Fila['Monto'], '.', '', '');
			fwrite($file,$Control.$Rif.";".$Fila['Cuenta_Bancaria'].";".$Fila['Beneficiario'].";".$Fila['Doc_Identidad'].";".$Monto.";".$Fila['Concepto'].";".$Fila['Email_Cuenta'].";".$Fila['Telefono_Cuenta'].";");
			$Control=PHP_EOL;
			
			// Actualizo el qr del registro que meti en el txt
			$query="UPDATE qr SET Saldo=Saldo-".$Fila['Monto']." WHERE Id_Qr=".$Fila['Id_Qr'];
			$res=0;
			$res=$dbConn->DB_Actualizar($query);
		
			
		}
		// fwrite($file, $Txt);
		fclose($file);
		Archivo($Txt,"Txt_Pagos_Banco2.txt");
	}
	
	
	Archivo("Query: ".$Query,"Txt_Pagos_Banco.txt");
	
}

function Porcentaje_Transferir($Id_Usuario){
	//Debe crearse el proceso para determinar el pocentaje de tranferencia sobre las transacciones de pago, mientras se establece el 100%
	return 100;
}

function Fecha_Correcta($Fecha){
	/* Funcion que verifica si una fecha tiene el formato correcto segun sea el caso
	  devuelve true si el formato es correcto o coincide con los aceptados, de lo contrario devuelve false.
	  Los formatos aceptados son YY-m-d (formato fecha) y YYYY-MM-DD HH:ii:ss (formato fecha hora)
	*/
	$Estado=false;
	if (strlen($Fecha)==19){
		$Fecha_Hora=explode(" ",$Fecha);  //separo la fecha de la hora
		Archivo("Longitd correcta, Fecha: ".$Fecha_Hora[0].", Hora: ".$Fecha_Hora[1],"Fecha_Correcta1.txt");
		// Evaluo la fecha
		$Solo_Fecha=explode("-",$Fecha_Hora[0]);
		if ($Solo_Fecha[0]>=1900 && $Solo_Fecha[0]<>9999){    // Si es un año valido
			Archivo("Año Correcto: ".$Solo_Fecha[0],"Fecha_Correcta2.txt");
			if ($Solo_Fecha[1]>=01 && $Solo_Fecha[1]<=12){    //Si es un mes valido
				Archivo("Mes Correcto: ".$Solo_Fecha[1],"Fecha_Correcta3.txt");
				// Verifico los dias, 
				// Los meses Enero(1), Marzo(3), Mayo(5), Julio(7), Agosto(8), Octubre(10) y Diciembre(12), tienen hasta 31
				// Los meses Abril(4), Junio(6), Septiemmre(9), Noviembre(11), tienen hasta 30 dias
				// Febrero tiene 28 dias, si es bisiesto tiene 29
				if($Solo_Fecha[1]=='01' || $Solo_Fecha[1]=='03' || $Solo_Fecha[1]=='05' || $Solo_Fecha[1]=='07' || $Solo_Fecha[1]=='08' || $Solo_Fecha[1]=='10' || $Solo_Fecha[1]=='12'){
					// Si los meses son Enero(1), Marzo(3), Mayo(5), Julio(7), Agosto(8), Octubre(10) y Diciembre(12), tienen hasta 31
					Archivo("Meses de 31 dias: ".$Solo_Fecha[1],"Fecha_Correcta4.txt");
					if ($Solo_Fecha[2]>=01 && $Solo_Fecha[2]<=31) {
						$Estado=true;
					}
					
				}
				if ($Solo_Fecha[1]=='04' || $Solo_Fecha[1]=='06' || $Solo_Fecha[1]=='09' || $Solo_Fecha[1]=='11'){  
					// Si los meses son Abril(4), Junio(6), Septiemmre(9), Noviembre(11), tienen hasta 30 dias
					Archivo("Meses de 30 dias: ".$Solo_Fecha[1],"Fecha_Correcta4.txt");
					if ($Solo_Fecha[2]>=1 && $Solo_Fecha[2]<=30) {
						$Estado=true;
					}
				}
				// Si el mes es Febrero hay que verificar si el año es bisiesto
				if ($Solo_Fecha[1]=='02'){
					Archivo("Meses Febrero: ".$Solo_Fecha[1],"Fecha_Correcta4.txt");
					$Bisiesto = ((($Solo_Fecha[0] % 4 == 0) && ($Solo_Fecha[0] % 100 != 0)) || ($Solo_Fecha[0] % 400 == 0)) ? true : false;
					Archivo("Mes Bisiesto: ".$Bisiesto,"Fecha_Correcta4.txt");
					if (!$Bisiesto) {  // Si el año no es bisiesto, el mes trae hasta 28 dias
						if ($Solo_Fecha[2]>=1 && $Solo_Fecha[2]<=28) {
							$Estado=true;
						}
					} else { // Si el año es bisiesto, el mes trae hasta 29 dias
						if ($Solo_Fecha[2]>=1 && $Solo_Fecha[2]<=29) {
							$Estado=true;
						}
					}
				}
			}
			
		} //Hasta aqi la evaluacion de fecha
		// Evalo la hoa si el estado es corecto
		if ($Estado){
			$Estado=false;
			$Solo_Hora=explode(":",$Fecha_Hora[1]);
			if ($Solo_Hora[0]>=00 && $Solo_Hora[0]<=23){    // Si la hoa es correcta
				if ($Solo_Hora[1]>=00 && $Solo_Hora[1]<=59){ // Si los minutos son corectos
					if ($Solo_Hora[2]>=00 && $Solo_Hora[2]<=59){ //Si los sagundos son correctos
						$Estado=true;						
					}
				}
			}
		}
	}
	//$Estado=validateDate($Fecha,"Y-d-m H:i:s");
	Archivo("Fecha: ".$Fecha.", Longitud fecha: ".strlen($Fecha).", Estado: ".$Estado,"Fecha_Correcta.txt");
	return $Estado;
}

///// **************** Funcion Realizada hasta aqui ***************************

function Recargar_Por_Punto($Cedula, $Monto){
	
	include 'db.php';
	// $json = '{"Estatus": 0, "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Usuario": "'.$Usuario.'" }';
	$dbConn= new AccesoDB;
	Archivo ('Paso por aqui','Recargar_Punto1.txt');
	//Consulto la tabla de Usuario para recuperar el id del usuario
	//*****************************
	//*****************************
	//*****************************
	$Query="SELECT * FROM usuarios WHERE Doc_Identidad=".$Cedula;
	$Con_Usuario=$dbConn->DB_Consultar($Query);
	$Usuario=$Con_Usuario->fetch_assoc();
	$Id_Usuario=$Usuario['Id_Usuario'];
	Archivo ($Id_Usuario,'Recargar_Punto2.txt');
	
	$Id_Tipo_Transaccion=1;
	$Id_Estado_Transaccion=1;
	$Query="INSERT INTO trans_usuarios(Id_Usuario, Id_Tipo_Transaccion, Id_Estado_Transaccion, f_Transaccion, Credito) VALUES (".$Id_Usuario.", ".$Id_Tipo_Transaccion.", ".$Id_Estado_Transaccion.", NOW(), ".$Monto.")";
	$Id_Trans_U_Insert=$dbConn->DB_Insertar($Query);
	// Archivo ($Query,'Recargar_Punto.txt');
	
	$query="UPDATE usuarios SET Saldo=Saldo+".$Monto." WHERE Doc_Identidad=".$Cedula;
	//Archivo($query,'Recargar_Por_Punto.txt');
	$Numfilas_Us=$dbConn->DB_Actualizar($query);
	
	$query="UPDATE usuarios_empresa SET Saldo=Saldo+".$Monto." WHERE Id_Usuario=1";
	//Archivo($query,'Recargar_Por_Punto.txt');
	$Numfilas_Us_Emp=$dbConn->DB_Actualizar($query);
	
	
	if ($Id_Trans_U_Insert>0 and $Numfilas_Us>0 and $Numfilas_Us_Emp>0){
		//Si hay registros es porque hubo una actualizacion
		
		// $mensaje = '1-,-Usuario correcto-,-'.$Usuario.'-,-'.$Id_Usuario.'-,-'.$Id_Punto.'-,-'.$Usuario_Bdv.'-,-'.$Clave_Bdv;
		$json = '{"Actualizado": 1"}';
		// Archivo($json,'datos_usuarios2.txt');
	}else{
		//Si no hay registros es porque no es un usuario del punto
		//envio mensaje de usuario incorrecto
		// $mensaje = '3-,-Usuario o clave incorrectos o no es usuario de un punto de venta-,- -,- -,- -,- -,- -';
		$json = '{"Actualizado": 0"}';
		// $json = '{"Estatus": 3, "Mensaje": "Usuario o clave incorrectos o no es usuario de un punto de venta", "Usuario": "'.$Usuario.'","Id_Usuario": 0,"Id_sesion_us": 0,"Id_Punto": 0,"Id_Usuario_Bdv": "","Clave_Bdv": "", "Nombre_Punto": "", "Correo_Punto": "" }';
	}
	return $json;

}


function Acomodar_Monto($Monto, $Sep_Decimal='.', $Sep_Dec_For=',', $Sep_Mil_For='.'){
	/* El monto puede llegar de varias formas, segun el origen, numerico, string de campos en pantalla y segun su origen tiene un formato diferente
	el sepaador decimal $Sep_Decimal indica cual es el sepaador con el cual voy a comparar, el $Sep_Dec_For indica el separador decimal que se le va 
	a colocar, el $Sep_Mil_For indica el separador de mil que se le va a colocar */
	
	/* Primero se ve si el monto trae decimales, si no trae decimales, le agrego dos 0, si trae decimales, se ve si es un decimal o dos,
	si es un decimal le agrego un cero al final*/
	$Posic_Decimales=strpos($Monto,$Sep_Decimal);
	
	if($Posic_Decimales===false){
		// 'No trae Decimales';
		$Entero=$Monto;
		$Decimal='00';
		// return 'Entro: '.$Entero.'; Decimal: '.$Decimal;
	}else{
		// 'Trae Decimales';
		//Extraigo los decimales para saber cuantos son, si es menos de dos caractres, le agrego un cero
		$Entero=substr($Monto, 0,$Posic_Decimales);
		$Entero=str_replace(',','',$Entero);
		$Entero=str_replace('.','',$Entero);
		$Decimal=substr($Monto, $Posic_Decimales+1);
		if (strlen($Decimal)<2){
			//Cuando es un solo decimal
			$Decimal.='0';
		}
	}
	
	$j=strlen($Entero);
	if ($j>3){
		$Control=0;
		$Entero2='';
		$j=$j - 1;
		$i=$j;
		for ($i=$j;$i>=0;$i--){
			if ($Control==3){
				$Entero2=$Sep_Mil_For.$Entero2;
				$Control=0;
			}
			$Control=$Control+1;
			$Val=$i;
			$Entero2=substr($Entero,$i,1).$Entero2;
		}
		$Entero=$Entero2;
	}else{
		switch ($Entero)
		{
			case '':
				$Entero='0';
			break;
			case '0':
				$Entero='0';
			break;
			default:
				$Entero=$Entero;
			break;
		}
	}
	$Numero=strval($Entero).$Sep_Dec_For.$Decimal;
	
	return $Numero;
}


function Reporte_Resumen_Diario($Id_Usuario, $Desde, $Hasta, $Tipo_Tran){
	include 'db.php';
	$json = '{"Estatus": 0, "Mensaje": "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Id_Usuario": 0 }';
	$Estatus=0;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$dbConn= new AccesoDB;
    $Query="SELECT concat_ws(' ', us.Nombre, us.Apellido) as nombre, concat_ws('-', tdi.Nemonico_Tipo_Doc_Identidad, us.Doc_Identidad) as ci,date_format(tr.F_Transaccion,'%d/%m/%Y') as fecha,
			et.Estado_Transaccion as estado,sum(tr.Monto_Transaccion) as total, COUNT(*) as registros 
			FROM transacciones as tr 
			INNER JOIN tipos_transacciones as tt 
			INNER JOIN qr as qr 
			INNER JOIN usuarios as us 
			INNER JOIN estados_transacciones et 
            INNER JOIN tipos_doc_identidad tdi
			WHERE tr.Id_Tipo_Transaccion=tt.Id_Tipo_Transaccion 
			AND tr.Id_Qr_Out=qr.Id_Qr AND qr.Id_Usuario=us.Id_Usuario 
			AND tr.Id_Estado_Transaccion=et.Id_Estado_Transaccion 
            AND us.Id_Tipo_Doc_Identidad=tdi.Id_Tipo_Doc_Identidad
			AND tt.Id_Tipo_Transaccion='".$Tipo_Tran."'
            AND qr.Id_Usuario='".$Id_Usuario."' 
			AND CAST(tr.F_Transaccion as DATE) BETWEEN STR_TO_DATE('".$Desde."','%d/%m/%Y') AND STR_TO_DATE('".$Hasta."','%d/%m/%Y') 
			GROUP BY concat_ws(' ', us.Nombre, us.Apellido), concat_ws('-', tdi.Nemonico_Tipo_Doc_Identidad, us.Doc_Identidad),date_format(tr.F_Transaccion,'%d/%m/%Y'),et.Estado_Transaccion 
			ORDER BY str_to_date(fecha,'%d/%m/%Y'), estado";
	$Consulta=$dbConn->DB_Consultar($Query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	if ($Numfilas>0){
		$Estatus=1;
        $Mensaje="Hay Registros";
        $i=0;
		while ($fila=$Consulta->fetch_assoc()){
			$ArrayData[$i]=$fila;
			$i++;
		}
		$DatosP=$ArrayData[0];
		$Cedula=$DatosP["ci"];
		$Nombre=$DatosP["nombre"];
		
	}else{
		$Estatus=2;
		$Mensaje="No Hay Registros con este criterio";
		$fila2=array("nombre"=>"","ci"=>"","fecha"=>"","estado"=>"","total"=>"","registros"=>0);
		$ArrayData[0]=$fila2;
		$Cedula="";
		$Nombre="";
    }    
	$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Cedula"=>$Cedula, "Nombre"=>$Nombre, "Registros"=>$ArrayData);
	return json_encode($Datos);
}



function Reporte_Total_Cobros($Id_Usuario, $Desde, $Hasta, $Tipo_Tran){
	include 'db.php';
	$json = '{"Estatus": 0, "Mensaje": "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Id_Usuario": 0 }';
	$Estatus=0;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$dbConn= new AccesoDB;
    $Query="SELECT concat(us.Nombre,' ', us.Apellido) as nombre, concat(tdi.Nemonico_Tipo_Doc_Identidad,'-', us.Doc_Identidad) as ci,et.Estado_Transaccion as estado,
			sum(tr.Monto_Transaccion) as total, COUNT(*) as registros 
			FROM transacciones as tr INNER JOIN tipos_transacciones as tt 
			INNER JOIN qr as qr 
			INNER JOIN usuarios as us 
			INNER JOIN tipos_doc_identidad tdi
			INNER JOIN estados_transacciones et 
			WHERE tr.Id_Tipo_Transaccion=tt.Id_Tipo_Transaccion 
			AND tr.Id_Qr_Out=qr.Id_Qr AND qr.Id_Usuario=us.Id_Usuario 
			AND us.Id_Tipo_Doc_Identidad=tdi.Id_Tipo_Doc_Identidad
			AND tr.Id_Estado_Transaccion=et.Id_Estado_Transaccion 
			AND tt.Id_Tipo_Transaccion='".$Tipo_Tran."' AND qr.Id_Usuario='".$Id_Usuario."' 
			AND CAST(tr.F_Transaccion as DATE) BETWEEN STR_TO_DATE('".$Desde."','%d/%m/%Y') AND STR_TO_DATE('".$Hasta."','%d/%m/%Y') 
			GROUP BY concat(us.Nombre,' ', us.Apellido), concat(tdi.Nemonico_Tipo_Doc_Identidad,'-', us.Doc_Identidad),et.Estado_Transaccion 
			ORDER BY estado";
	$Consulta=$dbConn->DB_Consultar($Query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	Archivo($Query,"reporte_total.txt");
	if ($Numfilas>0){
		$Estatus=1;
        $Mensaje="Hay Registros";
        $i=0;
		while ($fila=$Consulta->fetch_assoc()){
			$ArrayData[$i]=$fila;
			$i++;
		}
		$DatosP=$ArrayData[0];
		$Cedula=$DatosP["ci"];
		$Nombre=$DatosP["nombre"];
		
	}else{
		$Estatus=2;
		$Mensaje="No Hay Registros con este criterio";
		$fila2=array("nombre"=>"","ci"=>"","estado"=>"","total"=>"","registros"=>0);
		$ArrayData[0]=$fila2;
		$Cedula="";
		$Nombre="";
    }    
	$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Cedula"=>$Cedula, "Nombre"=>$Nombre, "Registros"=>$ArrayData);
	return json_encode($Datos);
}

function Listado_Detalle_Transacciones($Id_Usuario, $Desde, $Hasta, $Tipo_Tran){
	include 'db.php';
	$json = '{"Estatus": 0, "Mensaje": "Error al tratar de consumir el recurso, por favor consulte con el administrador", "Id_Usuario": 0 }';
	$Estatus=0;
	$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";
	$dbConn= new AccesoDB;
    $Query="SELECT tr.Id_Transaccion, tr.Monto_Transaccion, tr.F_Transaccion, et.Estado_Transaccion 
			FROM transacciones as tr 
			INNER JOIN tipos_transacciones as tt 
			INNER JOIN qr as qr 
			INNER JOIN estados_transacciones as et
			WHERE tr.Id_Tipo_Transaccion=tt.Id_Tipo_Transaccion 
			AND tr.Id_Qr_Out=qr.Id_Qr AND tr.Id_Estado_Transaccion=et.Id_Estado_Transaccion 
			AND qr.Id_Usuario='".$Id_Usuario."' AND tt.Id_Tipo_Transaccion='".$Tipo_Tran."' 
			AND CAST(tr.F_Transaccion as DATE) BETWEEN STR_TO_DATE('".$Desde."','%d/%m/%Y') AND STR_TO_DATE('".$Hasta."','%d/%m/%Y') 
			ORDER BY tr.Id_Transaccion";
	$Consulta=$dbConn->DB_Consultar($Query);
	$Numfilas=$dbConn->DB_Num_Rows($Consulta);
	if ($Numfilas>0){
		$Estatus=1;
        $Mensaje="Hay Registros";
        $i=0;
		while ($fila=$Consulta->fetch_assoc()){
			$ArrayData[$i]=$fila;
			$i++;
		}
		$DatosP=$ArrayData[0];
		
	}else{
		$Estatus=2;
		$Mensaje="No Hay Registros con este criterio";
		$fila2=array("Id_Transaccion"=>"","Monto_Transaccion"=>"","F_Transaccion"=>"","Estado_Transaccion"=>"");
		$ArrayData[0]=$fila2;
    }    
	$Datos=array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "Registros"=>$ArrayData);
	return json_encode($Datos);
}























function Abrir_Sesion_Us_Post($id_us, $Requiere_Obj=1){ //Archivo($id_us, 'Abrir_Sesion_Us_Post.txt');
	// pregunto nuevamente si el usuario tiene una sesion abierta, si es asi, me devuelvo y mando un mensaje de error, si no 
	// abro una sesion y envio mensaje de exito
    if ($Requiere_Obj==1) include 'db.php';
	//conexion();
	$dbConn= new AccesoDB;
	$query="INSERT INTO sesiones_usuarios (Id_Sesion_Us, Id_Usuarios, F_Inicio, F_Acceso, F_Fin, Id_Equipo, Estatus_Sesion) VALUES (NULL, ".$id_us.", NOW(), NOW(), NULL, NULL, 1)";
	//echo $query; exit();
	// $mysqli->query($query);
	// $id_sesion_us=$mysqli->insert_id;
	$id_sesion_us=$dbConn->DB_Insertar($query,1);
	return $id_sesion_us;
}
	
function Cerrar_Sesion_Us_Post($id_sesion_us, $Requiere_Obj=1){
	// pregunto nuevamente si el usuario tiene una sesion abierta, si es asi, me devuelvo y mando un mensaje de error, si no 
	// abro una sesion y envio mensaje de exito
    if ($Requiere_Obj==1) include 'db.php';
	//conexion();
	$dbConn= new AccesoDB;
	$query="UPDATE sesiones_usuarios SET F_Fin=NOW(), Estatus_Sesion = '0' WHERE Id_Sesion_Us = ".$id_sesion_us;
	//echo $query; exit();
	$res=0;
	$res=$dbConn->DB_Actualizar($query);
	// if($dbConn->DB_Actualizar($query)) $res=1;
	return $res;
}
	

function Transicion($Id){
	// Genera un numero unico con el que se va a identificar la operacion o la transaccion para luego poderla buscar
	$Transicion=$Id.uniqid('',true);
	return $Transicion;
}

function Monto_Choco(){
	$Monto_Descuento=2/100;
	return $Monto_Descuento;
}

function Monto_Tercero(){
	$Monto_Descuento=2/100;
	return $Monto_Descuento;
}

function Normalizar_Monto_Bd($Monto){
	// Quito las comas y los puntos
	$Monto=str_replace(',','',$Monto);
	$Monto=str_replace('.','',$Monto);
	$Entero=substr($Monto,0,strlen($Monto)-2);
	$Decimal=substr($Monto,strlen($Monto)-2);
	$Monto=$Entero.'.'.$Decimal;
	return $Monto;
}




?>

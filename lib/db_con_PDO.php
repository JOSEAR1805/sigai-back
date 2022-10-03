<?php 
class AccesoDB{
    private $host="66.23.226.204";
	private $puerto="5432";
    private $usuario="des4";         
    private $clave  ="1nd1c4";    
    private $db     ="indicadores";
    public $conexion;
    public function __construct(){
		$this->conexion = new PDO("pgsql:host=$this->host;port=$this->puerto;dbname=$this->db", $this->usuario, $this->clave);
        // or die(mysql_error());
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
			$resultado = $this->conexion->query($query); //or die($this->conexion->error);
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
            
			if($rs_conn && $rs_conn!="") $num_rows = $rs_conn->rowCount();
            else $num_rows = 0;
            return $num_rows; 
	}

}

?>
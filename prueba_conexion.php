<?php
include 'db.php';
$dbConn= new AccesoDB;
$query="SELECT * FROM usuarios";
$Consulta=$dbConn->db_Consultar($query);
$Numfilas=$dbConn->DB_Num_Rows($Consulta);
echo $query."<br>";
echo 'Filas: '.$Numfilas
// echo $Consulta->rowCount();
?>
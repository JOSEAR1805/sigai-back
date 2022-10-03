<?php

$host = 'localhost';
$dbname = 'indicadores';
$username = 'des4';
$password = '1nd1c4';

  $dsn = "pgsql:host=$host;port=5432;dbname=$dbname;user=$username;password=$password";

  try{
     $conn = new PDO($dsn);

     if($conn){
      echo "Successfully connected to $dbname!";
     }
  }catch (PDOException $e){
     echo $e->getMessage();
  }



?>

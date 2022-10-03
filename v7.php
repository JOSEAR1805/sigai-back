<?php
require_once 'config.php';

try {
	$dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$user;password=$clave";
	// make a database connection
	$pdo = new PDO($dsn, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

	if ($pdo) {
		echo "Connected to the $db database successfully!";
	}
} catch (PDOException $e) {
	die($e->getMessage());
} finally {
	if ($pdo) {
		$pdo = null;
	}
}

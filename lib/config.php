<?php



// Datos txt Banco Plaza
if (strtoupper(substr(php_uname(), 0, 3)) === 'WIN') {
    // Datos fijos de Conexion Windows
	define('HOST', '66.23.226.204');
//	define('HOST', 'localhost');
	define('PUERTO', '5432');
	define('USUARIO', 'des4');
	define('CLAVE', '1nd1c4');
	define('DB', 'indicadores');
	define('ESQUEMA', 'cantv');
} else {
    // Datos fijos de Conexion Linux
	define('HOST', '66.23.226.204');
	//define('HOST', 'localhost');
	define('PUERTO', '5432');
	define('USUARIO', 'des4');
	define('CLAVE', '1nd1c4');
	define('DB', 'indicadores');
	define('ESQUEMA', 'cantv');
}





?>

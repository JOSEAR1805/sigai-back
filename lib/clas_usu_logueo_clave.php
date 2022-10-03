<?php

class ClaveUsuario {
    // Opciones de contraseña:
    const HASH_D = PASSWORD_DEFAULT;
    const COST = 15;
    // Constructor
    public function __construct() {
        
    }
    // Funcionalidad de guardar los datos simulada:
    public function guardar() {
        // Guardar los datos en bd en la base de datos
    }

// Permite el cambio de contraseña:
    public function generarClave($password) {
		// $password contiene la clave sin cifrar y se devuelve la clave cifrada
        // $this->data->passwordHash = password_hash($password, self::HASH_D, ['cost' => self::COST]);
		return password_hash($password, self::HASH_D, ['cost' => self::COST]);
    }
    // Logear un usuario:
    public function verificarClave($password,$password_bd) {
		// Compara la contraseña que se metio en la pantalla $password, con la de la ba $password_bd, ambas deben pasarse como parametro
		// tomando en cuenta que la que se va a comparar debe encriptarse para podes compararla
        // Primero comprobamos si se ha empleado una contraseña correcta:
        // echo "Login: ", $password, "\n";
		// Exito, ahora se comprueba si la contraseña necesita un rehash:
        if (password_needs_rehash($password_bd, self::HASH_D, ['cost' => self::COST])) {
           // Si se necesita hacer rehash, emito un codigo de error 2
		   $result=3;
		}else {
			// Si no se necesita hacer rehash, compruebo si las dos claves son iguales
			if (password_verify($password, $password_bd)) {
				// Si son iguales emito codigo de correcto
				$result=1;
			} else {
				// Si no son iguales, emito codigo de Clave no coincide
				$result=2;
			}
		}
		return $result;
		
        
    }
}




?>
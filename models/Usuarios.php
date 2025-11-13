<?php 
	
	/**
	 * 
	 * Clase Usuarios
	 * Contiene todo lo que debe tener / hacer un usuario
	 * 
	 * */
	class Usuarios extends DBAbstract
	{

		/* atributos de un usuario */
		public $email, $password;
		
		/* al crear la instancia se ejecuta el constructor */
		function __construct()
		{

			/* llamo al constructor del padre DBAbstract */
			parent::__construct();

			$this->email = "";
			$this->password = "";
		}

		public function getCant(){
			return count($this->consultar("SELECT * FROM usuarios"));
		}
	

		/**
		 * 
		 * valido
		 * usuario sea valido y pass invalida
		 * usuario no exista
		 * usuario = ""
		 * password = ""
		 * 
		 * */
		public function login($form){

			$this->email = $form['txt_email'];
			$this->password = $form['txt_password'];

			/* no hay email*/
			if( strlen($this->email)==0 ){
				return ["errno" => 404, "error" => "Falta email"];
			}

			/* no hay contraseña */
			if(strlen($this->password) == 0){
				return ["errno" => 404, "error" => "Falta contraseña"];
			}

			$sql = "SELECT * FROM usuarios WHERE email = '".$this->email."'";

			$response = $this->consultar($sql);

			/* valido que exista el usuario*/
			if(count($response)==0){
				return ["errno" => 400, "error" => "usuario no registrado"];
			}

			/* si la contraseña no es valida */
			if($response[0]["contraseña"]!= $this->password){
				return ["errno" => 401, "error" => "contraseña incorrecta"];
			}


			return ["errno" => 202, "error" => "Acceso valido"];

		}

	}


 ?>